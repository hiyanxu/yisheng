<?php
namespace Admin\Behavior;

use Think\Behavior;

class AuthCheckBehavior extends Behavior{

	/*
	扩展一个行为类，主要用于检测用户是否登陆已经用户权限
	入口方法run()
	*/
	public function run(&$return){
		//echo "haha<br>";	
		if(session("?loginuser")){  //看当前是否有session信息，若有，则进行权限分配，无论菜单还是权限，都拿session做通信
			if(session("loginuser")=="admin"){//若当前登录人是admin，则应该给出所有权限
				return;
			}
			else{
				echo "你好意思哟，您没有这项权限";
			}
		}
	}

}