<?
header("Content-Type: text/html; charset=UTF-8");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="css/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<title>Måleraflæsning : Hals Vandværk</title>
	<script src="In2iScripts/In2iScripts.js?x=y" type="text/javascript" charset="utf-8"></script>
	<script src="In2iScripts/In2iDate.js?x=y" type="text/javascript" charset="utf-8"></script>
	<script src="In2iScripts/In2iInput.js?x=y" type="text/javascript" charset="utf-8"></script>
	<script src="js/controller.js?x=y" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<div class="base">
	<h1>Måleraflæsning, Hals Vandværk</h1>

	<h2>Målernummer:</h2>
	<div>(fremgår af aflæsningskortet)</div>
	<input type="text" id="number" class="text"/>
	<div class="error" id="number_error"></div>

	<h2>Seneste aflæsning:</h2>
	<div id="latest"></div>

	<h2>Aflæsningsdato:</h2>
	<input type="text" id="date" class="text"/>

	<h2>Aflæsning:</h2>
	<input type="text" id="value" class="text"/>
	<div class="error" id="value_error"></div>
	<div>
	<input type="submit" value="Afsend" id="submit" class="submit"/>
	</div>
</div>
</body>
</html>
