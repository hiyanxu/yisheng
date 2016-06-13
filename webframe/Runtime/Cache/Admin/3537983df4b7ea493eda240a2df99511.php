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
					&nbsp;审核状态：
					<select id="ex_status" name="ex_status">
						<option value="">全部</option>
						<option value="0">未提交审核</option>
						<option value="1">正在审核中</option>
						<option value="2">审核未通过</option>
						<option value="3">审核通过</option>
					</select>
					&nbsp;&nbsp;所属实验室：
					<select id="lec_workshop" name="lec_workshop">
						<option value="">全部</option>
						<?php if(is_array($workshop_rows)): foreach($workshop_rows as $k=>$workshop_row): ?><option value="<?php echo ($k); ?>"><?php echo ($workshop_row); ?></option><?php endforeach; endif; ?>
					</select>
					
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
					url: "/yisheng/webframe/index.php/Admin/Lecture/ajaxIndex",
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
						field: 'lec_id',
						title: 'ID',
						sortable: true  //是否排序
					}, {
						field: 'lec_name',
						title: '讲座名称',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'lec_time',
						title: '讲座时间',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'org_name_college',
						title: '所属学院',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'lec_speaker',
						title: '主讲人',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'org_name_workshop',
						title: '所属实验室',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'lec_place',
						title: '讲座地点',
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
				if(row.is_send_ex=="ok"){					
					btnSendEx='&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="sendEx('+row.lec_id+')" title="送审"><i class="glyphicon glyphicon-share"></i></a>';
				}
				if(row.is_examine=="ok"){
					btnEx='&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="examine('+row.lec_id+')" title="审核"><i class="glyphicon glyphicon-check"></i></a>';
				}
				if(row.is_edit=="ok"){
					btnEdit='<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="edit('+row.lec_id+')" title="修改"><i class="glyphicon glyphicon-pencil"></i></a>';
				}
				if(row.is_del=="ok"){
					btnDel='&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="delmore('+row.lec_id+')" title="删除"><i class="glyphicon glyphicon-trash"></i>',
						'</a>';
				}
				if(row.re_status=='<label style="color:#5CB85C;">已发布</label>'){
					btnUpload='&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="dataUpload('+row.lec_id+')" title="材料上传"><i class="glyphicon glyphicon-open"></i>',
						'</a>';
				}
				

				return [
						btnEdit+btnSendEx+btnDel+btnEx+btnUpload
						
						
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
					params.sort = 'lecture.lec_id'; //默认排序字段
					params.order = 'desc';
				}
				if(typeof(params.lec_workshop=="undefined")){
					params.lec_workshop=$("#lec_workshop").val();
				}
				if(typeof(params.lec_ex_status=="undefined")){
					params.lec_ex_status=$("#ex_status").val();
				}
				params.UserName = 4;
				params.page = params.pageNumber;
				//alert(JSON.stringify(params));
				return params;
			}



	/*
		讲座添加操作
		*/		
		function add(){
			var index = layer.open({
						type: 2,
						skin: 'demo-class',
						title: ['讲座添加', 'font-size:14px;color:black;'],
						move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['100%', '100%'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
						//closeBtn:2,
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['/yisheng/webframe/index.php/Admin/Lecture/add', 'yes'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var lec_name=obj.find("#lec_name").val();
						var lec_time=obj.find("#lec_time").val();
						var lec_speaker=obj.find("#lec_speaker").val();
						var lec_place=obj.find("#lec_place").val();
						if(lec_name==""||lec_time==""||lec_speaker==''||lec_place==""){
							layer.msg("请将信息填写完整", {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
							return;
						}
								$.ajax({
									type: 'post',
									url: '/yisheng/webframe/index.php/Admin/Lecture/insert',
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
		function sendEx(lec_id){
			$.ajax({
				type:"post",
				data:{lec_id:lec_id},
				url:"/yisheng/webframe/index.php/Admin/Lecture/sendEx",
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
		function examine(lec_id){
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
						content: ['/yisheng/webframe/index.php/Admin/Lecture/getExamine/lec_id/'+lec_id, 'yes'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var news_name=obj.find("#news_name").val();
						var news_time=obj.find("#news_time").val();
						if(news_name==""||news_time==""){
							layer.msg("请将信息填写完整", {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
							return;
						}
								$.ajax({
									type: 'post',
									url: '/yisheng/webframe/index.php/Admin/Lecture/examine',
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
		讲座修改操作
		*/		
		function edit(lec_id){
			var index = layer.open({
						type: 2,
						skin: 'demo-class',
						title: ['讲座修改', 'font-size:14px;color:black;'],
						move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['100%', '100%'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
						//closeBtn:2,
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['/yisheng/webframe/index.php/Admin/Lecture/edit/lec_id/'+lec_id, 'yes'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var lec_name=obj.find("#lec_name").val();
						var lec_time=obj.find("#lec_time").val();
						var lec_speaker=obj.find("#lec_speaker").val();
						var lec_place=obj.find("#lec_place").val();
						if(lec_name==""||lec_time==""||lec_speaker==''||lec_place==""){
							layer.msg("请将信息填写完整", {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
							return;
						}
								$.ajax({
									type: 'post',
									url: '/yisheng/webframe/index.php/Admin/Lecture/update',
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
		function delmore(lec_id){
			layer.confirm('确定要删除吗？', {
					btn: ['确定', '取消'],
				}, function (index, layero) {
					if (!lec_id) {
						var obj = $('#table').bootstrapTable('getSelections');
						var ids = '';
						$.each(obj, function (n, value) {
							ids += value.lec_id + ',';
						});
					} else {
						var ids = lec_id + ',';
					}

					var actionUrl = "/yisheng/webframe/index.php/Admin/Lecture/del";
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

		/*
		讲座总结材料上传操作
		*/
		function dataUpload(lec_id){
			var index = layer.open({
						type: 2,
						skin: 'demo-class',
						title: ['讲座修改', 'font-size:14px;color:black;'],
						move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['100%', '100%'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
						//closeBtn:2,
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['/yisheng/webframe/index.php/Admin/Lecture/dataUpload/lec_id/'+lec_id, 'yes'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var lec_thumb=obj.find("#lec_thumb").val();
						var lec_link=obj.find("#lec_link").val();
						var lec_ppt=obj.find("#lec_ppt").val();
						if(lec_thumb==""||lec_link==""||lec_ppt==''){
							layer.msg("请将信息填写完整", {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
							return;
						}
								$.ajax({
									type: 'post',
									url: '/yisheng/webframe/index.php/Admin/Lecture/dataUploadSave',
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

		$(function(){
			$("#lec_workshop").change(function(){
				$('#table').bootstrapTable('refresh', ''); //刷新表格
			});
			$("#ex_status").change(function(){
				$('#table').bootstrapTable('refresh', ''); //刷新表格
			});
		});




	</script>


	</body>
</html>