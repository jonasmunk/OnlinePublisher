<?php
require_once '../Include/Public.php';

header("Content-Type: text/html; charset=UTF-8");
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
	<meta name = "viewport" content = "user-scalable=no, width=device-width, initial-scale = 1, minimum-scale = 1, maximum-scale = 1"/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="apple-touch-icon" href="icon.png" />
	<title>OnlinePublisher</title>
	<link type="text/css" rel="stylesheet" href="css/base.css"/>
	<link type="text/css" rel="stylesheet" href="css/login.css"/>
	<link type="text/css" rel="stylesheet" href="../../hui/css/dev.css"/>
	<script src="../../hui/bin/joined.js"></script>
</head>

<body style="overflow: hidden;">
	<?php
	InternalSession::startSession();
	if (!InternalSession::isLoggedIn()) {
		?>
		<div id="login">
			<h1>Humanise<strong>Editor</strong></h1>
			<p><label>Username</label><input class="text" autocapitalize="off" id="username" placeholder="Username"/></p>
			<p><label>Password</label><input class="text" type="password" id="password" placeholder="Password"/></p>
			<p><button>Log in</button></p>
			<p><a onclick="document.location=document.location">Reload</a></p>
		</div>
		<?php
	}
	?>
	<div class="bar">
		<a>←Back</a>
		<a>◀</a>
		<a>▶</a>
		<a id="logout">Log out</a>
	</div>
	<div class="sidebar">
		<div class="bar">
			<a>Search</a>
		</div>
		<ul id="list"></ul>
	</div>
	<div class="main">
		<div class="page" id="container">
			
		</div>
		<div id="log"></div>
	</div>
		<div class="bottom_bar bar">
			<a class="button" onclick="document.location=document.location">Reload</a>
		</div>
	<div id="mover" style="display: none; width: 100px; height: 100px; background: blue; position: absolute; left: 200px; top: 200px;"></div>
	<script>
	<?php
	include('js/script.js')
	?>
	</script>
</body>
</html>
