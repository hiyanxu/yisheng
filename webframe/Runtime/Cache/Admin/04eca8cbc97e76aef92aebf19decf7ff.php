<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<title>选课信息列表页</title>
		<meta charset="utf-8"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>/Public/Admin/BootStrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/bootstrap-table.min.css">
		
		<!--js文件引入-->
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/jquery-1.11.1.min.js"></script>		
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/bootstrap-table.js"></script>
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/layer/layer.js"></script>
	</head>
	<body>
		<div id="page-content">
			<div id="main-container container-fluid" style="margin-left:40px;">
				<div id="headshow" >
					<?php if($return['status'] == 0): ?><button type="button" class="btn btn-success btn-sm" onclick="selectOk();"><span class="glyphicon glyphicon-ok"></span> 确认选课</button>
						<button type="button" class="btn btn-info btn-sm" onclick="mycourse()"><span class="glyphicon glyphicon-th-list"></span> 我的课程</button>
					<?php else: ?>
						<button type="button" class="btn btn-success btn-sm" disabled="disabled" ><span class="glyphicon glyphicon-ok"></span> 确认选课</button>
						<button type="button" class="btn btn-info btn-sm" disabled="disabled"><span class="glyphicon glyphicon-th-list"></span> 我的课程</button><?php endif; ?>
				</div>
				<input type="hidden" name="status" id="status" value="<?php echo ($return['status']); ?>">
				<div id="divTable">
					<table id="table"></table>
				</div>
			</div>
		</div>
	
	<script type="text/javascript">
	var status=$("#status").val();
	$('#table').bootstrapTable({
					classes: "table table-hover", //表的样式'table-no-bordered'无边宽，也可以自己加样式
					method: 'get',
					url: "/yisheng/webframe/index.php/Admin/Selectcourse/ajaxIndex/status/"+status,
					//cache: false,
					height: $(window).height(),
					striped: true, //是否显示条纹的行。
					dataType: "json",
					//showHeader: false,// 去隐藏表头
					pagination: true,
					queryParamsType: "limit",
					singleSelect: false,
					pageSize: 15, //每页显示多少条
					//pageList: [10, 25, 50, 100],
					pageNumber: 1,
					sidePagination: "server", //设置为服务器端分页
					search: false, //不显示 搜索框
					toolbar: "#headshow", //显示在头部的条，值为ID 和class
					//searchAlign: 'right',  
					//detailView:true,  设置为 True 可以显示详细页面模式。
					showRefresh: true,
					showToggle: true,
					contentType: "application/x-www-form-urlencoded",
					showColumns: true, //不显示下拉框选择显示的字段（选择显示的列）
					minimumCountColumns: 1, //是少显示多少个字段
					clickToSelect: true,
					queryParams: queryParams, //所带参数
					responseHandler: responseHandler, //服务端返回的参数
					columns: [{
						checkbox: true
					}, {
						field: 'course_id',
						title: 'ID',
						sortable: true  //是否排序
					}, {
						field: 'course_name',
						title: '课程名称',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'course_num',
						title: '课程号',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'course_score',
						title: '学分',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'course_speaker',
						title: '主讲人',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'course_time_txt',
						title: '上课时间及地点',
						width:"200",
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: '',
						title: '操作',
						formatter: handle,
					}],
					onSearch: function (text) {  
						// alert("ddd");
					}
			});

		function handle(value, row, index) {
				console.log(row);
				var btnStu="";
				if(row.btnCheckStu=="ok"){
					btnStu='&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="checkStu('+row.course_id+')" title="查看选课学生"><i class="glyphicon glyphicon-user"></i>',
						'</a>';
				}
				

				return [
						btnStu
						
						
				].join('');
			}

		function responseHandler(res) {

				if (res.total) {
					return{
						rows: res.data,
						total: res.total
					}
				} else {
					return {
						rows: [],
						total: 0
					}
				}
			}
			
			//传参数
			function queryParams(params) {

				if (typeof (params.sort) == "undefined") {
					params.sort = 'course.course_id'; //默认排序字段
					params.order = 'desc';
				}
				if(typeof(params.course_workshop=="undefined")){
					params.course_workshop=$("#course_workshop").val();
				}
				params.UserName = 4;
				params.page = params.pageNumber;
				//alert(JSON.stringify(params));
				return params;
			}

		/*
		查看选课学生操作
		*/
		function checkStu(course_id){
			window.location.href="<?php echo (WWW_PUB); ?>/index.php/Admin/Course/getSelectStu/course_id/"+course_id;
		}

		/*
		确认选课操作
		包括单个选择和多项选择
		*/
		function selectOk(){
			layer.confirm('确定要选择这些课程吗？', {
					btn: ['确定', '取消'],
				}, function (index, layero) {
						var obj = $('#table').bootstrapTable('getSelections');
						var ids = '';
						$.each(obj, function (n, value) {
							ids += value.course_id + ',';
						});
					if(ids==""){
						layer.msg("请选择课程", {
											icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
											});
						return;
					}

					var actionUrl = "/yisheng/webframe/index.php/Admin/Selectcourse/selectOk";
						$.ajax({
						type: 'post',
								url: actionUrl,
								data: {ids:ids},
								cache: false,
								success: function (data) {
									if (data.status) {
											layer.msg(data.msg, {
											icon: 1,
													time: 1000,
													skin: 'layer-ext-moon'
											});
											$('#table').bootstrapTable('refresh', ''); //刷新表格
										} else {
											layer.msg(data.msg, {
											icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
											});
										}
								},
								error: function (data) {
								layer.alert(index);
								}
						});
				}, function (index) {

				});
		}

		/*
		我的课程操作
		*/
		function mycourse(){
			window.location.href="/yisheng/webframe/index.php/Admin/Selectcourse/mycourse";
		}
	

	</script>



	</body>
</html>