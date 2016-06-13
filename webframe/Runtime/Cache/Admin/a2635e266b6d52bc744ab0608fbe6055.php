<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="keywords" content="jquery,ui,easy,easyui,web">
	<meta name="description" content="easyui help you build your web page easily!">
	<title>我的课程</title>

	<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/easyui/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/easyui/easyui.css">
	<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/easyui/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/layer/layer.js"></script>
	<style type="text/css">
		.left{
			width:120px;
			float:left;
		}
		.left table{
			background:#E0ECFF;
		}
		.left td{
			background:#eee;
		}
		.right{
			float:right;
			width:600px;
		}
		.right table{
			background:#E0ECFF;
			width:100%;
		}
		.right td{
			background:#fafafa;
			text-align:center;
			padding:2px;
		}
		.right td{
			background:#E0ECFF;
		}
		.right td.drop{
			background:#fafafa;
			width:100px;
		}
		.right td.over{
			background:#FBEC88;
		}
		.item{
			text-align:center;
			border:1px solid #499B33;
			background:#fafafa;
			width:100px;
		}
		.assigned{
			border:1px solid #BC2A4D;
		}
		
	</style>
	<script>
		$(function(){
			$('.left .item').draggable({
				revert:true,
				proxy:'clone'
			});
			$('.right td.drop').droppable({
				onDragEnter:function(){
					$(this).addClass('over');
				},
				onDragLeave:function(){
					$(this).removeClass('over');
				},
				onDrop:function(e,source){
					$(this).removeClass('over');
					if ($(source).hasClass('assigned')){
						$(this).append(source);
					} else {
						var c = $(source).clone().addClass('assigned');
						$(this).empty().append(c);
						c.draggable({
							revert:true
						});
					}
				}
			});
		});
	</script>
</head>
<body>
<div style="width:750px;">
	当前学生：<label style="font-weight:blod; font-size:18px; color:#2271ED;"><?php echo ($user_name); ?></label>
	<div class="right">
		<table>
			<tr>
				<td class="blank"></td>
				<td class="title">Monday</td>
				<td class="title">Tuesday</td>
				<td class="title">Wednesday</td>
				<td class="title">Thursday</td>
				<td class="title">Friday</td>
			</tr>
			<tr>
				<td class="time">第一讲</td>
				<td id="11" class="drop">
					
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='11'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
					
				</td>
				<td id="21" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='21'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="31" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='31'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="41" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='41'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="51" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='51'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
			</tr>
			<tr>
				<td class="time">第二讲</td>
				<td id="12" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='12'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="22" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='22'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="23" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='23'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="24" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='24'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="25" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='25'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
			</tr>
			<tr>
				<td class="time">休息</td>
				<td class="lunch" colspan="5">Lunch</td>
			</tr>
			<tr>
				<td class="time">第三讲</td>
				<td id="13" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='13'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="23" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='23'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="33" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='33'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="43" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='43'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="53" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='53'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
			</tr>
			<tr>
				<td class="time">第四讲</td>
				<td id="14" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='14'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="24" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='24'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="34" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='34'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="44" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='44'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="54" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='54'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
			</tr>
			<tr>
				<td class="time">休息</td>
				<td class="lunch" colspan="5">dinner</td>
			</tr>
			<tr>
				<td class="time">第五讲</td>
				<td id="15" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='15'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="25" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='25'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="35" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='35'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="45" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='45'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
				<td id="55" class="drop">
					<?php if(is_array($data)): foreach($data as $k=>$value): if(is_array($value['course_time']['course_table_location'])): foreach($value['course_time']['course_table_location'] as $k_location=>$val_location): if($val_location=='55'): echo ($data[$k]['course_info']['course_name']); ?><br>
								<?php echo ($data[$k]['course_time']['course_txt'][$k_location]); ?><br>
								<?php echo ($data[$k]['btn']); endif; endforeach; endif; endforeach; endif; ?>
				</td>
			</tr>
		</table>
	</div>


</div>
<!--退课操作-->
<script type="text/javascript">
	function tuike(course_id){
		if(course_id==""){
			layer.msg(
				"退课失败",
				{icon:2,time:1500}
				);
			return;
		}
		var actionUrl="/yisheng/webframe/index.php/Admin/Selectcourse/tuike/course_id/"+course_id;
		$.ajax({
			type:"post",
			url:actionUrl,
			success:function(data){
				if(data.status){
					layer.msg(
						data.msg,
						{icon:1,time:1500}
						);
					self.location.reload();
				}
				else{
					layer.msg(
						data.msg,
						{icon:2,time:1500}
						);
				}
			},
			error:function(){
				layer.msg(
					"网络通信错误",
					{icon:2,time:1500}
					);
			}
		});
	}

</script>


</body>
</html>