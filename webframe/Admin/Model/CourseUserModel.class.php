<?php
/*
用户课程模型model
*/
namespace Admin\Model;
use Think\Model;

class CourseUserModel extends Model{
	protected $tableName="user_course";

	/*
	数据入库的操作
	*/
	public function dataAdd($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
				);
			return $return;
		}
		else{
			if(M("user_course")->data($data)->add()){
				$return=array(
					"status"=>true,
					"msg"=>"添加成功"
					);
			}
			else{
				$return=array(
					"status"=>false,
					"msg"=>"添加失败"
					);
			}
		}
		return $return;
	}
}