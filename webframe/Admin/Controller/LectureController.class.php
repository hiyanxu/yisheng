<?php
namespace Admin\Controller;
use Think\Controller;

use Admin\Model\OrgModel;
use Admin\Model\LectureModel;

class LectureController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	讲座信息首页显示
	*/
	public function index(){
		$workshop=M('workshop')->where("isenable=0")->field('org_id')->select();
		$obj=new OrgModel();
		$workshop_rows=$obj->getOrgTreeRows($workshop[0]['org_id']);
		$this->assign('workshop_rows',$workshop_rows);
		$this->display("Lecture/index");  //调用页面
	}

	/*
	讲座信息添加页面显示
	*/
	public function add(){
		$cate_id=M('lec_duty')->where("isenable=0")->field("lec_duty_id,cate_id")->select();
		$cate_rows=M("category")->where("parentid={$cate_id[0]['cate_id']}")->field("cate_id,cate_name")->select();
		$this->assign("cate_rows",$cate_rows);

		$org_id=M("college")->where("isenable=0")->field("org_id")->select();
		$org_rows=M("organization")->where("parentid={$org_id[0]['org_id']}")->field('org_id,org_name')->select();
		$this->assign("org_rows",$org_rows);

		$loginuserid=session("loginuserid");  //获取当前登录人主键id
		$loginuser=session("loginuser");  //获取当前登录人
		
		$obj=new OrgModel();
		if($loginuser=="admin"){  //若当前登录人为admin，则给出所有的组织机构
			$workshop_enable=M("workshop")->where("isenable=0")->field('org_id')->select();
			$org_rows_last=M("organization")->where("org_id='".$workshop_enable[0]['org_id']."'")->field("org_id,org_name")->select();
			
			$workshop_rows=$obj->getOrgTreeRows($workshop_enable[0]['org_id']);
			$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		}
		else{

			$user_org=M("user")->where("user_id='".$loginuserid."'")->field("org_id")->select();
			
			$org_rows_last=M("organization")->where("org_id='".$user_org[0]['org_id']."'")->field("org_id,org_name")->select();
			
			$workshop_rows=$obj->getOrgTreeRows($user_org[0]['org_id']);  //获取子机构
			$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		}
		//var_dump($workshop_rows);die();

		$this->assign('workshop_rows',$workshop_rows);

		

		$this->display("Lecture/add");  //添加页面显示
	}

	/*
	讲座信息添加保存操作
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
			$lec_exam_status=3;
			$lec_wof_step_now=$workflow_enable_steps[0]['steps'];
			$lec_rel_status=1;
		}
		else{
			$account_step_now=M('workflowstep')->where("workflow_id={$workflow_enable[0]['workflow_id']} and role_id={$role_id[0]['role_id']}")->field('step_now')->select();
			$lec_wof_step_now=$account_step_now[0]['step_now'];      //当前登录人对应的工作流中所在步骤
			$lec_exam_status=0;
			$lec_rel_status=0;
		}


		$data=array(
			"lec_name"=>$data_post['lec_name'],
			'lec_time'=>strtotime($data_post['lec_time']),
			'lec_speaker'=>$data_post['lec_speaker'],
			'lec_duty'=>$data_post['lec_duty'],
			'lec_speaker_college'=>$data_post['lec_speaker_college'],
			'lec_college'=>$data_post['lec_college'],
			'lec_workshop'=>$data_post['lec_workshop'],
			'lec_place'=>$data_post['lec_place'],
			'lec_content'=>$data_post['lec_content'],
			'lec_add_user'=>$login_user,
			'lec_add_time'=>time(),
			'lec_exam_status'=>$lec_exam_status,
			'lec_wof_step_now'=>$lec_wof_step_now,
			'lec_rel_status'=>$lec_rel_status
			);


		$obj=new LectureModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	讲座列表数据获取
	*/
	public function ajaxIndex(){
		$order=I('get.sort')." ".I('get.order');
		$where="";

		$workflow_enable=M('lec_workflow')->where('isenable=0')->field('workflow_id')->select();//当前启用的工作流		
		$account=session('loginaccount');  //当前人登录账号
		$role=M('user_account')->where("user_account_id={$account}")->field('role_id')->select();
		$workflow_enable_steps=M('workflow')->where("workflow_id={$workflow_enable[0]['workflow_id']}")->field('steps')->select();

		if($role[0]['role_id']!=1){  //表示当前登录人不是系统最高管理员
			$login_user=M('user_account')->where("user_account_id={$account}")->field('user_id')->select();  //当前人的实验室id	
			$login_workshop=M('user')->where("user_id={$login_user[0]['user_id']}")->field("org_id")->select();
			
			$where.=" lec_workshop={$login_workshop[0]['org_id']}";
		}
		else{
			$lec_workshop=I('get.lec_workshop');
			if(!empty($lec_workshop)){
				$where.=" lec_workshop={$lec_workshop}";
			}
		}

		if(!empty($where)){
			$data=M('lecture')->where($where)
			->field('lec_id,lec_name,lec_time,lec_speaker,lec_college,lec_workshop,lec_place,lec_exam_status,lec_add_user,lec_wof_step_now,lec_rel_status')
			->order($order)->page(I("get.offset"),I("get.limit"))->select();
			$data_count=M('lecture')->where($where)->count();
		}
		else{
			$data=M('lecture')->field('lec_id,lec_name,lec_time,lec_speaker,lec_college,lec_workshop,lec_place,lec_exam_status,lec_add_user,lec_wof_step_now,lec_rel_status')
			->order($order)->page(I("get.offset"),I("get.limit"))->select();
			$data_count=M('lecture')->count();
		}

		
		
		foreach ($data as $key => $value) {
			# code...
			$data[$key]['lec_time']=date("Y-m-d H:i:s",$value['lec_time']);

			$college_row=M("organization")->where("org_id={$value['lec_college']}")->field('org_name')->select();			
			$data[$key]['org_name_college']=$college_row[0]['org_name'];
			$workshop_row=M("organization")->where("org_id={$value['lec_workshop']}")->field('org_name')->select();
			$data[$key]['org_name_workshop']=$workshop_row[0]['org_name'];

			if($account==$value['lec_add_user']){
				$is_login=true;
			}
			else{
				$is_login=false;
			}

			$btn=getExAcesByWof($role[0]['role_id'],$workflow_enable[0]['workflow_id'],$value['lec_wof_step_now'],$value['lec_exam_status'],$is_login);
			$data[$key]['is_edit']=$btn['is_edit'];
			$data[$key]['is_del']=$btn['is_del'];
			$data[$key]['ex_status_txt']=$btn['ex_status_txt'];
			$data[$key]['is_examine']=$btn['is_examine'];
			$data[$key]['is_send_ex']=$btn['is_send_ex'];

			if($workflow_enable_steps[0]['steps']==$value['lec_wof_step_now']&&$value['lec_exam_status']==3){
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
	设置where条件的方法
	*/
	public function setWhere($where=null,$type="and"){
		if(is_null($where)||!is_array($where)){
			return "";
		}
	}

	/*
	送审操作
	*/
	public function sendEx(){
		$lec_id=I("post.lec_id");  //获取过来的lec_id	
		$account=session('loginaccount');
		$row=M('lecture')->where("lec_id={$lec_id}")->field('lec_wof_step_now')->select();
		//送审操作，即将当前的状态改为1，将当前的审核步骤+1，填入审核人和审核时间
		$data=array(
			'lec_exam_status'=>1,
			'lec_exam_user'=>$account,
			'lec_exam_time'=>time(),
			'lec_wof_step_now'=>$row[0]['lec_wof_step_now']+1
		);	
		$obj=new LectureModel();
		$return=$obj->dataEdit($lec_id,$data);
		if($return['status']){
			$return['msg']="送审成功";
		}
		else{
			$return['msg']='送审失败';
		}

		$this->ajaxReturn($return,"JSON");

	}

	/*
	审核页面显示操作
	*/
	public function getExamine(){
		$lec_id=I('get.lec_id');
		$row=M('lecture')->where("lec_id={$lec_id}")->select();
		//对应值转换
		$row[0]['lec_time']=date('Y-m-d H:i:s',$row[0]['lec_time']);
		$row[0]['lec_duty']=M('category')->where("cate_id={$row[0]['lec_duty']}")->field('cate_name')->select();
		$row[0]['lec_college']=M('organization')->where("org_id={$row[0]['lec_college']}")->field('org_name')->select();
		$row[0]['lec_workshop']=M('organization')->where("org_id={$row[0]['lec_workshop']}")->field('org_name')->select();
		$row[0]['lec_speaker_college']=M('organization')->where("org_id={$row[0]['lec_speaker_college']}")->field('org_name')->select();

		//var_dump($row);die();
		$this->assign('row',$row);
		$this->display('Lecture/getExamine');

	}

	/*
	审核操作
	*/
	public function examine(){
		$lec_id=I('post.lec_id');
		$examine=I('post.examine');
		$row=M('lecture')->where("lec_id={$lec_id}")->field('lec_wof_step_now')->select();
		$workflow_enable=M('lec_workflow')->where('isenable=0')->field('workflow_id')->select();
		$workflow=M('workflow')->where("workflow_id={$workflow_enable[0]['workflow_id']}")->field('steps')->select();
		$workflow_role=M('workflowstep')->where("workflow_id={$workflow_enable[0]['workflow_id']} and step_now={$workflow[0]['steps']}")->field('role_id')->select();
		$login=session('loginaccount');
		$login_role=session('loginuserroleid');  //当前登录人对应的角色
		$lec_rel_status=0;
		if($examine==2){  //表示当前审核未通过
			$ex_status=2;
			$lec_wof_step_now=$row[0]['lec_wof_step_now']-1;
		}
		else{  //当前表示审核通过
			$ex_status=3;
			if($row[0]['lec_wof_step_now']==$workflow[0]['steps']){  //表示当前审核已经到最后一步了
				$lec_wof_step_now=$workflow[0]['steps'];
				$lec_rel_status=1;
			}
			else{

				if($workflow_role[0]['role_id']==$login_role){  //表示当前审核人是最后一个审核人在直接做审核
					$lec_wof_step_now=$workflow[0]['steps'];
					$lec_rel_status=1;
				}
				else{  
					$lec_wof_step_now=$row[0]['lec_wof_step_now'];
				}
				
			}

		}
		$data=array(
			'lec_exam_status'=>$ex_status,
			"lec_rel_status"=>$lec_rel_status,
			'lec_wof_step_now'=>$lec_wof_step_now,
			'lec_exam_user'=>$login,
			'lec_exam_time'=>time()
			);
		$obj=new LectureModel();
		$return=$obj->dataEdit($lec_id,$data);
		if($return['status']){
			$return['msg']="审核成功";
		}
		else{
			$return['msg']="审核失败";
		}
		$this->ajaxReturn($return,"JSON");

	}

	/*
	讲座修改页面显示操作
	*/
	public function edit(){
		$cate_id=M('lec_duty')->where("isenable=0")->field("lec_duty_id,cate_id")->select();
		$cate_rows=M("category")->where("parentid={$cate_id[0]['cate_id']}")->field("cate_id,cate_name")->select();
		$this->assign("cate_rows",$cate_rows);  //职称信息

		$org_id=M("college")->where("isenable=0")->field("org_id")->select();
		$org_rows=M("organization")->where("parentid={$org_id[0]['org_id']}")->field('org_id,org_name')->select();
		$this->assign("org_rows",$org_rows);  //学院信息

		$loginuserid=session("loginuserid");  //获取当前登录人主键id
		$loginuser=session("loginuser");  //获取当前登录人
		
		$obj=new OrgModel();
		if($loginuser=="admin"){  //若当前登录人为admin，则给出所有的组织机构
			$workshop_enable=M("workshop")->where("isenable=0")->field('org_id')->select();
			$org_rows_last=M("organization")->where("org_id='".$workshop_enable[0]['org_id']."'")->field("org_id,org_name")->select();
			
			$workshop_rows=$obj->getOrgTreeRows($workshop_enable[0]['org_id']);
			$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		}
		else{

			$user_org=M("user")->where("user_id='".$loginuserid."'")->field("org_id")->select();
			
			$org_rows_last=M("organization")->where("org_id='".$user_org[0]['org_id']."'")->field("org_id,org_name")->select();
			
			$workshop_rows=$obj->getOrgTreeRows($user_org[0]['org_id']);  //获取子机构
			$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		}
		//var_dump($workshop_rows);die();
		$lec_id=I('get.lec_id');
		$data=M('lecture')->where("lec_id={$lec_id}")->select();
		$this->assign('data',$data);

		$this->assign('workshop_rows',$workshop_rows);

		

		$this->display("Lecture/edit");  //添加页面显示
	}

	/*
	讲座信息修改保存操作
	*/
	public function update(){
		$data_post=I('post.');
		$login_user=session('loginaccount');  //当前登录人账号id 
		$workflow=M('lec_workflow')->where('isenable=0')->field('workflow_id')->select();
		$role_id=M('user_account')->where("user_account_id={$login_user}")->field('role_id')->select();  //获取当前登录人角色
		$workflow_enable=M('lec_workflow')->where('isenable=0')->field('workflow_id')->select();//当前启用的工作流
		$workflow_enable_steps=M('workflow')->where("workflow_id={$workflow_enable[0]['workflow_id']}")->field('steps')->select();
		$steps_role=M('workflowstep')->where("workflow_id={$workflow_enable[0]['workflow_id']} and step_now={$workflow_enable_steps[0]['steps']}")->field('role_id')->select();
		

		$data=array(
			"lec_name"=>$data_post['lec_name'],
			'lec_time'=>strtotime($data_post['lec_time']),
			'lec_speaker'=>$data_post['lec_speaker'],
			'lec_duty'=>$data_post['lec_duty'],
			'lec_speaker_college'=>$data_post['lec_speaker_college'],
			'lec_college'=>$data_post['lec_college'],
			'lec_workshop'=>$data_post['lec_workshop'],
			'lec_place'=>$data_post['lec_place'],
			'lec_content'=>$data_post['lec_content'],
			'lec_edit_user'=>$login_user,
			'lec_edit_time'=>time()
			);


		$obj=new LectureModel();
		$return=$obj->dataEdit($data_post['lec_id'],$data);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	数据删除操作
	包括批量删除
	*/
	public function del(){
		$ids=I("post.ids");
		$ids2=substr($ids, 0,-1);
		$idsToArr=explode(",", $ids2);
		$obj=new LectureModel();
		foreach ($idsToArr as $key => $value) {
			$return=$obj->dataDel($value);
		}
		$this->ajaxReturn($return,'JSON');
	}

	/*
	总结材料上传页面显示操作
	*/
	public function dataUpload(){
		$lec_id=I("get.lec_id");

		$row=M('lecture')->where("lec_id={$lec_id}")->select();
		$this->assign('row',$row);

		$this->assign('lec_id',$lec_id);
		$this->display('Lecture/dataUpload');

	}

	/*
	材料上传入库操作
	*/
	public function dataUploadSave(){
		$data_post=I('post.');
		$data=array(
			"lec_link"=>$data_post['lec_link'],
			'lec_ppt'=>$data_post['lec_ppt'],
			'lec_thumb'=>$data_post['lec_thumb']
			);
		$lec_id=I('post.lec_id');
		$obj=new LectureModel();
		$return=$obj->dataEdit($lec_id,$data);
		if($return['status']){
			$return['msg']="总结材料上传成功";
		}
		else{
			$return['msg']="总结材料上传失败";
		}
		return $return;

	}



}