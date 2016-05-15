<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>信息添加</title>
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

        <!--kindeditor-->
        <script type="text/javascript">
        KindEditor.ready(function(K) {
                    var editor1=K.create('textarea[name="lec_content"]',{
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
                    <input type="hidden" value="<?php echo ($row[0]['lec_id']); ?>" name="lec_id">
                    <div class="form-group">
                        <label class="col-xs-3 control-label">讲座名称：</label>
                        <div class="col-xs-8">
                            <label><?php echo ($row[0]['lec_name']); ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">讲座时间：</label>
                        <div class="col-xs-8">
                            <label><?php echo ($row[0]['lec_time']); ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">主讲人：</label>
                        <div class="col-xs-8">
                            <label><?php echo ($row[0]['lec_speaker']); ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">主讲人职称：</label>
                        <div class="col-xs-8">
                           <label><?php echo ($row[0]['lec_duty'][0]['cate_name']); ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">主讲人学院：</label>
                        <div class="col-xs-8">
                            <label><?php echo ($row[0]['lec_speaker_college'][0]['org_name']); ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">承办实验室：</label>
                        <div class="col-xs-8">
                            <label><?php echo ($row[0]['lec_workshop'][0]['org_name']); ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">承办学院：</label>
                        <div class="col-xs-8">
                            <label><?php echo ($row[0]['lec_college'][0]['org_name']); ?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">讲座地点：</label>
                        <div class="col-xs-8">
                            <label><?php echo ($row[0]['lec_place']); ?></label>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-xs-3 control-label">内容简介：</label>
                        <div class="col-xs-8">
                            <textarea name="lec_content" id="lec_content" plcaeholder="请给出内容"><label><?php echo ($row[0]['lec_content']); ?></label></textarea>
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