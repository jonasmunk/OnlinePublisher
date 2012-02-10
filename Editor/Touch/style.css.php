
html
{
	height: 100%;
	background: #000;
}

body
{
	margin:0;
	background: #000 url('<?=baseit('gfx/leather.jpg')?>');
	height: 100%;
}


div.bar
{
	height: 40px;
	background-image: -webkit-gradient(linear, 0 0, left bottom, from(#333), to(#000));
	border-bottom: 1px solid #333;
	font-size: 0;
}

div.bar a
{
	display: inline-block;
	line-height: 28px;
	height: 28px;
	margin: 5px 0 0 5px;
	border: 1px solid #111;
	background-image: -webkit-gradient(linear, 0 0, left bottom, from(#555), to(#222));
	color: #fff;
	padding: 0 8px;
	text-shadow: 0px 1px 0 #000;
	-webkit-box-shadow: 0 0 1px #ddd,0 1px 1px #333;
	-webkit-background-origin: padding-box;
	-webkit-background-clip: border-box;
	-webkit-border-radius: 3px;
	-webkit-background-clip: padding-box;	
	font-family: 'Lucida Grande',"Helvetica Neue", Helvetica, sans-serif;
	font-weight: bold;
	font-size: 13pt;
	vertical-align: top;
}


div.sidebar
{
	position: absolute;
	left: 0;
	top: 0;
	width: 300px;
	background-image: -webkit-gradient(linear, 0 0, left bottom, from(rgba(0,0,0,0)), to(rgba(0,0,0,.4))), url('<?=baseit('../../hui/gfx/backgrounds/sand_dark.png')?>');
	height: 100%;
}

div.sidebar ul
{
	list-style: none;
	color: #fff;
	margin: 0;
	padding: 5px;
	font-family: 'Lucida Grande',"Helvetica Neue", Helvetica, sans-serif;
}

.button
{
	display: inline-block;
	font-family: 'Lucida Grande',"Helvetica Neue", Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	padding: 0 10px;
	height: 30px;
	line-height: 30px;
	border: 3px solid #111;
	background: #000 -webkit-gradient( linear, left top, left bottom, from(#555), to(#000), color-stop(0.51, #222), color-stop(0.5, #333) );
	color: #fff;
	text-shadow: 0px 1px 0 #000;
	-webkit-background-origin: padding-box;
	-webkit-background-clip: border-box;
	-webkit-border-radius: 8px;
	-webkit-background-clip: padding-box;
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

div.main
{
}

div.page
{
	position: fixed;
	top: 40px;
	bottom: 40px;
	overflow: hidden;
	margin: 10px;
	background: #fff;/*url('<?=baseit('../../hui/gfx/backgrounds/sand_light.png')?>');*/
	font-family: Palatino;
	color: #321;
	border-radius: 3px;
	overflow: scroll;
	-webkit-overflow-scrolling: touch;
}

div.bottom_bar
{
	position: fixed;
	bottom: 0;
	right: 0;
	left: 0;
	height: 40px;
	background-image: -webkit-gradient(linear, 0 0, left bottom, from(#333), to(#000));
	border-bottom: 1px solid #333;
	font-size: 0;
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
		/*height: 800px;*/
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

