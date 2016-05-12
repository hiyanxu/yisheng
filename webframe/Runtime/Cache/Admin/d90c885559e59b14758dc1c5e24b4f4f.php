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
                    <input type="hidden" value="<?php echo ($workflow_id); ?>" name="workflow_id">
                    <div class="form-group">
                        <label class="col-xs-3 control-label">工作流名称：</label>
                        <div class="col-xs-8">
                            <input type="text" name="workflow_name" value="<?php echo ($workflow_row[0]['workflow_name']); ?>" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">工作流描述：</label>
                        <div class="col-xs-8">
                            <textarea cols="42" rows="5" name="workflow_desc">
                             <?php echo ($workflow_row[0]['workflow_desc']); ?>   
                            </textarea>
                        </div>
                    </div>
                    <?php if(is_array($workflowstep_rows)): foreach($workflowstep_rows as $key=>$workflowstep_row): ?><div id="divrole<?php echo ($workflowstep_row['step_now']); ?>" class="form-group">
                            <label class="col-xs-3 control-label">第<?php echo ($workflowstep_row['step_now']); ?>步</label>
                            <select name="step<?php echo ($workflowstep_row['step_now']); ?>" id="role<?php echo ($workflowstep_row['step_now']); ?>">
                                <option value="0">请选择</option>
                                <?php if(is_array($role_rows)): foreach($role_rows as $key=>$role_row): ?><option value="<?php echo ($role_row['role_id']); ?>" <?php if(($workflowstep_row['role_id'] == $role_row['role_id'])): ?>selected="selected"<?php endif; ?>><?php echo ($role_row['role_name']); ?></option><?php endforeach; endif; ?>
                            </select>
                            <a href="javascript:void(0)" onclick="reduce()" style="color:red; margin-left:20px;">X</a>
                        </div><?php endforeach; endif; ?>

                    <input type="hidden" id="steps" value="<?php echo ($workflow_row[0]['steps']); ?>">
                    <button type="button" class="btn btn-warning btn-sm" onclick="stepadd();"><i class="glyphicon glyphicon-hand-right"></i>&nbsp;步骤添加</button>
                </form>
            </div>
        </div>
        
        
        <!--自定义js-->
        <script type="text/javascript">
        /*
        步骤添加显示
        */
        var i;
        $(function(){
            i=parseInt($("#steps").val())+1;
        });        
        function stepadd(){
            
            $.ajax({
                type:"post",
                url:"/yisheng/webframe/index.php/Admin/Workflow/getrole",
                cache:false,
                dataType: "json",
                success:function(data){
                    var result=eval(data);
                    
                    var option="";
                    for(var j=0;j<result.length;j++){
                        option+="<option value='"+result[j]["role_id"]+"'>"+result[j]["role_name"]+"</option>";
                    }
                    var bindNode=$('<div id="divrole'+i+'" class="form-group">'
                        +'<label class="col-xs-3 control-label">第'+i+'步</label>'
                            +'<select name="step'+i+'" id="role'+i+'">' 
                            +'<option value="0">请选择</option>'                               
                            +option+'</select>'
                            +'<a href="javascript:void(0)" onclick="reduce()" style="color:red; margin-left:20px;">X</a>'
                            +'</div>');                    
                    $("#wt-forms").append(bindNode);
                    i++;
                },
                error:function(data){

                }
            });
        }

        /*
        去掉某一步骤的选择
        */
        function reduce(){
            i=i-1;
            var id="divrole"+i;
            var divreduce=document.getElementById(id);
            divreduce.remove();
            
        }
        </script>



    </body>
</html>