<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>登录页面</title>
		<meta name="keywords" content="Bootstrap模版,Bootstrap模版下载,Bootstrap教程,Bootstrap中文" />
		<meta name="description" content="站长素材提供Bootstrap模版,Bootstrap教程,Bootstrap中文翻译等相关Bootstrap插件下载" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- basic styles -->

		<link href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo (WWW_PUB); ?>Public/Admin/index/css/font-awesome.min.css" />

		<!--[if IE 7]>
		  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
		<![endif]-->

		<!-- page specific plugin styles -->

		<!-- fonts -->

		<!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" />-->

		<!-- ace styles -->

		<link rel="stylesheet" href="<?php echo (WWW_PUB); ?>Public/Admin/index/css/ace.min.css" />

		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="assets/js/html5shiv.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
                                                                    <span style="color:#69AA46;" class="glyphicon glyphicon-star"></span>
									<span class="red">欢迎登陆</span>
								</h1>
								<h4 class="blue">&copy; Copyright@yanxu</h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
                                                                                            <span style="color:#69AA46;" class="glyphicon glyphicon-pencil"></span>
												Please Enter Your Information
											</h4>

											<div class="space-6"></div>

                                                                                        <form method="post" action="/webframework/webframe/index.php/Admin/Login/login">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
                                                       <input name="userName" type="text" class="form-control" placeholder="Username" />
                                                       <span class="glyphicon glyphicon-user"></span>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
                                                     <input name="userPwd" type="password" class="form-control" placeholder="Password" />
                                                      <span class="glyphicon glyphicon-lock"></span>
														</span>
													</label>

													<div class="space"></div>

													<div class="clearfix">
														<label class="inline">
															<input type="checkbox" name="rememberMe"  />
															<span class="lbl"> Remember Me</span>
														</label>

                                                       <input type="submit" value="Login" class="width-35 pull-right btn btn-sm btn-primary">
														
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

											

								
</body>
</html>