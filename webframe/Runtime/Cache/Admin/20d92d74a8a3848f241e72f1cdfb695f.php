<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>Laravel</title>
        <link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>/Public/Admin/BootStrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/bootstrap-table.min.css">
		<!--js文件引入-->
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/jquery-1.11.1.min.js"></script>		
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div>
			<div class="modal-body">
				<form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">
					<input type="hidden" value="0" name="parentid">
                    <div class="form-group">
                        <label class="col-xs-3 control-label">菜单名称：</label>
                        <div class="col-xs-8">
                            <input type="text" name="menu_name" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">链接控制器：</label>
                        <div class="col-xs-8">
                            <input type="text" name="controller" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">链接方法：</label>
                        <div class="col-xs-8">
                            <input type="text" name="function" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">是否显示：</label>
                        <div class="col-xs-8">
                            <input type="radio" name="ishidden" value="0">显示&nbsp;&nbsp;
                            <input type="radio" name="ishidden" value="1">禁用
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">排序：</label>
                        <div class="col-xs-8">
                            <input type="text" name="sort" class="form-control input-sm"> 
                        </div>
                    </div>
                </form>
			</div>
		</div>		
	</body>
</html>