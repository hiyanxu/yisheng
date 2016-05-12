<?php
define("APP_DEBUG",TRUE);

$host=isset($_SERVER["HTTP_HOST"])?$_SERVER["HTTP_HOST"]:"127.0.0.1";  //定义当前的host地址
define("WWW_URL","http://".$host."/");  //定义当前的访问路径
define("WWW_PUB","http://".$host."/yisheng/webframe/");
define("UPLOAD_PATH","/var/www/html/yisheng/webframe/Public/upload");
header("Content-Type:text/html;charset=utf-8");  

include '../ThinkPHP/ThinkPHP.php';
