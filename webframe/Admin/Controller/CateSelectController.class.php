<?php
namespace Admin\Controller;
use Think\Controller;

class CateSelectController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	列表页显示方法
	*/
	public function ajaxIndexCateSelect($order='',$link_table,$link_table_ano,$link_table_id,$offset=null,$limit=null){
		if(is_null($offset)||is_null($limit)){  //分页条件
			$return=array(
				"status"=>"false",
				"msg"=>"给出分页条件"
				);
			return $return;
		}
		if(!empty($order)){  //表示当前需要有orderBy的顺序
			$data=M()->table(array("category"=>"cate","{$link_table}"=>"{$link_table_ano}"))
			->field("cate.cate_name,{$link_table_ano}.isenable,{$link_table_ano}.{$link_table_id}")
			->where("cate.cate_id={$link_table_ano}.cate_id")->order($order)->limit($offset,$limit)->select();
		}
		else{
			$data=M()->table(array("category"=>"cate","{$link_table}"=>"{$link_table_ano}"))
			->field("cate.cate_name,{$link_table_ano}.isenable,{$link_table_ano}.{$link_table_id}")
			->where("cate.cate_id={$link_table_ano}.cate_id")->limit($offset,$limit)->select();
		}
		//var_dump($data);die();

		foreach ($data as $key => $value) {
			# code...
			$data[$key]['isenableText']=$value['isenable']=="0"?"<label style='color:#5CB85C;'>启用</label>":"<label style='color:red;'>禁用</label>";
		}
		$data_count=M()->table(array("category"=>"cate","{$link_table}"=>"{$link_table_ano}"))
			->field("cate.cate_name,{$link_table_ano}.isenable,{$link_table_ano}.{$link_table_id}")
			->where("cate.cate_id={$link_table_ano}.cate_id")->count();

		$return=array(
			"total"=>$data_count,
			"data"=>$data
		);
		return $return;
	}

	/*
	设置禁用或启用的方法
	*/
	public function setCateIsEnable($link_table=null,$id=null,$flag=1,$link_table_id=null){
		if(is_null($id)||is_null($link_table)||is_null($link_table_id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
				);
			return $return;
		}
		if($flag==0){  //当前表示要启用某个记录，则我们应该把其它的先全部设置为1
			$this->_setCateEnable($link_table,1);
		}
		$data=array(
			"isenable"=>$flag
		);
		$return=$this->editSave($link_table,$id,$data,$link_table_id);
		return $return;

	}

	/*
	将数据库中是否可用的状态进行变换的方法
	*/
	protected function _setCateEnable($link_table=null,$isenable=1){
		if(is_null($link_table)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出表名"
				);
			return $return;
		}
		$data=array(
			"isenable"=>$isenable
			);
		if(M("{$link_table}")->execute("update {$link_table} set isenable='".$isenable."'")){
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
	public function editSave($link_table=null,$id=null,$data=null,$link_table_id=null){
		if(is_null($id)||is_null($data)||is_null($link_table)||is_null($link_table_id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("{$link_table}")->where("{$link_table_id}='".$id."'")->data($data)->save()){
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
	分类选择入库
	*/
	public function cateSelectInsert($link_table=null,$cate_id=null,$isenable=null){
		if(is_null($cate_id)||is_null($isenable)||is_null($link_table)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
				);
			return $return;
		}
		$data=array(
			"cate_id"=>$cate_id,
			"isenable"=>$isenable
		);
		if($data_post['isenable']=="0"){
			$this->_setCateEnable($link_table,1);
		}
		$return=$this->dataAdd($link_table,$data);
		return $return;
	}

	/*
	数据插入入库
	*/
	public function dataAdd($link_table=null,$data=null){
		if(is_null($data)||is_null($link_table)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
				);
		}
		else{
			if(M("{$link_table}")->data($data)->add()){
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
	删除某个所选分类
	正在启用状态的分类无法删除
	*/
	public function Catedel($id=null,$link_table=null,$link_table_id=null){
		if(is_null($id)||is_null($link_table)||is_null($link_table_id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
				);
			return $return;
		}
		$rows=M("{$link_table}")->where("{$link_table_id}='".$id."'")->field("isenable")->select();
		if($rows[0]["isenable"]==0){
			$return=array(
				"status"=>false,
				"msg"=>"该分类正在处于启用状态，无法删除"
			);
		}
		else{
			$return=$this->del($link_table,$link_table_id,$id);
		}
		return $return;
	}

	/*
	删除操作
	*/
	public function del($link_table=null,$link_table_id=null,$id=null){
		if(is_null($link_table)||is_null($link_table_id)||is_null($id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("{$link_table}")->where("{$link_table_id}='".$id."'")->delete()){
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
