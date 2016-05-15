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
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/treeview/js/fuelux.ntree.min.js"></script>
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/treeview/js/ace-elements.min.js"></script>
		
		<link type="text/css" rel="stylesheet" href="<?php echo (WWW_PUB); ?>Public/Admin/treeview/css/bootstrap-treeview.css"/>	
		<link type="text/css" rel="stylesheet" href="<?php echo (WWW_PUB); ?>Public/Admin/treeview/css/ace-fonts.css"/>	
		<link type="text/css" rel="stylesheet" href="<?php echo (WWW_PUB); ?>Public/Admin/treeview/css/ace-skins.min.css"/>	
		<link type="text/css" rel="stylesheet" href="<?php echo (WWW_PUB); ?>Public/Admin/treeview/css/ace.min.css"/>	
		<link type="text/css" rel="stylesheet" href="<?php echo (WWW_PUB); ?>Public/Admin/treeview/css/animate.min.css"/>
		<link rel="stylesheet" href="<?php echo (WWW_PUB); ?>Public/Admin/treeview/css/font-awesome.mine0a5.css?v=4.3.0"/>	
	</head>
	<body>
		<div style="margin-top:30px; margin-left:20px;">
				当前角色：<label style="color:#5CB85C; font-size:18px;"><?php echo ($row[0]['role_name']); ?></label>
			</div>
		<form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">			
			<input type="hidden" name="role_id" id="role_id" value="<?php echo ($role_id); ?>">
			<div id="treeview" class="tree" style="margin-left:30px; margin-top:20px;"></div>

			<button class="btn btn-success btn-sm" onclick="menuDisInsert();" style="margin-left:20px; margin-top:30px;">保存</button>
		</form>

		<!--自定义js-->
		<!--<script type="text/javascript">
		load();
		
		function load(){
			var result="";
			var actionUrl="/yisheng/webframe/index.php/Admin/Role/getMenuTree";
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
		</script>-->

		<!--自定义js-->
		<script type="text/javascript">
		//树形数据加载
		tree_data();

		function tree_data(){
			var role_id=$("#role_id").val();
			jQuery(function($) {
				// ------------------------------------------------------  组织树 的 json数据 
                    var DataSourceTree = function(options) {
                        this._data = options.data;
                        this._delay = options.delay;
                    }

                    DataSourceTree.prototype.data = function(options, callback) {
                        var self = this;
                        var $data = null;

                        if (! ("name" in options) && !("type" in options)) {
                            $data = this._data;
                            callback({
                                data: $data
                            });
                            return;
                        } else if ("type" in options && options.type == "folder") {
                            if ("additionalParameters" in options && "children" in options.additionalParameters) $data = options.additionalParameters.children;
                            else $data = {} //no data
                        }

                        // 如果数据不为空，则直接放入数据
                        if ($data != null) callback({
                            data: $data
                        });
                    };
                    var treeDataSource = '';
                    $.ajax({
                    	url: '/yisheng/webframe/index.php/Admin/Role/getMenuTree',
                    	type: 'GET',
                        dataType: 'json',
                        data: {role_id:role_id},
                        success:function(response){
                        	if (response.status) {
                                treeDataSource = new DataSourceTree({
                                    data: response.data
                                });
                            } else {
                                layer.alert(response.msg);
                            }

                            tree = $('#treeview').ace_tree({
                                dataSource: treeDataSource,
                                multiSelect: true,
                                // 允许多选
                                allItems: true,
                                // 是否生成所有节点	默认为 false 不生成全部节点
                                loadingHTML: '',
                                // 等待读取图标 <div class="tree-loading"><i class="icon-refresh icon-spin blue"></i></div>
                                'open-icon': 'icon-minus',
                                'close-icon': 'icon-plus',
                                'selectable': true,
                                // 是否允许选择
                                'selected-icon': 'icon-ok',
                                'unselected-icon': 'icon-remove'
                            });
                        },
                        error:function(response){
                        	layer.alert("网络通信错误");
                        }

                    });

			});
		}

		var tree;
		var menuString="";
		/*
		验证是否选中的方法
		*/
		function check(obj,flag){
			menuString="";
			if(flag){
				$.each(tree.data('tree').selectedItems(),function(k,v){
					menuString+=v.parentid+'|';
				});
			}
			return true;
		}

		function menuDisInsert(){
			var obj=$("#wt-forms");
			if(check(obj,true)){
				$.ajax({
					type:"post",
					url:"/yisheng/webframe/index.php/Admin/Role/menuDisInsert",
					data:obj.serialize() + '&menuString=' + menuString,
					cache:false,
					success:function(data){
						 if (data.status) {
											layer.msg(data.msg, {
											icon: 1,
													time: 1500,
													skin: 'layer-ext-moon'
											});
											window.location.href="/yisheng/webframe/index.php/Admin/Role/index";
										} else {
											layer.msg(data.msg, {
											icon: 3,
												time: 1000,
												skin: 'layer-ext-moon'
											});
										}

					},
					error:function(data){

					}
			});
		}
	}

		</script>


	</body>
</html>