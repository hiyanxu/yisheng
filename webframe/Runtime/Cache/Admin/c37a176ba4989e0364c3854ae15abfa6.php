<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<title>分类管理</title>
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>/Public/Admin/BootStrap/css/bootstrap.min.css">
			<!--js文件引入-->
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/jquery-1.11.1.min.js"></script>		
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/bootstrap-table.js"></script>
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/layer/layer.js"></script>

		<!--树形插件引入-->
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/treeview/js/bootstrap-treeview.js"></script>
		<link type="text/css" rel="stylesheet" href="<?php echo (WWW_PUB); ?>Public/Admin/treeview/css/bootstrap-treeview.css"/>	
	</head>
	<body>
		<div class="page-content">
			<div class="main-container container-fluid">
				<div id="headshow">
					<button type="button" style="margin-left:30px;" class="btn btn-success btn-sm" onclick="add();"><i class="glyphicon glyphicon-plus"></i>添加分类</button>
					
				</div>
			</div>
			<div id="fenLeiTree" class="test" style="margin-left:30px;"></div>
		</div>

		<!--自定义js-->
		<script type="text/javascript">
		load();

		/*加载信息的方法*/
		function load(){
			var result="";
			var actionUrl="/yisheng/webframe/index.php/Admin/Cate/getCateTree";
			$.ajax({
				type:"post",
				url:actionUrl,
				async: false,  //设置ajax同步执行，异步执行时第一时间获取不到数据
				success:function(jsonReturn){
					result=jsonReturn;
				},
				error:function(data){
					layer.msg("信息加载失败，请重试！",{icon:2,time:1500});
				}
			});
			$("#fenLeiTree").treeview({
				color:"#428bca",
				showBorder:!1,
				data:result
			});

		}

		/*
		添加分类的方法
		最顶级分类的添加
		*/
		function add(){
			var index=layer.open({
					type:2,
					skin:"demo-class",
					title:["分类添加","font-size:14px;background:#2b9af6;color:#fff"],
					move:".layui-layer-title",
					area:["500px","400px"],
					shade:[0.5,"#000"],
					shadeClose:true,
					shift:0,
					content:["/yisheng/webframe/index.php/Admin/Cate/add","no"],
					btn:['确定','取消'],
					yes:function(index){
						var obj=layer.getChildFrame("#wt-forms",index);
						var name=obj.find("#cate_name").val();
						if(name==""){
							layer.msg("分类名称不能为空",{icon:2,time:1500,skin:"layer-ext-moon"});
							return;
						}
						var actionUrl="/yisheng/webframe/index.php/Admin/Cate/insert";
						$.ajax({
							type:"post",
							url:actionUrl,
							data:obj.serialize(),
							cache:false,
							success:function(data){
								if(data.status){
									layer.msg(data.msg,{
										icon:1,
										time:1500,
										skin:'layer-ext-moon'
									});
									layer.close(index);
									load();
								}
								else{
									layer.msg(data.msg,{
										icon:3,
										time:1500,
										skin:'layer-ext-moon'
									});
								}
							},
							error:function(data){

							}
						});
					}

				});
		}

		/*
		添加子类的方法
		*/
		function addChild(fid){
			var index=layer.open({
				type:2,
				skin:"demo-class",
				title:["子类添加","font-size:14px;background:#2b9af6;color:#fff"],
				move:".layui-layer-title",
				area:["500px","400px"],
				shade:[0.5,"#000"],
				shadeClose:true,
				shift:0,
				content:['/yisheng/webframe/index.php/Admin/Cate/addChild/fid/'+fid,'no'],
				btn:['确定','取消'],
				yes:function(index){
					var obj=layer.getChildFrame("#wt-forms",index);
						var name=obj.find("#cate_name").val()
						if(name==""){
							layer.msg("分类名称不能为空",{icon:2,time:1500,skin:"layer-ext-moon"});
							return;
						}
						var actionUrl="/yisheng/webframe/index.php/Admin/Cate/insert";
						$.ajax({
							type:"post",
							url:actionUrl,
							data:obj.serialize(),
							cache:false,
							success:function(data){
								if(data.status){
									layer.msg(data.msg,{
										icon:1,
										time:1500,
										skin:'layer-ext-moon'
									});
									layer.close(index);
									load();
								}
								else{
									layer.msg(data.msg,{
										icon:3,
										time:1500,
										skin:'layer-ext-moon'
									});
								}
							},
							error:function(data){

							}
						});
				}
			});
		}

		/*
		修改操作
		*/
		function editChild(cate_id){
			var index=layer.open({
				type:2,
				skin:"demo-class",
				title:["分类修改","font-size:14px;background:#2b9af6;color:#fff"],
				move:".layui-layer-title",
				area:["500px","400px"],
				shade:[0.5,"#000"],
				shadeClose:true,
				shift:0,
				content:['/yisheng/webframe/index.php/Admin/Cate/edit/cate_id/'+cate_id],
				btn:['确定','取消'],
				yes:function(index){
					var obj=layer.getChildFrame("#wt-forms",index);
						var name=obj.find("#cate_name").val()
						if(name==""){
							layer.msg("分类名称不能为空",{icon:2,time:1500,skin:"layer-ext-moon"});
							return;
						}
						var actionUrl="/yisheng/webframe/index.php/Admin/Cate/update";
						$.ajax({
							type:"post",
							url:actionUrl,
							data:obj.serialize(),
							cache:false,
							success:function(data){
								if(data.status){
									layer.msg(data.msg,{
										icon:1,
										time:1500,
										skin:'layer-ext-moon'
									});
									layer.close(index);
									load();
								}
								else{
									layer.msg(data.msg,{
										icon:3,
										time:1500,
										skin:'layer-ext-moon'
									});
								}
							},
							error:function(data){

							}
						});
				}
			});
		}

		/*
		删除操作
		*/
		function delChild(cate_id){
			layer.confirm("确定要删除吗?",{btn:["确定","取消"]},function(index,layero){
					var actionUrl="/yisheng/webframe/index.php/Admin/Cate/del";
					$.ajax({
						type:"post",
						url:actionUrl,
						data:{"cate_id":cate_id},
						cache:false,
						success:function(data){
							
							if(data.status){
								layer.msg(data.msg,{icon:1,time:1000});
								layer.close(index);
								load();
							}
							else{
								layer.msg(data.msg,{icon:2,time:1000});
								layer.close(index);
								load();
							}
						},
						error:function(data){
							layer.alert(index);
						}
					});
			});
		}


		</script>

	</body>
</html>