<?php
namespace Admin\Controller;
use Think\Controller;

use Admin\Model\OrgModel;

class CourseController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	列表页显示
	*/
	public function index(){
		$workshop=M('workshop')->where("isenable=0")->field('org_id')->select();
		$org_rows_last=M("organization")->where("org_id='".$workshop[0]['org_id']."'")->field("org_id,org_name")->select();
		
		$obj=new OrgModel();
		
		$workshop_rows=$obj->getOrgTreeRows($workshop[0]['org_id']);
		$workshop_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		$this->assign('workshop_rows',$workshop_rows);
		$this->display('Course/index');
	}

	/*
	课程添加页面显示
	*/
	public function add(){

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
		$this->display("Course/add");
	}

	/*
	数据入库操作
	*/
	public function insert(){
		$data_post=I('post.');
		//var_dump($data_post);die();

		$login_user=session('loginaccount');  //当前登录人账号id 
		$workflow=M('lec_workflow')->where('isenable=0')->field('workflow_id')->select();
		$role_id=M('user_account')->where("user_account_id={$login_user}")->field('role_id')->select();  //获取当前登录人角色
		$workflow_enable=M('lec_workflow')->where('isenable=0')->field('workflow_id')->select();//当前启用的工作流
		$workflow_enable_steps=M('workflow')->where("workflow_id={$workflow_enable[0]['workflow_id']}")->field('steps')->select();
		$steps_role=M('workflowstep')->where("workflow_id={$workflow_enable[0]['workflow_id']} and step_now={$workflow_enable_steps[0]['steps']}")->field('role_id')->select();

		if($role_id[0]['role_id']==1||$steps_role[0]['role_id']==$role_id[0]['role_id']){  //表示当前登录人是系统最高管理员或者是工作流的最后一个审核人
			$course_ex_status=3;
			$course_wof_step_now=$workflow_enable_steps[0]['steps'];
			$course_rel_status=1;
		}
		else{
			$account_step_now=M('workflowstep')->where("workflow_id={$workflow_enable[0]['workflow_id']} and role_id={$role_id[0]['role_id']}")->field('step_now')->select();
			$course_wof_step_now=$account_step_now[0]['step_now'];      //当前登录人对应的工作流中所在步骤
			$course_ex_status=0;
			$course_rel_status=0;
		}

		$course_location=I('post.location1')."|";
		$course_time=I('post.week1')."-".I('post.week2')."|";
		foreach ($data_post['location'] as $key => $value) {
			# code...
			$course_location.=$value."|";
		}
		foreach ($data_post['week_start'] as $key => $value) {
			# code...	
			$course_time.=$value."-".$data_post['week_end'][$key]."|";
		}


		$data=array(
			'course_name'=>$data_post['course_name'],
			'course_start'=>strtotime($data_post['course_start']),
			'course_end'=>date($data_post['course_end']),
			'course_semester'=>$data_post['course_semester'],
			'course_workshop'=>$data_post['course_workshop'],
			'course_speaker'=>$data_post['course_speaker'],
			'course_hours'=>$data_post['course_hours'],
			'course_location'=>$data_post['course_location'],
			'course_time'=>$data_post['course_time'],
			'course_ex_status'=>$course_ex_status,
			'course_wof_step_now'=>$course_wof_step_now,
			'course_rel_status'=>$course_rel_status,
			'course_add_time'=>time(),
			'course_add_user'=>$login_user
			);
		$obj=new CourseModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");

	}




}