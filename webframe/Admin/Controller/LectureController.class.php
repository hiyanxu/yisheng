<?php
namespace Admin\Controller;
use Think\Controller;

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
		$this->display("Lecture/index");  //调用页面
	}

	/*
	讲座信息添加页面显示
	*/
	public function add(){
		$cate_id=M('lec_duty')->where("isenable=0")->field("lec_duty_id,cate_id")->first();
		$cate_rows=M("category")->where("parentid={$cate_id[0]['cate_id']}")->field("cate_id,cate_name")->select();
		$this->assign("cate_rows",$cate_rows);

		$org_id=M("college")->where("isenable=0")->field("org_id")->first();
		$org_rows=M("organization")->where("org_id={$org_id[0]['org_id']}")->field('org_id,org_name')->select();
		$this->assign("org_rows",$org_rows);

		

		$this->display("Lecture/add");  //添加页面显示
	}

	/*
	
	*/



}