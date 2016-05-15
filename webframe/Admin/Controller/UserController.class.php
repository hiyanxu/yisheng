<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\UserModel;
use Admin\Model\UseraccModel;
use Admin\Model\OrgModel;

class UserController extends Controller{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	管理员列表页显示
	*/
	public function index(){
		$this->display("index");
	}

	/*
	用户添加页面显示（包括普通用户和管理员）
	*/
	public function add(){
		$isAdmin=I("get.isAdmin");
		$role_rows=M("role")->field("role_id,role_name")->select();

		$loginuser=session("loginuser");  //获取登录账号的session信息
		$loginuserid=session("loginuserid");  //获取登录人对应user表主键id
		$org_obj=new OrgModel();
		if($loginuser=="admin"){  //若当前登录人是admin，默认给出所有组织机构			
			$org_rows=$org_obj->getOrgTreeRows(0);
		}
		else{
			$user_row=M("user")->where("user_id='".$loginuserid."'")->field("org_id")->select();  //获取对应管理员所在的机构org_id
			$org_rows=$org_obj->getOrgTreeRows($user_row[0]['org_id']);  //获取此用户对应机构及该机构的下属机构
		}

		$this->assign("org_rows",$org_rows);

		$this->assign("role_rows",$role_rows);
		$this->assign("isAdmin",$isAdmin);
		$this->display("add");
	}

	/*
	数据保存方法
	*/
	public function insert(){
		$data_post=I("post.");
		$data_user=array(
			"user_name"=>$data_post['user_name'],
			"user_sex"=>$data_post['user_sex'],
			"org_id"=>$data_post['org_id'],
			"user_phone"=>$data_post['user_phone'],
			"user_email"=>$data_post['user_email'],
			"user_address"=>$data_post['user_address']
		);
		$user_obj=new UserModel();
		$return=$user_obj->dataAdd($data_user);

		$user_identify=md5($data_post["user_account"]."haha"); 
		
		$data_account=array(
			"user_id"=>$return["user_id"],
			"user_account"=>$data_post["user_account"],
			"user_pwd"=>$data_post["user_pwd"],
			"user_identify"=>$user_identify,
			"role_id"=>$data_post["role_id"],
			"isenable"=>$data_post["isenable"],
			"is_admin"=>$data_post["is_admin"]
		);
		$accont_obj=new UseraccModel();
		$return_account=$accont_obj->dataAdd($data_account);
		
		$this->ajaxReturn($return_account,"JSON");
	}

	/*
	列表数据获取
	*/
	public function ajaxIndex(){
		$isAdmin=I("get.isAdmin");  //根据参数看是获取管理员的还是用户的数据
		$order=I("get.sort")." ".I("get.order");  //获取排列顺序
		if(I("get.sort")&&I("get.order")){
			if(I("get.search")){
				$user_data=M()->table(array("user"=>"user","user_account"=>"account"))
				->field("user.user_id,user.user_name,user.org_id,account.user_account,account.role_id,account.isenable,account.user_account_id")
				->where("account.user_id=user.user_id and user.user_name like '%".I("get.search")."%' and account.is_admin='".$isAdmin."'")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
			}
			else{
				$user_data=M()->table(array("user"=>"user","user_account"=>"account"))
				->field("user.user_id,user.user_name,user.org_id,account.user_account,account.role_id,account.isenable,account.user_account_id")
				->where("account.user_id=user.user_id and account.is_admin='".$isAdmin."'")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
			}
			
		}
		else{
			if(I("get.search")){
				$user_data=M()->table(array("user"=>"user","user_account"=>"account"))
				->field("user.user_id,user.user_name,user.org_id,account.user_account,account.role_id,account.isenable,account.user_account_id")
				->where("account.user_id=user.user_id and user.user_name like '%".I("get.search")."%' and account.is_admin='".$isAdmin."'")->limit(I("get.offset"),I("get.limit"))->select();
			}
			else{
				$user_data=M()->table(array("user"=>"user","user_account"=>"account"))
				->field("user.user_id,user.user_name,user.org_id,account.user_account,account.role_id,account.isenable,account.user_account_id")
				->where("account.user_id=user.user_id and account.is_admin='".$isAdmin."'")->limit(I("get.offset"),I("get.limit"))->select();
			}
			
		}
		//对应字段进行转换
		foreach ($user_data as $key => $value) {
			# code...
			$role_row=M("role")->where("role_id='".$value['role_id']."'")->field("role_name")->select();
			$user_data[$key]["role_name"]=$role_row[0]['role_name'];
			$user_row=M("organization")->where("org_id='".$value['org_id']."'")->field("org_name")->select();
			$user_data[$key]["org_name"]=$user_row[0]['org_name'];
			$user_data[$key]["isenable"]=$value["isenable"]=="0"?"启用":"<label style='color:red;'>禁用</label>";
		}
		
		$data_account=M()->table(array("user"=>"user","user_account"=>"account"))
				->field("user.user_id,user.user_name,user.org_id,account.user_account,account.role_id,account.isenable")
				->where("account.user_id=user.user_id and account.is_admin='".$isAdmin."'")->count();

		$return=array(
			"total"=>$data_account,
			"data"=>$user_data
		);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	修改的方法
	*/
	public function edit(){
		$isAdmin=I("get.isAdmin");
		$user_id=I("get.user_id");
		$user_account_id=I("get.user_account_id");

		$role_rows=M("role")->field("role_id,role_name")->select();

		$loginuser=session("loginuser");  //获取登录账号的session信息
		$loginuserid=session("loginuserid");  //获取登录人对应user表主键id
		$org_obj=new OrgModel();
		if($loginuser=="admin"){  //若当前登录人是admin，默认给出所有组织机构			
			$org_rows=$org_obj->getOrgTreeRows(0);
		}
		else{
			$user_row=M("user")->where("user_id='".$loginuserid."'")->field("org_id")->select();  //获取对应管理员所在的机构org_id
			$org_rows=$org_obj->getOrgTreeRows($user_row[0]['org_id']);  //获取此用户对应机构及该机构的下属机构
		}

		$user_row=M("user")->where("user_id='".$user_id."'")->select();
		$user_account_row=M("user_account")->where("user_account_id='".$user_account_id."'")->select();
		$this->assign("user_row",$user_row);
		$this->assign("user_account_row",$user_account_row);
		$this->assign("user_id",$user_id);
		$this->assign("user_account_id",$user_account_id);
		$this->assign("org_rows",$org_rows);
		$this->assign("isAdmin",$isAdmin);

		$this->assign("role_rows",$role_rows);
		$this->display("edit");

	}

	/*
	修改保存操作
	*/
	public function update(){
		$user_id=I("post.user_id");  //获取user表主键id
		$user_account_id=I("post.user_account_id");  //获取user_account表主键id
		$data_post=I("post.");
		$user_data=array(
			"user_name"=>$data_post['user_name'],
			"user_sex"=>$data_post['user_sex'],
			"org_id"=>$data_post['org_id'],
			"user_phone"=>$data_post['user_phone'],
			"user_email"=>$data_post['user_email'],
			"user_address"=>$data_post['user_address']
		);
		$user_obj=new UserModel();
		$return=$user_obj->editSave($user_id,$user_data);

		$user_identify=md5($data_post["user_account"]."haha");
		$data_account=array(
			"user_id"=>$user_id,
			"user_account"=>$data_post["user_account"],
			"user_pwd"=>$data_post["user_pwd"],
			"user_identify"=>$user_identify,
			"role_id"=>$data_post["role_id"],
			"isenable"=>$data_post["isenable"],
			"is_admin"=>$data_post["is_admin"]
		);
		$accont_obj=new UseraccModel();
		$return_account=$accont_obj->editSave($user_account_id,$data_account);

		$this->ajaxReturn($return_account,"JSON");
	}

	/*
	删除操作
	*/
	public function del(){
		$user_id=I("post.user_id");
		$user_account_id=I("post.user_account_id");
		$user_obj=new UserModel();
		$return=$user_obj->del($user_id);
		$accont_obj=new UseraccModel();
		$return_account=$accont_obj->del($user_account_id);
		$this->ajaxReturn($return_account,"JSON");
	}

	/*
	修改密码操作
	*/
	public function editPwd(){
		$user_account_id=I("get.user_account_id");
		$rows=M("user_account")->where("user_account_id='".$user_account_id."'")->field("user_account,user_pwd")->select();
		$this->assign("rows",$rows);
		$this->assign("user_account_id",$user_account_id);
		$this->display("editPwd");
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
	用户列表页显示
	*/
	public function userIndex(){
		$this->display("userIndex");
	}

	/*
	404操作页面
	*/
	public function _empty(){
		header("HTTP/1.0 404 NOT　Found");
		$this->display("Empty/index");  //让他找到404页面
	}

}