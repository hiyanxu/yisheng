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

        
        <script src="<?php echo (WWW_PUB); ?>Public/Admin/zyupload/zyupload-1.0.0.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/uploadify/uploadify.css">
        <link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/zyupload/skins/zyupload-1.0.0.min.css">
    </head>
    <body>
        <div>
            <div class="modal-body">
                <form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">
                    
                    <div class="form-group">
                        <label class="col-xs-3 control-label">媒体类型：</label>
                        <div class="col-xs-8">
                            <select id="file_type" name="file_type">
                                <option value="0">图片</option>
                                <option value="1">视频/音频（仅限于MP4和Ogg格式）</option>
                                <option value="2">其它文件</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">说明标题：</label>
                        <div class="col-xs-8">
                            <input type="text" id="file_title" name="file_title" placeholder="请输入说明标题" class="form-control input-sm"> 
                        </div>
                    </div>

                    <div id="divParams" style="display:none;" class="form-group">
                        <label class="col-xs-3 control-label">附加参数：</label>
                        <div class="col-xs-8">
                            宽（px）：<input type="number" name="file_param_width" min="1" max="1000" class="form-control input-sm">
                            高（px）：<input type="number" name="file_param_height" min="1" max="800" class="form-control input-sm">
                        </div>
                    </div>

                    <div id="file_upload" class="form-group">
                        <label class="col-xs-3 control-label">文件上传：</label>
                        <div id="divupload0">
                            <div style="float:left;">
                                <!-- <input type="text"  name ="file_upload_name" id="file_upload_name"/>  --> 
                                <input type="text" name="file_upload_name" id="file_upload_name" style="width:200px;">
                            </div>
                        </div>
                    </div>

                    <div id="zyupload" class="zyupload"></div>  
                    <?php $timestamp=time(); ?>

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
                    url              :   "/yisheng/webframe/index.php/Admin/File/uploader",  // 上传文件的路径
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
                            $('#file_upload_name').val(url);
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