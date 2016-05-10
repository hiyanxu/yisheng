<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\AccessModel;

class AccessController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	权限列表页显示
	*/
	public function index(){
		$this->display("index");
	}

	/*
	权限添加页面显示
	*/
	public function add(){
		$this->display("add");
	}

	/*
	权限插入的方法
	*/
	public function insert(){
		$data_post=I("post.");
		$access_url=$data_post['access_con']."/".$data_post['access_fun'];
		$data=array(
			"access_name"=>$data_post['access_name'],
			"access_url"=>$access_url,
			"remark"=>$data_post['remark']
		);
		$obj=new AccessModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	权限列表数据获取
	*/
	public function ajaxIndex(){
		$order=I("get.sort")." ".I("get.order");
		if(I("get.sort")&&I("get.order")){
			$data=M("access")->field("access_id,access_name,access_url,remark")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
		}
		else{
			$data=M("access")->field("access_id,access_name,access_url,remark")->limit(I("get.offset"),I("get.limit"))->select();
		}
		$total_count=M("access")->field("access_id,access_name,access_url,remark")->count();

		$return=array(
			"total"=>$total_count,
			"data"=>$data
		);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	权限修改页面显示
	*/
	public function edit(){
		$access_id=I("get.access_id");
		$row=M("access")->where("access_id='".$access_id."'")->select();
		$row_url_arr=explode("/", $row[0]['access_url']);
		$row[0]['access_con']=$row_url_arr[0];
		$row[0]['access_fun']=$row_url_arr[1];
		$this->assign("row",$row);
		$this->display("edit");
	}

	/*
	修改保存操作
	*/
	public function update(){
		$data_post=I("post.");
		$access_url=$data_post['access_con']."/".$data_post['access_fun'];
		$data=array(
			"access_name"=>$data_post['access_name'],
			"access_url"=>$access_url,
			"remark"=>$data_post['remark']
		);
		$obj=new AccessModel();
		$return=$obj->editSave($data_post['access_id'],$data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	权限删除操作
	*/
	public function del(){
		$ids=I("post.ids");
		$ids2=substr($ids, 0,-1);
		$idsToArr=explode(",",$ids2);
		foreach ($idsToArr as $key => $value) {
			# code...
			$obj=new AccessModel();
			$return=$obj->del($value);
		}
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