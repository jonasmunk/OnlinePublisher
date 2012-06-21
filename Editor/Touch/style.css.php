
html
{
	height: 100%;
	background: #000;
	overflow: hidden;
	-webkit-overflow-scrolling: none;
}

body
{
	margin:0;
	background: #fff;
	height: 100%;
	overflow: hidden;
	-webkit-overflow-scrolling: none;
}

div.sidebar
{
	position: absolute;
	left: 0;
	top: 0;
	width: 299px;
	background-image: -webkit-gradient(linear, 0 0, left bottom, from(rgba(0,0,0,0)), to(rgba(0,0,0,.4)))');
	height: 100%;
	overflow: auto;
	-webkit-overflow-scrolling: touch;
	border-right: 1px solid #ddd;
}

div.sidebar ul
{
	list-style: none;
	color: #666;
	margin: 0;
	padding: 0;
	font-family: "Helvetica Neue", Helvetica, sans-serif;
}

div.sidebar ul li
{
	border-bottom: 1px solid #eee;
	padding: 7px 0 7px 15px;
}


div.main
{
	position: fixed;
	top: 41px;
	bottom: 41px;
	right: 0;
	left: 300px;
	overflow: scroll;
	-webkit-overflow-scrolling: touch;
}



div.page
{
	margin: 20px;
	background: #fff;
	font-family: Palatino;
	color: #321;
	border-radius: 3px;
	padding: 10px;
}



div.bar
{
	height: 40px;
	background-image: -webkit-gradient(linear, 0 0, left bottom, from(#fff), to(#ddd));
	border-bottom: 1px solid #ccc;
	font-size: 0;
}

div.bar a
{
	display: inline-block;
	line-height: 28px;
	height: 28px;
	margin: 6px 0 0 5px;
	background-image: -webkit-gradient(linear, 0 0, left bottom, from(#fff), to(#bbb));
	color: #666;
	padding: 0 8px;
	text-shadow: 0px -1px 0 #fff;
	-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.3), 0 0 1px #fff;
	-webkit-background-origin: padding-box;
	-webkit-background-clip: border-box;
	-webkit-border-radius: 3px;
	-webkit-background-clip: padding-box;	
	font-family: "Helvetica Neue", Helvetica, sans-serif;
	font-weight: normal;
	font-size: 13pt;
	vertical-align: top;
}



.button:hover, .button.cancel, .button:active, .button.cancel:active
{
	color: #fff;
	text-shadow: none;
}

.button:hover, .button.cancel:hover
{
	background: -webkit-gradient( linear, left top, left bottom, from(#aaaee5), to(#10006d), color-stop(0.5, #1F3B97), color-stop(0.5, #081f6f) );
	-webkit-background-clip: padding-box;
}

.button.cancel
{
	background: -webkit-gradient( linear, left top, left bottom, from(#5c5c5b), to(#1e1b16), color-stop(0.2, #1e1b16) );
	margin-top: 6px;
}

div.bottom_bar
{
	position: fixed;
	bottom: 0;
	right: 0;
	left: 0;
	border-bottom: none;
	border-top: 1px solid #ddd;
}


@media screen and (orientation:portrait) {
	body {
	}
	
	div.sidebar
	{
		display: none;
	}
	
	div.main
	{
		left: 0;
	}
}

@media screen and (orientation:landscape) {
	body {
		 padding-left: 300px;
	}
	
	div.sidebar
	{
		display: block;
	}
	
	div.bottom_bar
	{
		left: 300px;
	}

}










<?
function baseit($path) {
	$handle = fopen($path, "r");
	$binary = fread($handle, filesize($path));
	fclose($handle);
	return 'data:image/jpg;base64,'.base64_encode($binary);
}
?>

