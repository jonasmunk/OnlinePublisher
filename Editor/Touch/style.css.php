
html
{
	height: 100%;
}

body
{
	margin:0;
	background: #000 url('<?=baseit('gfx/leather.jpg')?>');
	height: 100%;
}





div.sidebar
{
	position: absolute;
	left: 0;
	top: 0;
	width: 300px;
	background-image: -webkit-gradient(linear, 0 0, left bottom, from(rgba(0,0,0,0)), to(rgba(0,0,0,.4))), url('<?=baseit('../../In2iGui/gfx/backgrounds/sand_dark.png')?>');
	height: 100%;
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


div.page
{
	height: 800px;
	overflow: hidden;
	margin: 10px;
	background: #fff;/*url('<?=baseit('../../In2iGui/gfx/backgrounds/sand_light.png')?>');*/
	font-family: Palatino;
	color: #321;
	border-radius: 3px;
}




@media screen and (orientation:portrait) {
	body {
	}
	
	div.sidebar
	{
		display: none;
	}
	
	div.page
	{
		height: 800px;
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
	
	div.page
	{
		height: 600px;
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

