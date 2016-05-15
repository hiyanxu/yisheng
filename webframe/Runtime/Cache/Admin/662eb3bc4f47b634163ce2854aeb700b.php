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
                    <input type="hidden" value="0" name="parentid">
                    <div class="form-group">
                        <label class="col-xs-3 control-label">讲座名称：</label>
                        <div class="col-xs-8">
                            <input type="text" id="lec_name" name="lec_name" placeholder="请给出讲座名称" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">讲座时间：</label>
                        <div class="col-xs-8">
                            <input type="text" id="lec_time" name="lec_time" onclick="WdatePicker()" placeholder="请单击" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">主讲人：</label>
                        <div class="col-xs-8">
                            <input type="text" name='lec_speaker' class="form-control input-sm" placeholder="请输入主讲人">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">主讲人职称：</label>
                        <div class="col-xs-8">
                            <select name="lec_duty">
                                <?php if(is_array($cate_rows)): foreach($cate_rows as $k=>$cate_row): ?><option value="<?php echo ($cate_row['cate_id']); ?>"><?php echo ($cate_row['cate_name']); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">主讲人学院：</label>
                        <div class="col-xs-8">
                            <select name="lec_speaker_college">
                                <?php if(is_array($org_rows)): foreach($org_rows as $k=>$org_row): ?><option value="<?php echo ($org_row['org_id']); ?>"><?php echo ($org_row['org_name']); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">承办实验室：</label>
                        <div class="col-xs-8">
                            <select name="lec_workshop">
                                <?php if(is_array($workshop_rows)): foreach($workshop_rows as $k=>$workshop_row): ?><option value="<?php echo ($k); ?>"><?php echo ($workshop_row); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">承办学院：</label>
                        <div class="col-xs-8">
                            <select name="lec_college">
                                <?php if(is_array($org_rows)): foreach($org_rows as $k=>$org_row): ?><option value="<?php echo ($org_row['org_id']); ?>"><?php echo ($org_row['org_name']); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">讲座地点：</label>
                        <div class="col-xs-8">
                            <input type="text" name='lec_place' class="form-control input-sm" placeholder="请输入讲座地点">
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-xs-3 control-label">内容简介：</label>
                        <div class="col-xs-8">
                            <textarea name="lec_content" id="lec_content" plcaeholder="请给出内容"></textarea>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>

    </body>
</html>