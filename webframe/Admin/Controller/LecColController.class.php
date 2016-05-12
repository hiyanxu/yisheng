<?php
/*
学院选取控制器	
*/
namespace Admin\Controller;
use Think\Controller;

class LecColController extends OrgSelectController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}	

	/*
	首页显示
	*/
	public function index(){
		$this->display("LecCol/index");
	}

	/*
	选取页显示
	*/
	public function add(){
		$org_rows=M("organization")->where("parentid=0 and ishidden=0")->field("org_id,org_name")->select();  //只取出最顶级菜单
		//var_dump($org_rows);die();
		$this->assign("org_rows",$org_rows);
		$this->display("LecCol/add");
	}

	/*
	学院选取入库操作
	*/
	public function insert(){
		$data=I("post.");
		if($data['isenable']=="0"){
			$this->_setCollegeEnable('college',1);
		}
		//var_dump($data);die();
		$return=$this->colSelectInsert('college',$data['org_id'],$data['isenable']);  //调用继承过来的方法，进行数据入库
		//var_dump($return);die();
		$this->ajaxReturn($return);
	}

	/*
	列表数据获取方法
	*/
	public function ajaxIndex(){
		$data=I("get.");  //获取所有get过来的数据
		$order=$data['sort']." ".$data['order'];  //拼接order字符串
		$offset=$data['offset'];  //拼接分页条件
		$limit=$data['limit'];
		$return=$this->ajaxIndexColSelect($order,'college','college_ano','lec_org_id',$offset,$limit);
		$this->ajaxReturn($return);
	}

	/*
	设置开启或禁用的方法
	*/
	public function setLecColIsEnable(){
		$id=I("post.id");
		$flag=I("post.flag");
		//var_dump($id,$flag);die();
		$return=$this->setColIsEnable('college',$id,$flag,'lec_org_id');
		$this->ajaxReturn($return);
	}

	/*
	删除操作
	包括批量删除
	*/
	public function delAjax(){
		$ids=I("post.ids");  //获取post过来的ids数据
		$ids2=substr($ids, 0, -1);  //去掉最后的逗号
		$idsToArr=explode(",", $ids2);  //将其分割成数组
		foreach ($idsToArr as $key => $value) {
			# code...
			$return=$this->Coldel($value,'college','lec_org_id');
			if($return['status']=='false'){
				break;
			}
		}
		$this->ajaxReturn($return);
	}


}