<?php
/*
选课信息控制器
在这一部分只给普通学生访问的权限，最高级管理员不可访问
*/
namespace Admin\Controller;
use Think\Controller;

class SelectcourseController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	列表页面显示方法
	*/
	public function index(){
		//获取当前登录人信息，包括登录人账号、登录人角色
		$login_account=session("loginuser");
		$login_role_id=session('loginuserroleid');
		$course_semester="";
		if($login_role_id!=5){  //表示当前登录人不是学生，则我们不让该人进行选课操作
			$return=array(
				"status"=>1  //表示当前登录人不是学生
				);
		}
		else{
			$return=array(
				"status"=>0  //表示当前登录人是学生
				);
		}
		$this->assign("return",$return);

		$this->display("Selectcourse/index");
	}

	/*
	获取当前学期的方法
	*/
	public function getSemester(){
		$now=date("Y-m-d",time());  //获取当前时间
		$nowArr=explode("-", $now);  //获取当前年、月、日
		if($nowArr[1]>=3&&$nowArr[1]<=7){  //则表明当前是春季学期
			if($nowArr[0]>=2016&&$nowArr[0]<=2017){  //表示当前是16-17学年
				$course_semester=1;
			}
			elseif($nowArr[0]>=2017&&$nowArr[0]<=2018){
				$course_semester=3;  
			}
		}
		elseif(($nowArr[1]>=9&&$nowArr[1]<=12)||($nowArr[1]==1)){  //表示当前为秋季学期
			if($nowArr[0]>=2016&&$nowArr[0]<=2017){  //表示当前是16-17学年
				$course_semester=2;
			}
			elseif($nowArr[0]>=2017&&$nowArr[0]<=2018){
				$course_semester=4;  
			}
		}
		else{
			$course_semester=0;  //表示当前不是上课时期
		}
		return $course_semester;
	}

	/*
	获取列表数据
	在获取数据时，需要根据登录用户角色不同进行区分
	*/
	public function ajaxIndex(){
		$login_role_id=session('loginuserroleid');  //获取当前登录人角色
		$account=session('loginaccount');  //当前人登录账号
		$where="";
		if($login_role_id==1){  //表示当前管理员时系统最高管理员

		}
		else{  //表示当前人是实验室管理员
			$login_user=M('user_account')->where("user_account_id={$account}")->field('user_id')->select();  //当前人的实验室id	
			$login_workshop=M('user')->where("user_id={$login_user[0]['user_id']}")->field("org_id")->select();
			
			$where.=" course_workshop={$login_workshop[0]['org_id']}";
		}
		$course_semester_now=$this->getSemester();  //获取当前学期
		$order=I("get.sort")." ".I("get.order");
		if(!empty($where)){
			$data=M("course")->where("course_semester={$course_semester_now} and course_rel_status=1 and $where")->order($order)->page(I('get.offset'),I("get.limit"))->select();
			$count=M("course")->where("course_semester={$course_semester_now} and course_rel_status=1 and $where")->count();
		}
		else{
			$data=M("course")->where("course_semester={$course_semester_now} and course_rel_status=1")->order($order)->page(I("get.offset"),I("get.limit"))->select();
			$count=M("course")->where("course_semester={$course_semester_now} and course_rel_status=1")->count();
		}

		foreach ($data as $key => $value) {
			# code...
			if($login_role_id==1||$login_role_id==2){  //表示当前是系统管理员或实验室管理员登陆时，给出查看选课人的按钮
				$data[$key]['btnCheckStu']="ok";
			}
			else{
				$data[$key]['btnCheckStu']="error";
			}
			//转换对应的上课时间以及地点
			if(!empty($value['course_location'])){
				$location2=substr($value['course_location'],0,-1);
				$location=explode("|", $location2);
			}
			else{
				$location="";
			}

			if(!empty($value['course_time'])){
				$time2=substr($value['course_time'], 0,-1);
				$time=explode("|", $time2);
				foreach ($time as $key2 => $value2) {
					# code...
					$time['weekarr'][$key2]=explode("-", $value2);
				}
			}
			else{
				$time="";
			}

			if(!empty($value['course_zhouji'])){
				$zhouji2=substr($value['course_zhouji'], 0,-1);
				$zhouji=explode("|", $zhouji2);
			}
			else{
				$zhouji="";
			}

			if(!empty($value['course_jiang'])){
				$jiang2=substr($value['course_jiang'], 0,-1);
				$jiang=explode("|", $jiang2);
			}
			else{
				$jiang="";
			}

			$data[$key]['course_time_txt']="";
			if(!empty($location)){
				foreach ($location as $key1 => $value1) {
					# code...
					$data[$key]['course_time_txt'].=$time['weekarr'][$key1][0]."—".$time['weekarr'][$key1][1]."周"."  周".$zhouji[$key1]."  第".$jiang[$key1]."讲  ".$location[$key1]."<br/>";
				}
			}


		}

		$return=array(
			"total"=>$count,
			"data"=>$data
			);
		$this->ajaxReturn($return,"JSON");


	}

	/*
	确认选择操作
	*/
	public function selectOk(){
		$ids=I("post.ids");
		$user_account_id=session('loginaccount');
		$ids2=substr($ids, 0,-1);
		$idsToArr=explode(",", $ids2);
		if(empty($idsToArr)){
			$return=array(
				"status"=>false,
				"msg"=>"请选择课程"
				);
			return $return;
		}
		else{
			$obj=new CourseUserModel();

			foreach ($idsToArr as $key => $value) {
				# code...
				$data=array(
				"user_account_id"=>$user_account_id,
				"course_id"=>$value
				);
				$return=$obj->dataAdd($data);
			}
			if($return['status']){
				$return['msg']="选课成功";
			}
			else{
				$return['msg']="选课失败，请重试!";
			}
		}
		return $return;
	}


}