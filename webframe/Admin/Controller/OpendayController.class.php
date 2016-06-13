<?php
/*
开放日控制器
*/
namespace Admin\Controller;
use Think\Controller;

use Admin\Model\OrgModel;
use Admin\Model\OpendayModel;

class OpendayController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	列表页面显示
	*/
	public function index(){
		$workshop=M('workshop')->where("isenable=0")->field('org_id')->select();
		$org_rows_last=M("organization")->where("org_id='".$workshop[0]['org_id']."'")->field("org_id,org_name")->select();
		
		$obj=new OrgModel();
		
		$workshop_rows=$obj->getOrgTreeRows($workshop[0]['org_id']);
		$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		$this->assign('workshop_rows',$workshop_rows);
		$this->display('Openday/index');
	}

	/*
	开放日添加页面显示
	*/
	public function add(){
		$login_account=session('loginuser');  //当前登录人账号
		$login_account_id=session('loginaccount');  //当前登录人的账号表对应的主键id
		$login_role_row=M('user_account')->where("user_account_id={$login_account_id}")->field('role_id')->select();
		$login_user_id=session('loginuserid');  //获取当前登录人的user_id
		$user_org_id=M('user')->where("user_id={$login_user_id}")->field("org_id")->select();  //获取当前登录人所属的实验室

		$obj=new OrgModel();
		if($login_account=='admin'){  //表示当前人是以admin做登陆的，则我们给出所有的实验室
			$workshop_enable=M("workshop")->where("isenable=0")->field('org_id')->select();
			$org_rows_last=M("organization")->where("org_id='".$workshop_enable[0]['org_id']."'")->field("org_id,org_name")->select();
			
			$workshop_rows=$obj->getOrgTreeRows($workshop_enable[0]['org_id']);
			$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		}
		else{
			if($login_role_row[0]['role_id']==1){  //表示当前登录人不是admin，但是系统管理员，则给出所有实验室
				$workshop_enable=M("workshop")->where("isenable=0")->field('org_id')->select();
				$org_rows_last=M("organization")->where("org_id='".$workshop_enable[0]['org_id']."'")->field("org_id,org_name")->select();
				
				$workshop_rows=$obj->getOrgTreeRows($workshop_enable[0]['org_id']);
				$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
			}
			else{  //当前登录人即不是admin，也不是系统的最高管理员，那么我们就给出所在实验室就好了
				$user_org=M("user")->where("user_id='".$login_user_id."'")->field("org_id")->select();
			
				$org_rows_last=M("organization")->where("org_id='".$user_org[0]['org_id']."'")->field("org_id,org_name")->select();
				
				$workshop_rows=$obj->getOrgTreeRows($user_org[0]['org_id']);  //获取子机构
				$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
			}
		}
		$this->assign('workshop_rows',$workshop_rows);
		$this->display('Openday/add');


	}

	/*
	开放日信息入库操作
	*/
	public function insert(){
		$data_post=I('post.');

		$login_user=session('loginaccount');  //当前登录人账号id 
		$workflow=M('lec_workflow')->where('isenable=0')->field('workflow_id')->select();
		$role_id=M('user_account')->where("user_account_id={$login_user}")->field('role_id')->select();  //获取当前登录人角色
		$workflow_enable=M('lec_workflow')->where('isenable=0')->field('workflow_id')->select();//当前启用的工作流
		$workflow_enable_steps=M('workflow')->where("workflow_id={$workflow_enable[0]['workflow_id']}")->field('steps')->select();
		$steps_role=M('workflowstep')->where("workflow_id={$workflow_enable[0]['workflow_id']} and step_now={$workflow_enable_steps[0]['steps']}")->field('role_id')->select();

		if($role_id[0]['role_id']==1||$steps_role[0]['role_id']==$role_id[0]['role_id']){  //表示当前登录人是系统最高管理员或者是工作流的最后一个审核人
			$open_ex_status=3;
			$open_wof_step_now=$workflow_enable_steps[0]['steps'];
			$open_rel_status=1;
		}
		else{
			$account_step_now=M('workflowstep')->where("workflow_id={$workflow_enable[0]['workflow_id']} and role_id={$role_id[0]['role_id']}")->field('step_now')->select();
			$open_wof_step_now=$account_step_now[0]['step_now'];      //当前登录人对应的工作流中所在步骤
			$open_ex_status=0;
			$open_rel_status=0;
		}

		$data=array(
			'open_theme'=>$data_post['open_theme'],
			'open_start'=>strtotime($data_post['open_start']),
			'open_end'=>date($data_post['open_end']),
			'open_location'=>$data_post['open_location'],
			'open_workshop'=>$data_post['open_workshop'],
			'open_thumb'=>$data_post['open_thumb'],
			'open_content'=>$data_post['open_content'],
			'open_ex_status'=>$open_ex_status,
			'open_wof_step_now'=>$open_wof_step_now,
			'open_rel_status'=>$open_rel_status,
			'open_add_time'=>time(),
			'open_add_user'=>$login_user
			);
		$obj=new OpendayModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	列表数据获取操作
	*/
	public function ajaxIndex(){
		$order=I('get.sort')." ".I('get.order');
		$open_ex_status=I("get.lec_ex_status");
		$where="";

		$workflow_enable=M('lec_workflow')->where('isenable=0')->field('workflow_id')->select();//当前启用的工作流		
		$account=session('loginaccount');  //当前人登录账号
		$role=M('user_account')->where("user_account_id={$account}")->field('role_id')->select();
		$workflow_enable_steps=M('workflow')->where("workflow_id={$workflow_enable[0]['workflow_id']}")->field('steps')->select();

		if($role[0]['role_id']!=1){  //表示当前登录人不是系统最高管理员
			$login_user=M('user_account')->where("user_account_id={$account}")->field('user_id')->select();  //当前人的实验室id	
			$login_workshop=M('user')->where("user_id={$login_user[0]['user_id']}")->field("org_id")->select();
			
			$where.=" open_workshop={$login_workshop[0]['org_id']}";
			if($open_ex_status!=""){
				$where.=" open_workshop={$login_workshop[0]['org_id']} and open_ex_status={$open_ex_status}";
			}
			else{
				$where.=" open_workshop={$login_workshop[0]['org_id']}";
			}
		}
		else{
			$open_workshop=I('get.open_workshop');

			if(!empty($open_workshop)){
				if($open_ex_status!=""){
					$where.=" open_workshop={$open_workshop} and open_ex_status={$open_ex_status}";
				}
				else{
					$where.=" open_workshop={$open_workshop}";
				}
			}
			else{
				if($open_ex_status!=""){
					$where.=" open_ex_status={$open_ex_status}";
				}
				else{
					$where.="";
				}
			}
		}

		if(!empty($where)){
			$data=M('openday')->where($where)
			->field('open_id,open_theme,open_start,open_end,open_location,open_workshop,open_ex_status,open_add_user,open_wof_step_now,open_rel_status')
			->order($order)->page(I("get.offset"),I("get.limit"))->select();
			$data_count=M('openday')->where($where)->count();
		}
		else{
			$data=M('openday')->field('open_id,open_theme,open_start,open_end,open_location,open_workshop,open_ex_status,open_add_user,open_wof_step_now,open_rel_status')
			->order($order)->page(I("get.offset"),I("get.limit"))->select();
			//var_dump($data);die();
			$data_count=M('openday')->count();
		}

		
		
		foreach ($data as $key => $value) {
			# code...
			$data[$key]['open_start']=date("Y-m-d H:i:s",$value['open_start']);
			$data[$key]['open_end']=date("Y-m-d H:i:s",$value['open_end']);

			$workshop_row=M("organization")->where("org_id={$value['open_workshop']}")->field('org_name')->select();
			$data[$key]['open_workshop_name']=$workshop_row[0]['org_name'];

			if($account==$value['open_add_user']){
				$is_login=true;
			}
			else{
				$is_login=false;
			}

			$btn=getExAcesByWof($role[0]['role_id'],$workflow_enable[0]['workflow_id'],$value['open_wof_step_now'],$value['open_ex_status'],$is_login);
			$data[$key]['is_edit']=$btn['is_edit'];
			$data[$key]['is_del']=$btn['is_del'];
			$data[$key]['ex_status_txt']=$btn['ex_status_txt'];
			$data[$key]['is_examine']=$btn['is_examine'];
			$data[$key]['is_send_ex']=$btn['is_send_ex'];

			if($workflow_enable_steps[0]['steps']==$value['open_wof_step_now']&&$value['open_ex_status']==3){
				$data[$key]['re_status']='<label style="color:#5CB85C;">已发布</label>';
			}
			else{
				$data[$key]['re_status']='<label style="color:red;">未发布</label>';
			}
		}
		//var_dump($data);die();

		$return=array(
			"total"=>$data_count,
			"data"=>$data
			);

		$this->ajaxReturn($return,"JSON");	
	}

	/*
	送审操作
	*/
	public function sendEx(){
		$open_id=I("post.open_id");  //获取过来的lec_id	
		$account=session('loginaccount');
		$row=M('openday')->where("open_id={$open_id}")->field('open_wof_step_now')->select();
		//送审操作，即将当前的状态改为1，将当前的审核步骤+1，填入审核人和审核时间
		$data=array(
			'open_ex_status'=>1,
			'open_ex_user'=>$account,
			'open_ex_time'=>time(),
			'open_wof_step_now'=>$row[0]['open_wof_step_now']+1
		);	
		$obj=new OpendayModel();
		$return=$obj->dataEdit($open_id,$data);
		if($return['status']){
			$return['msg']="送审成功";
		}
		else{
			$return['msg']='送审失败';
		}

		$this->ajaxReturn($return,"JSON");
	}

	/*
	审核操作
	*/
	public function getExamine(){
		$open_id=I('get.open_id');
		$row=M('openday')->where("open_id={$open_id}")->select();
		//对应值转换
		$row[0]['open_start']=date('Y-m-d H:i:s',$row[0]['open_start']);
		$row[0]['open_thumb']="<img src='".UPLOAD_PATH."{$row[0]['open_thumb']}'>";
		$row[0]['open_end']=date('Y-m-d H:i:s',$row[0]['open_end']);
		$row[0]['open_workshop']=M('organization')->where("org_id={$row[0]['open_workshop']}")->field('org_name')->select();
		

		//var_dump($row);die();
		$this->assign('row',$row);
		$this->display('Openday/getExamine');
	}

	/*
	审核入库权限
	*/
	public function examine(){
		$open_id=I('post.open_id');
		$examine=I('post.examine');
		$row=M('openday')->where("open_id={$open_id}")->field('open_wof_step_now')->select();
		$workflow_enable=M('lec_workflow')->where('isenable=0')->field('workflow_id')->select();
		$workflow=M('workflow')->where("workflow_id={$workflow_enable[0]['workflow_id']}")->field('steps')->select();
		$workflow_role=M('workflowstep')->where("workflow_id={$workflow_enable[0]['workflow_id']} and step_now={$workflow[0]['steps']}")->field('role_id')->select();
		$login=session('loginaccount');
		$login_role=session('loginuserroleid');  //当前登录人对应的角色
		$open_rel_status=0;
		if($examine==2){  //表示当前审核未通过
			$open_ex_status=2;
			$open_wof_step_now=$row[0]['open_wof_step_now']-1;
		}
		else{  //当前表示审核通过
			$open_ex_status=3;
			if($row[0]['open_wof_step_now']==$workflow[0]['steps']){  //表示当前审核已经到最后一步了
				//var_dump("123");die();
				$open_wof_step_now=$workflow[0]['steps'];
				$open_rel_status=1;
			}
			else{

				if($workflow_role[0]['role_id']==$login_role){  //表示当前审核人是最后一个审核人在直接做审核
					//var_dump("324");die();
					$open_wof_step_now=$workflow[0]['steps'];
					$open_rel_status=1;
				}
				else{  
					//var_dump("364");die();
					$open_wof_step_now=$row[0]['open_wof_step_now'];
				}
				
			}

		}
		$data=array(
			'open_ex_status'=>$open_ex_status,
			"open_rel_status"=>$open_rel_status,
			'open_wof_step_now'=>$open_wof_step_now,
			'lec_exam_user'=>$login,
			'lec_exam_time'=>time()
			);
		$obj=new OpendayModel();
		$return=$obj->dataEdit($open_id,$data);
		if($return['status']){
			$return['msg']="审核成功";
		}
		else{
			$return['msg']="审核失败";
		}
		$this->ajaxReturn($return,"JSON");
	}

	/*
	修改页面显示操作
	*/
	public function edit(){
		$open_id=I('get.open_id');

		$login_account=session('loginuser');  //当前登录人账号
		$login_account_id=session('loginaccount');  //当前登录人的账号表对应的主键id
		$login_role_row=M('user_account')->where("user_account_id={$login_account_id}")->field('role_id')->select();
		$login_user_id=session('loginuserid');  //获取当前登录人的user_id
		$user_org_id=M('user')->where("user_id={$login_user_id}")->field("org_id")->select();  //获取当前登录人所属的实验室

		$obj=new OrgModel();
		if($login_account=='admin'){  //表示当前人是以admin做登陆的，则我们给出所有的实验室
			$workshop_enable=M("workshop")->where("isenable=0")->field('org_id')->select();
			$org_rows_last=M("organization")->where("org_id='".$workshop_enable[0]['org_id']."'")->field("org_id,org_name")->select();
			
			$workshop_rows=$obj->getOrgTreeRows($workshop_enable[0]['org_id']);
			$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		}
		else{
			if($login_role_row[0]['role_id']==1){  //表示当前登录人不是admin，但是系统管理员，则给出所有实验室
				$workshop_enable=M("workshop")->where("isenable=0")->field('org_id')->select();
				$org_rows_last=M("organization")->where("org_id='".$workshop_enable[0]['org_id']."'")->field("org_id,org_name")->select();
				
				$workshop_rows=$obj->getOrgTreeRows($workshop_enable[0]['org_id']);
				$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
			}
			else{  //当前登录人即不是admin，也不是系统的最高管理员，那么我们就给出所在实验室就好了
				$user_org=M("user")->where("user_id='".$login_user_id."'")->field("org_id")->select();
			
				$org_rows_last=M("organization")->where("org_id='".$user_org[0]['org_id']."'")->field("org_id,org_name")->select();
				
				$workshop_rows=$obj->getOrgTreeRows($user_org[0]['org_id']);  //获取子机构
				$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
			}
		}
		$this->assign('workshop_rows',$workshop_rows);
		$data=M('openday')->where("open_id={$open_id}")->select();
		$data[0]['open_start']=date("Y-m-d H:i:s",$data[0]['open_start']);
		$data[0]['open_end']=date("Y-m-d H:i:s",$data[0]['open_end']);
		$this->assign('data',$data);
		$this->display("Openday/edit");

	}

	/*
	修改保存入库操作
	*/
	public function update(){
		$data_post=I('post.');
		$login_user=session('loginaccount');  //当前登录人账号id 
		$data=array(
			"open_theme"=>$data_post['open_theme'],
			'open_start'=>strtotime($data_post['open_start']),
			'open_end'=>strtotime($data_post['open_end']),
			'open_thumb'=>$data_post['open_thumb'],
			'open_location'=>$data_post['open_location'],
			'open_workshop'=>$data_post['open_workshop'],
			'open_content'=>$data_post['open_content'],
			'open_edit_time'=>time(),
			"open_edit_user"=>$login_user
			);
		$obj=new OpendayModel();
		$return=$obj->dataEdit($data_post['open_id'],$data);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	数据删除操作
	*/
	public function del(){
		$ids=I('post.ids'); 
		$ids2=substr($ids,0,-1);
		$idsToArr=explode(",", $ids2);
		$obj=new OpendayModel();
		foreach ($idsToArr as $key => $value) {
			# code...
			$return=$obj->dataDel($value);
		}
		$this->ajaxReturn($return,"JSON");
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