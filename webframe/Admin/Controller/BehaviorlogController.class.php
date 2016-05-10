<?php
namespace Admin\Controller;
use Think\Controller;

class BehaviorlogController extends AdminController{
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
		$this->display("index");
	}

	/*
	列表页数据
	*/
	public function ajaxIndex(){
		$order=I("post.sort")." ".I("post.order");
		if(I("post.sort")&&I("post.order")){
			$data=M("behavior_log")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
		}
		else{
			$data=M("behavior_log")->limit(I("get.offset"),I("get.limit"))->select();
		}

		foreach ($data as $key => $value) {
			# code...
			$row=M("user_account")->where("user_account_id='".$value['behavior_log_account']."'")->field("user_account")->select();
			$data[$key]['behavior_log_time']=date("Y-m-d H:i:s",$value['behavior_log_time']);
			$data[$key]['behavior_log_route']="__URL__/".$value['behavior_log_con']."/".$value['behavior_log_func'];
			$data[$key]['behavior_log_account']=$row[0]['user_account'];			
		}

		$total_count=M("behavior_log")->count();

		$return=array(
			"total"=>$total_count,
			"data"=>$data
		);
		$this->ajaxReturn($return,"JSON");
	}


}