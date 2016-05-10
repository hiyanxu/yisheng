<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Behavior;
use Admin\Model\LoginLogModel;

class LoginController extends Controller{

	/*
	显示登录页面的操作
	*/
	public function showLogin(){
		$cookie_loginuser=cookie("cookie_loginuser");
		//var_dump($cookie_loginuser);die();
		if(empty($cookie_loginuser)){
			$this->display("login");  //否则表示当前没有人登录，则可以显示login页面，让用户去填入信息登录
		}
		else{  //根据cookie中的值进行当前用户、第二标识、token等的判断
			list($user_identify,$token)=explode(":", $cookie_loginuser);  //获取cookie值中存放的第二身份标识和token，并赋值

			//我们根据当前第二身份标识去获取当前用户的信息
			$row=M("user_account")->where("user_identify='$user_identify'")->select();  //获取当前用户信息
			//var_dump($row);die();
			if($row){  //表示当前有这个用户
				//我们去判断token，看看是不是从同一个地方进行的登录
				if($row[0]['token']!=$token){  //当前表示token不相等，则不是从一个地方登录的，则显示登录页面进行登录
					//var_dump("token这");die();
					cookie("cookie_loginuser",null); 
					$this->display("login");
				}
				else{  //表示当前token相符合，则我们判断当前cookie是否过期，若过期，则重新登录
					$now=time();
					if($now>$row[0]['timeout']){  //表示当前cookie过期，则销毁当前cookie，重新登录
						//echo "<script>alert('刚才忘了设置过期时间咯！')</script>";
						//var_dump("过期时间这");die();
						cookie("cookie_loginuser",null);
						$this->display("login");
					}
					else{
						$row_user_identify=md5($row[0]['user_account']."haha");  //
						if($user_identify!=$row_user_identify){  //表示当前身份标识非法
							//echo "<script>alert('身份标识有问题了')</script>";
							//var_dump("非法身份");die();
							cookie("cookie_loginuser",null);  //销毁cookie
							$this->display("login");  //显示登录页面
						}
						else{  //表示当前用户合法，则我们可以让其进行登录，跳转到后台首页，重新建立session信息
							session('loginuser',$row[0]["user_account"]);
							session('loginuserid',$row[0]["user_id"]);
							session('loginuserroleid',$row[0]['role_id']);
							session('loginaccount',$row[0]['user_account_id']);
							if($data_post['userName']!="admin"){
								//当前不是admin的情况下，我们要把所有的权限取出来并存入session中
								$access_ids=M("role_access")->where("role_id='".$row[0]['role_id']."'")->field("access_id")->select();  //获取当前所有的权限id
								$access_urls=array();
								foreach ($access_ids as $key => $value) {
									# code...将所有的该id对应的url操作字段获取出来
									$get_row=M("access")->where("access_id='".$value['access_id']."'")->field("access_url")->select();
									$access_urls[]=$get_row[0];
								}
								//var_dump($access_urls);die();
								session("access_urls",$access_urls);
							}
							$this->redirect("Index/index");
						}
					}
				}

			}
			else{  //表示当前根据这个cookie没有得到当前用户的信息，则让其去跳转登录
				$this->display("login");  //显示登录页面，让用户去登录
			}

		}
	}

	/*
	登录判断的方法
	*/
	public function login(){
		$data_post=I("post.");
		if($data_post["userName"]==""){
			echo "<script>alert('请输入用户名')</script>";
			return;
		}
		if($data_post['userPwd']==""){
			echo "<script>alert('请输入密码')</script>";
			return;
		}
		$row=M("user_account")->where("user_account='".$data_post["userName"]."' and user_pwd='".$data_post["userPwd"]."'")->select();
		
		if(!$row){
			//在这里建立登录日志，当前登录人登录失败日志
			$login_ip=$_SERVER['REMOTE_ADDR'];
			$data=array(
				"login_ip"=>$login_ip,
				"login_account"=>$data_post['userName'],
				"login_time"=>time(),
				"login_operation"=>0,   //0:登录  1：登出
				"login_status"=>1,  //1:表示当前登录失败  0:表示当前登录成功
				"is_remember"=>1  //0：表示当前记住  1：表示当前不是记住我
				);
			$this->insertLoginLog($data);

			$this->error("用户名或密码错误","showLogin",2);
			return;
		}		

		/*
		能走到这里，则表示当前登录人填写的用户名和密码正确，则我们首先判断用户是否选中了“记住我”：
		若没选中，则我们只是简单记录一些session信息，更新token，然后记住登录日志
		若选中：
		（1）则我们现在应该更新数据库token，
		（2）存储cookie，根据当前用户做一个用户名和密码，将当前第二标识和token存储进去
		（3）设置过期时间，系统自动默认过期时间为7天
		（4）记录登录日志
		*/
		if(isset($data_post['rememberMe'])){  //表示当前选中了“记住我”操作
			$token=md5(uniqid(rand(), TRUE));  //生成一个唯一标识token
			$timeout=time()+60*60*24*7;  //表示从现在开始的7天后的时间
			$user_identify=md5($data_post["userName"]."haha");  //根据当前登录用户生成唯一标识
			$data_token=array(
				"token"=>$token,
				"timeout"=>$timeout,
				"user_identify"=>$user_identify
				);
			M("user_account")->where("user_account='".$data_post["userName"]."' and user_pwd='".$data_post["userPwd"]."'")->save($data_token);  //更新数据库token

			cookie("cookie_loginuser",$user_identify.":".$token,$timeout);  //设置cookie信息
			//记录登录日志
			$login_ip=$_SERVER['REMOTE_ADDR'];
			$data=array(
				"login_ip"=>$login_ip,
				"login_account"=>$data_post['userName'],
				"login_time"=>time(),
				"login_operation"=>0,   //0:登录  1：登出
				"login_status"=>0,  //1:表示当前登录失败  0:表示当前登录成功
				"is_remember"=>0  //0：表示当前记住  1：表示当前不是记住我
				);
			$this->insertLoginLog($data);

		}
		else{	
			//记录登录日志，是没有设置cookie信息的登录日志
			$token=md5(uniqid(rand(), TRUE));  //生成一个唯一标识token
			$time='';
			$data_token=array(
				"token"=>$token,
				"timeout"=>$time
				);
			M("user_account")->where("user_account='".$data_post["userName"]."' and user_pwd='".$data_post["userPwd"]."'")->save($data_token);  //更新数据库token
			$user_identify=md5($data_post["userName"]."haha");  //根据当前登录用户生成唯一标识
			cookie("cookie_loginuser",$user_identify.":".$token);  //设置cookie信息

			
			$login_ip=$_SERVER['REMOTE_ADDR'];
			$data=array(
				"login_ip"=>$login_ip,
				"login_account"=>$data_post['userName'],
				"login_time"=>time(),
				"login_operation"=>0,   //0:登录  1：登出
				"login_status"=>0,  //1:表示当前登录失败  0:表示当前登录成功
				"is_remember"=>1  //0：表示当前记住  1：表示当前不是记住我
				);
			$this->insertLoginLog($data);

		}
		$login_ip=$_SERVER["REMOTE_ADDR"];
			$datetime=time();
			$data=array(
				"last_log_ip"=>$login_ip,
				"last_log_time"=>$datetime
			);
			M("user_account")->where("user_account='".$data_post["userName"]."' and user_pwd='".$data_post["userPwd"]."'")->save($data);
			session('loginuser',$data_post["userName"]);
			session('loginuserid',$row[0]["user_id"]);
			session('loginuserroleid',$row[0]['role_id']);
			session('loginaccount',$row[0]['user_account_id']);
			if($data_post['userName']!="admin"){
				//当前不是admin的情况下，我们要把所有的权限取出来并存入session中
				$access_ids=M("role_access")->where("role_id='".$row[0]['role_id']."'")->field("access_id")->select();  //获取当前所有的权限id
				$access_urls=array();
				foreach ($access_ids as $key => $value) {
					# code...将所有的该id对应的url操作字段获取出来
					$get_row=M("access")->where("access_id='".$value['access_id']."'")->field("access_url")->select();
					$access_urls[]=$get_row[0];
				}
				//var_dump($access_urls);die();
				session("access_urls",$access_urls);
			}
			//var_dump(session("loginuserroleid"));
			//记录登录日志
			//B('Admin\Behavior\AuthCheck');
			$this->redirect("Index/index");

	}



	
	/*
	显示登录方法 
	*/
	/*public function showLogin(){  //将登录和首页的显示分开，LoginController不去继承AdminController，IndexController中去继承AdminController
		if(session("?loginuser")){
			//$this->display("index");
			//session("loginuser",null);
			//若当前存在session，则证明当前人已经登录，则可以跳转至index页面
			//session("loginuser",null);
			//echo "<script>alert('".session("loginuser")."')</script>";
			$this->redirect("Index/index");
			

		}
		else{
			//echo "<script>alert('".session("loginuser")."')</script>";
			$this->display("login");  //否则表示当前没有人登录，则可以显示login页面，让用户去填入信息登录
		}
		
	}*/

	/*
	登录判断方法
	*/
	/*public function login(){
		$data_post=I("post.");
		if($data_post["userName"]==""){
			echo "<script>alert('请输入用户名')</script>";
			return;
		}
		if($data_post['userPwd']==""){
			echo "<script>alert('请输入密码')</script>";
			return;
		}
		$row=M("user_account")->where("user_account='".$data_post["userName"]."' and user_pwd='".$data_post["userPwd"]."'")->select();
		
		if(!$row){
			$this->error("用户名或密码错误","showLogin",2);
			return;
		}		
		$login_ip=$_SERVER["REMOTE_ADDR"];
		$datetime=time();
		$data=array(
			"last_log_ip"=>$login_ip,
			"last_log_time"=>$datetime
		);
		M("user_account")->where("user_account='".$data_post["userName"]."' and user_pwd='".$data_post["userPwd"]."'")->save($data);
		session('loginuser',$data_post["userName"]);
		session('loginuserid',$row[0]["user_id"]);
		session('loginuserroleid',$row[0]['role_id']);
		session('loginaccount',$row[0]['user_account_id']);
		if($data_post['userName']!="admin"){
			//当前不是admin的情况下，我们要把所有的权限取出来并存入session中
			$access_ids=M("role_access")->where("role_id='".$row[0]['role_id']."'")->field("access_id")->select();  //获取当前所有的权限id
			$access_urls=array();
			foreach ($access_ids as $key => $value) {
				# code...将所有的该id对应的url操作字段获取出来
				$get_row=M("access")->where("access_id='".$value['access_id']."'")->field("access_url")->select();
				$access_urls[]=$get_row[0];
			}
			//var_dump($access_urls);die();
			session("access_urls",$access_urls);
		}
		//B('Admin\Behavior\AuthCheck');
		$this->redirect("Index/index");
		//$this->display("index");

	
	}
*/


	/*
	记录登录日志的操作
	*/
	public function insertLoginLog($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			$obj=new LoginLogModel();
			$return=$obj->dataAdd($data);
		}
		return $return;
	}

	


	/*
	头部的显示
	*/
	public function head(){
		$loginuser=I("session.loginuser");
		$this->assign("loginuser",$loginuser);
		$this->display("head");
	}

	/*
	左边的显示
	*/
	public function left(){	//left仅仅用于做菜单显示权限，点击等权限交给行为类去做	
		if(session("loginuser")=="admin"){  //当前是admin，则给出所有菜单权限
			$menu_top_rows=$this->getmenu(0);  //获取所有的最顶级菜单
			$menu_second_rows=array();
			// foreach ($menu_top_rows as $key => $value) {
			// 	# code...
			// 	//var_dump($value["menu_id"]);
			// 	$menu_second_rows[$key]=$this->getmenu($value["menu_id"]);
				
			// }
			$menu_second_rows=M("menu")->where("parentid!=0 and ishidden=0")->order("sort asc")->field("menu_id,menu_name,menu_url,parentid")->select();
			//var_dump($menu_second_rows);
			$this->assign("menu_top_rows",$menu_top_rows);
			$this->assign("menu_second_rows",$menu_second_rows);
			$this->display("left");
		}
		else{
			echo "不好意思，我们要比对权限喽！";
		}
	}

	/*
	右边的显示
	*/
	public function right(){
		$this->display("right");
	}

	/*
	获取菜单的方法
	*/
	public function getmenu($parentid=0){
		$rows=M("menu")->where("parentid='".$parentid."' and ishidden=0")->field("menu_id,menu_name,parentid,menu_url")->order("sort asc")->select();
		return $rows;
	}

	/*
	404操作页面
	*/
	public function _empty(){
		header("HTTP/1.0 404 NOT　Found");
		$this->display("Empty/index");  //让他找到404页面
	}

}