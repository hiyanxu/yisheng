<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <meta http-equiv=content-type content="text/html; charset=utf-8" />     
    </head>
    <body>
        <table cellspacing=0 cellpadding=0 width="100%" align=center border=0>
            <tr height=28>
                <td background=<?php echo ($smarty["const"]["ADMIN_IMG_URL"]); ?>title_bg1.jpg>当前位置:<label style="font-weight:bold;">&nbsp;首页</label> </td></tr>
            <tr>
                <td bgcolor=#b1ceef height=1></td></tr>
            <tr height=20>
                <td background=<?php echo ($smarty["const"]["ADMIN_IMG_URL"]); ?>shadow_bg.jpg></td></tr></table>
        <table cellspacing=0 cellpadding=0 width="90%" align=center border=0>
            <tr height=100>
                <td align=middle width=100><img height=100 src="<?php echo (WWW_PUB); ?>Public/Admin/index/images/user.png" 
                                                width=90></td>
                <td width=60>&nbsp;</td>
                <td>
                    <table height=100 cellspacing=0 cellpadding=0 width="100%" border=0>

                        <tr>
                            <td style="font-weight:bold;">当前时间：<?php echo date("Y-m-d H:i:s",time());?></td></tr>
                        <tr>
                            <td style="font-weight: bold; font-size: 16px"><?php echo ($data['user_name']); ?></td></tr>
                        <tr>
                            <td>欢迎进入后台管理中心！</td></tr></table></td></tr>
            <tr>
                <td colspan=3 height=10></td></tr></table>
        <table cellspacing=0 cellpadding=0 width="95%" align=center border=0>
            <tr height=20>
                <td></td></tr>
            <tr height=22>
                <td style="padding-left: 20px; font-weight: bold; color: #ffffff" 
                    align=middle background=<?php echo ($smarty["const"]["ADMIN_IMG_URL"]); ?>title_bg2.jpg>您的相关信息</td></tr>
            <tr bgcolor=#ecf4fc height=12>
                <td></td></tr>
            <tr height=20>
                <td></td></tr></table>
        <table cellspacing=0 cellpadding=2 width="95%" align=center border=0>
            <tr>
                <td align=right width=100>登陆帐号：</td>
                <td style="color: #880000"><?php echo ($data['loginaccount']); ?></td></tr>
            <tr>
                <td align=right>真实姓名：</td>
                <td style="color: #880000"><?php echo ($data['user_name']); ?></td></tr>
            <tr> 
                <td align=right>上次登录ip地址：</td>
                <td style="color: #880000"><?php echo ($data['last_log_ip']); ?></td></tr>
            <tr>
                <td align=right>上次登录时间：</td>
                <td style="color: #880000"><?php echo ($data['last_log_time']); ?></td></tr>
            <tr>
        </table>
    </body>
</html>