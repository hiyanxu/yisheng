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

        <script src="<?php echo (WWW_PUB); ?>Public/Admin/uploadify/jquery.uploadify.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/uploadify/uploadify.css">
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
                                <input type="text"  name ="file_upload_name" id="file_upload_name" class="input-xlarge"/>   
                            </div>
                            <div style="float:left;">
                                <i id="pickfiles"></i>  
                            </div>
                        </div>
                    </div>
                    

                </form>
            </div>
        </div>


        <!--上传部分js代码-->
        <script type="text/javascript">
        var fileext="*.jpg;*.gif;*.jpeg;*.png";
        var fileDesc="请选择图像文件";
        $(function(){
            $("#file_type").change(function(){
                if($("#file_type").val()==1){
                    $("#divParams").css("display","block");
                    fileext="*.mp4;*.ogg";
                    fileDesc="请选择视频文件";
                    upload_init(fileext,fileDesc);
                }
                else{
                    $("#divParams").css("display","none");
                    if($("#file_type").val()==0){
                        fileext="*.jpg;*.gif;*.jpeg;*.png";
                        fileDesc="请选择图像文件";
                        upload_init(fileext,fileDesc);
                    }
                    else if($("#file_type").val()==2){
                        fileext="*.doc;*zip;*.xlxs";
                        fileDesc="请选择其它文件";
                        upload_init(fileext,fileDesc);
                    }
                }
            });
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
                'uploader': '/yisheng/webframe/index.php/Admin/File/uploader', // 服务器端处理程序                
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
                    if(status){
                        $('#file_upload_name').val(data.name);
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