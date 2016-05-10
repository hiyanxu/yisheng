<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\RoleModel;
use Admin\Model\RoleAccModel;
use Admin\Model\RoleMenuModel;



class RoleController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	角色列表页显示
	*/
	public function index(){
		$this->display("index");
	}

	/*
	角色添加页面显示
	*/
	public function add(){
		$this->display("add");
	}

	/*
	角色添加数据插入
	*/
	public function insert(){
		$data_post=I("post.");
		$data=array(
			"role_name"=>$data_post["role_name"],
			"role_desc"=>$data_post["role_desc"]
		);
		$role=new RoleModel();
		$return=$role->dataAdd($data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	角色列表显示
	*/
	public function ajaxIndex(){
		$data_post=I("get.");  //获取get过来的数据
		$order=$data_post["sort"]." ".$data_post["order"];
		if(isset($_GET['sort'])&&isset($_GET['order'])){
			$role_rows=M("role")->field("role_id,role_name,role_desc")->order($order)->page(I("get.offset"),I("get.limit"))->select();
		}
		else{
			$role_rows=M("role")->field("role_id,role_name,role_desc")->page(I("get.offset"),I("get.limit"))->select();
		}

		$role_count=M("role")->count();

		$role_return=array(
			"total"=>$role_count,
			"data"=>$role_rows
		);

		$this->ajaxReturn($role_return,"JSON");
	}

	/*
	角色修改操作
	*/
	public function edit(){
		$role_id=I("get.role_id");
		
		$row=M("role")->where("role_id='".$role_id."'")->field("role_name,role_desc")->select();
		
		$this->assign("row",$row);
		$this->assign("role_id",$role_id);
		$this->display("edit");
	}

	/*
	角色修改保存操作
	*/
	public function update(){
		$data_post=I("post.");
		$data=array(
			"role_name"=>$data_post['role_name'],
			"role_desc"=>$data_post["role_desc"]
		);
		$role=new RoleModel();
		$return=$role->editSave($data_post['role_id'],$data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	角色删除操作
	*/
	public function del(){
		$role_ids=I("post.ids");
		$ids = substr($role_ids, 0, -1);
        $idsArr = explode(",", $ids);
        
        foreach ($idsArr as $key => $value) {
        	# code...
        	$obj=new RoleModel();  //实例化model对象
			$return=$obj->del($value);  
        }
        $this->ajaxReturn($return,"JSON");
	}

	/*
	权限分配操作
	*/
	public function accessDis(){
		$role_id=I("get.role_id");  //获取被分配权限的角色主键id
		$role_row=M("role")->where("role_id='".$role_id."'")->field("role_name")->select();  //获取被分配权限的角色名称

		$access_rows=M("access")->field("access_id,access_name,remark")->select();  //获取所有权限的id和name

		foreach ($access_rows as $key => $value) {  //给是否具有该权限做标志位
			# code...
			$row=M("role_access")->where("role_id='".$role_id."' and access_id='".$value['access_id']."'")->select();
			if($row){
				$access_rows[$key]['isselect']="ok";
			}
			else{
				$access_rows[$key]['isselect']="error";
			}
		}
		
		$this->assign("role_id",$role_id);
		$this->assign("role_row",$role_row);
		$this->assign("access_rows",$access_rows);
		$this->display("accessDis");
	}

	/*
	权限分配保存
	*/
	public function accessDisInsert(){
		$data_post=I("post.");
		$role_id=$data_post['role_id'];  //获取角色id
		$access_ids=array();
		//var_dump($data_post);die();
		//M("role_access")->where("role_id='".$role_id."'")->delete();  //将所有此角色对应的权限全部删除，然后在直接添加
		$obj=new RoleAccModel();
		$return=$obj->del($role_id);

		foreach ($data_post as $key => $value) {
			# code...
			$data=array(
				"role_id"=>$role_id,
				"access_id"=>$key
			);

			if($key!="role_id"){
				$return=$obj->dataAdd($data);				
				//M("role_access")->data($data)->add();
			}
		}
		$this->ajaxReturn($return,"JSON");
	}

	/*
	菜单分配页面显示
	*/
	public function menuDis(){
		$role_id=I("get.role_id");
		$row=M("role")->where("role_id='".$role_id."'")->field("role_name")->select();
		$this->assign("row",$row);
		$this->assign("role_id",$role_id);
		$this->display("menuDis");
	}

	/*
	获取菜单树的操作
	*/
	public function getMenuTree(){
		$role_id=I("get.role_id");  //获取对应的角色id
		$menu_rows=M("menu")->where("parentid=0 and ishidden=0")->field("menu_name,menu_id")->select();  //首先获取所有最顶级菜单
		//获取角色菜单表数据
		$role_menu_rows=M("role_menu")->where("role_id='".$role_id."'")->select();
		$user_arr="";
		if($role_menu_rows){  //若当前管理表中存在数据，则将所有menu_id值存入一个数组中
			foreach ($role_menu_rows as $key => $value) {
				# code...\
				$user_arr[]=$value['menu_id'];
			}
		}
		$rs=array();
		foreach ($menu_rows as $key => $value) {
			# code...
			$selected=FALSE;
			if($user_arr){  //判断当前这里面是否存在数据，即关联表里面是否有数据
				if(in_array($value['menu_id'], $user_arr)){  //判断当前管理表里面是否有次id值
					$selected=TRUE;  //当前所存在的情况下，置标志位为ok
				}
			}
			if(!empty($value['menu_id'])){  //当前菜单表不为空的情况下
				$is_ok=M("menu")->where("parentid='".$value['menu_id']."'")->count();  //查看当前在菜单表里面，这个id值是否是最底层菜单
				if($is_ok){  //当前表示不是最底层菜单
					$rs['data'][$key]=array('name'=>$value['menu_name'],'type'=>"folder","additionalParameters"=>$this->_childMenuId($value['menu_id'],$user_arr));
					//var_dump($rs);
				}
				else{
					$rs['data'][$key]=array("name"=>$value['menu_name'],'type'=>"item","parentid"=>$value['menu_id'],"selected"=>$selected);
				}
			}
		}
		//die();
		$rs["status"]=true;
		$this->ajaxReturn($rs,"JSON");
	}

	/*
	获取子菜单数据
	找到所有子级菜单，且拼装数据格式
	*/
	private function _childMenuId($menu_id,$user_arr){
		$data=array();
		$menu_rows=M("menu")->where("parentid='".$menu_id."'")->field("menu_name,menu_id,parentid")->select();  //得到所有该id值的子菜单
		foreach ($menu_rows as $key => $value) {
			# code...
			$selected=FALSE;
			if($user_arr){
				if(in_array($value['menu_id'], $user_arr)){  //判断当前管理表里面是否有次id值
					$selected=TRUE;  //当前所存在的情况下，置标志位为ok
				}
			}
				$is_ok=M("menu")->where("parentid='".$value['menu_id']."'")->count();  //查看当前在菜单表里面，这个id值是否是最底层菜单
				if($is_ok){  //当前表示不是最底层菜单
					$data['children'][]=array('name'=>$value['menu_name'],'type'=>"folder","additionalParameters"=>$this->_childMenuId($value['menu_id'],$user_arr));
				}
				else{
					$data['children'][]=array("name"=>$value['menu_name'],'type'=>"item","parentid"=>$value['menu_id'],"selected"=>$selected);
				}
		}
		return $data;
	}

	/*
	获取对应格式数据
	*/
	private function _getTree($parentid=0){
		$array=array();
		$data=M("menu")->where("parentid='".$parentid."' and ishidden=0")->select();

		foreach ($data as $key => $value) {
			# code...
			$array[$key]['text'] = $value["menu_name"]."&nbsp;&nbsp;&nbsp;"."<input type='checkbox' name='".$value['menu_id']."'>";
			$array[$key]['menu_id'] = $value["menu_id"];
			$is_ok = M("menu")->where('parentid="'.$value["menu_id"].'" and ishidden=0')->count();
			if($is_ok){
				$array[$key]['nodes'] = $this->_getTree($value['menu_id']);
			}
		}
		return $array;
		
	}

	/*
	菜单分配入库操作
	*/
	public function menuDisInsert(){
		$data=I("post.");
		$role_id=$data['role_id'];  //获取角色id
		$menuString=$data['menuString'];  //获取所选中的菜单项
		$menuString2=substr($menuString, 0,-1);
		$menuStringToArr=explode("|", $menuString2);  //切割成数组
		$obj=new RoleMenuModel(); 
		$obj->del($role_id);
		foreach ($menuStringToArr as $key => $value) {
			# code...
			$editdata=array(
				"role_id"=>$role_id,
				"menu_id"=>$value
			);
			$return=$obj->dataAdd($editdata);
		}
		$this->ajaxReturn($return,"JSON");
	}

	/*
	404操作页面
	*/
	public function _empty(){
		header("HTTP/1.0 404 NOT　Found");
		$this->display("Empty/index");  //让他找到404页面
	}

}