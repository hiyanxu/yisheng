<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<title>学院选取列表页</title>
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
					<button type="button" class="btn btn-success btn-sm" onclick="add();"><span class="glyphicon glyphicon-plus"></span>学院选取</button>
					<button type="button" class="btn btn-danger btn-sm" onclick="delmore(null)"><span class="glyphicon glyphicon-trash"></span>批量删除</button>
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
					url: "/yisheng/webframe/index.php/Admin/LecCol/ajaxIndex",
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
						field: 'lec_org_id',
						title: 'ID',
						width: 100, //宽度
						align: 'center', //
						valign: 'middle',
						sortable: true  //是否排序
					}, {
						field: 'org_name',
						title: '工作流名称',
						// visible: false, //刚开始是否显示此字段
						//sortable: false  //是否排序
					}, {
						field: 'isenableText',
						title: '当前状态',
						// visible: false, //刚开始是否显示此字段
						//sortable: true  //是否排序
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
				var btnIsEnable='';
				if(row.isenable=="0"){
					btnIsEnable='&nbsp;&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="setNewsWorkflow('+row.lec_org_id+',1)" title="设为禁用"><i class="glyphicon glyphicon-remove-circle"></i></a>';
				}
				else{
					btnIsEnable='&nbsp;&nbsp;<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="setNewsWorkflow('+row.lec_org_id+',0)" title="设为启用"><i class="glyphicon glyphicon-off"></i></a>';
				}
				return [
						'<a class="remove ml10 btn btn-xs btn-outline btn-default" href="javascript:void(0)" onclick="delmore('+row.lec_org_id+')" title="删除">',
						'<i class="glyphicon glyphicon-trash"></i>',
						'</a>'+btnIsEnable
						
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
					params.sort = 'college_ano.lec_org_id'; //默认排序字段
					params.order = 'desc';
				}

				params.UserName = 4;
				params.page = params.pageNumber;
				//alert(JSON.stringify(params));
				return params;
			}




	/*
		学院选取操作
		*/		
		function add(){
			var index = layer.open({
						type: 2,
						skin: 'demo-class',
						title: ['学院选择', 'font-size:14px;color:black;'],
						move: '.layui-layer-title', //触发拖动的元素false 禁止拖拽，.layui-layer-title 可以拖拽
						area: ['400px', '300px'], //设置弹出框的宽高
						shade: [0.5, '#000'], //配置遮罩层颜色和透明度
						shadeClose: true, //是否允许点击遮罩层关闭弹窗 true /false
						//closeBtn:2,
						// time:1000,  设置自动关闭窗口时间 1秒=1000；
						shift: 0, //打开效果：0-6 。0放大，1从上到下，2下到上，3左到右放大，4翻滚效果；5渐变；6抖窗口
						content: ['/yisheng/webframe/index.php/Admin/LecCol/add', 'no'],
						btn: ['确定', '取消']
						, yes: function (index) {

						var obj = layer.getChildFrame('#wt-forms', index); //获取form的值
						var org_id=obj.find("#org_id").val();
						if(org_id==""){
							layer.msg("请选择具体组织机构", {
											icon: 2,
												time: 1000,
												skin: 'layer-ext-moon'
											});
							return;
						}
								$.ajax({
									type: 'post',
									url: '/yisheng/webframe/index.php/Admin/LecCol/insert',
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
		设置禁用或启用的方法
		*/
		function setNewsWorkflow(id,flag){
			$.ajax({
				type:"post",
				data:{id:id,flag:flag},
				url:"/yisheng/webframe/index.php/Admin/LecCol/setLecColIsEnable",
				success:function(data){
					if (data.status) {
											layer.msg("设置成功", {
											icon: 1,
													time: 1000,
													skin: 'layer-ext-moon'
											});
											$('#table').bootstrapTable('refresh', ''); //刷新表格
										} else {
											layer.msg("设置失败", {
											icon: 3,
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
		数据删除的方法  包括批量删除
		*/
		function delmore(lec_org_id){
			layer.confirm('确定要删除吗？', {
					btn: ['确定', '取消'],
				}, function (index, layero) {
					if (!lec_org_id) {
						var obj = $('#table').bootstrapTable('getSelections');
						var ids = '';
						$.each(obj, function (n, value) {
							ids += value.lec_org_id + ',';
						});
					} else {
						var ids = lec_org_id + ',';
					}

					var actionUrl = "/yisheng/webframe/index.php/Admin/LecCol/delAjax";
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




	</script>


	</body>
</html>