<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\CateModel;

class CateController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	分类数据获取
	*/
	public function index(){
		$this->display("index");
	}

	/*
	最顶级分类添加页面显示
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
			"cate_name"=>$data_post['cate_name'],
			"parentid"=>$data_post['parentid'],
			"ishidden"=>$data_post["ishidden"]
		);
		$obj=new CateModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	获取所有分类数据
	*/
	public function getCateTree(){
		$rows=$this->_getTree();
		$this->ajaxReturn($rows,"JSON");
	}

	/*
	获取对应格式数据
	*/
	private function _getTree($parentid=0){
		$array=array();
		$data=M("category")->where("parentid='".$parentid."' and ishidden=0")->select();

		foreach ($data as $key => $value) {
			# code...
			$array[$key]['text'] = $value["cate_name"]."&nbsp;&nbsp;&nbsp;"."<a href='javascritp::void(0)' onclick='addChild({$value["cate_id"]});' title='添加'><span class='glyphicon glyphicon-plus'></span></a>".
			"&nbsp;&nbsp;"."<a href='javascritp::void(0)' onclick='editChild({$value["cate_id"]});' title='修改'><span class='glyphicon glyphicon-pencil'></span></a>".
			"&nbsp;&nbsp;"."<a href='javascritp::void(0)' onclick='delChild({$value["cate_id"]});' title='删除'><span class='glyphicon glyphicon-trash'></span></a>";
			$array[$key]['cate_id'] = $value["cate_id"];
			$is_ok = M("category")->where('parentid="'.$value["cate_id"].'" and ishidden=0')->count();
			if($is_ok){
				$array[$key]['nodes'] = $this->_getTree($value['cate_id']);
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
			$row=M("category")->where("cate_id='".$fid."'")->field("cate_name")->select();
			$this->assign("row",$row);
			$this->assign("fid",$fid);
			$this->display("addChild");
		}
	}

	/*
	分类修改方法
	*/
	public function edit(){
		$cate_id=I("get.cate_id");
		$row=M("category")->where("cate_id='".$cate_id."'")->field("cate_name,parentid,ishidden")->select();
		$this->assign("row",$row);
		$this->assign("cate_id",$cate_id);
		$this->display("edit");
	}

	/*
	分类修改保存操作
	*/
	public function update(){
		$data_post=I("post.");
		$data=array(
			"cate_name"=>$data_post['cate_name'],
			"parentid"=>$data_post['parentid'],
			"ishidden"=>$data_post['ishidden']
		);
		$obj=new CateModel();
		$return=$obj->editSave($data_post['cate_id'],$data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	删除操作
	*/
	public function del(){
		$cate_id=I("post.cate_id");
		$child_count=M("category")->where("parentid='".$cate_id."'")->count();
		if($child_count){
			$return=array(
				"status"=>false,
				"msg"=>"删除失败，请先删除分类下数据"
			);
		}
		else{
			$obj=new CateModel();
			$return=$obj->del($cate_id);
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