<?php
namespace Admin\Model;
use Think\Model;

class NewsCateModel extends Model{
	protected $tableName="news_category";

	/*
	数据插入入库
	*/
	public function dataAdd($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
				);
		}
		else{
			if(M("news_category")->data($data)->add()){
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
	设置该条记录的isable是否可用的方法
	*/
	public function setCateEnable($isenable){
		$data=array(
			"isenable"=>$isenable
			);
		if(M("news_category")->execute("update news_category set isenable='".$isenable."'")){
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
	数据修改保存方法
	*/
	public function editSave($news_cate_id=null,$data=null){
		if(is_null($news_cate_id)||is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("news_category")->where("news_cate_id='".$news_cate_id."'")->data($data)->save()){
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
	public function del($news_cate_id=null){
		if(is_null($news_cate_id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("news_category")->where("news_cate_id='".$news_cate_id."'")->delete()){
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