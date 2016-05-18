<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<title>信息列表页</title>
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
					<button type="button" class="btn btn-success btn-sm" onclick="add();"><span class="glyphicon glyphicon-plus"></span>添加信息</button>
					<button type="button" class="btn btn-danger btn-sm" onclick="delmore(null)"><span class="glyphicon glyphicon-trash"></span>批量删除</button>
					<?php if($flag == 0): ?><button type="button" class="btn btn-warning btn-sm" onclick="openSelect()"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;开放选课</button>
					<?php elseif($flag == 1): ?>
						<button type="button" class="btn btn-warning btn-sm" onclick="openSelect()"><span class="glyphicon glyphicon-eye-close"></span>&nbsp;关闭选课</button>
					<?php else: ?>
						<button type="button" class="btn btn-warning btn-sm" disabled="disabled"><span class="glyphicon glyphicon-eye-open"></span>&nbsp;开放选课</button><?php endif; ?>
					&nbsp;&nbsp;所属实验室：
					<select id="course_workshop" name="course_workshop">
						<option value="">全部</option>
						<?php if(is_array($workshop_rows)): foreach($workshop_rows as $k=>$workshop_row): ?><option value="<?php echo ($k); ?>"><?php echo ($workshop_row); ?></option><?php endforeach; endif; ?>
					</select>
					
				</div>
				<input type="hidden" id="flag" value="<?php echo ($flag); ?>">
				<div id="divTable">
					<table id="table"></table>
				</div>
			</div>
		</div>
	<script type="text/javascript">
	$('#table').bootstrapTable({
					classes: "table table-hover", //表的样式'table-no-bordered'无边宽，也可以自己加样式
					method: 'get',
					url: "/yisheng/webframe/index.php/Admin/Course/ajaxIndex",
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
						field: 'course_start',
						title: '开始时间',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'course_semester_txt',
						title: '开课学期',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'course_speaker',
						title: '主讲人',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'course_workshop_name',
						title: '所属实验室',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'course_hours',
						title: '学时',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'ex_status_txt',
						title: '审核状态',
						// visible: false, //刚开始是否显示此字段
						//sortable: true  //是否排序
					}, {
						field: 're_status',
						title: '发布状态',
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
				var btnEdit='';
				var btnSendEx="";
				var btnEx="";
				var btnDel="";
				var btnUpload="";
				var btnStu="";
				if(row.is_send_ex=="ok"){					
					btnSendEx='&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="sendEx('+row.course_id+')" title="送审"><i class="glyphicon glyphicon-share"></i></a>';
				}
				if(row.is_examine=="ok"){
					btnEx='&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="examine('+row.course_id+')" title="审核"><i class="glyphicon glyphicon-check"></i></a>';
				}
				if(row.is_edit=="ok"){
					btnEdit='<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="edit('+row.course_id+')" title="修改"><i class="glyphicon glyphicon-pencil"></i></a>';
				}
				if(row.is_del=="ok"){
					btnDel='&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="delmore('+row.course_id+')" title="删除"><i class="glyphicon glyphicon-trash"></i>',
						'</a>';
				}
				if(row.re_status=='<label style="color:#5CB85C;">已发布</label>'){
					btnUpload='&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="dataUpload('+row.course_id+')" title="信息补全"><i class="glyphicon glyphicon-open"></i>',
						'</a>';
				}
				if(row.is_stu=="ok"){
					btnStu='&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="checkStu('+row.course_id+')" title="查看选课学生"><i class="glyphicon glyphicon-user"></i>',
						'</a>';
				}
				

				return [
						btnEdit+btnSendEx+btnDel+btnEx+btnUpload+btnStu
						
						
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
		课程添加操作
		*/		
		function add(){
			var index = layer.open({
						type: 2,
						skin: 'demo-class',
						title: ['课程添加', 'font-size:14px;color:black;'],
						move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['100%', '100%'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
						//closeBtn:2,
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['/yisheng/webframe/index.php/Admin/Course/add', 'yes'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var course_name=obj.find("#course_name").val();
						var course_start=obj.find("#course_start").val();
						var course_end=obj.find("#course_end").val();
						var course_speaker=obj.find("#course_speaker").val();
						var course_hours=obj.find("#course_hours").val();
						if(course_name==""||course_start==""||course_end==''||course_speaker==""||course_hours==""){
							layer.msg("请将信息填写完整", {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
							return;
						}
						var partten=/^([0-9.]+)$/;
						if(!partten.test(course_hours)){
							layer.msg("学时只能是数字", {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
							return;
						}
								$.ajax({
									type: 'post',
									url: '/yisheng/webframe/index.php/Admin/Course/insert',
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
		送审操作
		*/
		function sendEx(course_id){
			$.ajax({
				type:"post",
				data:{course_id:course_id},
				url:"/yisheng/webframe/index.php/Admin/Course/sendEx",
				success:function(data){
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
				error:function(data){
					layer.alert("网络通信错误");
				}
			});
		}

		/*
		审核操作
		*/
		function examine(course_id){
			var index = layer.open({
						type: 2,
						skin: 'demo-class',
						title: ['信息审核', 'font-size:14px;;color:black;'],
						move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['100%', '100%'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
						//closeBtn:2,
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['/yisheng/webframe/index.php/Admin/Course/getExamine/course_id/'+course_id, 'yes'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
								$.ajax({
									type: 'post',
									url: '/yisheng/webframe/index.php/Admin/Course/examine',
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
		课程修改操作
		*/		
		function edit(course_id){
			var index = layer.open({
						type: 2,
						skin: 'demo-class',
						title: ['课程修改', 'font-size:14px;color:black;'],
						move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['100%', '100%'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
						//closeBtn:2,
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['/yisheng/webframe/index.php/Admin/Course/edit/course_id/'+course_id, 'yes'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var course_name=obj.find("#course_name").val();
						var course_start=obj.find("#course_start").val();
						var course_end=obj.find("#course_end").val();
						var course_speaker=obj.find("#course_speaker").val();
						var course_hours=obj.find("#course_hours").val();
						if(course_name==""||course_start==""||course_end==''||course_speaker==""||course_hours==""){
							layer.msg("请将信息填写完整", {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
							return;
						}
						var partten=/^([0-9.]+)$/;
						if(!partten.test(course_hours)){
							layer.msg("学时只能是数字", {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
							return;
						}
								$.ajax({
									type: 'post',
									url: '/yisheng/webframe/index.php/Admin/Course/update',
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
		数据删除的方法  包括批量删除
		*/
		function delmore(course_id){
			layer.confirm('确定要删除吗？', {
					btn: ['确定', '取消'],
				}, function (index, layero) {
					if (!course_id) {
						var obj = $('#table').bootstrapTable('getSelections');
						var ids = '';
						$.each(obj, function (n, value) {
							ids += value.course_id + ',';
						});
					} else {
						var ids = course_id + ',';
					}

					var actionUrl = "/yisheng/webframe/index.php/Admin/Course/del";
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
											icon: 3,
												time: 1000,
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

		$(function(){
			$("#course_workshop").change(function(){
				$('#table').bootstrapTable('refresh', ''); //刷新表格
			});
		});

		/*
		信息不全操作
		*/
		function dataUpload(course_id){
			var index = layer.open({
						type: 2,
						skin: 'demo-class',
						title: ['信息补全', 'font-size:14px;color:black;'],
						move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['500px', '400px'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
						//closeBtn:2,
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['/yisheng/webframe/index.php/Admin/Course/dataUpload/course_id/'+course_id, 'yes'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var course_num=obj.find("#course_num").val();
						var course_score=obj.find("#course_score").val();
						if(course_num==""||course_score==""){
							layer.msg("请将信息填写完整", {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
							return;
						}
						var partten=/^([0-9.]+)$/;
						if(!partten.test(course_score)){
							layer.msg("学时只能是数字", {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
							return;
						}
								$.ajax({
									type: 'post',
									url: '/yisheng/webframe/index.php/Admin/Course/dataUploadSave',
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
		开放选课设置
		*/
		function openSelect(){
			var flag=$("#flag").val();
			if(flag==3){
				layer.msg("当前学期没有可用课程", {
											icon: 2,
												time: 1500,
												skin: 'layer-ext-moon'
											});
				return;
			}
			$.ajax({
				type:"post",
				data:{flag:flag},
				url:"/yisheng/webframe/index.php/Admin/Course/openSelect",
				success:function(data){
					if (data.status) {
											layer.msg(data.msg, {
											icon: 1,
													time: 1000,
													skin: 'layer-ext-moon'
											});
											$('#table').bootstrapTable('refresh', ''); //刷新表格
											self.location.reload();
										} else {
											layer.msg(data.msg, {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
										}
				},
				error:function(data){
					layer.alert("网络通信错误");
				}
			});
		}

		/*
		查看选课学生操作
		*/
		function checkStu(course_id){
			window.location.href="/yisheng/webframe/index.php/Admin/Course/getSelectStu/course_id/"+course_id;
		}





	</script>


	</body>
</html>