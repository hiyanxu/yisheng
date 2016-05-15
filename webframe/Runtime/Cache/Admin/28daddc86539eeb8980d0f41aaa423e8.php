<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>信息审核</title>
        <link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>/Public/Admin/BootStrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/bootstrap-table.min.css">
        <!--js文件引入-->
        <script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/jquery-1.11.1.min.js"></script>     
        <script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/bootstrap.min.js"></script>

        <script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/My97DatePicker/WdatePicker.js"></script>
        <script src="<?php echo (WWW_PUB); ?>Public/Admin/kindeditor-4.1.10/kindeditor.js"></script>
        <script src="<?php echo (WWW_PUB); ?>Public/Admin/kindeditor-4.1.10/lang/zh_CN.js"></script>
        <link rel="stylesheet" href="<?php echo (WWW_PUB); ?>Public/Admin/kindeditor-4.1.10/plugins/code/prettify.css">
        <script src="<?php echo (WWW_PUB); ?>Public/Admin/kindeditor-4.1.10/plugins/code/prettify.js"></script>

        <script src="<?php echo (WWW_PUB); ?>Public/Admin/uploadify/jquery.uploadify.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/uploadify/uploadify.css">

        <!--kindeditor-->
        <script type="text/javascript">
        KindEditor.ready(function(K) {
                    var editor1=K.create('textarea[name="open_content"]',{
                        cssPath : '<?php echo (WWW_PUB); ?>Public/Admin/kindeditor-4.1.10/plugins/code/prettify.css',
                        uploadJson : '<?php echo (WWW_PUB); ?>Public/Admin/kindeditor-4.1.10/php/upload_json.php',
                        fileManagerJson : '<?php echo (WWW_PUB); ?>Public/Admin/kindeditor-4.1.10/php/file_manager_json.php',
                        allowFileManager : true,
                        afterBlur : function(){
                            this.sync();
                        }
                    });
                    prettyPrint();
                });
        </script>

    </head>
    <body>
        <div>
            <div class="modal-body">
                <form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">
                    <input type="hidden" value="<?php echo ($row[0]['open_id']); ?>" name="open_id">
                    <div class="form-group">
                        <label class="col-xs-3 control-label">开放日主题：</label>
                        <div class="col-xs-8">
                           <label><?php echo ($row[0]['open_theme']); ?></label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-xs-3 control-label">开始时间：</label>
                        <div class="col-xs-8">
                            <label><?php echo ($row[0]['open_start']); ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">结束时间：</label>
                        <div class="col-xs-8">
                            <label><?php echo ($row[0]['open_end']); ?></label>
                        </div>
                    </div>
                    
                    <div id="file_upload" class="form-group">
                        <label class="col-xs-3 control-label">海报：</label>
                        <div id="divupload0">
                            <div style="float:left;">
                                <?php echo ($row[0]['open_thumb']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">开放日地点：</label>
                        <div class="col-xs-8">
                            <label><?php echo ($row[0]['open_location']); ?></label>
                        </div>
                    </div>
                    

                    <div class="form-group">
                        <label class="col-xs-3 control-label">承办实验室：</label>
                        <div class="col-xs-8">
                            <label><?php echo ($row[0]['open_workshop'][0]['org_name']); ?></label>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-xs-3 control-label">内容简介：</label>
                        <div class="col-xs-8">
                            <textarea name="open_content" id="open_content" plcaeholder="请给出内容"><?php echo ($row[0]['open_content']); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">审核：</label>
                        <div class="col-xs-8" style="margin-top:1%;">
                            <select name="examine">
                                <option value="3">审核通过</option>
                                <option value="2">退回修改</option>
                            </select>
                        </div>
                    </div>



                </form>
            </div>
        </div>



    </body>
</html>