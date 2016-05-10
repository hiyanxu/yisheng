<?php
namespace Admin\Model;
use Think\Model;

class UserModel extends Model{
	protected $tableName="user";

	/*
	数据保存方法
	*/
	public function dataAdd($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			$user_id=M("user")->data($data)->add();
			if($user_id){
				$return=array(
					"status"=>true,
					"user_id"=>$user_id,
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
	public function editSave($user_id=null,$data=null){
		if(is_null($user_id)||is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("user")->where("user_id='".$user_id."'")->data($data)->save()){
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
	public function del($user_id=null){
		if(is_null($user_id)){
			$return=array(
				"status"=>false,
				"msg"=>"数据删除失败"
			);
		}
		else{
			if(M("user")->where("user_id",$user_id."'")->delete()){
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