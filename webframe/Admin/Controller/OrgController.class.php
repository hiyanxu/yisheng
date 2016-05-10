<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\OrgModel;

class OrgController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	组织机构列表页面显示
	*/
	public function index(){
		$this->display("index");
	}

	/*
	组织机构添加页面显示
	*/
	public function add(){
		$this->display("add");
	}

	/*
	分类入库操作
	*/
	public function insert(){
		$data_post=I("post.");  
		$data=array(
			"org_name"=>$data_post['org_name'],
			"parentid"=>$data_post['parentid'],
			"ishidden"=>$data_post["ishidden"]
		);
		$obj=new OrgModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	获取所有分类数据
	*/
	public function getOrgTree(){
		$rows=$this->_getTree();
		$this->ajaxReturn($rows,"JSON");
	}

	/*
	获取对应格式数据
	*/
	private function _getTree($parentid=0){
		$array=array();
		$data=M("organization")->where("parentid='".$parentid."' and ishidden=0")->select();

		foreach ($data as $key => $value) {
			# code...
			$array[$key]['text'] = $value["org_name"]."&nbsp;&nbsp;&nbsp;"."<a href='javascritp::void(0)' onclick='addChild({$value["org_id"]});' title='添加'><span class='glyphicon glyphicon-plus'></span></a>".
			"&nbsp;&nbsp;"."<a href='javascritp::void(0)' onclick='editChild({$value["org_id"]});' title='修改'><span class='glyphicon glyphicon-pencil'></span></a>".
			"&nbsp;&nbsp;"."<a href='javascritp::void(0)' onclick='delChild({$value["org_id"]});' title='删除'><span class='glyphicon glyphicon-trash'></span></a>";
			$array[$key]['org_id'] = $value["org_id"];
			$is_ok = M("organization")->where('parentid="'.$value["org_id"].'" and ishidden=0')->count();
			if($is_ok){
				$array[$key]['nodes'] = $this->_getTree($value['org_id']);
			}
		}
		return $array;
		
	}

	/*
	子类添加页面显示
	*/
	public function addChild(){
		$fid=I("get.fid");
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
			$this->display("addChild");
		}
	}

	/*
	分类修改方法
	*/
	public function edit(){
		$org_id=I("get.org_id");
		$row=M("organization")->where("org_id='".$org_id."'")->field("org_name,parentid,ishidden")->select();
		$this->assign("row",$row);
		$this->assign("org_id",$org_id);
		$this->display("edit");
	}

	/*
	分类修改保存操作
	*/
	public function update(){
		$data_post=I("post.");
		$data=array(
			"org_name"=>$data_post['org_name'],
			"parentid"=>$data_post['parentid'],
			"ishidden"=>$data_post['ishidden']
		);
		$obj=new OrgModel();
		$return=$obj->editSave($data_post['org_id'],$data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	删除操作
	*/
	public function del(){
		$org_id=I("post.org_id");
		$child_count=M("organization")->where("parentid='".$org_id."'")->count();
		if($child_count){
			$return=array(
				"status"=>false,
				"msg"=>"删除失败，请先删除下级机构"
			);
		}
		else{
			$obj=new OrgModel();
			$return=$obj->del($org_id);
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