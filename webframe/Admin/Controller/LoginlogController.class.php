<?php
/*
行为日志控制器
*/
namespace Admin\Controller;
use Think\Controller;

class LoginlogController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	后台首页显示
	*/
	public function index(){
		$this->display("index");
	}

	/*
	列表数据获取
	*/
	public function ajaxIndex(){
		$order=I("get.sort")." ".I("get.order");
		if(I("get.sort")&&I("get.order")){
			$data=M("login_log")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
		}
		else{
			$data=M("login_log")->limit(I("get.offset"),I("get.limit"))->select();
		}

		foreach ($data as $key => $value) {
			# code...
			$data[$key]['login_time']=date("Y-m-d H:i:s",$value['login_time']);
			$data[$key]['login_operation_text']=$value['login_operation']==0?"<label style='color:#39AEF5;'>登录</label>":"<label style='color:red;'>退出</label>";
			$data[$key]['is_remember_text']=$value['is_remember']==0?"是":"否";
			$data[$key]['login_status_text']=$value['login_status']==0?"<label style='color:#39AEF5;'>成功</label>":"<label style='color:red;'>失败</label>";
		}

		$total_count=M("login_log")->count();

		$return=array(
			"total"=>$total_count,
			"data"=>$data
		);
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