<?php
namespace Admin\Controller;
use Think\Controller;

use Admin\Model\OrgModel;
use Admin\Model\CourseModel;

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
			$flag=0;  //开启
		}
		$is_select_row=M("course")->where("course_semester={$course_semester} and course_rel_status=1")->field("course_is_select")->limit(1)->select();
		//var_dump($is_select_row);die();
		if(!empty($is_select_row)){
			if($is_select_row[0]['course_is_select']==1){  //表示当前正在关闭选课
				$flag=0;  //开启
			}
			else{
				$flag=1; //关闭
			}
		}
		else{
			$flag=3;  //表示当前可用没有数据
		}
		
		$this->assign('flag',$flag);

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

		$course_zhouji=I('post.zhouji1')."|";
		$course_jiang=I('post.jiang1')."|";
		foreach ($data_post['zhouji'] as $key => $value) {
			# code...
			$course_zhouji.=$value."|";
		}
		foreach ($data_post['jiang'] as $key => $value) {
			# code...
			$course_jiang.=$value."|";
		}
		//var_dump($course_zhouji,$course_jiang);die();


		$data=array(
			'course_name'=>$data_post['course_name'],
			'course_start'=>strtotime($data_post['course_start']),
			'course_end'=>strtotime($data_post['course_end']),
			'course_semester'=>$data_post['course_semester'],
			'course_workshop'=>$data_post['course_workshop'],
			'course_speaker'=>$data_post['course_speaker'],
			'course_hours'=>$data_post['course_hours'],
			'course_zhouji'=>$course_zhouji,
			"course_jiang"=>$course_jiang,
			'course_location'=>$course_location,
			'course_time'=>$course_time,
			'course_content'=>$data_post['course_content'],
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

	/*
	列表数据获取操作
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
			
			$where.=" course_workshop={$login_workshop[0]['org_id']}";
		}
		else{
			$course_workshop=I('get.course_workshop');
			if(!empty($course_workshop)){
				$where.=" course_workshop={$course_workshop}";
			}
		}

		if(!empty($where)){
			$data=M('course')->where($where)
			->field('course_id,course_name,course_start,course_semester,course_speaker,course_hours,course_workshop,course_ex_status,course_add_user,course_wof_step_now,course_rel_status')
			->order($order)->page(I("get.offset"),I("get.limit"))->select();
			$data_count=M('course')->where($where)->count();
		}
		else{
			$data=M('course')->field('course_id,course_name,course_start,course_semester,course_speaker,course_hours,course_workshop,course_ex_status,course_add_user,course_wof_step_now,course_rel_status')
			->order($order)->page(I("get.offset"),I("get.limit"))->select();
			//var_dump($data);die();
			$data_count=M('course')->count();
		}

		
		
		foreach ($data as $key => $value) {
			# code...
			$data[$key]['course_start']=date("Y-m-d H:i:s",$value['course_start']);
			switch ($data[$key]['course_semester']) {
				case '1':
					# code...
				$data[$key]['course_semester_txt']='16-17春季学期';
					break;
				case '2':
					# code...
				$data[$key]['course_semester_txt']='16-17秋季学期';
					break;
				case '3':
					# code...
				$data[$key]['course_semester_txt']='17-18春季学期';
					break;
				case '14':
					# code...
				$data[$key]['course_semester_txt']='17-18秋季学期';
					break;
				
				default:
					# code...
				$data[$key]['course_semester_txt']='暂无';
					break;
			}

			$workshop_row=M("organization")->where("org_id={$value['course_workshop']}")->field('org_name')->select();
			$data[$key]['course_workshop_name']=$workshop_row[0]['org_name'];

			if($account==$value['course_add_user']){
				$is_login=true;
			}
			else{
				$is_login=false;
			}

			$btn=getExAcesByWof($role[0]['role_id'],$workflow_enable[0]['workflow_id'],$value['course_wof_step_now'],$value['course_ex_status'],$is_login);
			$data[$key]['is_edit']=$btn['is_edit'];
			$data[$key]['is_del']=$btn['is_del'];
			$data[$key]['ex_status_txt']=$btn['ex_status_txt'];
			$data[$key]['is_examine']=$btn['is_examine'];
			$data[$key]['is_send_ex']=$btn['is_send_ex'];

			$course_semester_now=$this->getSemester();

			if($workflow_enable_steps[0]['steps']==$value['course_wof_step_now']&&$value['course_ex_status']==3){
				$data[$key]['re_status']='<label style="color:#5CB85C;">已发布</label>';
				if($course_semester_now==$data[$key]['course_semester']){  //表示当前是以发布的且是当前学期
					$data[$key]['is_stu']="ok";
				}
				else{
					$data[$key]['is_stu']="error";
				}
			}
			else{
				$data[$key]['re_status']='<label style="color:red;">未发布</label>';
				$data[$key]['is_stu']="error";
			}
		}

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
		$course_id=I("post.course_id");  //获取过来的lec_id	
		$account=session('loginaccount');
		$row=M('course')->where("course_id={$course_id}")->field('course_wof_step_now')->select();
		//送审操作，即将当前的状态改为1，将当前的审核步骤+1，填入审核人和审核时间
		$data=array(
			'course_ex_status'=>1,
			'course_ex_user'=>$account,
			'course_ex_time'=>time(),
			'course_wof_step_now'=>$row[0]['course_wof_step_now']+1
		);	
		$obj=new CourseModel();
		$return=$obj->dataEdit($course_id,$data);
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
		$course_id=I('get.course_id');
		$row=M('course')->where("course_id={$course_id}")->select();
		//对应值转换
		$row[0]['course_start']=date('Y-m-d H:i:s',$row[0]['course_start']);
		$row[0]['course_end']=date('Y-m-d H:i:s',$row[0]['course_end']);
		
		$row[0]['course_workshop']=M('organization')->where("org_id={$row[0]['course_workshop']}")->field('org_name')->select();
		switch ($row[0]['course_semester']) {
				case '1':
					# code...
				$row[0]['course_semester']='16-17春季学期';
					break;
				case '2':
					# code...
				$row[0]['course_semester']='16-17秋季学期';
					break;
				case '3':
					# code...
				$row[0]['course_semester']='17-18春季学期';
					break;
				case '14':
					# code...
				$row[0]['course_semester']='17-18秋季学期';
					break;
				
				default:
					# code...
				$row[0]['course_semester']='暂无';
					break;
			}

		if(!empty($row[0]['course_location'])){
			$location2=substr($row[0]['course_location'], 0,-1);
			$location=explode("|", $location2);
		}
		else{
			$row[0]['course_location']="";
		}
		
		if(!empty($row[0]['course_time'])){
			$time2=substr($row[0]['course_time'], 0,-1);
			$time=explode("|", $time2);
		}
		else{
			$time="";
		}
		
		if(!empty($row[0]['course_zhouji'])){
			$zhouji2=substr($row[0]['course_zhouji'], 0,-1);
			$zhouji=explode("|", $zhouji2);
		}
		else{
			$zhouji="";
		}
		
		if(!empty($row[0]['course_jiang'])){
			$jiang2=substr($row[0]['course_jiang'], 0,-1);
			$jiang=explode("|", $jiang2);
		}
		else{
			$jiang="";
		}
		//var_dump($location,$time,$zhouji,$jiang);die();

		$this->assign("location",$location);
		$this->assign('time',$time);
		$this->assign("zhouji",$zhouji);
		$this->assign("jiang",$jiang);

		//var_dump($row);die();
		$this->assign('row',$row);
		$this->display('Course/getExamine');
	}

	/*
	审核操作
	*/
	public function examine(){
		$course_id=I('post.course_id');
		$examine=I('post.examine');
		$row=M('course')->where("course_id={$course_id}")->field('course_wof_step_now')->select();
		$workflow_enable=M('lec_workflow')->where('isenable=0')->field('workflow_id')->select();
		$workflow=M('workflow')->where("workflow_id={$workflow_enable[0]['workflow_id']}")->field('steps')->select();
		$workflow_role=M('workflowstep')->where("workflow_id={$workflow_enable[0]['workflow_id']} and step_now={$workflow[0]['steps']}")->field('role_id')->select();
		$login=session('loginaccount');
		$login_role=session('loginuserroleid');  //当前登录人对应的角色
		$course_rel_status=0;
		//var_dump("222");die();
		if($examine==2){  //表示当前审核未通过
			$course_ex_status=2;
			$course_wof_step_now=$row[0]['course_wof_step_now']-1;
		}
		else{  //当前表示审核通过
			$course_ex_status=3;
			if($row[0]['course_wof_step_now']==$workflow[0]['steps']){  //表示当前审核已经到最后一步了
				//var_dump("123");die();
				$course_wof_step_now=$workflow[0]['steps'];
				$course_rel_status=1;
			}
			else{

				if($workflow_role[0]['role_id']==$login_role){  //表示当前审核人是最后一个审核人在直接做审核
					//var_dump("324");die();
					$course_wof_step_now=$workflow[0]['steps'];
					$course_rel_status=1;
				}
				else{  
					//var_dump("364");die();
					$course_wof_step_now=$row[0]['course_wof_step_now'];
				}
				
			}

		}
		$data=array(
			'course_ex_status'=>$course_ex_status,
			"course_rel_status"=>$course_rel_status,
			'course_wof_step_now'=>$course_wof_step_now,
			'course_ex_user'=>$login,
			'course_ex_time'=>time()
			);
		$obj=new CourseModel();
		$return=$obj->dataEdit($course_id,$data);
		if($return['status']){
			$return['msg']="审核成功";
		}
		else{
			$return['msg']="审核失败";
		}
		$this->ajaxReturn($return,"JSON");
	}

	/*
	课程修改页面显示
	*/
	public function edit(){
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
		$course_id=I('get.course_id');  //得到课程主键id
		$data=M("course")->where("course_id={$course_id}")->select();
		if(!empty($data[0]['course_location'])){
			$location2=substr($data[0]['course_location'],0,-1);
			$location=explode("|", $location2);
		}
		else{
			$location="";
		}

		if(!empty($data[0]['course_time'])){
			$time2=substr($data[0]['course_time'], 0,-1);
			$time=explode("|", $time2);
			foreach ($time as $key => $value) {
				# code...
				$time['weekarr'][$key]=explode("-", $value);
			}
		}
		else{
			$time="";
		}

		if(!empty($data[0]['course_zhouji'])){
			$zhouji2=substr($data[0]['course_zhouji'], 0,-1);
			$zhouji=explode("|", $zhouji2);
		}
		else{
			$zhouji="";
		}
		
		if(!empty($data[0]['course_jiang'])){
			$jiang2=substr($data[0]['course_jiang'], 0,-1);
			$jiang=explode("|", $jiang2);
		}
		else{
			$jiang="";
		}
		//var_dump($zhouji,$jiang);die();
		$data[0]['course_start']=date("Y-m-d H:i:s",$data[0]['course_start']);
		$data[0]['course_end']=date("Y-m-d H:i:s",$data[0]['course_end']);
		$this->assign("data",$data);
		$this->assign('time',$time);
		$this->assign("location",$location);
		$this->assign("zhouji",$zhouji);
		$this->assign("jiang",$jiang);

		$this->assign('workshop_rows',$workshop_rows);
		$this->display("Course/edit");
	}


	/*
	修改保存的方法
	*/
	public function update(){
		$data_post=I('post.');
		$course_id=I('post.course_id');

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

		$course_zhouji=I('post.zhouji1')."|";
		$course_jiang=I('post.jiang1')."|";
		foreach ($data_post['zhouji'] as $key => $value) {
			# code...
			$course_zhouji.=$value."|";
		}
		foreach ($data_post['jiang'] as $key => $value) {
			# code...
			$course_jiang.=$value."|";
		}
		//var_dump($course_zhouji,$course_jiang);die();


		$data=array(
			'course_name'=>$data_post['course_name'],
			'course_start'=>strtotime($data_post['course_start']),
			'course_end'=>strtotime($data_post['course_end']),
			'course_semester'=>$data_post['course_semester'],
			'course_workshop'=>$data_post['course_workshop'],
			'course_speaker'=>$data_post['course_speaker'],
			'course_hours'=>$data_post['course_hours'],
			'course_zhouji'=>$course_zhouji,
			"course_jiang"=>$course_jiang,
			'course_location'=>$course_location,
			'course_time'=>$course_time,
			'course_content'=>$data_post['course_content'],
			'course_edit_time'=>time(),
			'course_edit_user'=>$login_user
			);
		$obj=new CourseModel();
		$return=$obj->dataEdit($course_id,$data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	数据删除操作
	*/
	public function del(){
		$ids=I("post.ids");
		if(empty($ids)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
				);
			return $return;
		}
		$ids2=substr($ids, 0,-1);
		$idsToArr=explode(",", $ids2);
		$obj=new CourseModel();
		foreach ($idsToArr as $key => $value) {
			# code...
			$return=$obj->dataDel($value);
		}
		$this->ajaxReturn($return,"JSON");

	}

	/*
	信息补全页面显示操作
	*/
	public function dataUpload(){
		$course_id=I("get.course_id");
		$this->assign("course_id",$course_id);
		$this->display("Course/dataUpload");
	}

	/*
	信息补全入库操作
	*/
	public function dataUploadSave(){
		$course_id=I("post.course_id");
		$course_num=I("post.course_num");
		$course_score=I("post.course_num");
		$data=array(
			"course_num"=>$course_num,
			"course_score"=>$course_score
			);
		$obj=new CourseModel();
		$return=$obj->dataEdit($course_id,$data);
		if($return['status']){
			$return['msg']="补全成功";
		}
		else{
			$return['msg']="补全失败";
		}
		$this->ajaxReturn($return,"JSON");
	}

	/*
	开放选课设置
	*/
	public function openSelect(){
		$flag=I('post.flag');
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
			$return=array(
				"status"=>false,
				"msg"=>"当前非上课时期，无法开启选课"
				);
			$this->ajaxReturn($return,"JSON");
		}
		$data=array(
			"course_is_select"=>$flag
			);
		//var_dump($data,$course_semester);die();
		$obj=new CourseModel();
		$return=$obj->openSelect($course_semester,$data);
		if($flag==0){
			if($return['status']){
				$return['msg']="开启成功";
			}
			else{
				$return['msg']="开启失败";
			}
		}
		else{
			if($return['status']){
				$return['msg']="关闭成功";
			}
			else{
				$return['msg']="关闭失败";
			}
		}
		$this->ajaxReturn($return,"JSON");
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
	获取当前课程选课列表页显示操作
	在这里我不需要做限制，我仅仅做一个数据的显示就可以了
	*/
	public function getSelectStu(){
		$course_id=I("get.course_id");
		$course_speaker=M("course")->where("course_id={$course_id}")->field("course_name,course_speaker")->select()->limit(1);
		$this->assign("course_speaker",$course_speaker[0]['course_speaker']);
		$this->assign("course_id",$course_id);
		$this->assign("course_name",$course_name);
		$this->display("Course/getSelectStu");
	}

	/*
	获取选课学生信息
	*/
	public function ajaxSelectStuIndex(){
		$course_id=I("get.course_id");  //获取课程id

		$order=I("get.sort")." ".I("get.order");  //获取排序方式
		$data=M("user_course")->where("course_id={$course_id}")->field("user_account_id")->order($order)->page(I("get.offset"),I("get.limit"))->select(); 
		foreach ($data as $key => $value) {
			# code...
			$user_id_rows=M("user_account")->where("user_account_id={$value['user_account_id']}")->field("user_id")->select();
			$user_info=M("user")->where("user_id={$user_id_rows[0]['user_id']}")->field("user_name,user_sex,org_id,user_phone")->select()->limit(1);
			$data[$key]['user_name']=$user_info[0]['user_name'];
			$data[$key]['user_sex']=$user_info[0]['user_sex']==0?"男":"女";
			$data[$key]['user_phone']=$user_info[0]['user_phone'];
			$user_workshop=M("organization")->where("org_id={$user_info[0]['org_id']}")->field("org_name")->select()->limit(1);
			$data[$key]['course_workshop_name']=$user_workshop[0]['org_name'];
		}
		$count=M("user_course")->where("course_id={$course_id}")->field("user_account_id")->count();
		$return=array(
			"total"=>$count,
			"data"=>$data
			);
		$this->ajaxReturn($return,"JSON");


	}






}