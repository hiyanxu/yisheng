<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\UseraccModel;

class UseraccountController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	账号列表页显示
	*/
	public function index(){
		$this->display("index");  
	}

	/*
	账号添加的方法
	*/
	public function add(){
		//根据当前session信息，获取用户所在的机构id，只给出对应机构下的人
		$loginuser=session("loginuser");
		$loginuserid=session("loginuserid");
		if($loginuser=="admin"){
			$user_rows=M("user")->field("user_id,user_name")->select();
			$role_rows=M("role")->field("role_id,role_name")->select();
			$this->assign("user_rows",$user_rows);
			$this->assign("role_rows",$role_rows);
		}
		else{
			$org_id=M("user")->where("user_id='".$loginuserid."'")->field("org_id")->select();
			$user_rows=M("user")->where("org_id='".$org_id[0]['user_id']."'")->field("user_id,user_name")->select();
			$role_rows=M("role")->field("role_id,role_name")->select();
			$this->assign("user_rows",$user_rows);
			$this->assign("role_rows",$role_rows);
		}
		
		$this->display("add");  
	}

	/*
	添加保存操作
	*/
	public function insert(){
		$data_post=I("post.");
		$user_identify=md5($data_post["user_account"]."haha");
		$data=array(
			"user_account"=>$data_post['user_account'],
			'user_pwd'=>$data_post['user_pwd'],
			'user_identify'=>$user_identify,
			'user_id'=>$data_post['user_id'],
			"role_id"=>$data_post['role_id'],
			'isenable'=>$data_post['isenable'],
			"is_admin"=>$data_post['is_admin']
		);
		$obj=new UseraccModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	列表数据获取操作
	*/
	public function ajaxIndex(){
		$order=I("get.sort")." ".I("get.order");  //获取排列顺序
		if(I("get.sort")&&I("get.order")){
			if(I("get.search")){
				$data=M()->table(array("user"=>"user","user_account"=>"account"))
				->field("user.user_name,account.user_account_id,account.user_account,account.role_id,account.isenable,account.is_admin")
				->where("account.user_id=user.user_id and user.user_name like '%".I("get.search")."%'")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
			}
			else{
				$data=M()->table(array("user"=>"user","user_account"=>"account"))
				->field("user.user_name,account.user_account_id,account.user_account,account.role_id,account.isenable,account.is_admin")
				->where("account.user_id=user.user_id")->order($order)->limit(I("post.offset"),I("post.limit"))->select();
			}
		}
		else{
			if(I("get.search")){
				$data=M()->table(array("user"=>"user","user_account"=>"account"))
				->field("user.user_name,account.user_account_id,account.user_account,account.role_id,account.isenable,account.is_admin")
				->where("account.user_id=user.user_id and user.user_name like '%".I("get.search")."%'")->limit(I("get.offset"),I("get.limit"))->select();
			}
			else{
				$data=M()->table(array("user"=>"user","user_account"=>"account"))
				->field("user.user_name,account.user_account_id,account.user_account,account.role_id,account.isenable,account.is_admin")
				->where("account.user_id=user.user_id")->limit(I("get.offset"),I("get.limit"))->select();
			}
		}
		//对应字段进行转换
		foreach ($data as $key => $value) {
			# code...
			$role_row=M("role")->where("role_id='".$value['role_id']."'")->field("role_name")->select();
			$data[$key]["role_name"]=$role_row[0]['role_name'];
			$data[$key]["isenable"]=$value["isenable"]=="0"?"启用":"<label style='color:red;'>禁用</label>";
			$data[$key]["is_admin"]=$value["is_admin"]=="0"?"<label style='color:#5CB85C'>管理员</label>":"<label style='color:#F0AD4E'>普通用户</label>";
		}
		
		if(I("get.search")){
			$data_account=M()->table(array("user"=>"user","user_account"=>"account"))
					->field("user.user_name,account.user_account_id,account.user_account,account.role_id,account.isenable,account.is_admin")
					->where("account.user_id=user.user_id and user.user_name like '%".I("get.search")."%'")->count();
		}
		else{
			$data_account=M()->table(array("user"=>"user","user_account"=>"account"))
				->field("user.user_name,account.user_account_id,account.user_account,account.role_id,account.isenable,account.is_admin")
				->where("account.user_id=user.user_id")->limit(I("get.offset"),I("get.limit"))->count();
		}

		$return=array(
			"total"=>$data_account,
			"data"=>$data
		);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	账号修改页面显示
	*/
	public function edit(){
		$loginuser=session("loginuser");
		$loginuserid=session("loginuserid");
		if($loginuser=="admin"){
			$user_rows=M("user")->field("user_id,user_name")->select();
			$role_rows=M("role")->field("role_id,role_name")->select();
			$this->assign("user_rows",$user_rows);
			$this->assign("role_rows",$role_rows);
		}
		else{
			$org_id=M("user")->where("user_id='".$loginuserid."'")->field("org_id")->select();
			$user_rows=M("user")->where("org_id='".$org_id[0]['user_id']."'")->field("user_id,user_name")->select();
			$role_rows=M("role")->field("role_id,role_name")->select();
			$this->assign("user_rows",$user_rows);
			$this->assign("role_rows",$role_rows);
		}


		$user_account_id=I("get.user_account_id");  //获取账号表主键id
		$account_rows=M("user_account")->where("user_account_id='".$user_account_id."'")->select();  //获取账号信息
		$this->assign("account_rows",$account_rows);
		$this->display("edit");
	}

	/*
	修改保存操作
	*/
	public function update(){
		$data_post=I("post.");
		$user_identify=md5($data_post["user_account"]."haha");
		$data=array(
			"user_account"=>$data_post['user_account'],
			'user_pwd'=>$data_post['user_pwd'],
			'user_identify'=>$user_identify,
			'user_id'=>$data_post['user_id'],
			"role_id"=>$data_post['role_id'],
			'isenable'=>$data_post['isenable'],
			"is_admin"=>$data_post['is_admin']
		);
		$obj=new UseraccModel();
		$return=$obj->editSave($data_post['user_account_id'],$data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	删除操作
	*/
	public function del(){
		$ids=I("post.ids");  //获取post过来的ids数据
		$ids2=substr($ids,0,-1);
		$idsToArr=explode(",", $ids2);   //将字符串按特定字符切割成数组
		
		foreach ($idsToArr as $key => $value) {
			# code...
			$obj=new UseraccModel();
			$return=$obj->del($value);
		}
		$this->ajaxReturn($return,"JSON");
	}

	/*
	修改密码操作
	*/
	public function editPwd(){
		$user_account_id=I("get.user_account_id");
		$rows=M("user_account")->where("user_account_id='".$user_account_id."'")->field("user_account,user_pwd")->select();
		$this->assign("rows",$rows);
		$this->assign("user_account_id",$user_account_id);
		$this->display("User/editPwd");
	}

	/*
	密码修改保存操作
	*/
	public function editPwdSave(){
		$user_account_id=I("post.user_account_id");
		$user_pwd=I("post.user_pwd");
		$data=array(
			"user_pwd"=>$user_pwd
		);
		$accont_obj=new UseraccModel();
		$return_account=$accont_obj->editSave($user_account_id,$data);
		$this->ajaxReturn($return_account,"JSON");

	}

	/*
	404操作页面
	*/
	public function _empty(){
		header("HTTP/1.0 404 NOT　Found");
		$this->display("Empty/index");  //让他找到404页面
	}




}