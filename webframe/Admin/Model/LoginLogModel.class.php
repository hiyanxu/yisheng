<?php
/*
登录日志model
*/
namespace Admin\Model;
use Think\Model;

class LoginLogModel extends Model{
	protected $tableName="login_log";

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
			if(M("login_log")->data($data)->add()){
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