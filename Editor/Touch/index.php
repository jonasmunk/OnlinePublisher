<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<!--<meta name = "viewport" content = "initial-scale = 1, user-scalable = no">-->
	<meta name = "viewport" content = "user-scalable=no, width=device-width, initial-scale = 1, minimum-scale = 1, maximum-scale = 1"/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="apple-touch-icon" href="icon.png" />
	<title>OnlinePublisher</title>
	<style>
		<?
		include('style.css.php')
		?>
	</style>
</head>

<body>
	<div class="sidebar">fsdfs</div>
	<div style="height: 100px;"></div>
<div style="height:200px; background:red; overflow: hidden;" ontouchdown="alert(0)" id="container">
	<div style="height: 400px;">
		<h1>Lorem ipsum dolor sit amet, consectetur adipisicing elit</h1>
		<p>, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
		<h1>, sed do eiusmod tempor incididunt ut labore</h1>
		<p> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p>
		<h1> et dolore magna aliqua. Ut enim ad minim veniam</h1>
		<p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	</div>
</div>
	<div id="log"></div>
	
	<a class="button" onclick="document.location=document.location">Reload</a>
	<div id="mover" style="width: 100px; height: 100px; background: blue; position: absolute; left: 200px; top: 200px;"></div>
<script>
<?
include('script.js')
?>
</script>
</body>
</html>
