<?php
namespace Admin\Model;
use Think\Model;

class MenuModel extends Model{
	protected $tableName = 'menu';   //定义所对应的表名

	/*
	数据保存的方法
	*/
	public function dataSave($data){
		$menu=M("menu");  //实例化对象
		if($menu->data($data)->add()){
			$return=array(
				"status"=>true,
				"msg"=>"保存成功！"
			);
		}
		else{
			$return=array(
				"status"=>false,
				"msg"=>"保存失败！"
			);
		}
		return $return;
	}

	/*
	数据修改的方法
	*/
	public function editSave($id=null,$data){
		if(is_null($id)){
			$return=array(
				"status"=>false,
				"msg"=>"请输入有效数据"
			);
		}
		else{
			if(M("menu")->where("menu_id='".$id."'")->data($data)->save()){
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
	数据删除的方法
	*/
	public function del($id=null){
		if(is_null($id)){
			$return=array(
				"status"=>false,
				"msg"=>"请输入有效数据"
			);
		}
		else{
			if(M("menu")->where("menu_id='".$id."'")->delete()){
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