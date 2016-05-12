<?php
/*
讲座工作流选取控制器
*/
namespace Admin\Controller;
use Think\Controller;

class LecWofController extends WofSelectController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();  
	}

	/*
	工作流选取首页显示
	*/
	public function index(){
		$this->display("LecWof/index");
	}

	/*
	工作流选取页面显示
	*/
	public function add(){
		$workflow_rows=M("workflow")->field("workflow_id,workflow_name")->select();  //只取出最顶级菜单
		$this->assign("workflow_rows",$workflow_rows);
		$this->display('LecWof/add');
	}

	/*
	工作流选取入库操作
	*/
	public function insert(){
		$data=I("post.");
		//var_dump($data);die();
		if($data['isenable']=="0"){
			$this->_setWofEnable('lec_workflow',1);
		}
		$return=$this->wofSelectInsert('lec_workflow',$data['workflow_id'],$data['isenable']);  //调用继承过来的方法，进行数据入库
		//var_dump($return);die();
		$this->ajaxReturn($return);
	}

	/*
	列表数据获取
	*/
	public function ajaxIndex(){
		$data=I("get.");  //获取所有get过来的数据
		$order=$data['sort']." ".$data['order'];  //拼接order字符串
		$offset=$data['offset'];  //拼接分页条件
		$limit=$data['limit'];
		$return=$this->ajaxIndexWorkflowSelect($order,'lec_workflow','lec_workflow_ano','lec_workflow_id',$offset,$limit);
		$this->ajaxReturn($return);
	}

	/*
	设置开启或禁用的方法
	*/
	public function setLecWorkflowIsEnable(){
		$id=I("post.id");
		$flag=I("post.flag");
		//var_dump($id,$flag);die();
		$return=$this->setCateIsEnable('lec_workflow',$id,$flag,'lec_workflow_id');
		$this->ajaxReturn($return);
	}

	/*
	删除操作
	包括批量删粗
	*/
	public function delAjax(){
		$ids=I("post.ids");  //获取post过来的ids数据
		$ids2=substr($ids, 0, -1);  //去掉最后的逗号
		$idsToArr=explode(",", $ids2);  //将其分割成数组
		foreach ($idsToArr as $key => $value) {
			# code...
			$return=$this->wofdel($value,'lec_workflow','lec_workflow_id');
			if($return['status']=='false'){
				break;
			}
		}
		$this->ajaxReturn($return);
	}




}