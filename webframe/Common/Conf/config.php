<?php
//$host=isset($_SERVER["HTTP_HOST"])?$_SERVER["HTTP_HOST"]:"127.0.0.1";
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'yisheng',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'yanxu4568797',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  '',    // 数据库表前缀
    'DB_PARAMS'          	=>  array(), // 数据库连接参数    
    'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    
    //页面trace信息追踪     
    'SHOW_PAGE_TRACE' =>true, 

    'VAR_SESSION_ID'      =>  'session_id',


    /*'APP_SUB_DOMAIN_DEPLOY'   =>    1, // 开启子域名配置
    'APP_SUB_DOMAIN_RULES'    =>    array(      
        '42.96.149.81'  => 'Admin',  // admin.domain1.com域名指向Admin模块 
    ),*/

);
