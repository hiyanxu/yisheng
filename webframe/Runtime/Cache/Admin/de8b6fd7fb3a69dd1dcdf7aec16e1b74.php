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
        <script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/layer/layer.js"></script>
	</head>
    <body>
        <div>
            <div class="modal-body">
                <form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">
                    <input type="hidden" value="<?php echo ($id); ?>" id="id" name="id">
                    当前登录人：<label style="color:#5CB85C; font-size:15px"><?php echo ($user_name); ?></label>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">登录账号：</label>
                        <div class="col-xs-8">
                            <input type="text" id="user_account" name="user_account" value="<?php echo ($data[0]['user_account']); ?>" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">密码：</label>
                        <div class="col-xs-8">
                            <input type="text" id="user_pwd" name="user_pwd" value="<?php echo ($data[0]['user_pwd']); ?>" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-8">
                            <button class="btn btn-default btn-small" style="margin-left:22%;" onclick="singalSave()">保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    
    <!--个人信息管理保存-->
    <script type="text/javascript">
    function singalSave(){
        var user_account=$("#user_account").val();
        var user_pwd=$("#user_pwd").val();
        var user_account_id=$("#id").val();

        if(user_account_id==""||user_pwd==""||user_account==""){
            layer.msg(
                "请将信息填写完整",
                {icon:2,time:1500}
                );
            return $return;
        }
        var actionUrl="/yisheng/webframe/index.php/Admin/User/singalSave";
        $.ajax({
            type:"post",
            data:{user_account:user_account,user_pwd:user_pwd,user_account_id:user_account_id},
            url:actionUrl,
            success:function(data){
                if(data.status){
                    layer.msg(
                        data.msg,
                        {icon:1,time:1500}
                        );
                }
                else{
                    layer.msg(
                        data.msg,
                        {icon:2,time:1500}
                    );
                }
            },
            error:function(){
                layer.msg(
                    "网络通信错误",
                    {icon:3,time:1500}
                );
            }
        });

    }
    </script>
    

    </body>
</html>