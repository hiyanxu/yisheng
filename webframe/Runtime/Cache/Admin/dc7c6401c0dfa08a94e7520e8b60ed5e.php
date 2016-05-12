<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
	<head>		
		<meta Charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title></title>
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/bootstrap.min.css">		
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/index/css/left.css">	
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/index/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/index/css/ace.min.css">
		<script src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/jquery-1.11.1.min.js"></script>		
		<script src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/bootstrap.min.js"></script>

		<style type="text/css">
		#main-nav {
            margin-left: 1px;
        }
 
            #main-nav.nav-tabs.nav-stacked > li > a {
                padding: 10px 8px;
                font-size: 12px;
                font-weight: 600;
                color: #4A515B;
                background: #438EB9;
                background: -moz-linear-gradient(top, #FAFAFA 0%, #E9E9E9 100%);
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FAFAFA), color-stop(100%,#E9E9E9));
                background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
                background: -o-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
                background: -ms-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
                background: linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FAFAFA', endColorstr='#E9E9E9');
                -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#FAFAFA', endColorstr='#E9E9E9')";
                border: 1px solid #D5D5D5;
                border-radius: 4px;
            }
 
                #main-nav.nav-tabs.nav-stacked > li > a > span {
                    color: #4A515B;
                }
 
                #main-nav.nav-tabs.nav-stacked > li.active > a, #main-nav.nav-tabs.nav-stacked > li > a:hover {
                    color: #FFF;
                    background: #438EB9;
                    background: -moz-linear-gradient(top, #4A515B 0%, #3C4049 100%);
                    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#4A515B), color-stop(100%,#3C4049));
                    background: -webkit-linear-gradient(top, #4A515B 0%,#3C4049 100%);
                    background: -o-linear-gradient(top, #4A515B 0%,#3C4049 100%);
                    background: -ms-linear-gradient(top, #4A515B 0%,#3C4049 100%);
                    background: linear-gradient(top, #4A515B 0%,#3C4049 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#4A515B', endColorstr='#3C4049');
                    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#4A515B', endColorstr='#3C4049')";
                    border-color: #2B2E33;
                }
 
                    #main-nav.nav-tabs.nav-stacked > li.active > a, #main-nav.nav-tabs.nav-stacked > li > a:hover > span {
                        color: #FFF;
                    }
 
            #main-nav.nav-tabs.nav-stacked > li {
                margin-bottom: 4px;
            }
 
        /*定义二级菜单样式*/
        .secondmenu a {
            font-size: 10px;
            color: #4A515B;
            text-align: center;
        }
 
        .navbar-static-top {
            background-color: #212121;
            margin-bottom: 5px;
        }
 
        .navbar-brand {
            background: url('') no-repeat 10px 8px;
            display: inline-block;
            vertical-align: middle;
            padding-left: 50px;
            color: #fff;
        }
		</style>


	<body>
            <!-- <div style="margin-left: 20px; margin-top: 10px;" class="sidebar-menu toggle-others fixed">
                    <div class="DivisionLine"></div>
                    <a href="#a<?php echo ($pv['menuitem_id']); ?>" class="nav-header menu-first collapsed" data-toggle="collapse">
                            <span class="glyphicon glyphicon-file"></span> 
                            <?php echo ($pv["menuitem_name"]); ?>
                    </a>
                    <ul id="a<?php echo ($pv['menuitem_id']); ?>" class="nav nav-list collapse menu-second">
                            <li><a href="<?php echo (SURL); ?>index.php/Admin/<?php echo ($sv['menuitem_controller']); ?>/<?php echo ($sv['menuitem_function']); ?>" target="right">-><?php echo ($sv["menuitem_name"]); ?></a></li>
                    </ul>               
            </div>		 -->


			<!-- <div class="navbar navbar-duomi navbar-static-top" role="navigation">
			        <div class="container-fluid">
			            <div class="navbar-header">
			                <a class="navbar-brand" href="/Admin/index.html" id="logo">配置管理系统（流量包月）
			                </a>
			            </div>
			        </div>
			    </div> -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <ul id="main-nav" class="nav nav-tabs nav-stacked" style="">
                    <li class="active">
                        <a href="#">
                            <i class="glyphicon glyphicon-th-large"></i>
                            首页         
                        </a>
                    </li>
                    <?php if(is_array($menu_top_rows)): foreach($menu_top_rows as $key=>$toprows): ?><li>
	                        <a href="#<?php echo ($toprows['menu_id']); ?>" class="nav-header collapsed" data-toggle="collapse">
	                            <i class="glyphicon glyphicon-th-list"></i>
		                        <?php echo ($toprows["menu_name"]); ?>		                       
	                            <span class="pull-right glyphicon glyphicon-chevron-down"></span>
	                        </a>
	                        <ul id="<?php echo ($toprows['menu_id']); ?>" class="nav nav-list collapse secondmenu" style="height: 0px;">
	                        	<?php if(is_array($menu_second_rows)): foreach($menu_second_rows as $key=>$secondrows): if($secondrows['parentid'] == $toprows['menu_id']): ?><li><a target="right" href="/yisheng/webframe/index.php/Admin/<?php echo ($secondrows['menu_url']); ?>" style="font-size:12px;"><i class="glyphicon glyphicon-file"></i><?php echo ($secondrows["menu_name"]); ?></a></li><?php endif; endforeach; endif; ?>	                            
	                        </ul>
	                    </li><?php endforeach; endif; ?>
                    
                </ul>
            </div>            
        </div>
    </div>





	</body>
</html>