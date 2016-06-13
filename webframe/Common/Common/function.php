<?php
/*
公共函数封装文件
将一些经常使用的公共函数进行封装，以便控制器调用
*/
/*************以下部分是对工作流进行权限封装的公共方法*****************/
/*
参数列表：
$role:当前登录人角色
$wof_id:当前启用的工作流主键id
$wof_step_now:当前该条信息所在的审核步骤
$ex_status:当前该条信息所在审核步骤的审核情况
$is_login:当前该条信息是否是当前登录人添加的
*/
function getExAcesByWof($role=null,$wof_id=null,$wof_step_now=null,$ex_status=null,$is_login=true){	
	if(is_null($role)||is_null($wof_id)||is_null($wof_step_now)||is_null($ex_status)){
		$return=array(
			"status"=>false,
			"msg"=>"请给出有效参数"
			);
		return $return;
	}

	$is_admin=session("loginuser");  //获取当前登录人用户名，判断是否是admin
	if($is_admin=='admin'){  //表示当前登录人为admin，则我们给出所有权限（修改、删除）
		$is_edit='ok';
		$is_del='ok';
	}
	 //表示当前登录人不是admin，则我们根据当前登录角色和工作流步骤进行判断
		$is_need_role_row=M('workflowstep')->where("workflow_id={$wof_id} and role_id={$role}")->field("step_now")->select();  //当前工作流中是否有该角色
		$wof_ex_steps_row=M("workflow")->where("workflow_id={$wof_id}")->field("steps")->select();  //获取该工作流的总步骤
		
		if($is_need_role_row){  //表示当前工作流中有该角色
			//则接下来我们应该根据当前登录人所在步骤和该信息的审核步骤作比较，给出操作权限
			if($is_need_role_row[0]['step_now']>$wof_step_now){  //表示当前人所在步骤在当前该信息所在审核步骤的上面，更上层的审核人，有审核权限
				$is_edit='ok';
				$is_del='ok';
				//审核权限应该根据添加人是否提交审核以及下级管理员审核情况确定
				if($ex_status==0){  //表示还未提交审核
					$ex_status_txt='添加者未提交审核';
					$is_examine='error';
					$is_send_ex='error';
				}
				else if($ex_status==1){
					$ex_status_txt='下级管理员审核中';
					$is_examine='ok';
					$is_send_ex='error';
				}
				else if($ex_status==2){
					$ex_status_txt='下级管理员审核未通过';
					$is_examine='error';
					$is_send_ex='error';
				}
				else if($ex_status==3){
					$ex_status_txt='下级管理员审核通过，未送审';
					$is_examine='error';
					$is_send_ex='error';
				}

			}
			else if($is_need_role_row[0]['step_now']==$wof_step_now){  //表示当前人就应该是审核人				
				if($is_need_role_row[0]['step_now']==$wof_ex_steps_row[0]['steps']){ //表示当前人就应该是审核人，且是最高审核人
					$is_edit='ok';
					$is_del='ok';
					if($ex_status==0){
						$ex_status_txt='添加者未提交审核';
						$is_examine='error';
						$is_send_ex='error';
					}
					else if($ex_status==1){
						$ex_status_txt='待审核';
						$is_examine='ok';
						$is_send_ex='error';
					}
					else if($ex_status==2){
						$ex_status_txt='审核未通过';
						$is_examine='error';
						$is_send_ex='error';
					}
					else if($ex_status==3){
						$ex_status_txt='审核通过';
						$is_examine='error';
						$is_send_ex='error';  //最高审核人不用送审了
					}
				}
				elseif ($is_need_role_row[0]['step_now']==0) {  //表示当前登录人是最底层的审核人
					# code...
					if($is_login){  //当前信息是当前添加人添加的，则该人是最底层审核人也是添加人
						$is_edit='ok';
						$is_del='ok';
						
						if($ex_status==0){
							$ex_status_txt='未提交审核';
							$is_examine='error';
							$is_send_ex='ok';
						}
						else if($ex_status==1){
							$ex_status_txt='上级管理员审核中';
							$is_edit='error';
							$is_del='error';
							$is_examine='error';
							$is_send_ex='error';
						}
						else if($ex_status==2){
							$ex_status_txt='上级管理员审核未通过';
							$is_examine='error';
							$is_send_ex='ok';
						}
						else if($ex_status==3){
							$ex_status_txt='上级管理员审核通过';
							$is_edit='error';
							$is_del='error';
							$is_examine='error';
							$is_send_ex='error';
						}
					}
					else{   //当前人是最底层审核人，但不是该人添加的，则什么权限都没有 
						$is_edit='error';
						$is_del='error';
						$is_examine='error';
						$is_send_ex='error';
						if($ex_status==0){
							$ex_status_txt='';
						}
						else if($ex_status==1){
							$ex_status_txt='';
						}
						else if($ex_status==2){
							$ex_status_txt='';
						}
						else if($ex_status==3){
							$ex_status_txt='';
						}
					}

					
				}
				else{  //表示当前人是审核人，但不是最高审核人，也不是最低审核人
					if($is_login){  //判断该条信息是当前人添加的
						$is_edit='ok';
						$is_del='ok';
						if($ex_status==0){
							$ex_status_txt='添加者未提交审核';
							$is_examine='error';
							$is_send_ex='ok';
						}
						else if($ex_status==1){
							$ex_status_txt='待审核';
							$is_edit='error';
							$is_del='error';
							$is_examine='error';
							$is_send_ex='error';
						}
						else if($ex_status==2){
							$ex_status_txt='上级管理员审核未通过';
							$is_examine='error';
							$is_send_ex='ok';
						}
						else if($ex_status==3){
							$ex_status_txt='上级管理员审核通过';
							$is_edit='error';
							$is_del='error';
							$is_examine='error';
							$is_send_ex='error';  //当前人审核通过了，也得送审
						}
					}
					else{  //表示该人是中间审核人，但不是添加人
						$is_edit='ok';
						$is_del='ok';
						if($ex_status==0){
							$ex_status_txt='添加者未提交审核';
							$is_examine='error';
							$is_send_ex='error';
						}
						else if($ex_status==1){
							$ex_status_txt='待审核';
							$is_examine='ok';
							$is_send_ex='error';
						}
						else if($ex_status==2){
							$ex_status_txt='审核未通过，返回下级管理员';
							$is_examine='error';
							$is_send_ex='error';
						}
						else if($ex_status==3){
							$ex_status_txt='审核通过';
							$is_edit='error';
							$is_del='error';
							$is_examine='error';
							$is_send_ex='ok';  //当前人审核通过了，也得送审
						}
					}
					
				}


				
			}
			else if($is_need_role_row[0]['step_now']<$wof_step_now){  //表示当前人是该登录人的下级管理员
					$is_edit='error';
					$is_del='error';
					if($ex_status==0){
						$ex_status_txt='添加者未提交审核';
						$is_examine='error';
						$is_send_ex='error';
					}
					else if($ex_status==1){
						$ex_status_txt='上级管理员审核中';
						$is_examine='error';
						$is_send_ex='error';
					}
					else if($ex_status==2){
						$ex_status_txt='上级管理员审核未通过';
						$is_examine='error';
						$is_send_ex='error';
					}
					else if($ex_status==3){
						$ex_status_txt='审核通过';
						$is_examine='error';
						$is_send_ex='error';  //当前人审核通过了，也得送审
					}
			}


		}

		$return=array(
			"is_edit"=>$is_edit,
			"is_del"=>$is_del,
			"ex_status_txt"=>$ex_status_txt,
			"is_examine"=>$is_examine,
			"is_send_ex"=>$is_send_ex
			);
		return $return;



}

/*
页面数据打印
*/
function dd($data){
	echo "<pre>";
	var_dump($data);
	echo "</pre>";
	die();
}