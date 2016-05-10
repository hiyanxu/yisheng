<?php if (!defined('THINK_PATH')) exit();?><!--权限分配页面-->
<!DOCTYPE html>
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
    	<div style="margin-top:10px; margin-left:20px;;" class="form control">
    		当前角色：<label class="form control" style="color:#5CB85C; font-size:16px;"><?php echo ($role_row[0]['role_name']); ?></label>
    	</div>
    	<form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">
    		<input type="hidden" name="role_id" value="<?php echo ($role_id); ?>">
	    	<div class="table-responsive" style="margin-top:20px; margin-left:20px; width:80%;">
	    		<table class="table-bordered table-hover table">
	    			<thead style="height:30px; ">
	    				<tr>
	    					<th style="width:5%; text-align:center;">选择</th>
	    					<th style="width:5%; text-align:center;">ID</th>
	    					<th style="text-align:center;">权限名称</th>
	    					<th>备注信息</th>
	    				</tr>
	    			</thead>
	    			<tbody>
	    				<?php if(is_array($access_rows)): foreach($access_rows as $key=>$access_row): ?><tr>
			    					<th style="width:5%; text-align:center; color:#333333; font-size:14px; font-weight:normal;"><input type="checkbox" <?php if($access_row['isselect'] == 'ok'): ?>checked="checked"<?php endif; ?> name="<?php echo ($access_row['access_id']); ?>"></th>
			    					
			    					<th style="width:5%; text-align:center; color:#333333; font-size:14px; font-weight:normal;"><?php echo ($access_row["access_id"]); ?></th>
			    					
			    					<th style=" text-align:center; color:#333333; font-size:14px; font-weight:normal;"><?php echo ($access_row["access_name"]); ?></th>
			    					
			    					<th style=" color:#333333; font-size:14px; font-weight:normal;"><?php echo ($access_row["remark"]); ?></th>
			    				</tr><?php endforeach; endif; ?>
	    			</tbody>
	    		</table>

	    	</div>
    	</form>
    </body>
</html>