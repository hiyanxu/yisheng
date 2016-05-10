<?php
namespace Admin\Model;
use Think\Model;

class CateModel extends Model{
	protected $tableName="category";

	/*
	数据插入保存的方法
	*/
	public function dataAdd($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("category")->data($data)->add()){
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
	修改保存方法
	*/
	public function editSave($cate_id=null,$data=null){
		if(is_null($cate_id)||is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if (M("category")->where("cate_id='".$cate_id."'")->data($data)->save()) {
				# code...
				$return=array(
					"status"=>true,
					"msg"=>"数据保存成功"
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
	public function del($cate_id=null){
		if(is_null($cate_id)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("category")->where("cate_id='".$cate_id."'")->delete()){
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

	/*
	得到分类信息，对外的接口
	*/
	public function getCateTreeRows($parentid=0){
		$rows=M("category")->where("ishidden=0")->select();
		$data=array();
		$this->getTree($data,$rows,$parentid);
		foreach ($data as $key => $value) {
			# code...
			$datas[$value["cate_id"]]=$value["cate_name"];
		}
		return $datas;
	}

	/*
	根据分类的所有信息层级列出
	参数：
	$data  用于返回最后的数据
	$rows  分类数据
	$parentId  父id
	$space  分隔空格
	*/
	public function getTree(&$data, $rows, $parentid = 0, $separate = ''){
		foreach ($rows as $k => $v) {

            if ($v['parentid'] == $parentid) {
                $v['cate_name'] = $separate . $v['cate_name'];
                $data[] = $v;
               $this->getTree($data, $rows, $v['cate_id'], $separate . "&nbsp;&nbsp;&nbsp;&nbsp;");
            }
        }
	}

}