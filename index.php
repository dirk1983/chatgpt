<?php
$type = "个人";
//  if (substr($_SERVER["REMOTE_ADDR"],0,9)!="127.0.0.1"){
//    if (strpos($_SERVER["HTTP_USER_AGENT"],"MicroMessenger")){
//      echo "<div style='height:100%;width:100%;text-align:center;margin-top:30%;'><h1>请点击右上角，选择”在浏览器打开“</h1></div>";
//      exit;
//    }
//    if (!isset($_SERVER['PHP_AUTH_USER'])) {
//      header('WWW-Authenticate: Basic realm="Please input username and password."');
//      header('HTTP/1.0 401 Unauthorized');
//      echo 'Bye, honey.';
//      exit;
//    } else {
//      if (($_SERVER['PHP_AUTH_USER']=="admin")&&($_SERVER['PHP_AUTH_PW']=="admin")){
//        $type = "外网";
//      } else {
//        echo 'Wrong password, bye...';
//        exit;
//      }
//    }
//  } else {
//    $type = "内网";
//  }
?>
<html lang="zh-CN">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>ChatGPT<?= $type ?>专用版</title>
	<link rel="stylesheet" href="css/common.css?v1.1">
	<link rel="stylesheet" href="css/wenda.css?v1.1">
	<link rel="stylesheet" href="css/hightlight.css">
</head>

<body>
	<div class="layout-wrap">
		<header class="layout-header">
			<div class="container" data-flex="main:justify cross:start">
				<div class="header-logo">
					<h2 class="logo"><a class="links" id="clean" title="清空对话信息"><span class="logo-title">清空对话信息</span></a></h2>
				</div>
				<div class="header-logo">
					<h2 class="logo"><a class="links" href="https://github.com/dirk1983/chatgpt"><span class="logo-title">获取源码</span></a></h2>
				</div>
			</div>
		</header>
		<div class="layout-content">
			<div class="container">
				<article class="article" id="article">
					<div class="article-box">
						<div style="text-align: center;color:#9ca2a8">
							采用最新的gpt-3.5-turbo模型，stream模式，秒回
						</div>
						<div class="precast-block" data-flex="main:left">
							<div class="input-group">
								<span style="text-align: center;color:#9ca2a8">&nbsp;&nbsp;连续对话：</span>
								<input type="checkbox" id="keep" checked style="min-width:220px;">
								<label for="keep"></label>
								<span style="text-align: center;color:#9ca2a8">&nbsp;&nbsp;&nbsp;&nbsp;默认开启</span>
							</div>
							<!--
							<div class="input-group">
								<span style="text-align: center;color:#9ca2a8">&nbsp;&nbsp;请输入您的API-KEY&nbsp;&nbsp;</span>
								<input type="password" id="key" style="border:1px solid grey;display:block;max-width:270px;width:calc(100% - 160px);" onload="this.focus();">
							</div>
							-->
						</div>
						<ul id="article-wrapper">
						</ul>
						<div class="creating-loading" data-flex="main:center dir:top cross:center">
							<div class="semi-circle-spin"></div>
						</div>
						<div id="fixed-block">
							<div class="precast-block" id="kw-target-box" data-flex="main:left cross:center">
								<div id="target-box" class="box">
									<input type="text" name="kw-target" placeholder="请点此提问" id="kw-target" autofocus>
								</div>
								<div class="right-btn layout-bar">
									<p class="btn ai-btn bright-btn" id="ai-btn" data-flex="main:center cross:center"><i class="iconfont icon-wuguan"></i>发送</p>
								</div>
							</div>
						</div>
					</div>
				</article>
			</div>
		</div>
	</div>
	<script src="js/jquery.min.js"></script>
	<script src="js/jquery.cookie.min.js"></script>
	<script src="js/layer.min.js" type="application/javascript"></script>
	<script src="js/chat.js?v2.8"></script>
	<script src="js/highlight.min.js"></script>
	<script src="js/showdown.min.js"></script>
</body>

</html>