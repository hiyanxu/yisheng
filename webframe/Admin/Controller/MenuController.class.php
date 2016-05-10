<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\MenuModel;

class MenuController extends AdminController{
	/*
	构造函数
	继承父类的构造函数，用于检查当前登录用户权限
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	菜单列表页面显示
	*/
	public function index(){
		$this->display("index");
	}

	/*
	菜单添加页面显示
	*/
	public function add(){
		$this->display();
	}

	/*
	菜单添加保存方法
	*/
	public function insert(){
		$data_post=I('post.');  //获取整个post过来的数据
		$menu_url=$data_post["controller"]."/".$data_post["function"];  //组装url地址
		$this->__doSort($data_post["parentid"],$data_post["sort"]);  //将数据进行排序
		//var_dump("123");
		//组装数据
		$data=array(
			"parentid"=>$data_post['parentid'],
			"menu_name"=>$data_post["menu_name"],
			"menu_url"=>$menu_url,
			"sort"=>$data_post["sort"],
			"ishidden"=>$data_post["ishidden"]
		);
		//var_dump($data);
		$obj=new MenuModel();  //实例化model对象
		$return=$obj->dataSave($data);  
		$this->ajaxReturn($return,"JSON");  //将数据返回
	}

	/*
	进行排序的方法
	递归调用
	*/
	private function __doSort($parentid,$sort,$menu_id=0){
		if($menu_id==0){
			$row=M("menu")->where("sort='".$sort."' and parentid='".$parentid."'")->field("menu_id,sort")->select();
		}
		else{
			$row=M("menu")->where("menu_id='".$menu_id."'")->field("menu_id,sort")->select();
		}		
		
		if($row){  //若当前为这个排序的数据非空，则进行将所有和这个相同的加1
			$data['sort']=$sort+1;
			
			$row_gai=M("menu")->where("sort='".$data['sort']."' and parentid='".$parentid."'")->field("parentid,sort,menu_id")->select();
			M("menu")->where("menu_id='".$row[0]["menu_id"]."'")->save($data);			
			
			$this->__doSort($row_gai[0]["parentid"],$row_gai[0]['sort'],$row_gai[0]["menu_id"]);
			return;
		}
	}

	/*
	一级菜单
	列表页面数据获取
	*/
	public function ajaxIndex(){
		$menu=D("menu");
		$order=I("post.sort")." ".I("post.order");		
		$menu_data=$menu->where("parentid=0")->field("menu_id,menu_name,parentid,menu_url,sort,ishidden")->order("sort asc")->limit(I("post.offset"),I("post.limit"))->select();
		

		//数据格式转换
		foreach ($menu_data as $key => $value) {
			# code...
			$menu_data[$key]["ishidden"]=$value["ishidden"]=="0"?"<lable>显示</lable>":"<label style='color:red;'>禁用</label>";
			$menu_data[$key]["menu_name"]="<a href='javascript:void(0)' onclick='getchild(".$value["menu_id"].")'>".$value["menu_name"]."</a>";
		}



		$rows_count=$menu->field("menu_id,menu_name,parentid,menu_url,sort,ishidden")->count();	
		
		
		$return=array(
			"total"=>$rows_count,
			"data"=>$menu_data
		);
		
		//var_dump($return);
		
		$this->ajaxReturn($return,"JSON");		
	}

	/*
	子菜单列表页面显示方法
	*/
	public function getchild(){
		$menu_id=I("get.id");
		$row=M("menu")->where("menu_id='".$menu_id."'")->field("parentid")->select();
		$this->assign("menu_id",$menu_id);  
		$this->assign("parentid",$row[0]["parentid"]);
		$this->display("child");
	}

	/*
	子菜单列表页面数据获取
	*/
	public function childAjaxIndex(){
		$menu_id=I("get.menu_id");  //获取父id
		
		$menu=D("menu"); 
		$menu_data=$menu->where("parentid='".$menu_id."'")->field("menu_id,menu_name,parentid,menu_url,sort,ishidden")->order("sort asc")->limit(I("post.offset"),I("post.limit"))->select();
	
		//数据格式转换
		foreach ($menu_data as $key => $value) {
			# code...
			$menu_data[$key]["ishidden"]=$value["ishidden"]=="0"?"<lable>显示</lable>":"<label style='color:red;'>禁用</label>";
			$menu_data[$key]["menu_name"]="<a href='javascript:void(0)' onclick='getchild(".$value["menu_id"].")'>".$value["menu_name"]."</a>";
		}
		$rows_count=$menu->where("parentid='".$parentid."'")->field("menu_id,menu_name,parentid,menu_url,sort,ishidden")->count();	
		
		
		$return=array(
			"total"=>$rows_count,
			"data"=>$menu_data
		);
		
		//var_dump($return);
		
		$this->ajaxReturn($return,"JSON");		
	}

	/*
 	子菜单添加页面显示	
	*/
	public function addChild(){
		$menu_id=I("get.menu_id");
		$row=M("menu")->where("menu_id='".$menu_id."'")->field("menu_id,menu_name")->select();
		$this->assign("row",$row);
		$this->display();
	}

	/*
	返回上级页面的方法
	*/
	public function back(){
		$parentid=I("get.parentid");  //获取父id
		if($parentid==0){
			$this->display("index");
		}
		else{  //返回以此id为父id的列表页
			$row=M("menu")->where("menu_id='".$parentid."'")->field("menu_id,parentid")->select();
			$this->assign("menu_id",$row[0]["menu_id"]);  
			$this->assign("parentid",$row[0]["parentid"]);
			$this->display("child");
		}
	}

	/*
	菜单修改页面显示
	*/
	public function edit(){
		$menu_id=I("get.menu_id");  //获取该项主键id
		$row=M("menu")->where("menu_id='".$menu_id."'")->select();
		$rows_menu_url_arr=explode("/", $row[0]["menu_url"]);
		$row[0]["menu_con"]=$rows_menu_url_arr[0];
		$row[0]["menu_fun"]=$rows_menu_url_arr[1];

		$this->assign("row",$row);
		$this->display("edit");
	}

	/*
	菜单修改保存方法
	*/
	public function update(){
		$data_post=I("post.");  //获取所有post过来的数据
		
		$this->__doSort($data_post["parentid"],$data_post["sort"]);  //将数据进行排序
		$data=array(
			"menu_id"=>$data_post["menu_id"],
			"menu_name"=>$data_post["menu_name"],
			"parentid"=>$data_post["parentid"],
			"menu_url"=>$data_post["controller"]."/".$data_post["function"],
			"sort"=>$data_post["sort"],
			"ishidden"=>$data_post["ishidden"]
		);
		$obj=new MenuModel();  //实例化model对象
		$return=$obj->editSave($data_post["menu_id"],$data);  
		$this->ajaxReturn($return,"JSON");  //将数据返回
	}

	/*
	子菜单修改页面显示
	*/
	public function editChild(){
		$menu_id=I("get.menu_id");  //获取menu_id
		$row=M("menu")->where("menu_id='".$menu_id."'")->select();
		$rows_menu_url_arr=explode("/", $row[0]["menu_url"]);
		$row[0]["menu_con"]=$rows_menu_url_arr[0];
		$row[0]["menu_fun"]=$rows_menu_url_arr[1];
		$row_father=M("menu")->where("menu_id='".$row[0]["parentid"]."'")->field("menu_id,menu_name")->select();
		
		$this->assign("row",$row);
		$this->assign("row_father",$row_father);
		$this->display("editChild");
	}

	/*
	一级菜单删除的方法
	*/
	public function del(){
		$menu_ids=I("post.ids");
		$ids = substr($menu_ids, 0, -1);
        $idsArr = explode(",", $ids);
        foreach ($idsArr as $key => $value) {
        	# code...
        	$row_parentid=M("menu")->where("parentid='".$value."'")->select();
        	if($row_parentid){
	        	$return=array(
	        		"status"=>false,
	        		"msg"=>"请先删除子菜单数据"
	        	);
	        	$this->ajaxReturn($return,"JSON");
	        }
        }
        foreach ($idsArr as $key => $value) {
        	# code...
        	$obj=new MenuModel();  //实例化model对象
			$return=$obj->del($value);  
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