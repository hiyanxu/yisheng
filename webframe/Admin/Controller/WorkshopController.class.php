<?php
/*
实验室控制器
*/
namespace Admin\Controller;
use Think\Controller;

use Admin\Model\OrgModel;

class WorkshopController extends OrgSelectController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	首页显示
	*/
	public function index(){
		$this->display('Workshop/index');
	}

	/*
	选取页显示
	*/
	public function add(){
		$org_rows=M("organization")->where("parentid=0 and ishidden=0")->field("org_id,org_name")->select();  //只取出最顶级菜单
		//var_dump($org_rows);die();
		$this->assign("org_rows",$org_rows);
		$this->display("Workshop/add");
	}

	/*
	实验室选取入库操作
	*/
	public function insert(){
		$data=I("post.");
		if($data['isenable']=="0"){
			$this->_setCollegeEnable('workshop',1);
		}
		//var_dump($data);die();
		$return=$this->colSelectInsert('workshop',$data['org_id'],$data['isenable']);  //调用继承过来的方法，进行数据入库
		//var_dump($return);die();
		$this->ajaxReturn($return);
	}

	/*
	列表数据显示
	注意点：
		（1）根据当前登录人的不同判断应该被显示的实验室信息
		（2）将最上层的信息一起显示
	*/
	public function ajaxIndex(){	
		$login_user=session('loginuser');  //获取当前登录人的登录账号
		$login_user_id=session('loginuserid');  //获取当前登录人对应的user_id
		$login_user_role=session('loginuserroleid');  //获取登录人角色
		$order=I("get.sort")." ".I("get.order");

		if($login_user_role==1){  //表示当前人是系统最高管理员，则应该给出所有实验室
			$workshop=M('workshop')->where('isenable=0')->field('org_id')->select();
			
			$data=M('organization')->where("parentid={$workshop[0]['org_id']}")->field('org_id,org_name,org_english_name,org_user_id,org_icon,org_location')
				->order($order)->limit(I("get.offset"),I("get.limit"))->select();
			$data_count=M('organization')->where("parentid={$workshop[0]['org_id']}")->field('org_id,org_name,org_english_name,org_user_id,org_icon,org_location')
				->count();
		}
		else{
			$login_user_org=M('user')->where("user_id={$login_user_id}")->field("org_id")->select();
			$data=M('organization')->where("org_id={$login_user_org[0]['org_id']}")->field('org_id,org_name,org_english_name,org_user_id,org_icon,org_location')
				->order($order)->limit(I("get.offset"),I("get.limit"))->select();
			$data_count=M('organization')->where("org_id={$login_user_org[0]['org_id']}")->count();
		}

		//对应数据转换
		foreach ($data as $key => $value) {
			# code...
			$user_name=M('user')->where("user_id={$value['org_user_id']}")->field('user_name')->select();
			$data[$key]['user_name']=$user_name[0]['user_name'];
			//$data[$key]['org_icon']="<img src=".UPLOAD_PATH."{$value['org_icon']} />";
			$data[$key]['btn_edit']="ok";  //修改权限应该都具有

		}

		$return=array(
			"total"=>$data_count,
			"data"=>$data
		);

		$this->ajaxReturn($return,"JSON");		

	}

	/*
	实验室添加操作
	*/
	public function add_workshop(){
		$parent_row=M("workshop")->where("isenable=0")->field('org_id')->select();  //获取当前选取的组织机构的id
		if(empty($parent_row)){
			$return=array(
				"status"=>false,
				"msg"=>"请先进行实验室选取"
				);
			return $return;
		}
		$fid=$parent_row[0]['org_id'];  //获取父id
		if($fid==""){
			$return=array(
				"status"=>false,
				"msg"=>"网络通信错误，请重试"
			);
			return $return;
		}
		else{
			$row=M("organization")->where("org_id='".$fid."'")->field("org_name")->select();
			$this->assign("row",$row);
			$this->assign("fid",$fid);
			$this->display("Org/addChild");
		}
	}


}