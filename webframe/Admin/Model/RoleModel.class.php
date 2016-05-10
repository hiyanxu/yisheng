<?php
namespace Admin\Model;
use Think\Model;

class RoleModel extends Model{
	protected $tableName = 'role';   //定义所对应的表名

	/*
	数据插入的方法
	*/
	public function dataAdd($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("role")->data($data)->add()){
				$return=array(
					"status"=>true,
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
	修改保存的方法
	*/
	public function editSave($role_id=null,$data){
		if(is_null($role_id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("role")->where("role_id='".$role_id."'")->save($data)){
				$return=array(
					"status"=>true,
					"msg"=>"数据修改成功"
				);
			}
			else{
				# code...
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
	public function del($id=null){
		if(is_null($id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("role")->where("role_id='".$id."'")->delete()){
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