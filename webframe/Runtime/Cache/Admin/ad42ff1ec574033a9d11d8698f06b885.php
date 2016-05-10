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
                        <label class="col-xs-3 control-label">登录账号：</label>
                        <div class="col-xs-8">
                            <input type="text" id="user_account" name="user_account" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">密码：</label>
                        <div class="col-xs-8">
                            <input type="password" id="user_pwd" name="user_pwd" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">对应用户：</label>
                        <div class="col-xs-8">
                            <select name="user_id">
                                <?php if(is_array($user_rows)): foreach($user_rows as $key=>$user_row): ?><option value="<?php echo ($user_row['user_id']); ?>"><?php echo ($user_row['user_name']); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">对应角色：</label>
                        <div class="col-xs-8">
                            <select name="role_id">
                                <?php if(is_array($role_rows)): foreach($role_rows as $key=>$role_row): ?><option value="<?php echo ($role_row['role_id']); ?>"><?php echo ($role_row['role_name']); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">是否启用：</label>
                        <div class="col-xs-8">
                            <input type="radio" name="isenable" value="0" checked="checked">启用
                            <input type="radio" name="isenable" value="1">禁用
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">是否为管理员：</label>
                        <div class="col-xs-8">
                            <input type="radio" name="is_admin" value="0" checked="checked">管理员
                            <input type="radio" name="is_admin" value="1">普通用户
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </body>
</html>