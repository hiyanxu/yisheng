<?php
namespace Admin\Model;
use Think\Model;

class RoleMenuModel extends Model{
	protected $tableName="role_menu";

	/*
	添加函数
	*/
	public function dataAdd($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("role_menu")->data($data)->add()){
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
	删除函数
	*/
	public function del($role_id=null){
		if(is_null($role_id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
				);
		}
		else{
			if(M("role_menu")->where("role_id='".$role_id."'")->delete()){
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


}