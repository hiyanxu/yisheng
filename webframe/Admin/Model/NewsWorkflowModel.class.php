<?php
namespace Admin\Model;
use Think\Model;

class NewsWorkflowModel extends Model{
	protected $tableName="news_workflow";

	/*
	设置该条记录的isable是否可用的方法
	*/
	public function setCateEnable($isenable){
		$data=array(
			"isenable"=>$isenable
			);
		if(M("news_workflow")->execute("update news_workflow set isenable='".$isenable."'")){
			$return=array(
				"status"=>true,
				"msg"=>"更新成功"
			);
		}
		else{
			$return=array(
				"status"=>false,
				"msg"=>"更新失败"
			);
		}
		return $return;
	}

	/*
	添加保存操作
	*/
	public function dataAdd($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
				);
		}
		else{
			if(M("news_workflow")->data($data)->add()){
				$return=array(
					"status"=>true,
					"msg"=>"数据添加成功"
					);
			}
			else{
				$return=array(
					"status"=>false,
					"msg"=>"数据添加失败"
				);
			}
		}
		return $return;
	}

	/*
	数据修改保存方法
	*/
	public function editSave($news_Workflow_id=null,$data=null){
		if(is_null($news_Workflow_id)||is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("news_workflow")->where("news_Workflow_id='".$news_Workflow_id."'")->data($data)->save()){
				$return=array(
					"status"=>true,
					"msg"=>"数据修改成功"
				);
			}
			else{
				$return=array(
					"status"=>false,
					"msg"=>"数据修改失败"
				);
			}
		}
		return $return;
	}

	/*
	删除操作
	*/
	public function del($news_Workflow_id=null){
		if(is_null($news_Workflow_id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("news_workflow")->where("news_Workflow_id='".$news_Workflow_id."'")->delete()){
				$return=array(
					"status"=>true,
					"msg"=>"删除成功"
				);
			}
			else{
				$return=array(
					"status"=>false,
					"msg"=>"删除失败"
				);
			}
		}
		return $return;
	}


}