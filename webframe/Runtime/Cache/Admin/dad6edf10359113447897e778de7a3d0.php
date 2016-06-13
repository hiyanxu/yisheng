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

        <script src="<?php echo (WWW_PUB); ?>Public/Admin/zyupload/zyupload-1.0.0.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/zyupload/skins/zyupload-1.0.0.min.css">

        <!--kindeditor-->
        <script type="text/javascript">
        KindEditor.ready(function(K) {
                    var editor1=K.create('textarea[name="org_content"]',{
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
                    <input type="hidden" value="<?php echo ($org_row[0]['org_id']); ?>" name="org_id">
                    <div class="form-group">
                        <label class="col-xs-3 control-label">英文名称：</label>
                        <div class="col-xs-8">
                            <input type="text" id="org_english_name" value="<?php echo ($org_row[0]['org_english_name']); ?>" name="org_english_name" placeholder="请给出英文名称" class="form-control input-sm"> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">负责人：</label>
                        <div class="col-xs-8">
                            <select name="org_user_id">
                                <?php if(is_array($user_rows)): foreach($user_rows as $k=>$user_row): ?><option value="<?php echo ($user_row['user_id']); ?>" <?php if($org_row[0]['org_user_id'] == $user_row['user_id']): ?>selected='selected'<?php endif; ?>><?php echo ($user_row['user_name']); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-xs-3 control-label">理念宗旨：</label>
                        <div class="col-xs-8">
                            <input type="text" id="org_idea" name="org_idea" value="<?php echo ($org_row[0]['org_idea']); ?>" placeholder="请给出理念机构理念宗旨" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div id="file_upload" class="form-group">
                        <label class="col-xs-3 control-label">logo：</label>
                        <div id="divupload0">
                            <div style="float:left;">
                                <input type="text" value="<?php echo ($org_row[0]['org_icon']); ?>"  name ="org_icon" id="org_icon">   
                            </div>
                            <div style="float:left;">
                                <i id="pickfiles"></i>  
                            </div>
                        </div>
                    </div>
                    <div id="zyupload" class="zyupload"></div>  
                     <div class="form-group">
                        <label class="col-xs-3 control-label">地理位置：</label>
                        <div class="col-xs-8">
                            <input type="text" id="org_location" value="<?php echo ($org_row[0]['org_location']); ?>" name="org_location" placeholder='地理位置' class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">联系电话：</label>
                        <div class="col-xs-8">
                            <input type="phone" id="org_phone" name="org_phone" <?php if($org_row[0]['org_phone'] == 0): ?>value=''<?php else: ?>value="<?php echo ($org_row[0]['org_phone']); ?>"<?php endif; ?> class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">联系邮箱：</label>
                        <div class="col-xs-8">
                            <input type="email" id="org_email" value="<?php echo ($org_row[0]['org_email']); ?>" name="org_email" class="form-control input-sm"> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">上级单位：</label>
                        <div class="col-xs-8">
                            <select name="org_college_id">
                                <?php if(is_array($org_college_rows)): foreach($org_college_rows as $k=>$org_college_row): ?><option value="<?php echo ($k); ?>" <?php if($org_row[0]['org_college_id'] == $k): ?>selected='selected'<?php endif; ?>><?php echo ($org_college_row); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">简介：</label>
                        <div class="col-xs-8">
                            <textarea name="org_content" id="org_content" plcaeholder="请给出简介"><?php echo ($org_row[0]['org_content']); ?></textarea>
                        </div>
                    </div>


                </form>
            </div>
        </div>
        
      <script type="text/javascript">
            $(function(){
                var _token=$("#_token").val();
                // 初始化插件
                $("#zyupload").zyUpload({
                    width            :   "650px",                 // 宽度
                    height           :   "400px",                 // 宽度
                    itemWidth        :   "140px",                 // 文件项的宽度
                    itemHeight       :   "115px",                 // 文件项的高度
                    url              :   "/yisheng/webframe/index.php/Admin/Org/uploader",  // 上传文件的路径
                    fileType         :   ["jpg","png","PNG","jpeg","txt","js","exe","mp4"],// 上传文件的类型
                    fileSize         :   51200000,                // 上传文件的大小
                    multiple         :   false,                    // 是否可以多个文件上传
                    dragDrop         :   true,                    // 是否可以拖动上传文件
                    tailor           :   true,                    // 是否可以裁剪图片
                    del              :   true,                    // 是否可以删除文件
                    finishDel        :   false,                   // 是否在上传文件完成后删除预览
                    /* 外部获得的回调接口 */
                    onSelect: function(selectFiles, allFiles){    // 选择文件的回调方法  selectFile:当前选中的文件  allFiles:还没上传的全部文件
                        console.info("当前选择了以下文件：");
                        console.info(selectFiles);
                    },
                    onDelete: function(file, files){              // 删除一个文件的回调方法 file:当前删除的文件  files:删除之后的文件
                        console.info("当前删除了此文件：");
                        console.info(file.name);
                    },
                    onSuccess: function(file, response){          // 文件上传成功的回调方法
                        console.info("此文件上传成功：");
                        console.info(file.name);
                        console.info("此文件上传到服务器地址：");
                        console.info(response);
                        $("#uploadInf").append("<p>上传成功，文件地址是：" + response + "</p>");
                        var responseEval=JSON.parse(response);
                        if(responseEval['status']){
                            var url="/var/www/html/yisheng/webframe/Public/upload/"+responseEval.name;
                            $('#org_icon').val(url);
                        }
                    },
                    onFailure: function(file, response){          // 文件上传失败的回调方法
                        console.info("此文件上传失败：");
                        console.info(file.name);
                    },
                    onComplete: function(response){               // 上传完成的回调方法
                        console.info("文件上传完成");
                        console.info(response);
                    }
                });
                
            });
        
        </script> 






    </body>
</html>