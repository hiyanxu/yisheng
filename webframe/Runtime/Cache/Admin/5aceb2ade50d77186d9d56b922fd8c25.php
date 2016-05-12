<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<title>角色列表页</title>
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
				
				<div id="divTable">
					<table id="table"></table>
				</div>
			</div>
		</div>

	<script type="text/javascript">
	$('#table').bootstrapTable({
					classes: "table table-hover", //表的样式'table-no-bordered'无边宽，也可以自己加样式
					method: 'get',
					url: "/yisheng/webframe/index.php/Admin/Loginlog/ajaxIndex",
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
						field: 'login_log_id',
						title: 'ID',
						align: 'center', //
						valign: 'middle',
						sortable: true  //是否排序
					}, {
						field: 'login_account',
						title: '登录账号',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'login_ip',
						title: 'ip地址',
						// visible: false, //刚开始是否显示此字段
						//sortable: true  //是否排序
					}, {
						field: 'login_time',
						title: '操作时间',
						// visible: false, //刚开始是否显示此字段
						//sortable: true  //是否排序
					}, {
						field: 'login_operation_text',
						title: '操作类型',
						// visible: false, //刚开始是否显示此字段
						//sortable: true  //是否排序
					}, {
						field: 'login_status_text',
						title: '登录状态',
						// visible: false, //刚开始是否显示此字段
						//sortable: true  //是否排序
					}, {
						field: 'is_remember_text',
						title: '是否记住我',
						// visible: false, //刚开始是否显示此字段
						//sortable: true  //是否排序
					}],
					onSearch: function (text) {  
						// alert("ddd");
					}
			});

		
			
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
					params.sort = 'login_log_id'; //默认排序字段
					params.order = 'desc';
				}

				params.UserName = 4;
				params.page = params.pageNumber;
				//alert(JSON.stringify(params));
				return params;
			}
		</script>


	</body>