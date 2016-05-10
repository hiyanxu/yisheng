<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\NewsCateModel;
use Admin\Model\NewsWorkflowModel;
use Admin\Model\OrgModel;
use Admin\Model\CateModel;
use Admin\Model\NewsModel;

class NewsController extends AdminController{
	/*
	构造函数
	*/
	public function __construct(){
		parent::__construct();
	}

	/*
	列表页显示
	*/
	public function index(){
		$org_rows=M("organization")->where("ishidden=0")->field("org_id,org_name")->select();
		$this->assign("org_rows",$org_rows);
		$this->display("index");  
	}

	/*
	信息分类列表页
	*/
	public function cateIndex(){
		$this->display("cateIndex");
	}

	/*
	分类选择操作
	*/
	public function cateSelect(){
		$cate_rows=M("category")->where("parentid=0 and ishidden=0")->field("cate_id,cate_name")->select();  //只取出最顶级菜单
		$this->assign("cate_rows",$cate_rows);
		$this->display("cateSelect");
	}

	/*
	分类选择入库
	*/
	public function cateSelectInsert(){
		$data_post=I("post.");  //获取所有post过来的数据
		$data=array(
			"cate_id"=>$data_post['cate_id'],
			"isenable"=>$data_post['isenable']
		);
		if($data_post['isenable']=="0"){
			$this->_setCateEnable(1);
		}
		$obj=new NewsCateModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	将数据库中是否可用的状态进行变换的方法
	*/
	public function _setCateEnable($isenable){
		$obj=new NewsCateModel();
		$return=$obj->setCateEnable($isenable);
	}

	/*
	信息分类获取列表数据的方法	
	*/
	public function newsCateAjaxIndex(){
		$order=I("get.sort")." ".I("get.order");
		if(I("get.sort")&&I("get.order")){
			$data=M()->table(array("category"=>"cate","news_category"=>"news_cate"))
			->field("cate.cate_name,news_cate.isenable,news_cate.news_cate_id")
			->where("cate.cate_id=news_cate.cate_id")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
		}
		else{
			$data=M()->table(array("category"=>"cate","news_category"=>"news_cate"))
			->field("cate.cate_name,news_cate.isenable,news_cate.news_cate_id")
			->where("cate.cate_id=news_cate.cate_id")->limit(I("get.offset"),I("get.limit"))->select();
		}
		//var_dump($data);die();

		foreach ($data as $key => $value) {
			# code...
			$data[$key]['isenableText']=$value['isenable']=="0"?"<label style='color:#5CB85C;'>启用</label>":"<label style='color:red;'>禁用</label>";
		}
		$data_count=M()->table(array("category"=>"cate","news_category"=>"news_cate"))
			->field("cate.cate_name,news_cate.isenable,news_cate.news_cate_id")
			->where("cate.cate_id=news_cate.cate_id")->count();

		$return=array(
			"total"=>$data_count,
			"data"=>$data
		);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	设置禁用或启用的方法
	*/
	public function setNewsCateIsEnable(){
		$news_cate_id=I("post.id");
		$flag=I("post.flag");
		if($flag==0){  //当前表示要启用某个记录，则我们应该把其它的先全部设置为1
			$this->_setCateEnable(1);
		}
		$data=array(
			"isenable"=>$flag
		);
		$obj=new NewsCateModel();
		$return=$obj->editSave($news_cate_id,$data);
		$this->ajaxReturn($return,"JSON");

	}

	/*
	删除某个所选分类
	正在启用状态的分类无法删除
	*/
	public function newsCatedel(){
		$id=I("post.id");  
		$rows=M("news_category")->where("news_cate_id='".$id."'")->field("isenable")->select();
		if($rows[0]["isenable"]==0){
			$return=array(
				"status"=>false,
				"msg"=>"该分类正在处于启用状态，无法删除"
			);
		}
		else{
			$obj=new NewsCateModel();
			$return=$obj->del($id);
		}
		$this->ajaxReturn($return,"JSON");
	}

	/*
	工作流选取列表页显示
	*/
	public function workflowIndex(){
		$this->display("workflowIndex");
	}

	/*
	工作流选取页面显示
	*/
	public function workSelect(){
		$workflow_rows=M("workflow")->field("workflow_id,workflow_name")->select();  //只取出最顶级菜单
		$this->assign("workflow_rows",$workflow_rows);
		$this->display("workSelect");
	}

	/*
	工作流选择入库操作
	*/
	public function workflowSelectInsert(){
		$data_post=I("post.");  //获取所有post过来的数据
		$data=array(
			"workflow_id"=>$data_post['workflow_id'],
			"isenable"=>$data_post['isenable']
		);
		if($data_post['isenable']=="0"){
			$this->_setWorkflowEnable(1);
		}
		$obj=new NewsWorkflowModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	设置全部工作流为禁用操作
	*/
	private function _setWorkflowEnable($isenable){
		$obj=new NewsWorkflowModel();
		$return=$obj->setCateEnable($isenable);
	}

	/*
	获取工作流列表数据
	*/
	public function newsWorkflowAjaxIndex(){
		$order=I("get.sort")." ".I("get.order");
		//var_dump($order);
		if(I("get.sort")&&I("get.order")){
			$data=M()->table(array("workflow"=>"wf","news_workflow"=>"news_wf"))
			->field("wf.workflow_name,news_wf.isenable,news_wf.news_workflow_id")
			->where("wf.workflow_id=news_wf.workflow_id")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
			//var_dump($order);die();
		}
		else{
			$data=M()->table(array("workflow"=>"wf","news_workflow"=>"news_wf"))
			->field("wf.workflow_name,news_wf.isenable,news_wf.news_workflow_id")
			->where("wf.workflow_id=news_wf.workflow_id")->limit(I("get.offset"),I("get.limit"))->select();
		}
		//var_dump($data);die();
		

		foreach ($data as $key => $value) {
			# code...
			$data[$key]['isenableText']=$value['isenable']=="0"?"<label style='color:#5CB85C;'>启用</label>":"<label style='color:red;'>禁用</label>";
		}
		$data_count=M()->table(array("workflow"=>"workflow","news_workflow"=>"news_wf"))
			->field("workflow.workflow_name,news_wf.isenable,news_wf.news_workflow_id")
			->where("workflow.workflow_id=news_wf.workflow_id")->count();

		$return=array(
			"total"=>$data_count,
			"data"=>$data
		);
		//var_dump($return);die();
		$this->ajaxReturn($return,"JSON");
	}

	/*
	设置工作流启用或禁用的方法
	*/
	public function setNewsWorkflowIsEnable(){
		$news_workflow_id=I("post.id");
		$flag=I("post.flag");
		if($flag==0){  //当前表示要启用某个记录，则我们应该把其它的先全部设置为1
			$this->_setWorkflowEnable(1);
		}
		$data=array(
			"isenable"=>$flag
		);
		$obj=new NewsWorkflowModel();
		$return=$obj->editSave($news_workflow_id,$data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	工作流选取删除的方法
	*/
	public function newsWorkflowedel(){
		$id=I("post.id");  
		$rows=M("news_workflow")->where("news_workflow_id='".$id."'")->field("isenable")->select();
		if($rows[0]["isenable"]==0){
			$return=array(
				"status"=>false,
				"msg"=>"该分类正在处于启用状态，无法删除"
			);
		}
		else{
			$obj=new NewsWorkflowModel();
			$return=$obj->del($id);
		}
		$this->ajaxReturn($return,"JSON");
	}

	/*
	信息添加页面显示
	*/
	public function add(){
		$loginuser=session("loginuser");  //获取当前登录人
		$loginuserid=session("loginuserid");  //获取当前登录人主键id
		$obj=new OrgModel();
		if($loginuser=="admin"){  //若当前登录人为admin，则给出所有的组织机构
			
			$org_rows=$obj->getOrgTreeRows();
		}
		else{
			$user_org=M("user")->where("user_id='".$loginuserid."'")->field("org_id")->select();
			$org_rows_last=M("organization")->where("org_id='".$user_org[0]['org_id']."'")->field("org_id,org_name")->select();
			
			$org_rows=$obj->getOrgTreeRows($user_org[0]['org_id']);  //获取子机构
			$org_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		}
		//var_dump($org_rows);die();
		$select_cate=M("news_category")->where("isenable=0")->field("cate_id")->select();
		$obj_cate=new CateModel();
		$cate_rows=$obj_cate->getCateTreeRows($select_cate[0]['cate_id']);

		$this->assign("cate_rows",$cate_rows);
		$this->assign("org_rows",$org_rows);

		$this->display("add");
	}

	/*
	信息添加操作
	*/
	public function insert(){
		$data_post=I("post.");

		$step_rows=$this->_getWorkflowStep();  //获取当前启用的工作流步骤
		$loginuserroleid=session("loginuserroleid");  //获取当前登录人的角色id
		$loginuser=session("loginuser");
		$workflow_select=M("news_workflow")->where("isenable=0")->field("workflow_id")->select();  //获取当前启用工作流
		$workflow_row=M("workflow")->where("workflow_id='".$workflow_select[0]['workflow_id']."'")->select();
		if($loginuser=="admin"){
			
			$workflow_step=$workflow_row[0]['steps'];  //若当前为admin添加，则信息直接到最高步骤，审核通过
			$ex_status=3;  //0：添加完成 1：正在审核中 2：审核未通过 3：审核通过
			$re_status=1;  //已发布
		}
		else{
			foreach($step_rows as $key=>$val){
				if($loginuserroleid==$val['role_id']){
					$step_current=$val['step_now'];  //获取当前正处于的步骤
					break;
				}
			}
			$workflow_step=$step_current;  //表示当前正在进行的步骤
			$step_total=$workflow_row[0]['steps'];  //得到当前启用工作流的步骤总数
			if($step_current==1){  //表示当前处于最底层步骤中
				$ex_status=0;
				$re_status=0;  //未发布
			}
			else if($step_current>1&&$step_current<$step_total){  //表示当前为处于中间的步骤的人添加的
				$ex_status=3;
				$re_status=0;  //未发布
			}
			else if($step_current==$step_total){  //表示当前是最高步骤的人添加的
				$ex_status=3;  //最高添加人添加的直接审核通过
				$re_status=1;  //0:未发布 1:已发布
			}
		}
		

		$data=array(
			"news_name"=>$data_post['news_name'],
			"news_time"=>strtotime($data_post['news_time']),
			"news_content"=>$data_post['news_content'],
			"news_cate_id"=>$data_post['news_cate_id'],
			"news_org_id"=>$data_post['news_org_id'],
			"addtime"=>time(),
			"add_account"=>session('loginaccount'),
			"workflow_step"=>$workflow_step,
			"ex_status"=>$ex_status,
			"re_status"=>$re_status,
			"isshow"=>0
		);
		//var_dump($data);die();
		$obj=new NewsModel();
		$return=$obj->dataAdd($data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	获取当前启用工作流的步骤情况
	*/
	private function _getWorkflowStep(){
		$workflow_select=M("news_workflow")->where("isenable=0")->field("workflow_id")->select();  //获取当前启用工作流
		$step_rows=M("workflowstep")->where("workflow_id='".$workflow_select[0]['workflow_id']."'")->field("step_now,role_id")->select();
		return $step_rows;
	}

	/*
	获取信息列表的操作
	*/
	public function ajaxIndex(){
		//在获取列表时，是有限制的，对应机构下的人登录进来只能看对应机构下的新闻信息
		$order=I("get.sort")." ".I("get.order");  //获取排序字段
		if(I("get.sort")&&I("get.order")){  //当前条件下获取的列表是有分类的
			$loginuser=session("loginuser");  //获取当前登录人
			$loginuserid=session("loginuserid");  //获取当前登录人主键id
			$ex_status=I("get.ex_status");  //获取前面传入的审核状态条件
			$news_org_id=I("get.news_org_id");  //获取页面传入的组织机构条件
			if($loginuser=="admin"){  //若当前登录人是admin，则我们给出所有机构下的所有新闻信息
				if($ex_status==""&&$news_org_id==""){  //表示当前是全部
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id")->count();
				}
				else if($ex_status!=""&&$news_org_id==""){
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status'")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status'")->count();
				}
				else if($ex_status==""&&$news_org_id!=""){
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.news_org_id='$news_org_id'")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.news_org_id='$news_org_id'")->count();


				}
				else if($ex_status!=""&&$news_org_id!=""){
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status' and news.news_org_id='$news_org_id'")->order($order)->limit(I("get.offset"),I("get.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status' and news.news_org_id='$news_org_id'")->count();
				}
				
			}
			else{
				$loginuser_org=$this->_getLoginuserOrg($loginuserid);  //根据登录人主键id获取当前登录人所属机构id
				$org_string="";
				$this->__getOrgAndChild($org_string,$loginuser_org[0]['org_id']);
				$org_string=$org_string.$loginuser_org[0]['org_id'];
				//var_dump($org_string);
				if($ex_status==""){
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.news_org_id in ($org_string)")
					->order($order)->limit(I("get.offset"),I("get.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.news_org_id in ($org_string)")->count();
				}
				else{
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status' and news.news_org_id in ($org_string)")
					->order($order)->limit(I("get.offset"),I("get.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status' and news.news_org_id in ($org_string)")->count();
				}				

			}
		}
		else{  //表示当前不用排序
			$loginuser=session("loginuser");  //获取当前登录人
			$loginuserid=session("loginuserid");  //获取当前登录人主键id
			$ex_status=I("get.ex_status");  //获取前面传入的审核状态条件
			$news_org_id=I("get.news_org_id");  //获取页面传入的组织机构条件
			if($loginuser=="admin"){  //若当前登录人是admin，则我们给出所有机构下的所有新闻信息
				if($ex_status==""&&$news_org_id==""){  //表示当前是全部
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id")->limit(I("post.offset"),I("post.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id")->count();
				}
				else if($ex_status!=""&&$news_org_id==""){
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status'")->limit(I("post.offset"),I("post.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status'")->count();
				}
				else if($ex_status==""&&$news_org_id!=""){
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.news_org_id='$news_org_id'")->limit(I("post.offset"),I("post.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.news_org_id='$news_org_id'")->count();
				}
				else if($ex_status!=""&&$news_org_id!=""){
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status' and news.news_org_id='$news_org_id'")->limit(I("post.offset"),I("post.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status' and news.news_org_id='$news_org_id'")->count();
				}
				
			}
			else{
				$loginuser_org=$this->_getLoginuserOrg($loginuserid);  //根据登录人主键id获取当前登录人所属机构id
				$org_string="";
				$this->__getOrgAndChild($org_string,$loginuser_org[0]['org_id']);
				$org_string=$org_string.$loginuser_org[0]['org_id'];
				//var_dump($org_string);
				if($ex_status==""){
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.news_org_id in ($org_string)")
					->limit(I("post.offset"),I("post.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.news_org_id in ($org_string)")->count();
				}
				else{
					$data=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status' and news.news_org_id in ($org_string)")
					->limit(I("post.offset"),I("post.limit"))->select();
				
					$data_count=M()->table(array("category"=>"cate","news"=>"news","organization"=>"org"))
					->field("news.workflow_step,cate.cate_name,news.news_name,news.news_id,news.addtime,news.news_time,org.org_name,news.add_account,news.ex_status,news.re_status")
					->where("cate.cate_id=news.news_cate_id and news.news_org_id=org.org_id and news.ex_status='$ex_status' and news.news_org_id='$news_org_id'")->count();
				}				

			}
		}

		//做对应值的转换
		foreach ($data as $key => $value) {
			# code...
			$data[$key]['news_time']=date("Y-m-d H:i:s",$value['news_time']);
			$data[$key]['addtime']=date("Y-m-d H:i:s",$value['addtime']);
			
			if($value['re_status']==0){
				$data[$key]['reStatusText']="未发布";
			}
			else{
				$data[$key]['reStatusText']="已发布";
			}

			//获取添加人信息
			$add_user_id_row=M("user_account")->where("user_account_id='".$value['add_account']."'")->field("user_id")->select();
			$add_user_info_row=M("user")->where("user_id='".$add_user_id_row[0]["user_id"]."'")->field("user_name")->select();
			$data[$key]['user_name']=$add_user_info_row[0]['user_name'];

			//获取审核步骤信息
			$news_workflow_row=M("news_workflow")->where("isenable=0")->field("workflow_id")->select();  //获取正在被使用的工作流
			$news_workflow_steps=M("workflow")->where("workflow_id='".$news_workflow_row[0]['workflow_id']."'")->field("steps")->select();  //获取该工作流总步骤数
			$loginuserroleid=session("loginuserroleid");

			$role_workflow_step=M("workflowstep")->where("workflow_id='".$news_workflow_row[0]['workflow_id']."' and role_id='".$loginuserroleid."'")->field("step_now")->select();  //获取当前人所应在的步骤
			//var_dump($role_workflow_step);die();
			if(session("loginuser")=="admin"){
				$data[$key]["isNeedEdit"]="ok";
				$data[$key]["isDel"]="ok";
			}
			if(!$role_workflow_step){  //表示这个审核工作流中并没有这个人
				$data[$key]['isNeedEx']="error";
				$data[$key]['isNeedSendEx']="error";
				$data[$key]['isNeedEdit']="error";
				if($value['workflow_step']==$news_workflow_steps[0]['steps']){  //表示当前所在步骤是最后一步
					if($value["ex_status"]==0){
						$data[$key]["exStatusText"]="未提交审核";
					}
					else if($value["ex_status"]==1){
						$data[$key]["exStatusText"]="正在审核中";
					}
					else if($value["ex_status"]==2){
						$data[$key]["exStatusText"]="审核未通过";
					}
					else if($value["ex_status"]==3){
						$data[$key]["exStatusText"]="审核通过";
					}
					
				}
			}
			else{  //表示当前的审核工作流有这个角色
				if($value['workflow_step']==$news_workflow_steps[0]['steps']){  //表示当前在最后的步骤里面
					//var_dump($value['workflow_step']);die();
					if($value['ex_status']==0||$value['ex_status']==1){
						$data[$key]['isNeedEx']="ok";  //表示当前该条信息需要被审核
						$data[$key]['isNeedSendEx']="error";
						$data[$key]['isNeedEdit']="ok";
					}
					if($value["ex_status"]==0){
						$data[$key]["exStatusText"]="未提交审核";
					}
					else if($value["ex_status"]==1){
						$data[$key]["exStatusText"]="正在审核中";
					}
					else if($value["ex_status"]==2){
						$data[$key]["exStatusText"]="审核未通过";
					}
					else if($value["ex_status"]==3){
						$data[$key]["exStatusText"]="审核通过";
					}
				}
				else if($value["workflow_step"]<$role_workflow_step[0]['step_now']){  //表示当前审核步骤还没有到这里，但这个人属于更上层的人，也可以审核
					
					if($value["ex_status"]==0){
						$data[$key]["exStatusText"]="添加者未提交审核";
						$data[$key]['isNeedEx']="error";
						$data[$key]['isNeedSendEx']="error";
						$data[$key]["isNeedEdit"]="error";
						$data[$key]["isDel"]="ok";
					}
					else if($value["ex_status"]==1){
						$data[$key]["exStatusText"]="下级管理员正在审核中";
						$data[$key]['isNeedEx']="ok";
						$data[$key]['isNeedSendEx']="error";
						$data[$key]["isNeedEdit"]="error";
						$data[$key]["isDel"]="ok";
					}
					else if($value["ex_status"]==2){
						$data[$key]["exStatusText"]="下级管理员审核未通过";
						$data[$key]['isNeedEx']="error";
						$data[$key]['isNeedSendEx']="error";
						$data[$key]["isNeedEdit"]="error";
						$data[$key]["isDel"]="ok";
					}
					else if($value["ex_status"]==3){
						$data[$key]["exStatusText"]="下级管理员审核通过";
						$data[$key]['isNeedEx']="ok";
						$data[$key]['isNeedSendEx']="ok";
						$data[$key]["isNeedEdit"]="ok";
						$data[$key]["isDel"]="ok";
					}

				}
				else if($value["workflow_step"]==$role_workflow_step[0]['step_now']){  //表示当前正好是在这个人这里
					//var_dump($role_workflow_step[0]['step_now']);var_dump($value["workflow_step"]);die();
					if($value["ex_status"]==0){
							$data[$key]["exStatusText"]="添加者未提交审核";
							$data[$key]['isNeedEx']="error";
							$data[$key]['isNeedSendEx']="ok";
							$data[$key]["isNeedEdit"]="ok";
							$data[$key]["isDel"]="ok";
						}
						else if($value["ex_status"]==1){
							$data[$key]["exStatusText"]="正在审核中";
							$data[$key]['isNeedEx']="ok";
							$data[$key]['isNeedSendEx']="ok";
							$data[$key]["isNeedEdit"]="ok";
							$data[$key]["isDel"]="ok";
						}
						else if($value["ex_status"]==2){
							$data[$key]["exStatusText"]="审核未通过";
							$data[$key]['isNeedEx']="error";
							$data[$key]['isNeedSendEx']="ok";
							$data[$key]["isNeedEdit"]="ok";
							$data[$key]["isDel"]="error";
						}
						else if($value["ex_status"]==3){
							$data[$key]["exStatusText"]="审核通过";
							$data[$key]['isNeedEx']="ok";
							if($role_workflow_step[0]["step_now"]<$news_workflow_steps[0]['steps']){  //当前人不是最后一个审核人，需要送审
								$data[$key]['isNeedSendEx']="ok";
							}							
							$data[$key]["isNeedEdit"]="ok";
							$data[$key]["isDel"]="error";
					}

				}
				else if($value["workflow_step"]>$role_workflow_step[0]['step_now']){  //表示已经经过了这个步骤了
					//var_dump("10");die();
					if($value["ex_status"]==0){
						$data[$key]["exStatusText"]="添加者未提交审核";
					}
					else if($value["ex_status"]==1){
						$data[$key]["exStatusText"]="上级管理员正在审核中";						
					}
					else if($value["ex_status"]==2){
						$data[$key]["exStatusText"]="上级管理员审核未通过";						
					}
					else if($value["ex_status"]==3){
						$data[$key]["exStatusText"]="上级管理员审核通过";						
					}
					$data[$key]['isNeedEx']="error";
					$data[$key]['isNeedSendEx']="error";
					$data[$key]["isNeedEdit"]="error";
					$data[$key]["isDel"]="error";
				}
			}
			


		}



		$return=array(
			"total"=>$data_count,
			"data"=>$data
		);
		$this->ajaxReturn($return,"JSON");


	}

	/*
	获取当前登录人所属机构
	*/
	private function _getLoginuserOrg($user_id){
		$user_row=M("user")->where("user_id='".$user_id."'")->field("org_id")->select();
		return $user_row;
	}

	/*
	获取当前机构及其子机构的方法
	*/
	private function __getOrgAndChild(&$data,$org_id){
		//$data.=$org_id.",";
		//var_dump($data);die();
		$is_have=M("organization")->where("parentid='".$org_id."' and ishidden=0")->select();  //判断当前是否还存在以此id为父id的记录
		
		if($is_have){  //表示当前仍然有
			foreach ($is_have as $key => $value) {
				# code...
				$data.=$value['org_id'].",";
				//var_dump($data);
				$this->__getOrgAndChild($data,$value['org_id']);
			}			
		}
		else{
			return;
		}
		//var_dump($data);die();
	}

	/*
	信息修改操作
	*/
	public function edit(){
		$news_id=I("get.news_id");  //获取主键id
		$row=M("news")->where("news_id='$news_id'")->select();  //获取新闻信息数据

		$loginuser=session("loginuser");  //获取当前登录人
		$loginuserid=session("loginuserid");  //获取当前登录人主键id
		$obj=new OrgModel();
		if($loginuser=="admin"){  //若当前登录人为admin，则给出所有的组织机构
			
			$org_rows=$obj->getOrgTreeRows();
		}
		else{
			$user_org=M("user")->where("user_id='".$loginuserid."'")->field("org_id")->select();
			$org_rows_last=M("organization")->where("org_id='".$user_org[0]['org_id']."'")->field("org_id,org_name")->select();
			
			$org_rows=$obj->getOrgTreeRows($user_org[0]['org_id']);  //获取子机构
			$org_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		}
		
		$select_cate=M("news_category")->where("isenable=0")->field("cate_id")->select();
		$obj_cate=new CateModel();
		$cate_rows=$obj_cate->getCateTreeRows($select_cate[0]['cate_id']);
		$row[0]["news_time"]=date("Y-m-d",$row[0]['news_time']);

		$this->assign("cate_rows",$cate_rows);
		$this->assign("org_rows",$org_rows);
		$this->assign("row",$row);
		$this->assign("news_id",$news_id);
		

		$this->display("edit");

	}

	/*
	信息修改保存操作
	*/
	public function update(){
		$data_post=I("post.");
		

		$data=array(
			"news_name"=>$data_post['news_name'],
			"news_time"=>strtotime($data_post['news_time']),
			"news_content"=>$data_post['news_content'],
			"news_cate_id"=>$data_post['news_cate_id'],
			"news_org_id"=>$data_post['news_org_id'],
			"edittime"=>time()
		);
		//var_dump($data);die();
		$obj=new NewsModel();
		$return=$obj->editSave($data_post['news_id'],$data);
		$this->ajaxReturn($return,"JSON");
	}

	/*
	送审操作
	*/
	public function sendEx(){
		$news_id=I("post.news_id");
		$row=M("news")->where("news_id='$news_id'")->field("workflow_step,ex_status")->select();  //获取当前所在步骤和审核情况
		//var_dump($row);
		if($row[0]["workflow_step"]==1){  //表示当前正处于最底层的步骤中，即该信息为才添加的信息，可以送审
			$workflow_step=2;  //让当前步骤+1
			$ex_status=1;  //将当前审核状态置为正在审核中
			$data=array(
					"workflow_step"=>$workflow_step,
					"ex_status"=>$ex_status
					);
				$obj=new NewsModel();
				$return=$obj->editSave($news_id,$data);
				$return["msg"]="送审成功";
		}
		else{  //表示当前处于中间步骤，因为最高步骤不会再有送审操作，则我们应该根据当前ex_status来判断
			if($row[0]['ex_status']!=3){  //表示当前在中间步骤，且中间操作人还没有审核，则不可送审
				$return=array(
					"status"=>false,
					"msg"=>"请审核后在向上级管理员提交审核"
				);
			}
			else{
				$workflow_step=$row[0]['workflow_step']+1;
				$ex_status=1;  //将当前审核步骤+1，且将当前审核状态置为正在审核中
				$data=array(
					"workflow_step"=>$workflow_step,
					"ex_status"=>$ex_status
					);
				$obj=new NewsModel();
				$return=$obj->editSave($news_id,$data);
				$return["msg"]="送审成功";
			}
		}
		//var_dump($return);die();

		$this->ajaxReturn($return,"JSON");

	}

	/*
	审核页面显示
	*/
	public function getExamine(){
		$news_id=I("get.news_id");  //获取主键id
		$row=M("news")->where("news_id='$news_id'")->select();  //获取新闻信息数据

		$loginuser=session("loginuser");  //获取当前登录人
		$loginuserid=session("loginuserid");  //获取当前登录人主键id
		$obj=new OrgModel();
		if($loginuser=="admin"){  //若当前登录人为admin，则给出所有的组织机构
			
			$org_rows=$obj->getOrgTreeRows();
		}
		else{
			$user_org=M("user")->where("user_id='".$loginuserid."'")->field("org_id")->select();
			$org_rows_last=M("organization")->where("org_id='".$user_org[0]['org_id']."'")->field("org_id,org_name")->select();
			
			$org_rows=$obj->getOrgTreeRows($user_org[0]['org_id']);  //获取子机构
			$org_rows[$org_rows_last[0]['org_id']]=$org_rows_last[0]['org_name'];
		}
		
		$select_cate=M("news_category")->where("isenable=0")->field("cate_id")->select();
		$obj_cate=new CateModel();
		$cate_rows=$obj_cate->getCateTreeRows($select_cate[0]['cate_id']);
		$row[0]["news_time"]=date("Y-m-d",$row[0]['news_time']);

		$this->assign("cate_rows",$cate_rows);
		$this->assign("org_rows",$org_rows);
		$this->assign("row",$row);
		$this->assign("news_id",$news_id);
		

		$this->display("examine");
	}

	/*
	审核保存操作
	*/
	public function examine(){
		$data_post=I("post.");  //获取post过来的数据
		$row=M("news")->where("news_id='".$data_post['news_id']."'")->select();
		//审核的操作：若当前人即为审核过程的最后一个，则置为发布。若不是，则将审核步骤+1，将当前审核状态设为3（审核通过）
		$loginuser=session("loginuser");
		//该信息所对应的工作流是哪个
		$news_workflow=M("news_workflow")->where("isenable=0")->field("workflow_id")->select();
		//当前工作流总步骤数
		$news_workflow_steps=M("workflow")->where("workflow_id='".$news_workflow[0]['workflow_id']."'")->field("steps")->select();
		//当前角色所对应的工作流步骤
		$loginuserroleid=session("loginuserroleid");
		$role_workflow_step=M("workflowstep")->where("workflow_id='".$news_workflow[0]['workflow_id']."' and role_id='$loginuserroleid'")->field("step_now")->select();

		if($loginuser=="admin"){  //若当前审核人为admin，则直接将审核步骤置为最后一步，将当前审核状态置为3，发布状态置为发布
			if($data_post["examine"]==0){  //表示当前为审核通过,则置为最后一步，审核状态置为3，发布状态置为发布
				$workflow_step=$news_workflow_steps[0]['steps'];
				$ex_status=3;  //置为审核通过
				$re_status=1;  //已发布
			}
			else{  //表示当前没有审核通过，则应该将当前步骤-1，状态置为2（审核未通过）
				$workflow_step=$row[0]['workflow_step']-1;
				$ex_status=2;
				$re_status=0;  
			}

		}
		else{  //表示当前登录人不是admin，则我们应该根据当前登录人的角色所对应的步骤，给出操作
			if($role_workflow_step[0]['step_now']==$news_workflow_steps[0]["steps"]){  //表示当前已经是最后一步
				if($data_post["examine"]==0){  //表示当前为审核通过,则置为最后一步，审核状态置为3，发布状态置为发布
					$workflow_step=$news_workflow_steps[0]['steps'];
					$ex_status=3;  //置为审核通过
					$re_status=1;  //已发布
				}
				else{  //表示当前没有审核通过，则应该将当前步骤-1，状态置为2（审核未通过）
					$workflow_step=$row[0]['workflow_step']-1;
					$ex_status=2;
					$re_status=0;  
				}
			}
			else{  //表示当前审核人为中间步骤的审核人
				if($data_post["examine"]==0){  //表示当前为审核通过,则置为最后一步，审核状态置为3，发布状态置为发布
					$workflow_step=$row[0]['workflow_step']+1;
					$ex_status=3;  //置为审核通过
					$re_status=1;  //已发布
				}
				else{  //表示当前没有审核通过，则应该将当前步骤-1，状态置为2（审核未通过）
					$workflow_step=$row[0]['workflow_step']-1;
					$ex_status=2;
					$re_status=0;  
				}
			}

		}
		$data=array(
			"workflow_step"=>$workflow_step,
			"ex_status"=>$ex_status,
			"re_status"=>$re_status,
			"examine_account"=>session("loginaccount")
		);
		$obj=new NewsModel();
		$return=$obj->editSave($data_post['news_id'],$data);
		if($return['status']){
			$return['msg']="审核成功";
		}
		else{
			$return['msg']="审核失败，请重试";
		}
		$this->ajaxReturn($return,"JSON");

	}

	/*
	删除操作  包括批量删除
	*/
	public function del(){
		$ids=I("post.ids");

		$ids_string=substr($ids,0,-1);  //去掉最后的逗号
		$idsToArr=explode(",",$ids_string);  //按逗号切割，获取数组

		//该信息所对应的工作流是哪个
		$news_workflow=M("news_workflow")->where("isenable=0")->field("workflow_id")->select();
		//当前工作流总步骤数
		$news_workflow_steps=M("workflow")->where("workflow_id='".$news_workflow[0]['workflow_id']."'")->field("steps")->select();
		//当前角色所对应的工作流步骤
		$loginuserroleid=session("loginuserroleid");
		$role_workflow_step=M("workflowstep")->where("workflow_id='".$news_workflow[0]['workflow_id']."' and role_id='$loginuserroleid'")->field("step_now")->select();
		foreach ($idsToArr as $key => $value) {
			# code...
			$row=M("news")->where("news_id='".$value."'")->field("workflow_step")->select();
			if($role_workflow_step[0]['step_now']<$row[0]['workflow_step']){
				$return=array(
					"status"=>false,
					"msg"=>"您上级管理员正在处理，无法删除"
					);
				$this->ajaxReturn($return,"JSON");
			}			
		}
		foreach ($idsToArr as $key => $value) {
			# code...
			$obj=new NewsModel();
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