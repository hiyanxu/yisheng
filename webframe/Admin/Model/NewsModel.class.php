<?php
namespace Admin\Model;
use Think\Model;

class NewsModel extends Model{
	protected $tableName="news";

	/*
	添加操作
	*/
	public function dataAdd($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("news")->data($data)->add()){
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
	修改操作
	*/
	public function editSave($news_id=null,$data=null){
		if(is_null($news_id)||is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("news")->where("news_id='$news_id'")->data($data)->save()){
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
	public function del($news_id=null){
		if(is_null($news_id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("news")->where("news_id='$news_id'")->delete()){
				$return=array(
					"status"=>true,
					"msg"=>"删除成功"
				);
			}
			else{
				$return=array(
					"status"=>true,
					"msg"=>"删除失败"
				);
			}
		}
		return $return;
	}



}