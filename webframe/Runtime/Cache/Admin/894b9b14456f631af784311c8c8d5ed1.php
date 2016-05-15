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
                    <input type="hidden" value="0" name="parentid">
                    <div class="form-group">
                        <label class="col-xs-3 control-label">开放日主题：</label>
                        <div class="col-xs-8">
                            <input type="text" id="open_theme" name="open_theme" placeholder="请给出开放日主题" class="form-control input-sm"> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-xs-3 control-label">开始时间：</label>
                        <div class="col-xs-8">
                            <input type="text" id="open_start" name="open_start" onclick="WdatePicker()" placeholder="请单击" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">结束时间：</label>
                        <div class="col-xs-8">
                            <input type="text" id="open_end" name="open_end" onclick="WdatePicker()" placeholder="请单击" class="form-control input-sm"> 
                        </div>
                    </div>
                    
                    <div id="file_upload" class="form-group">
                        <label class="col-xs-3 control-label">海报：</label>
                        <div id="divupload0">
                            <div style="float:left;">
                                <input type="text"  name ="open_thumb" id="open_thumb" class="input-xlarge"/>   
                            </div>
                            <div style="float:left;">
                                <i id="pickfiles"></i>  
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">开放日地点：</label>
                        <div class="col-xs-8">
                            <input type="text" name='open_location' id="open_location" class="form-control input-sm" placeholder="请给出开放日地点">
                        </div>
                    </div>
                    

                    <div class="form-group">
                        <label class="col-xs-3 control-label">承办实验室：</label>
                        <div class="col-xs-8">
                            <select name="open_workshop">
                                <?php if(is_array($workshop_rows)): foreach($workshop_rows as $k=>$workshop_row): ?><option value="<?php echo ($k); ?>"><?php echo ($workshop_row); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-xs-3 control-label">内容简介：</label>
                        <div class="col-xs-8">
                            <textarea name="open_content" id="open_content" plcaeholder="请给出内容"></textarea>
                        </div>
                    </div>



                </form>
            </div>
        </div>



    <!--上传部分js代码-->
        <script type="text/javascript">
        $(function(){
                        fileext="*.jpg;*.gif;*.jpeg;*.png";
                        fileDesc="请选择图像文件";
                        upload_init(fileext,fileDesc);
                    
        });


        var sid="<?php echo session_id();?>";
        jQuery(function($) {
             upload_init(fileext,fileDesc);

        });

        function upload_init(fileext,fileDesc){
            $('#pickfiles').uploadify({
                'debug': false,
                'method': 'post',
                'swf': "<?php echo (WWW_PUB); ?>Public/Admin/uploadify/uploadify.swf", //    swf 地址
                'uploader': '/yisheng/webframe/index.php/Admin/Openday/uploader', // 服务器端处理程序                
                'wmode': 'transparent', //使浏览按钮的flash背景文件透明
                'buttonText': '选择',
                'formData': {
                    'timestamp': '<?php echo $timestamp;?>',
                    'token': '<?php echo md5('unique_salt' . $timestamp);?>',
                    "session_id":sid
                },
                'height': 35,
                'width': 50,
                'preventCaching': true, // true不缓存 false缓存
                'fileTypeExts':fileext,
                'fileTypeDesc': fileDesc,
                //'removeTimeout' : 3,          // 上传完成后 移除的时间
                //'queueSizeLimit' : 10,            // 上传队列中一次可容纳的最大条数。该选项不限制上传文件数量。限制上传文件数量，使用uploadlimit选项。如果上传队列中的数量超过此限制，则触发onselecterror事件。 
                //'successTimeout' : 3600,      // 成功等待时间？
                'uploadLimit': 10, // 定义允许的最大上传数量。当达到或者超过该数值时，将触发 onSelectError事件。 
                'multi': true,
                'onUploadSuccess': function(file, data, response) {
                    var data = $.parseJSON(data);
                    var status = data.status;
                    alert(status);
                    if(status){
                        $('#open_thumb').val(data.name);
                    }
                    if (status == false)
                    {
                        $.weitac.alert('上传失败，' + data.msg, 0);
                    }
                },
                'onUploadError': function(file, errorCode, errorMsg) {
                    alert(errorMsg);alert(errorCode);
                },
                // 模版的设置

                // 不显示模版，通过事件来显示文件
                //'itemTemplate' : '',

                // --------------------------------------------------------------------------------  一些事件

               /* // 动态设置有问题，再研究
                'onUploadStart': function(file) {
                    //$("#file_upload").uploadify("settings", "uploadUserId", '3333');
                },
                // 失败执行的函数 报错！！
                'onUploadError': function(file, errorCode, errorMsg) {
                    //alert('上传错误，请重新再试!');
                },*/
                // 上传成功一个文件后 执行的事件  data 为 PHP echo输出的内容  response为true 上传成功 false上传失败
                /*'onUploadSuccess': function(file, data, response) {
                    alert("成功了！");
                    var data = $.parseJSON(data);
                    alert(data);
                    var status = data.status;
                    $('#thumb').val("/"+data.info.filepath+data.info.filename);

                    if (status == false)
                    {
                        $.weitac.alert('上传失败，' + data.msg, 0);
                    }
                },
                // 上传完毕 调用的函数 uploadsSuccessful成功的文件数量  uploadsErrored失败的文件数量
                'onQueueComplete': function(queueData) {

                    // $.weitac.tableRefresh(wt);
                    // alert(queueData.uploadsSuccessful + ' 个文件上传成功.' + queueData.uploadsErrored + '个文件上传失败.');
                },
                // 每添加一个文件至上传队列时触发该事件
                'onSelect': function(file) {
                    // alert(file.name + ' 文件添加至上传队列.');
                },
                'onSelectError': function(file, errorCode, errorMsg) {

                    if (errorCode == SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
                        this.queueData.errorMsg = "上传的文件数过多"
                    }

                    if (errorCode == SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT) {
                        this.queueData.errorMsg = "文件太大了"
                    }

                    if (errorCode == SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE) {
                        this.queueData.errorMsg = "不能传空文件"
                    }

                    if (errorCode == SWFUpload.QUEUE_ERROR.INVALID_FILETYPE) {
                        this.queueData.errorMsg = "文件类型不正确"
                    }
                },
                // 每一个文件上传完成都会触发该事件，不管是上传成功还是上传失败
                'onUploadComplete': function(file) {
                    //alert(file.name + ' 文件上传完成.');
                }*/
            });

        }
        </script>



    </body>
</html>