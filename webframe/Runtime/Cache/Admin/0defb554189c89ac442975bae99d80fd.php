<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>信息修改</title>
        <link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>/Public/Admin/BootStrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/bootstrap-table.min.css">
        <!--js文件引入-->
        <script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/jquery-1.11.1.min.js"></script>     
        <script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/layer/layer.js"></script>

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
                    var editor1=K.create('textarea[name="course_content"]',{
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
                    <input type="hidden" value="<?php echo ($data[0]['course_id']); ?>" name="course_id">
                    <div class="form-group">
                        <label class="col-xs-3 control-label">课程名称：</label>
                        <div class="col-xs-8">
                            <input type="text" id="course_name" name="course_name" value="<?php echo ($data[0]['course_name']); ?>" placeholder="请给出课程名称" class="form-control input-sm"> 
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-xs-3 control-label">开始时间：</label>
                        <div class="col-xs-8">
                            <input type="text" id="course_start" name="course_start" value="<?php echo ($data[0]['course_start']); ?>" onclick="WdatePicker()" placeholder="请单击" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">结束时间：</label>
                        <div class="col-xs-8">
                            <input type="text" id="course_end" name="course_end" value="<?php echo ($data[0]['course_end']); ?>" onclick="WdatePicker()" placeholder="请单击" class="form-control input-sm"> 
                        </div>
                    </div>
                    
                    <div id="file_upload" class="form-group">
                        <label class="col-xs-3 control-label">开课学期：</label>
                        <div id="divupload0">
                            <div style="float:left;">
                                <select name="course_semester">
                                    <option value="1" <?php if($data[0]['course_semester'] == 1): ?>selected="selected"<?php endif; ?>>16-17春季学期</option>
                                    <option value="2" <?php if($data[0]['course_semester'] == 2): ?>selected="selected"<?php endif; ?>>16-17秋季学期</option>
                                    <option value="3" <?php if($data[0]['course_semester'] == 3): ?>selected="selected"<?php endif; ?>>17-18春季学期</option>
                                    <option value="4" <?php if($data[0]['course_semester'] == 4): ?>selected="selected"<?php endif; ?>>17-18秋季学期</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">主讲人：</label>
                        <div class="col-xs-8">
                            <input type="text" name='course_speaker' value="<?php echo ($data[0]['course_speaker']); ?>" id="course_speaker" class="form-control input-sm" placeholder="请给出课程主讲人">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">学时：</label>
                        <div class="col-xs-8">
                            <input type="text" name='course_hours' id="course_hours" value="<?php echo ($data[0]['course_hours']); ?>" class="form-control input-sm" placeholder="请给出课程学时">
                        </div>
                    </div>

                    <div id='time' class="form-group">
                        <label class="col-xs-3 control-label">时间及地点：</label>
                        <div class="col-xs-8">
                            <input type="text" name='week1' id="week1" value="<?php echo ($time['weekarr'][0][0]); ?>" class="input-sm" placeholder="请给出课程开始周">——
                            <input type="text" name="week2" id="week2" value="<?php echo ($time['weekarr'][0][1]); ?>" class="input-sm" placeholder="请给出课程结束周">&nbsp;&nbsp;
                            <select name="zhouji1">
                                <option value='1' <?php if($zhouji[0] == 1): ?>selected="selected"<?php endif; ?>>周一</option>
                                <option value='2' <?php if($zhouji[0] == 2): ?>selected="selected"<?php endif; ?>>周二</option>
                                <option value='3' <?php if($zhouji[0] == 3): ?>selected="selected"<?php endif; ?>>周三</option>
                                <option value='4' <?php if($zhouji[0] == 4): ?>selected="selected"<?php endif; ?>>周四</option>
                                <option value='5' <?php if($zhouji[0] == 5): ?>selected="selected"<?php endif; ?>>周五</option>
                                <option value='6' <?php if($zhouji[0] == 6): ?>selected="selected"<?php endif; ?>>周六</option>
                                <option value='7' <?php if($zhouji[0] == 7): ?>selected="selected"<?php endif; ?>>周日</option>
                            </select>&nbsp;&nbsp;
                            第
                            <select name="jiang1">
                                <option value='1' <?php if($jiang[0] == 1): ?>selected="selected"<?php endif; ?>>一</option>
                                <option value='2' <?php if($jiang[0] == 2): ?>selected="selected"<?php endif; ?>>二</option>
                                <option value='3' <?php if($jiang[0] == 3): ?>selected="selected"<?php endif; ?>>三</option>
                                <option value='4' <?php if($jiang[0] == 4): ?>selected="selected"<?php endif; ?>>四</option>
                                <option value='5' <?php if($jiang[0] == 5): ?>selected="selected"<?php endif; ?>>五</option>
                            </select>
                            讲
                            <input type="text" name="location1" id="location1" value="<?php echo ($location[0]); ?>" class="input-sm" placeholder="请给出授课地点">
                            <button class="btn btn-warning btn-sm" type="button" onclick="add();" title="继续添加"><i class=" glyphicon glyphicon-plus"></i></button>
                        </div>
                        <input type="hidden" id="hiddenI" value="<?php echo count($location);?>">
                        <?php if(is_array($location)): foreach($location as $k=>$value): if($k != 0): ?><div id="divtime<?php echo ($k); ?>" style="margin-left:290px; margin-top:10px;">
                                    <input type="text" name="week_start[]" id="week_start<?php echo ($k); ?>" value="<?php echo ($time['weekarr'][$k][0]); ?>" class="week" placeholder="请给出课程开始周">——
                                    <input type="text" name="week_end[]" id="week_end<?php echo ($k); ?>" value="<?php echo ($time['weekarr'][$k][1]); ?>" class="week" placeholder="请给出课程结束周">
                                    <select name="zhouji[]">
                                        <option value='1' <?php if($zhouji[$k] == 1): ?>selected="selected"<?php endif; ?>>周一</option>
                                        <option value='2' <?php if($zhouji[$k] == 2): ?>selected="selected"<?php endif; ?>>周二</option>
                                        <option value='3' <?php if($zhouji[$k] == 3): ?>selected="selected"<?php endif; ?>>周三</option>
                                        <option value='4' <?php if($zhouji[$k] == 4): ?>selected="selected"<?php endif; ?>>周四</option>
                                        <option value='5' <?php if($zhouji[$k] == 5): ?>selected="selected"<?php endif; ?>>周五</option>
                                        <option value='6' <?php if($zhouji[$k] == 6): ?>selected="selected"<?php endif; ?>>周六</option>
                                        <option value='7' <?php if($zhouji[$k] == 7): ?>selected="selected"<?php endif; ?>>周日</option>
                                    </select>&nbsp;&nbsp;
                                    第
                                    <select name="jiang[]">
                                        <option value='1' <?php if($jiang[$k] == 1): ?>selected="selected"<?php endif; ?>>一</option>
                                        <option value='2' <?php if($jiang[$k] == 2): ?>selected="selected"<?php endif; ?>>二</option>
                                        <option value='3' <?php if($jiang[$k] == 3): ?>selected="selected"<?php endif; ?>>三</option>
                                        <option value='4' <?php if($jiang[$k] == 4): ?>selected="selected"<?php endif; ?>>四</option>
                                        <option value='5' <?php if($jiang[$k] == 5): ?>selected="selected"<?php endif; ?>>五</option>
                                    </select>
                                    讲
                                    &nbsp;&nbsp;<input type="text" name="location[]" value="<?php echo ($location[$k]); ?>" id="location<?php echo ($k); ?>"  class="input-sm" placeholder="请给出授课地点">
                                    <a href="javascript:void(0)" onclick="reduce()" style="color:red; margin-left:20px;">X</a>
                                </div><?php endif; endforeach; endif; ?>
                        
                    </div>
                    

                    <div class="form-group">
                        <label class="col-xs-3 control-label">所属实验室：</label>
                        <div class="col-xs-8">
                            <select name="course_workshop">
                                <?php if(is_array($workshop_rows)): foreach($workshop_rows as $k=>$workshop_row): ?><option value="<?php echo ($k); ?>"<?php if($data[0]['course_workshop'] == $k): ?>selected="selected"<?php endif; ?>><?php echo ($workshop_row); ?></option><?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-xs-3 control-label">课程大纲：</label>
                        <div class="col-xs-8">
                            <textarea name="course_content" id="course_content" plcaeholder="请给出课程大纲"><?php echo ($data[0]['course_content']); ?></textarea>
                        </div>
                    </div>



                </form>
            </div>
        </div>
    <script type="text/javascript">
    /*
        步骤添加显示
        */
        var i;
        $(function(){
            i=$("#hiddenI").val();
        });
        function add(){
                    var bindNode=$('<div id="divtime'+i+'" style="margin-left:290px; margin-top:10px;">'
                        +'<input type="text" name="week_start[]" id="week_start'+i+'" class="week" placeholder="请给出课程开始周">——'
                            +'<input type="text" name="week_end[]" id="week_end'+i+'" class="week" placeholder="请给出课程结束周">' 
                            +'<select name="zhouji[]">'
                                +'<option value="1">周一</option>'
                                +'<option value="2">周二</option>'
                                +'<option value="3">周三</option>'
                                +'<option value="4">周四</option>'
                                +'<option value="5">周五</option>'
                                +'<option value="6">周六</option>'
                                +'<option value="7">周日</option>'
                            +'</select>&nbsp;&nbsp;'
                            +'第'
                            +'<select name="jiang[]">'
                                +'<option value="1">一</option>'
                                +'<option value="2">二</option>'
                                +'<option value="3">三</option>'
                                +'<option value="4">四</option>'
                                +'<option value="5">五</option>'
                            +'</select>'
                            +'讲'
                            +'&nbsp;&nbsp;<input type="text" name="location[]" id="location'+i+'" class="input-sm" placeholder="请给出授课地点">'
                            +'<a href="javascript:void(0)" onclick="reduce()" style="color:red; margin-left:20px;">X</a>'
                            +'</div>');                  
                    $("#time").append(bindNode);
                    i++;
                
        }

        /*
        去掉某一步骤的选择
        */
        function reduce(){
            i=i-1;
            var id="divtime"+i;
            var divreduce=document.getElementById(id);
            divreduce.remove();
            
        }

        $(function(){
            $("#week1").blur(function(){
                var partten=/^[0-9]{1,20}$/;
                var week1=$("#week1").val();
                if(!partten.test(week1)){
                    layer.msg("只能输入1-20之间的数据",{icon:2,time:1500,skin: 'layer-ext-moon'});
                    return;
                }
            });
            $("#week2").blur(function(){
                var partten=/^[0-9]{1,20}$/;
                var week1=$("#week1").val();
                var week2=$("#week2").val();
                if(!partten.test(week2)){
                    layer.msg("只能输入1-20之间的数据",{icon:2,time:1500,skin: 'layer-ext-moon'});
                    return;
                }
                if(week2<week1){
                    layer.msg("结束周不能小于开始周",{icon:2,time:1500,skin: 'layer-ext-moon'});
                    return;
                }
            });

        });

    </script>



    </body>
</html>