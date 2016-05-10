<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<title>管理员列表页</title>
		<meta charset="utf-8"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>/Public/Admin/BootStrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/bootstrap-table.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/font-awesome.mine0a5.css">
		
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/animate.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/jquery-ui-1.10.3.full.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/bootstrap-responsive.min.css">

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
					<button type="button" class="btn btn-success btn-sm" onclick="add();"><span class="glyphicon glyphicon-plus"></span>添加管理员</button>
					
				</div>
				<div id="divTable">
					<table id="table"></table>
				</div>
			</div>
		</div>

		<script type="text/javascript">
		$('#table').bootstrapTable({
					classes: "table table-hover", //表的样式'table-no-bordered'无边宽，也可以自己加样式
					method: 'get',
					url: "/webframework/webframe/index.php/Admin/User/ajaxIndex",
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
					search: true, //不显示 搜索框
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
						field: 'user_id',
						title: 'ID',
						width: 100, //宽度
						align: 'center', //
						valign: 'middle',
						sortable: true  //是否排序
					}, {
						field: 'user_account',
						title: '登录账号',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'user_name',
						title: '用户姓名',
						// visible: false, //刚开始是否显示此字段
						//sortable: true  //是否排序
					}, {
						field: 'isenable',
						title: '状态',
						// visible: false, //刚开始是否显示此字段
						//sortable: true  //是否排序
					}, {
						field: 'role_name',
						title: '角色'
					}, {
						field: 'org_name',
						title: '所属机构'
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
				return [
						'</a>',
						'<a class="edit ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="edit(' + row.user_id + ','+row.user_account_id+')" title="编辑">',
						'<i class="glyphicon glyphicon-pencil"></i>',
						'</a>',
						'&nbsp;&nbsp;',
						'<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="delmore(' + row.user_id + ','+row.user_account_id+')" title="删除">',
						'<i class="glyphicon glyphicon-trash"></i>',
						'</a>',
						'&nbsp;&nbsp;',
						'<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="editPwd('+row.user_account_id+')" title="修改密码">',
						'<i class="glyphicon glyphicon-cog"></i>',
						'</a>'
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
					params.sort = 'user.user_id'; //默认排序字段
					params.order = 'desc';
				}
				if(typeof(params.isAdmin)=="undefined"){
					params.isAdmin=0;
				}

				params.UserName = 4;
				params.page = params.pageNumber;
				//alert(JSON.stringify(params));
				return params;
			}



		/*
		管理员添加方法
		*/
		function add(){
			//var linkUrl=U("Menu/add");
			var index = layer.open({
						type: 2,
						skin: 'demo-class',
						title: ['添加管理员', 'font-size:14px;background:#2b9af6;color:#fff'],
						move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['100%', '100%'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
						//closeBtn:2,
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['/webframework/webframe/index.php/Admin/User/add/isAdmin/0', 'yes'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var re_phone=/^1\d{10}$/;  //正则表达式，以1开头，匹配10个数字
						var user_phone=obj.find("#user_phone").val();
						if(!re_phone.test(user_phone)){
							layer.msg("手机格式错误", {
											icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
											});
							return;
						}
						var re_email=/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
						var user_email=obj.find("#user_email").val();
						if(!re_email.test(user_email)){
							layer.msg("邮箱格式错误", {
											icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
											});
							return;
						}
						var user_account=obj.find("#user_account").val();
						var user_pwd=obj.find("#user_pwd").val();
						var user_name=obj.find("#user_name").val();
						if((user_account=="")||(user_pwd=="")||(user_name=="")){
							layer.msg("信息不能为空", {
											icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
											});
							return;
						}
						if(user_name=="admin"){
							layer.msg("管理员不可为admin",{
								icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
							});
							return;
						}
								$.ajax({
									type: 'post',
									url: '/webframework/webframe/index.php/Admin/User/insert',
									data: obj.serialize(),
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
												time: 1000,
												skin: 'layer-ext-moon'
											});
										}
									},
									error: function (data) {

									}
								});
								//console.log(obj.serialize());
								layer.close(index); //一般设定yes回调，必须进行手工关闭

						}, cancel: function (index) {

						}
				});
		}

		/*
		管理员修改方法
		*/
		function edit(user_id,user_account_id){
			//var linkUrl=U("Menu/add");
			var index = layer.open({
						type: 2,
						skin: 'demo-class',
						title: ['修改管理员', 'font-size:14px;background:#2b9af6;color:#fff'],
						move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['100%', '100%'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
						//closeBtn:2,
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['/webframework/webframe/index.php/Admin/User/edit/isAdmin/0/user_id/'+user_id+'/user_account_id/'+user_account_id, 'yes'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var re_phone=/^1\d{10}$/;  //正则表达式，以1开头，匹配10个数字
						var user_phone=obj.find("#user_phone").val();
						if(!re_phone.test(user_phone)){
							layer.msg("手机格式错误", {
											icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
											});
							return;
						}
						var re_email=/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
						var user_email=obj.find("#user_email").val();
						if(!re_email.test(user_email)){
							layer.msg("邮箱格式错误", {
											icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
											});
							return;
						}
						var user_account=obj.find("#user_account").val();
						var user_pwd=obj.find("#user_pwd").val();
						var user_name=obj.find("#user_name").val();
						if((user_account=="")||(user_pwd=="")||(user_name=="")){
							layer.msg("信息不能为空", {
											icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
											});
							return;
						}
						if(user_name=="admin"){
							layer.msg("管理员不可为admin",{
								icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
							});
							return;
						}
								$.ajax({
									type: 'post',
									url: '/webframework/webframe/index.php/Admin/User/update',
									data: obj.serialize(),
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
												time: 1000,
												skin: 'layer-ext-moon'
											});
										}
									},
									error: function (data) {

									}
								});
								//console.log(obj.serialize());
								layer.close(index); //一般设定yes回调，必须进行手工关闭

						}, cancel: function (index) {

						}
				});
		}

		/*
		管理员删除方法
		*/
		function delmore(user_id,user_account_id){
			layer.confirm('确定要删除吗？', {
					btn: ['确定', '取消'],
				}, function (index, layero) {					
					var actionUrl = "/webframework/webframe/index.php/Admin/User/del";
						$.ajax({
						type: 'post',
								url: actionUrl,
								data: {user_id:user_id,user_account_id:user_account_id},
								cache: false,
								success: function (data) {
								// 验证不通过
								layer.msg(data.msg, {icon: 1, time: 1000});
										if (data.status = true) {
								$('#table').bootstrapTable('refresh', ''); //刷新表格
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
		修改密码操作
		*/
		function editPwd(user_account_id){
			var index = layer.open({
						type: 2,
						skin: 'demo-class',
						title: ['修改密码', 'font-size:14px;background:#2b9af6;color:#fff'],
						move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['500px', '430px'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
						//closeBtn:2,
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['/webframework/webframe/index.php/Admin/User/editPwd/user_account_id/'+user_account_id, 'no'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var user_pwd=obj.find("#user_pwd").val();
						if(user_pwd==""){
							layer.msg("新密码不能为空", {
											icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
											});
							return;
						}
								$.ajax({
									type: 'post',
									url: '/webframework/webframe/index.php/Admin/User/editPwdSave',
									data: obj.serialize(),
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
											icon: 3,
												time: 1000,
												skin: 'layer-ext-moon'
											});
										}
									},
									error: function (data) {

									}
								});
								//console.log(obj.serialize());
								layer.close(index); //一般设定yes回调，必须进行手工关闭

						}, cancel: function (index) {

						}
				});
		}

		</script>


	</body>
</html>