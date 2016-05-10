<?php
namespace Admin\Controller;
use Think\Controller;

class WorkflowController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	工作流列表页面显示
	*/
	public function index(){
		$this->display("index");
	}

	/*
	工作流添加页面
	*/
	public function add(){
		$this->display("add");
	}

	/*
	获取当前系统中所有角色
	*/
	public function getrole(){
		$rows=M("role")->field("role_id,role_name")->select();
		
		$this->ajaxReturn($rows,"JSON");
	}

	/*
	工作流保存方法
	*/
	public function insert(){
		$data_post=I("post.");
		$steps=count($data_post)-2;
		if($steps==0){
			$return=array(
				"status"=>false,
				"msg"=>"请选择工作流处理过程"
			);
			$this->ajaxReturn($return,"JSON");
		}
		$workflow_step=array();
		for($i=1;$i<=$steps;$i++){
			$workflow_step[$i]=$data_post["step{$i}"];
			if($workflow_step[$i]=="0"){
				$return=array(
					"status"=>false,
					"msg"=>"请选择有效的系统角色"
				);
				$this->ajaxReturn($return,"JSON");
			}
		}
		$data_workflow=array(
			"workflow_name"=>$data_post["workflow_name"],
			"workflow_desc"=>$data_post["workflow_desc"],
			"steps"=>$steps
		);
		$workflow_id=M("workflow")->data($data_workflow)->add();  //将工作流入库
		foreach($workflow_step as $key=>$val){
			$data_workflowstep=array(
				"workflow_id"=>$workflow_id,
				"step_now"=>$key,
				"role_id"=>$val
			);
			M("workflowstep")->data($data_workflowstep)->add();  //将工作流步骤入库
		}
		$return=array(
			"status"=>true,
			"msg"=>"数据添加成功"
		);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	获取工作流列表数据
	*/
	public function ajaxIndex(){
		$order=I("get.sort")." ".I("get.order");
		if(isset($_GET['sort'])&&isset($_GET['order'])){
			$rows=M("workflow")->field("workflow_id,workflow_name,workflow_desc,steps")->order($order)->page(I("get.offset"),I("get.limit"))->select();
		}
		else{
			$rows=M("workflow")->field("workflow_id,workflow_name,workflow_desc,steps")->page(I("get.offset"),I("get.limit"))->select();
		}

		$rows_count=M("workflow")->count();
		$return=array(
			"total"=>$rows_count,
			"data"=>$rows
		);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	修改方法
	*/
	public function edit(){
		$workflow_id=I("get.workflow_id");  //获取对应的主键id
		$workflow_row=M("workflow")->where("workflow_id='".$workflow_id."'")->field("workflow_name,workflow_desc,steps")->select();
		$workflowstep_rows=M("workflowstep")->where("workflow_id='".$workflow_id."'")->field("step_now,role_id")->select();
		$role_rows=M("role")->select();
		$this->assign("role_rows",$role_rows);
		$this->assign("workflow_row",$workflow_row);
		$this->assign("workflowstep_rows",$workflowstep_rows);
		$this->assign("workflow_id",$workflow_id);
		$this->display("edit");
	}

	/*
	修改保存的方法
	*/
	public function update(){
		$data_post=I("post.");
		$steps=count($data_post)-3;
		if($steps==0){
			$return=array(
				"status"=>false,
				"msg"=>"请选择工作流处理过程"
			);
			$this->ajaxReturn($return,"JSON");
		}
		$workflow_step=array();
		for($i=1;$i<=$steps;$i++){
			$workflow_step[$i]=$data_post["step{$i}"];
			if($workflow_step[$i]=="0"){
				$return=array(
					"status"=>false,
					"msg"=>"请选择有效的系统角色"
				);
				$this->ajaxReturn($return,"JSON");
			}
		}
		$data_workflow=array(
			"workflow_name"=>$data_post["workflow_name"],
			"workflow_desc"=>$data_post["workflow_desc"],
			"steps"=>$steps
		);
		M("workflow")->where("workflow_id='".$data_post['workflow_id']."'")->save($data_workflow);
		M("workflowstep")->where("workflow_id='".$data_post['workflow_id']."'")->delete();
		foreach($workflow_step as $key=>$val){
			$data_workflowstep=array(
				"workflow_id"=>$data_post['workflow_id'],
				"step_now"=>$key,
				"role_id"=>$val
			);
			M("workflowstep")->data($data_workflowstep)->add();
		}
		$return=array(
			"status"=>true,
			"msg"=>"数据修改成功"
		);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	工作流删除的方法
	*/
	public function del(){
		$workflow_id=I("post.workflow_id");
		M("workflow")->where("workflow_id='".$workflow_id."'")->delete();
		M("workflowstep")->where("workflow_id='".$workflow_id."'")->delete();
		$return=array(
			"status"=>true,
			"msg"=>"数据删除成功"
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