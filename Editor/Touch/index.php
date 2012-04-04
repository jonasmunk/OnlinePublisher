<?php
require_once '../../Config/Setup.php';
require_once '../Include/Security.php';
require_once '../Classes/Services/RenderingService.php';

header("Content-Type: text/html; charset=UTF-8");
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<!--<meta name = "viewport" content = "initial-scale = 1, user-scalable = no">-->
	<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
	<meta name = "viewport" content = "user-scalable=no, width=device-width, initial-scale = 1, minimum-scale = 1, maximum-scale = 1"/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="apple-touch-icon" href="icon.png" />
	<title>OnlinePublisher</title>
	<style>
		<?php
		include('style.css.php')
		?>
	</style>
	<script src="../../hui/js/hui.js"></script>
	<script src="../../hui/js/ui.js"></script>
</head>

<body style="overflow: hidden;">
	<div class="bar">
		<a>←Back</a>
		<a>◀</a>
		<a>▶</a>
	</div>
	<div class="sidebar">
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
	include('script.js')
	?>
	</script>
</body>
</html>
