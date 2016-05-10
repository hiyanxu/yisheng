<?php
namespace Admin\Model;
use Think\Model;

class UseraccModel extends Model{
	protected $tableName="user_account";

	/*
	数据插入保存
	*/
	public function dataAdd($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			$user_account_id=M("user_account")->data($data)->add();
			if($user_account_id){
				$return=array(
					"status"=>true,
					"user_account_id"=>$user_account_id,
					"msg"=>"数据保存成功"
				);
			}
			else{
				$return=array(
					"status"=>false,
					"msg"=>"数据保存失败"
				);
			}
		}
		return $return;
	}

	/*
	数据修改保存操作
	*/
	public function editSave($user_account_id=null,$data=null){
		if(is_null($user_account_id)||is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("user_account")->where("user_account_id='".$user_account_id."'")->data($data)->save()){
				$return=array(
					"status"=>true,
					"msg"=>"数据修改成功"
				);
			}
			else{
				$return=array('status' =>false ,"msg"=>"数据修改失败" );
			}
		}
		return $return;
	}

	/*
	删除操作
	*/
	public function del($user_account_id=null){
		if(is_null($user_account_id)){
			$return=array(
				"status"=>false,
				"msg"=>"数据删除失败"
			);
		}
		else{
			if(M("user_account")->where("user_account_id='".$user_account_id."'")->delete()){
				$return=array(
					"status"=>true,
					"msg"=>"数据删除成功"
				);
			}
			else{
				$return=array(
					"status"=>false,
					"msg"=>"数据删除失败"
				);
			}
		}
		return $return;
	}

}