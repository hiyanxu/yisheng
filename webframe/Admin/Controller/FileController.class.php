<?php
/*
附件管理控制器
*/
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\FileModel;

class FileController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	附件管理列表页显示
	*/
	public function index(){
		$this->display("index");
	}

	/*
	附件上传页面显示
	*/
	public function add(){
		$this->display("add");
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

	/*
	上传的后端处理方法
	*/
	public function uploader(){
			// Define a destination
		$path = UPLOAD_PATH; // Relative to the root
		//dd($path);
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

		                //当前在这里执行入库操作
		                $filpath=$destination;  //文件存放路径
		                $file_former_name=$files["name"];  //文件最初的名字
		                $file_mime=mime_content_type($files['name']);  //获取文件mime类型
		                $file_ext=$ext;
		                $file_size=filesize($files['name']);  //获取文件大小
		                $is_effective=1;  //0：有效  1：无效  默认为无效
		                $create_by=session("loginaccount");
		                $create_at=time();
		                $data=array(
		                	"file_name"=>$fileName,
		                	"file_path"=>$filpath,
		                	"file_former_name"=>$file_former_name,
		                	"file_mime"=>$fileInfo[0]['type'],
		                	"file_ext"=>$file_ext,
		                	"file_size"=>$fileInfo[0]['size'],
		                	"is_effective"=>$is_effective,
		                	"create_by"=>$create_by,
		                	"create_at"=>$create_at
		                	);
		                $return_ins=$this->insert($data);
		                //$this->ajaxReturn($return,"JSON");
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
	文件入库操作
	*/
	public function insert($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效参数"
				);
		}
		else{
			//var_dump($data);die();
			if(M("file")->data($data)->add()){
				$return=array(
					"status"=>true,
					"msg"=>"添加成功"
					);
			}
			else{
				$return=array(
					"status"=>false,
					"msg"=>"添加失败"
					);
			}

		}
		return $return;
	}

	/*
	保存操作
	*/
	public function dataInsert(){
		$data_post=I("post.");  //获取所有post过来的数据
		if($data_post['file_title']==""){
			$return=array(
				"status"=>false,
				"msg"=>"请将信息填写完整"
				);
			$this->ajaxReturn($return,"JSON");
		}
		if($data_post['file_type']==1){
			if($data_post["file_param_width"]==""||$data_post['file_param_height']==""){
				$return=array(
					"status"=>false,
					"msg"=>"请将参数信息填写完整"
					);
				$this->ajaxReturn($return,"JSON");
			}
		}
		$data=array(
			"file_type"=>$data_post['file_type'],
			"file_title"=>$data_post['file_title'],
			"file_param_height"=>$data_post['file_param_height'],
			"file_param_width"=>$data_post['file_param_width'],
			"is_effective"=>0
			);
		$obj=new FileModel();
		//var_dump($data_post);die();
		$return=$obj->editSave(null,$data_post['file_upload_name'],$data);
		if($return['status']){
			$return['msg']="添加成功";
		}
		else{
			$return['msg']="添加失败";
		}
		$this->ajaxReturn($return,"JSON");


	}

	/*
	文件列表数据获取操作
	*/
	public function ajaxIndex(){
		$order=I("get.sort")." ".I("get.order");
		if(I("get.sort")&&I("get.order")){
			if(I("get.file_type")){
				$file_type=I("get.file_type");
				$data=M()->table(array("file"=>"file","user_account"=>"user_act"))
					->where("file.create_by=user_act.user_account_id and file_type='$file_type'")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
				}
			else{
				$data=M()->table(array("file"=>"file","user_account"=>"user_act"))
					->where("file.create_by=user_act.user_account_id")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
				}
			}
			
		else{
			if(I("get.file_type")){
				$file_type=I("get.file_type");
				$data=M()->table(array("file"=>"file","user_account"=>"user_act"))
					->where("file.create_by=user_act.user_account_id and file_type='$file_type'")->limit(I("get.offset"),I("get.limit"))->select();
				}
			else{
				$data=M()->table(array("file"=>"file","user_account"=>"user_act"))
					->where("file.create_by=user_act.user_account_id")->limit(I("get.offset"),I("get.limit"))->select();
				}
		}
		$total_count=M()->table(array("file"=>"file","user_account"=>"user_act"))
			->where("file.create_by=user_act.user_account_id")->count();

		foreach ($data as $key => $value) {
			# code...
			$row=M("user_account")->field("user_account")->where("user_account_id='".$value['create_by']."'")->select();
			$data[$key]["addUser"]=$row[0]['user_account'];
			$data[$key]['is_effective_text']=$value['is_effective']==0?"<label style='color:#7EAEF5;'>有效</label>":"<label style='color:red;'>无效</label>";
			$data[$key]['file_path']=str_replace("/var/www/html/yisheng/webframe", "", $value['file_path']);
			$data[$key]['download_path']=WWW_PUB.$data[$key]['file_path'];

		}

		$return=array(
			"total"=>$total_count,
			"data"=>$data
		);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	删除操作
	*/
	public function del(){
		$ids=I("post.ids");  //获取文件ids

		$ids2=substr($ids, 0,-1);  //将最后的逗号去掉
		$idsToArr=explode(",", $ids2);  //将字符串切割成数组

		if(empty($idsToArr)){
			$return=array(
				"status"=>false,
				"msg"=>"删除失败"
				);
			$this->ajaxReturn($return,"JSON");
		}
		else{
			foreach ($idsToArr as $key => $value) {
				# code...
				$obj=new FileModel();
				$return=$obj->del($value);
			}
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



}