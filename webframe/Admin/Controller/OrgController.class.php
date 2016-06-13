<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\OrgModel;

class OrgController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	组织机构列表页面显示
	*/
	public function index(){
		$this->display("index");
	}

	/*
	组织机构添加页面显示
	*/
	public function add(){
		$this->display("add");
	}

	/*
	分类入库操作
	*/
	public function insert(){
		$data_post=I("post.");  
		$data=array(
			"org_name"=>$data_post['org_name'],
			"parentid"=>$data_post['parentid'],
			"ishidden"=>$data_post["ishidden"]
		);
		$obj=new OrgModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	获取所有分类数据
	*/
	public function getOrgTree(){
		$rows=$this->_getTree();
		$this->ajaxReturn($rows,"JSON");
	}

	/*
	获取对应格式数据
	*/
	private function _getTree($parentid=0){
		$array=array();
		$data=M("organization")->where("parentid='".$parentid."' and ishidden=0")->select();

		foreach ($data as $key => $value) {
			# code...
			$array[$key]['text'] = $value["org_name"]."&nbsp;&nbsp;&nbsp;"."<a href='javascritp::void(0)' onclick='addChild({$value["org_id"]});' title='添加'><span class='glyphicon glyphicon-plus'></span></a>".
			"&nbsp;&nbsp;"."<a href='javascritp::void(0)' onclick='editChild({$value["org_id"]});' title='修改'><span class='glyphicon glyphicon-pencil'></span></a>".
			"&nbsp;&nbsp;"."<a href='javascritp::void(0)' onclick='delChild({$value["org_id"]});' title='删除'><span class='glyphicon glyphicon-trash'></span></a>".
			"&nbsp;&nbsp;"."<a href='javascritp::void(0)' onclick='infoGiveChild({$value["org_id"]});' title='信息完善'><span class='glyphicon glyphicon-edit'></span></a>";
			$array[$key]['org_id'] = $value["org_id"];
			$is_ok = M("organization")->where('parentid="'.$value["org_id"].'" and ishidden=0')->count();
			if($is_ok){
				$array[$key]['nodes'] = $this->_getTree($value['org_id']);
			}
		}
		return $array;
		
	}

	/*
	子类添加页面显示
	*/
	public function addChild(){
		$fid=I("get.fid");
		if($fid==""){
			$return=array(
				"status"=>false,
				"msg"=>"网络通信错误，请重试"
			);
			return $return;
		}
		else{
			$row=M("organization")->where("org_id='".$fid."'")->field("org_name")->select();
			$this->assign("row",$row);
			$this->assign("fid",$fid);
			$this->display("addChild");
		}
	}

	/*
	分类修改方法
	*/
	public function edit(){
		$org_id=I("get.org_id");
		$row=M("organization")->where("org_id='".$org_id."'")->field("org_name,parentid,ishidden")->select();
		$this->assign("row",$row);
		$this->assign("org_id",$org_id);
		$this->display("edit");
	}

	/*
	分类修改保存操作
	*/
	public function update(){
		$data_post=I("post.");
		$data=array(
			"org_name"=>$data_post['org_name'],
			"parentid"=>$data_post['parentid'],
			"ishidden"=>$data_post['ishidden']
		);
		$obj=new OrgModel();
		$return=$obj->editSave($data_post['org_id'],$data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	删除操作
	*/
	public function del(){
		$org_id=I("post.org_id");
		$child_count=M("organization")->where("parentid='".$org_id."'")->count();
		if($child_count){
			$return=array(
				"status"=>false,
				"msg"=>"删除失败，请先删除下级机构"
			);
		}
		else{
			$obj=new OrgModel();
			$return=$obj->del($org_id);
		}
		$this->ajaxReturn($return,"JSON");
	}

	/*
	404操作页面
	*/
	public function _empty(){
		header("HTTP/1.0 404 NOT　Found");
		$this->display("Empty/index");  //让他找到404页面
	}

	/*
	信息完善页面显示
	*/
	public function infoGive(){
		$org_id=I("get.org_id");  //获取主键id
		$org_row=M('organization')->where("org_id={$org_id}")->select();
		$this->assign("org_row",$org_row);

		//获取所属学院（上级单位）
		$college_isenable_row=M('college')->where("isenable=0")->field("org_id")->select();  
		$org_obj=new OrgModel();
		$org_college_rows=$org_obj->getOrgTreeRows($college_isenable_row[0]['org_id']);

		//根据当前登录人获取该实验室的负责人信息
		$login_role_id=session("loginuserroleid");  //获取当前登录人角色信息
		if($login_role_id==1){  //表示当前登录人是系统最高管理员，则我们给出所有当前管理员信息
			//获取所有管理员角色用户
			$user=M('user_account')->where('is_admin=0')->field('user_id')->select();
			foreach ($user as $key => $value) {
				# code...
				$row=M('user')->where("user_id={$user[$key]['user_id']}")->field('user_id,user_name')->select();
				$user_rows[$key]=$row[0];
			}
		}
		else{  //表示当前登录人肯定某个实验室的实验室管理员
			$login_user_id=session("loginuserid");
			$login_user_name=M("user")->where("user_id={$login_user_id}")->field("user_id,user_name")->limit(1)->select(); 
			$user_rows[0]=$login_user_name[0];  //获取某一个指定的用户信息
		}

		
		$this->assign('user_rows',$user_rows);

		$this->assign("org_college_rows",$org_college_rows);

		$this->display('info');
	}


	/*
	信息完善保存操作
	*/
	public function infoGiveUpload(){
		$data_post=I('post.');

		$data=array(
			"org_english_name"=>$data_post['org_english_name'],
			'org_user_id'=>$data_post['org_user_id'],
			"org_idea"=>$data_post['org_idea'],
			"org_icon"=>$data_post['org_icon'],
			"org_location"=>$data_post['org_location'],
			"org_phone"=>$data_post['org_phone'],
			"org_email"=>$data_post['org_email'],
			"org_college_id"=>$data_post['org_college_id'],
			"org_content"=>$data_post['org_content']
			);
		$obj=new OrgModel();  //获取model对象
		$return=$obj->editSave($data_post['org_id'],$data);
		$this->ajaxReturn($return);

	}

	/*
	图片上传处理程序
	*/
	public function uploader(){
			// Define a destination
		$path = UPLOAD_PATH; // Relative to the root
		$verifyToken = md5('unique_salt' . $_POST['timestamp']);

		if (!empty($_FILES)) {
			if(!file_exists($path)){
		        mkdir($path,0777,true);
		    }
		    $fileInfo=  $this->getFileInfo($_FILES);
		    //var_dump($fileInfo);die();
		    if(!($fileInfo&&is_array($fileInfo))){
		        exit();
		    }
		    foreach($fileInfo as $files){        
		        if($files["error"]==UPLOAD_ERR_OK){  //当前表示上传没有错误
		            $ext=$this->getFileExt($files["name"]);  //得到文件的扩展名
		            $fileName=$this->getUniName().".".$ext;
		            $destination=$path."/".$fileName;
		            if(move_uploaded_file($files["tmp_name"], $destination)){
		                $file['name']=$fileName;
		                unset($files['error'],$files['tmp_name'],$files['size'],$files['type']);
		                $uploadedFiles['status']="true";
		                $uploadedFiles['name']=$file['name'];
		            }
		            else{
		                $mes="文件上传失败！";
		            }
		        }
		        else{
		            switch ($files["error"]){
		                case 1:
		                    $mes="超过配置文件限制的上传大小";
		                    break;
		                case 2:
		                    $mes="超过表单设置的上传文件大小";
		                    break;
		                case 3:
		                    $mes="文件部分被上传";
		                    break;
		                case 4:
		                    $mes="文件没有被上传";
		                    break;
		                case 6:
		                    $mes="没有找到临时目录";
		                    break;
		                case 7:
		                    $mes="文件不可写";
		                    break;
		                case 8:
		                    $mes="由于php的扩展程序中断了文件上传";
		                    break;                
		            }
		            $uploadedFiles['status']="false";
		            $uploadedFiles['msg']=$mes;
		        }        
		    }
		    echo $this->ajaxReturn($uploadedFiles,"JSON");
		}
	}

	/*
	 * 通过用户上传文件信息得到用户的一维数组的信息
	 * 参数：用户上传的$_FILES数组
	 */
	function getFileInfo($files){
	    foreach ($files as $file){
	        $fileInfo[]=$file;
	    }
	    return $fileInfo;
	}

	/*
	 * 得到用户上传文件的扩展名
	 * 参数：用户上传的$fileName
	 */
	function getFileExt($filename){
	    return strtolower(end(explode(".", $filename)));
	}

	/*
	 * 给出一个唯一字符串
	 */
	function getUniName(){
	    return md5(uniqid(microtime(true),true));
	}




}