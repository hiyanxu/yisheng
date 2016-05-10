<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Model\LoginLogModel;

class IndexController extends AdminController{
	/*
	构造函数
	继承父类的构造函数，主要用于检测当前人的权限
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	头部的显示
	*/
	public function head(){
		$loginuser=I("session.loginuser");
		$this->assign("loginuser",$loginuser);
		$this->display("Login/head");
	}

	/*
	首页显示
	*/
	public function index(){
		$this->display("Login/index");
	}

	/*
	左边菜单栏显示
	*/
	public function left(){
		$login_username=session("loginuser");  //获取登录人的用户名session信息
		if($login_username=="admin"){  //表示当前登录人是admin，则给出所有的菜单权限
			$menu_top_rows=$this->getmenu(0);  //获取所有的最顶级菜单
			$menu_second_rows=array();
			$menu_second_rows=M("menu")->where("parentid!=0 and ishidden=0")->order("sort asc")->field("menu_id,menu_name,menu_url,parentid")->select();
			//var_dump($menu_second_rows);
			
		}
		else{  //若当前登录人不是admin，则我们应该根据登录人角色，查找出当前人的菜单权限
			$role_id=session("loginuserroleid");  //获取当前登录人的角色role_id
			$menu_ids=M("role_menu")->where("role_id='".$role_id."'")->field("menu_id")->select();  //获取所有的菜单主键id
			$menu_top_rows=$this->getmenu(0);
			foreach($menu_ids as $key=>$val){
				$parentid=M("menu")->where("menu_id='".$val['menu_id']."'")->field("parentid")->select();
				$get_rows=M("menu")->where("menu_id='".$val['menu_id']."'")->field("menu_id,menu_name,menu_url,parentid")->order("sort asc")->select();
				$menu_second_rows[]=$get_rows[0];
			}
		}
		//var_dump($menu_second_rows);
		$this->assign("menu_top_rows",$menu_top_rows);
		$this->assign("menu_second_rows",$menu_second_rows);
		$this->display("Login/left");
	}

	/*
	右边的显示
	*/
	public function right(){
		$loginuser=session("loginuser");
		$loginaccount=session("loginaccount");
		$loginuserid=session("loginuserid");  //获取user_id
		$row=M("user")->where("user_id='$loginuserid'")->field("user_name")->select();
		$row_account=M("user_account")->where("user_account_id='$loginaccount'")->field("user_account,last_log_ip,last_log_time")->select();
		if(!empty($row)&&!empty($row_account)){
			$data=array(
				"loginaccount"=>$row_account[0]['user_account'],
				"user_name"=>$row[0]['user_name'],
				"last_log_ip"=>$row_account[0]['last_log_ip'],
				"last_log_time"=>date("Y-m-d H:i:s",$row_account[0]['last_log_time'])
				);
		}
		else{
			$data=array(
				"loginaccount"=>$row_account[0]['user_account'],
				"user_name"=>"",
				"last_log_ip"=>$row_account[0]['last_log_ip'],
				"last_log_time"=>date("Y-m-d H:i:s",$row_account[0]['last_log_time'])
				);
		}
		
		$this->assign("data",$data);
		$this->display("Login/right");
	}

	/*
	获取菜单的方法
	*/
	public function getmenu($parentid=0){
		$rows=M("menu")->where("parentid='".$parentid."' and ishidden=0")->field("menu_id,menu_name,parentid,menu_url")->order("sort asc")->select();
		return $rows;
	}

	/*
	系统登出的方法
	*/
	public function logout(){
		$loginaccountid=session("loginaccount");
		$login_account_row=M("user_account")->where("user_account_id='$loginaccountid'")->field("user_account")->select();
		cookie("cookie_loginuser",null);
		session('loginuser',null);
		session('loginuserid',null);
		session('loginuserroleid',null);
		session('loginaccount',null);

		$login_ip=$_SERVER['REMOTE_ADDR'];
			$data=array(
				"login_ip"=>$login_ip,
				"login_account"=>$login_account_row[0]['user_account'],
				"login_time"=>time(),
				"login_operation"=>1,   //0:登录  1：登出
				"login_status"=>0,  //1:表示当前登录失败  0:表示当前登录成功
				"is_remember"=>1  //0：表示当前记住  1：表示当前不是记住我
				);
			$obj=new LoginLogModel();
			$return=$obj->dataAdd($data);


		$this->redirect("Login/showLogin");

	}

	/*
	404操作页面
	*/
	public function _empty(){
		header("HTTP/1.0 404 NOT　Found");
		$this->display("Empty/index");  //让他找到404页面
	}


}