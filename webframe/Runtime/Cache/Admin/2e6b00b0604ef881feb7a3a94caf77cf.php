<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>管理员修改</title>
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
                    <div class="form-group">
                        <label class="col-xs-3 control-label">权限名称：</label>
                        <div class="col-xs-8">
                            <input type="text" id="access_name" name="access_name" placeholder="请输入权限名称" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">对应控制器：</label>
                        <div class="col-xs-8">
                            <input type="text" id="access_con" name="access_con" placeholder="请输入对应控制器名（不包括Controller）" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">对应方法：</label>
                        <div class="col-xs-8">
                            <input type="text" id="access_fun" name="access_fun" placeholder="请输入控制器中对应方法" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">备注信息：</label>
                        <div class="col-xs-8">
                            <textarea cols="70" name="remark" placeholder="请输入备注信息" rows="5"></textarea>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </body>
</html>