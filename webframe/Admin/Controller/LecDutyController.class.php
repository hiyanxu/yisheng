<?php
namespace Admin\Controller;
use Think\Controller;

class LecDutyController extends CateSelectController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	职称选取首页显示
	*/
	public function index(){
		$this->display('LecDuty/index');  
	}

	/*
	添加页面显示
	*/
	public function add(){
		$cate_rows=M("category")->where("parentid=0 and ishidden=0")->field("cate_id,cate_name")->select();  //只取出最顶级菜单
		$this->assign("cate_rows",$cate_rows);
		$this->display("LecDuty/add");
	}

	/*
	数据插入操作
	*/
	public function insert(){
		$data=I("post.");
		if($data['isenable']=="0"){
			$this->_setCateEnable('lec_duty',1);
		}
		$return=$this->cateSelectInsert('lec_duty',$data['cate_id'],$data['isenable']);  //调用继承过来的方法，进行数据入库
		//var_dump($return);die();
		$this->ajaxReturn($return);
	}

	/*
	列表数据获取
	*/
	public function ajaxIndex(){
		$data=I("get.");  //获取所有get过来的数据
		$order=$data['sort']." ".$data['order'];  //拼接order字符串
		$offset=$data['offset'];  //拼接分页条件
		$limit=$data['limit'];
		$return=$this->ajaxIndexCateSelect($order,'lec_duty','lec_duty_ano','lec_duty_id',$offset,$limit);
		$this->ajaxReturn($return);
	}

	/*
	设置开启或关闭的方法
	*/
	public function setLecDutyIsEnable(){
		$id=I("post.id");
		$flag=I("post.flag");
		//var_dump($id,$flag);die();
		$return=$this->setCateIsEnable('lec_duty',$id,$flag,'lec_duty_id');
		$this->ajaxReturn($return);
	}

	/*
	删除的方法
	*/
	public function delAjax(){
		$ids=I("post.ids");  //获取post过来的ids数据
		$ids2=substr($ids, 0, -1);  //去掉最后的逗号
		$idsToArr=explode(",", $ids2);  //将其分割成数组
		foreach ($idsToArr as $key => $value) {
			# code...
			$return=$this->Catedel($value,'lec_duty','lec_duty_id');
			if($return['status']=='false'){
				break;
			}
		}
		$this->ajaxReturn($return);
	}



}