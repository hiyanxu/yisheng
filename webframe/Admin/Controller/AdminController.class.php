<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Behavior;

class AdminController extends Controller{
	/*
	类的构造函数
	该方法主要用于去调用行为扩展类的run()方法
	*/
	public function __construct(){

		parent::__construct();

		//记录用户行为日志   （为节省空间，暂时先不去记录用户行为日志）
		/*$data=array(
			"behavior_log_account"=>session("loginaccount"),
			"behavior_log_con"=>CONTROLLER_NAME,
			"behavior_log_func"=>ACTION_NAME,
			"behavior_log_time"=>time(),
			"behavior_log_ip"=>$_SERVER['REMOTE_ADDR']
			);
		$this->insertBehaviorLog($data);*/

		$cookie_loginuser=cookie("cookie_loginuser");
		//var_dump($cookie_loginuser);die();
		if(empty($cookie_loginuser)){
			$this->redirect("Login/showLogin");  //否则表示当前没有人登录，则可以显示login页面，让用户去填入信息登录			
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
					$this->redirect("Login/showLogin");
				}
				else{  //表示当前token相符合，则我们判断当前cookie是否过期，若过期，则重新登录
					$now=time();
					if($row[0]['timeout']==""||$row[0]['timeout']==0){  //表示当前没有记住密码，直接对照身份
						$row_user_identify=md5($row[0]['user_account']."haha");  //
						if($user_identify!=$row_user_identify){  //表示当前身份标识非法
							//echo "<script>alert('身份标识有问题了')</script>";
							//var_dump("非法身份");die();
							cookie("cookie_loginuser",null);  //销毁cookie
							$this->redirect("Login/showLogin");  //显示登录页面
						}
						else{  //表示当前用户合法，则我们可以让其进行登录，跳转到后台首页

							$loginuser=session("loginuser");  //获取当前登录人的session值
							if($loginuser!="admin"){  //只有在当前登录人不是admin的时候，我们才需要去获取当前人执行的请求方法，然后进行权限对比
								$con_name=CONTROLLER_NAME;
								$fun_name=ACTION_NAME;
								$url=$con_name."/".$fun_name;
								$access_urls=session("access_urls");  //将所有目前session中的权限获取出来
								
								//echo "<script>alert('".$url."')</script>";
								if($fun_name!="ajaxIndex"){				

									//echo "<script>alert('".$url."')</script>";
									if($url=="Index/index"||$url=="Index/head"||$url=="Index/left"||$url=="Index/right"||$url="Index/logout"){  //我们默认给出显示首页的三个方法的权限
										
									}
									else{
										$is_have="error";
										foreach ($access_urls as $key => $value) {
											# code...
											if(in_array($url, $value)){
												$is_have="ok";
												break;
											}
										}
										
										if($is_have=="error"){  //若最终为error，则表示当前没有该权限
											echo "<script>alert('您不具备该权限!')</script>";
											die();
										}
									}
								}
							}

						}
					}
					else{
						if($now>$row[0]['timeout']){  //表示当前cookie过期，则销毁当前cookie，重新登录
							//echo "<script>alert('刚才忘了设置过期时间咯！')</script>";
							//var_dump("过期时间这");die();
							cookie("cookie_loginuser",null);
							$this->redirect("Login/showLogin");
						}
						else{
							$row_user_identify=md5($row[0]['user_account']."haha");  //
							if($user_identify!=$row_user_identify){  //表示当前身份标识非法
								//echo "<script>alert('身份标识有问题了')</script>";
								var_dump("非法身份");die();
								cookie("cookie_loginuser",null);  //销毁cookie
								$this->redirect("Login/showLogin");  //显示登录页面
							}
							else{  //表示当前用户合法，则我们可以让其进行登录，跳转到后台首页

								$loginuser=session("loginuser");  //获取当前登录人的session值
								if($loginuser!="admin"){  //只有在当前登录人不是admin的时候，我们才需要去获取当前人执行的请求方法，然后进行权限对比
									$con_name=CONTROLLER_NAME;
									$fun_name=ACTION_NAME;
									$url=$con_name."/".$fun_name;
									$access_urls=session("access_urls");  //将所有目前session中的权限获取出来
									
									//echo "<script>alert('".$url."')</script>";
									if($fun_name!="ajaxIndex"){				

										//echo "<script>alert('".$url."')</script>";
										if($url=="Index/index"||$url=="Index/head"||$url=="Index/left"||$url=="Index/right"||$url="Index/logout"){  //我们默认给出显示首页的三个方法的权限
											
										}
										else{
											$is_have="error";
											foreach ($access_urls as $key => $value) {
												# code...
												if(in_array($url, $value)){
													$is_have="ok";
													break;
												}
											}
											
											if($is_have=="error"){  //若最终为error，则表示当前没有该权限
												echo "<script>alert('您不具备该权限!')</script>";
												die();
											}
										}
									}
								}

							}
						}
					}
				}

			}
			else{  //表示当前根据这个cookie没有得到当前用户的信息，则让其去跳转登录
				$this->redirect("Login/showLogin");  //显示登录页面  //显示登录页面，让用户去登录
			}

		}



		
		/*if(session("?loginuser")){  //表示当前人已经登录，则我们应该获取
			$loginuser=session("loginuser");  //获取当前登录人的session值
			if($loginuser!="admin"){  //只有在当前登录人不是admin的时候，我们才需要去获取当前人执行的请求方法，然后进行权限对比
				$con_name=CONTROLLER_NAME;
				$fun_name=ACTION_NAME;
				$url=$con_name."/".$fun_name;
				$access_urls=session("access_urls");  //将所有目前session中的权限获取出来
				
				//echo "<script>alert('".$url."')</script>";
				if($fun_name!="ajaxIndex"){				

					//echo "<script>alert('".$url."')</script>";
					if($url=="Index/index"||$url=="Index/head"||$url=="Index/left"||$url=="Index/right"){  //我们默认给出显示首页的三个方法的权限
						
					}
					else{
						$is_have="error";
						foreach ($access_urls as $key => $value) {
							# code...
							if(in_array($url, $value)){
								$is_have="ok";
								break;
							}
						}
						
						if($is_have=="error"){  //若最终为error，则表示当前没有该权限
							echo "<script>alert('您不具备该权限!')</script>";
							die();
						}
					}
				}
			}

		}
		else{
			$this->redirect("Login/showLogin");
		}*/
	}

	public function index(){
		$this->display();
	}

	/*
	记录用户行为日志
	*/
	public function insertBehaviorLog($data=null){
		if(is_null($data)){
			$return=array(
				"status"=>false,
				"msg"=>"请给出有效数据"
			);
		}
		else{
			if(M("behavior_log")->data($data)->add()){
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

