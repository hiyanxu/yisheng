<?php
namespace Admin\Model;
use Think\Model;

class RoleAccModel extends Model{
	protected $tableName="role_access";

	/*
	数据添加保存操作
	*/
	public function dataAdd($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("role_access")->data($data)->add()){
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
	数据删除操作
	*/
	public function del($id=null){
		if(is_null($id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("role_access")->where("role_id='".$id."'")->delete()){
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