<?php
namespace Admin\Model;
use Think\Model;

class FileModel extends Model{
	protected $tableName="file";

	/*
	数据添加保存方法
	*/
	public function dataAdd($data=null){
		if (is_null($data)) {
			# code...
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(D("file")->data($data)->add()){
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
	数据修改保存操作
	*/
	public function editSave($file_id=null,$file_name=null,$data=null){
		if(is_null($file_id)&&is_null($file_name)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
				);
		}
		else{
			if(!is_null($file_id)){
				$where="file_id='$file_id'";
			}
			else if(!is_null($file_name)){
				$where="file_name='$file_name'";
			}
			//var_dump($where);die();
			if(M("file")->where($where)->data($data)->save()){
				$return=array(
					"status"=>true,
					"msg"=>"修改成功"
					);
			}
			else{
				$return=array(
					"status"=>false,
					"msg"=>"修改失败"
					);
			}
		}
		return $return;
	}

	/*
	删除操作
	*/
	public function del($file_id=null){
		if(is_null($file_id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
				);
			return $return;
		}
		else{
			if(M("file")->where("file_id='$file_id'")->delete()){
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