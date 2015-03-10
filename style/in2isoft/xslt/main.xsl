<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 xmlns:widget="http://uri.in2isoft.com/onlinepublisher/part/widget/1.0/"
 exclude-result-prefixes="p f h n o util widget"
 >
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>
<!--  indent="yes"-->
<xsl:include href="../../basic/xslt/util.xsl"/>

<xsl:template match="p:page">
<xsl:call-template name="util:doctype"/>
<html>
	<xsl:call-template name="util:html-attributes"/>
	<head>
		<title>
			<xsl:choose>
				<xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id]">
					<xsl:text>Humanise - </xsl:text>
					<xsl:choose>
						<xsl:when test="//p:page/p:meta/p:language='en'"><xsl:text>Software for humans</xsl:text></xsl:when>
						<xsl:otherwise><xsl:text>Software til mennesker</xsl:text></xsl:otherwise>
					</xsl:choose>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="@title"/><xsl:text> - </xsl:text><xsl:value-of select="f:frame/@title"/>
				</xsl:otherwise>
			</xsl:choose>
		</title>
		<meta name="viewport" content="user-scalable=yes, initial-scale = 1, maximum-scale = 10, minimum-scale = 0.2"/>
		<meta name="google-site-verification" content="WMeBqZoNf7fYYk8Yvu8p05cFXnskJt1_Y6SJtXE-Ym0" />
		<link rel="shortcut icon" href="{$path}style/in2isoft/gfx/favicon.ico" type="image/x-icon" />
		<xsl:call-template name="util:metatags"/>
		<xsl:call-template name="util:watermark"/>
        
        <xsl:call-template name="util:style-inline">
			<xsl:with-param name="compiled">body{padding:0;margin:0;background:#fafafa;text-align:center;font-family:Arial,'Helvetica',sans-serif}body,html{height:100%}div.layout{margin:0 auto;width:990px;min-height:100%;position:relative;text-align:left}div.document{text-align:left}div.layout_base{padding-bottom:220px;width:100%}div.layout_head{overflow:hidden}div.layout_middle{box-shadow:0 1px 3px 0 #999;-webkit-box-shadow:0 1px 3px #999;background:#fff;border-radius:5px}body.msie div.layout_middle{box-shadow:0 1px 6px 0 #bbb}div.layout_info{margin-top:20px;box-shadow:inset 0 1px 3px #DDD;-webkit-box-shadow:inset 0 1px 3px #DDD;background:#f6f6f6;border-radius:5px;overflow:hidden}div.layout_middle_top{border-bottom:1px solid #ddd;height:31px;position:relative}div.layout_content{overflow:hidden;min-height:400px}div.layout_content_sidebar{padding-right:0;position:relative}div.layout_inner_content{padding:20px}div.layout_content_sidebar div.layout_inner_content{margin-right:200px}div.layout_sidebar{width:199px;position:absolute;right:0;bottom:0;top:0;font-size:11pt;padding-top:10px;border-left:1px solid #ddd}ul.layout_navigation{float:left;margin:0;padding:5px 0 0 6px;display:block;overflow:hidden;height:44px;cursor:default;font-size:0}ul.layout_navigation li{float:left;list-style:none;height:30px;padding-top:5px}ul.layout_navigation li a{display:inline-block;color:#333;text-decoration:none;font-size:13pt;height:30px;line-height:30px;margin-right:5px;cursor:pointer;border-radius:3px;font-weight:200}form.search{margin-top:13px;position:absolute;right:0}form.search .submit{position:absolute;top:0;left:-1000px;width:1px;display:none}ul.layout_sub_navigation{margin:0;padding:3px 0 3px 3px;display:block;overflow:hidden;height:28px;line-height:24px;list-style:none;font-size:10pt;cursor:default}ul.layout_sub_navigation li{float:left}ul.layout_navigation li span{display:inline-block;height:30px;padding:0 8px}ul.layout_navigation_selected li span{color:#8f99aa;font-weight:300}body.windows ul.layout_navigation_selected li span{font-weight:400}ul.layout_navigation_selected:hover li span{color:#333}ul.layout_navigation li.normal a:hover{background:#eee;background:-moz-linear-gradient(top,#fff 0,#eee 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(0,#fff),color-stop(100%,#eee));background:-webkit-linear-gradient(top,#fff 0,#eee 100%);background:-o-linear-gradient(top,#fff 0,#eee 100%);background:-ms-linear-gradient(top,#fff 0,#eee 100%);background:linear-gradient(top,#fff 0,#eee 100%);box-shadow:inset 0 0 2px rgba(0,0,0,.15)}body.msie ul.layout_navigation li.normal a:hover{box-shadow:inset 0 0 6px rgba(0,0,0,.1)}ul.layout_navigation li.normal a:hover span{color:#000}ul.layout_navigation li.selected a,ul.layout_navigation li.highlighted a{box-shadow:inset 0 1px 4px rgba(0,0,0,.15);background:#eee;background:-webkit-linear-gradient(top,#dedfe0 0,#f4f4f4 100%);background:-moz-linear-gradient(top,#dedfe0 0,#f4f4f4 100%)}body.msie ul.layout_navigation li.selected a,body.msie ul.layout_navigation li.highlighted a{box-shadow:inset 0 1px 6px rgba(0,0,0,.15)}ul.layout_navigation li.selected a{color:#0072bc}ul.layout_navigation li.selected a span,ul.layout_navigation li.highlighted a span{color:#0072bc;font-weight:400}ul.layout_navigation li.first a{width:170px;height:40px;margin:-7px 10px 0 -6px;border-radius:0;box-shadow:none;-webkit-box-shadow:none}ul.layout_navigation li.first span{display:none}span.hui_icon_1,span.hui_icon_16,a.hui_icon_16{display:inline-block;width:16px;height:16px;vertical-align:middle}a.hui_icon_16:hover{background-position:0 -16px}span.hui_icon_2,span.hui_icon_32,a.hui_icon_32{display:inline-block;width:32px;height:32px;vertical-align:middle}span.hui_icon_12,a.hui_icon_12{display:inline-block;width:12px;height:12px;vertical-align:middle}span.hui_icon_24{display:inline-block;width:24px;height:24px;vertical-align:middle}span.hui_icon_64{display:inline-block;width:64px;height:64px;vertical-align:middle}span.hui_icon_128{display:inline-block;width:128px;height:128px;vertical-align:middle}a.hui_icon_labeled{display:inline-block;vertical-align:middle;text-align:center;text-decoration:none}a.hui_icon_labeled strong{display:block;color:#333;font-size:12px;font-weight:normal;margin-top:2px}a.hui_icon_labeled_128 strong{font-size:18px}a.hui_icon_labeled:hover span.hui_icon_64{background-position:0 -64px}a.hui_icon_labeled:hover span.hui_icon_128{background-position:0 -128px}div.hui_curtain{width:100%;background:#000;position:absolute;top:0;left:0}.hui_imageviewer{background:#fff;font:10pt "Lucida Grande","Lucida Sans Unicode",Verdana,sans-serif;text-align:left}.hui_imageviewer_viewer{width:500px;height:360px;overflow:hidden}.hui_imageviewer_status{position:absolute;color:#666;margin-top:-21px;margin-left:5px}.hui_imageviewer_image{float:left;background:url(/hui/gfx/spinner.gif) center center no-repeat}.hui_imageviewer_image_error,.hui_imageviewer_image_abort{background:url(/hui/gfx/sad_mac.png) center center no-repeat}div.hui_imageviewer_controller{height:50px;width:180px;position:absolute;margin-top:-70px;background:url(/hui/gfx/overlay.png) left top no-repeat}div.hui_imageviewer_controller div{height:50px;background:url(/hui/gfx/overlay.png) right -50px no-repeat}div.hui_imageviewer_controller div div{background:url(/hui/gfx/overlay.png) left -100px repeat-x;margin:0 10px}div.hui_imageviewer_controller a{float:left;width:40px;height:25px;margin:10px 0 0;background-image:url(/hui/gfx/imageviewer_controls.png);background-repeat:no-repeat;cursor:pointer}.hui_imageviewer_previous{background-position:7px -75px}.hui_imageviewer_next{background-position:7px -50px}.hui_imageviewer_play{background-position:7px 0}.hui_imageviewer_pause{background-position:7px -25px}.hui_imageviewer_close{background-position:7px -100px}div.hui_imageviewer_text{height:20px;position:absolute;line-height:20px;margin-left:2px;margin-top:-22px;padding:0 5px;background:#fff}div.hui_imageviewer_zoomer{position:absolute;overflow:hidden;z-index:100;cursor:crosshair;background:url(/hui/gfx/spinner.gif) center center no-repeat}textarea.hui_editor_header{background:0;border:0;position:absolute;padding:0;display:block;outline:0}textarea.hui_editor_html{background:0;border:0;padding:0;display:block;width:100%}.hui_editor_part_active{outline:1px dashed #3875d7}div.hui_overlay_icon:hover{cursor:pointer;background-position:0 -32px}.hui_editor_part_hover{outline:1px dashed rgba(0,0,0,.2)}.hui_editor_column_hover{outline:1px dashed rgba(0,0,0,.2)}.hui_editor_column_edit{outline:2px dashed #3875d7!important}div.hui_editor_drop_placeholder{position:relative;overflow:hidden;height:0}div.hui_editor_drop_placeholder div{height:100%;border:2px dashed #DDD;background:#f6f6f6;border-radius:10px;box-sizing:border-box}div.hui_editor_dragproxy{text-align:left;position:absolute;top:0;left:0;z-index:1001;-webkit-transform-origin:0 0}div.hui_editor_dropmarker{position:absolute;height:1px;background:#ABF;box-shadow:0 0 4px 1px #abf;z-index:1000}div.hui_overlay{display:none;position:absolute;height:40px;background:url(/hui/gfx/overlay_24.png) left top no-repeat;padding-left:7px;font-size:0}div.hui_inner_overlay{background:url(/hui/gfx/overlay_24.png) right -40px no-repeat;padding-right:7px;height:40px;white-space:nowrap}div.hui_inner_overlay div.hui_inner_overlay{background:url(/hui/gfx/overlay_24.png) left -80px repeat-x;padding:0}div.hui_overlay_icon{width:32px;height:32px;float:left;margin:3px 1px 0}div.hui_overlay a.hui_button{vertical-align:top;margin-top:5px;margin-left:2px}div.hui_overlay a.hui_button,div.hui_overlay a.hui_button span{background-image:url(/hui/gfx/button_overlay.png);color:#fff}div.hui_overlay a.hui_button_highlighted,div.hui_overlay a.hui_button_highlighted span{background-image:url(/hui/gfx/button_overlay_highlighted.png)}span.hui_overlay_text{display:inline-block;line-height:33px;vertical-align:top;height:30px;padding:0 10px;color:#fff;font-family:"Lucida Grande","Lucida Sans Unicode",sans-serif;font-size:12px;margin-top:2px;cursor:default}div.hui_overlay_light{background:#fafafa;border:1px solid #eee;border-radius:4px;padding:0 3px;height:36px}div.hui_overlay_light div.hui_inner_overlay{background:0;padding:0;height:36px}div.hui_overlay_light div.hui_overlay_icon{margin-top:2px}div.hui_box{margin:0 auto;text-align:left}a.hui_box_close{width:29px;height:31px;background:url(/hui/gfx/box_close.png) left top no-repeat;position:absolute;margin:-10px 0 0 -10px;z-index:200}a.hui_box_close:hover{background-position:0 -31px}a.hui_box_close:focus{background-position:0 -62px;outline:0}a.hui_box_close:focus:hover{background-position:0 -93px;outline:0}div.hui_box_absolute{position:absolute;left:50%;top:-10000px;margin-left:-1000px;visibility:hidden}div.hui_box_top{height:5px;background:url(/hui/gfx/box_shadow.png) left top no-repeat;padding-left:18px}div.hui_box_top div{height:5px;background:url(/hui/gfx/box_shadow.png) right -5px no-repeat;padding-right:18px}div.hui_box_top div div{background:url(/hui/gfx/box_shadow.png) left -10px repeat-x;padding:0}div.hui_box_bottom{height:13px;background:url(/hui/gfx/box_shadow.png) left -15px no-repeat;padding-left:18px}div.hui_box_bottom div{height:13px;background:url(/hui/gfx/box_shadow.png) right -28px no-repeat;padding-right:18px}div.hui_box_bottom div div{background:url(/hui/gfx/box_shadow.png) left -41px repeat-x;padding:0}div.hui_box_middle{background:url(/hui/gfx/box_shadow.png) left -54px repeat-y;padding-left:7px}div.hui_box_middle div.hui_box_middle{background:url(/hui/gfx/box_shadow.png) right -54px repeat-y;padding:0 7px 0 0}div.hui_box_body{padding:0;background:#fff}div.hui_box_textured div.hui_box_body{background:#f3f3f3;border:1px solid #fff}div.hui_box_header{height:58px;background:url(/hui/gfx/box_toolbar.png) left top repeat-x;border:1px solid #b6c1d0;border-width:0 1px;font-family:"Lucida Grande","Lucida Sans Unicode",sans-serif}strong.hui_box_title{float:left;height:58px;line-height:58px;font-size:12pt;color:#44474c;font-weight:normal;padding:0 15px;text-shadow:rgba(255,255,255,.5) 0 1px 0}div.hui_box_header_toolbar strong.hui_box_title{float:right}div.hui_box_header div.hui_toolbar{float:left}div.hui_box_header div.hui_toolbar_icon_selected{background:url(/hui/gfx/box_toolbar.png) left -174px repeat-x}div.hui_box_header div.hui_toolbar_icon_selected div.hui_toolbar_inner_icon{background:url(/hui/gfx/box_toolbar.png) left -58px no-repeat}div.hui_box_header div.hui_toolbar_icon_selected div.hui_toolbar_inner_icon div.hui_toolbar_inner_icon{background:url(/hui/gfx/box_toolbar.png) right -116px no-repeat}div.hui_box_rounded div.hui_box_top,div.hui_box_rounded div.hui_box_top div{font-size:0;background:url(/hui/gfx/box_rounded.png) no-repeat;height:7px;padding:0}div.hui_box_rounded div.hui_box_top div{background-position:right -7px}div.hui_box_rounded div.hui_box_top div div{background-position:left -14px;background-repeat:repeat-x;margin:0 8px}div.hui_box_rounded div.hui_box_bottom,div.hui_box_rounded div.hui_box_bottom div{font-size:0;background:url(/hui/gfx/box_rounded.png) left -21px no-repeat;height:10px;padding:0}div.hui_box_rounded div.hui_box_bottom div{background-position:right -31px;padding:0}div.hui_box_rounded div.hui_box_bottom div div{background-position:left -41px;background-repeat:repeat-x;margin:0 8px}div.hui_box_rounded div.hui_box_middle{background:url(/hui/gfx/box_rounded_middle.png) repeat-y;padding:0}div.hui_box_rounded div.hui_box_middle div.hui_box_middle{background:url(/hui/gfx/box_rounded_middle.png) right repeat-y}div.hui_box_rounded div.hui_box_body{margin:0 3px;background:#fff;padding:1px 0;overflow:hidden}div.hui_buttons{overflow:hidden;font-size:0;line-height:0;white-space:nowrap}div.hui_buttons_right div.hui_buttons_body{text-align:right}div.hui_buttons_center div.hui_buttons_body{text-align:center}div.hui_buttons a.hui_button{margin-right:2px}div.hui_buttons_right a.hui_button{margin:0 0 0 2px}div.hui_buttons_center a.hui_button{margin:0 1px 0 1px}a.hui_button{background:url(/hui/gfx/button.png) top left no-repeat;color:#004a80;display:inline-block;vertical-align:middle;font-size:12px;height:28px;line-height:28px;cursor:pointer;text-decoration:none;font-family:"Lucida Grande","Lucida Sans Unicode",sans-serif;-webkit-user-select:none;-moz-user-select:none;white-space:nowrap}a.hui_button span{background-image:url(/hui/gfx/button.png);background-repeat:no-repeat;display:inline-block;background-position:right -56px;background-repeat:no-repeat;height:28px}a.hui_button span span{background-position:0 -28px;background-repeat:repeat-x;margin:0 5px;padding:0 5px}em.hui_button_icon{padding:0;margin:6px 3px 0 -2px;width:16px;height:16px;display:inline-block;vertical-align:top}em.hui_button_icon_notext{margin:6px -3px 0 -3px}a.hui_button:hover em.hui_button_icon{background-position:0 -16px}a.hui_button:hover{background-position:0 -84px}a.hui_button:hover span{background-position:100% -140px}a.hui_button:hover span span{background-position:0 -112px;background-repeat:repeat-x}a.hui_button:active{background-position:0 -168px;outline:0}a.hui_button:active span{background-position:100% -224px}a.hui_button:active span span{background-position:0 -196px;background-repeat:repeat-x}a.hui_button:focus{background-position:0 -252px;outline:0}a.hui_button:focus span{background-position:100% -308px}a.hui_button:focus span span{background-position:0 -280px;background-repeat:repeat-x}a.hui_button:focus:hover{background-position:0 -336px;outline:0}a.hui_button:focus:hover span{background-position:100% -392px}a.hui_button:focus:hover span span{background-position:0 -364px;background-repeat:repeat-x}a.hui_button:active:focus{background-position:0 -420px;outline:0}a.hui_button:active:focus span{background-position:100% -476px}a.hui_button:active:focus span span{background-position:0 -448px;background-repeat:repeat-x}a.hui_button_highlighted,a.hui_button_highlighted span{background-image:url(/hui/gfx/button_highlighted.png);color:#152}a.hui_button_disabled{color:#666;opacity:.5;cursor:default}a.hui_button_disabled:hover{background-position:top left}a.hui_button_disabled:hover span{background-position:100% -56px}a.hui_button_disabled:hover span span{background-position:0 -28px}a.hui_button_small{height:24px;background-image:url(/hui/gfx/button_small.png);line-height:0;font-size:0}a.hui_button_small span{height:24px;background-image:url(/hui/gfx/button_small.png);background-position:right -24px}a.hui_button_small span span{background-position:left -48px;margin:0 12px;padding:0 3px;font-size:12px;line-height:23px;text-shadow:0 1px 0 #fff}a.hui_button_small:hover{background-position:left -72px}a.hui_button_small:hover span{background-position:right -96px}a.hui_button_small:hover span span{background-position:left -120px}a.hui_button_small:active{background-position:left -144px}a.hui_button_small:active span{background-position:right -168px}a.hui_button_small:active span span{background-position:left -192px}a.hui_button_small:focus{background-position:left -216px}a.hui_button_small:focus span{background-position:right -240px}a.hui_button_small:focus span span{background-position:left -264px}a.hui_button_small:focus:hover{background-position:left -288px}a.hui_button_small:focus:hover span{background-position:right -312px}a.hui_button_small:focus:hover span span{background-position:left -336px}a.hui_button_small:focus:active{background-position:left -360px}a.hui_button_small:focus:active span{background-position:right -384px}a.hui_button_small:focus:active span span{background-position:left -408px}a.hui_button_small_highlighted,a.hui_button_small_highlighted span{background-image:url(/hui/gfx/button_small_highlighted.png);color:#152}a.hui_button_small em.hui_button_icon{margin-left:-3px;margin-top:4px}a.hui_button_small em.hui_button_icon_notext{margin-right:-4px;margin-left:-4px}a.hui_button_light,a.hui_button_light span{background:0;height:26px;line-height:26px;font-family:Georgia,serif;font-family:"Lucida Grande","Lucida Sans Unicode",sans-serif;font-size:14px}a.hui_button_light,a.hui_button_light:focus{border:1px solid #ddd;border-radius:4px;background:-webkit-gradient(linear,0 0,0 100%,from(#fff),to(#f6f6f6));background:-moz-linear-gradient(top,#fff,#f6f6f6);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#ffffff,endColorstr=#f6f6f6);-webkit-background-clip:border-box;color:#333}a.hui_button_light:hover{border-color:#dadada;background:-webkit-gradient(linear,0 0,0 100%,from(#fafafa),to(#eee));background:-moz-linear-gradient(top,#fafafa,#eee);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#fafafa,endColorstr=#eeeeee)}a.hui_button_light:active{border-color:#dadada;background:-webkit-gradient(linear,0 0,0 100%,from(#eee),to(#fafafa));background:-moz-linear-gradient(top,#eee,#fafafa);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#eeeeee,endColorstr=#fafafa)}a.hui_button_light:focus{border-color:#0169ff;box-shadow:0 0 4px #0169ff}a.hui_button_light em.hui_button_icon{margin-left:-6px;margin-right:5px;margin-top:5px}a.hui_button_light em.hui_button_icon_notext{margin-right:-5px}a.hui_button_small_light,a.hui_button_small_light span{height:20px;line-height:18px}a.hui_button_small_light span span{margin:0;padding:0 5px;font-size:11px;line-height:18px}a.hui_button_mini_light{border-radius:16px}a.hui_button_mini_light,a.hui_button_mini_light span{height:16px;line-height:16px;font-size:0}a.hui_button_mini_light span span{margin:0;padding:0 10px;font-size:10px;line-height:16px}a.hui_button_tiny_light{border-radius:14px}a.hui_button_tiny_light,a.hui_button_tiny_light span{height:14px;line-height:14px;font-size:0}a.hui_button_tiny_light span span{margin:0;padding:0 8px;font-size:10px;line-height:14px}.hui_context_dark a.hui_button,.hui_context_dark a.hui_button span{background-image:url(/hui/gfx/button_overlay.png);color:#fff}.hui_context_dark a.hui_button_highlighted,.hui_context_dark a.hui_button_highlighted span{background-image:url(/hui/gfx/button_overlay_highlighted.png)}a.hui_button_bar{border:1px solid #ddd;border-radius:3px;background:0;height:26px;line-height:26px;font-family:Georgia,serif;font-family:"Lucida Grande","Lucida Sans Unicode",sans-serif;font-size:14px;border-color:#ccc #d7d7d7 #ccc;background:-webkit-gradient(linear,0 0,0 100%,from(#fdfdfd),to(#e6e6e6));background:-moz-linear-gradient(top,#fafafa,#eee);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#fafafa,endColorstr=#eeeeee)}a.hui_button_bar span{background:0;color:#333}a.hui_button_small_bar,a.hui_button_small_bar span{height:20px;line-height:20px}a.hui_button_small_bar span span{margin:0;padding:0 7px;font-size:11px;line-height:17px;text-shadow:0 1px 0 #fff}form.hui_formula{padding:0;margin:0;font-family:"Lucida Grande","Lucida Sans Unicode",sans-serif;text-align:left}div.hui_formula_fieldset{border:1px solid #ddd;padding:10px 10px 5px;margin-top:4px;background:#fff}strong.hui_formula_fieldset{position:absolute;margin-top:-19px;background:#fff;font-size:12px;padding:0 4px;margin-left:-2px;color:#666;-moz-border-radius:4px;border-radius:4px}div.hui_window strong.hui_formula_fieldset{background:#e7e7e7}div.hui_window div.hui_formula_fieldset{background:#eee;border:1px solid #ccc;border-radius:3px;box-shadow:0 1px 3px #ddd inset}.hui_formula_header{color:#0077cd;font-size:13pt;padding-bottom:5px}table.hui_formula_fields{width:100%;font-size:0}p.hui_formula_field_hint{font-size:9px;color:#999;margin:-5px 0 0 3px}table.hui_formula_fields_above{border-spacing:0}form.hui_formula table.split{border-spacing:5px 0}td.hui_fields_buttons{border-spacing:0}label.hui_formula_field{color:#004a80;font-size:12px;font-weight:normal;line-height:13px;display:inline-block;padding-left:2px}table.hui_formula_fields_above label.hui_formula_field{line-height:13px;display:inline-block;padding-left:2px}table.hui_formula_fields th label.hui_formula_field{font-size:9pt}table.hui_formula_fields th{width:1%;vertical-align:top;padding:5px 5px 0 0;text-align:right;white-space:nowrap}table.hui_formula_fields th.hui_formula_middle{vertical-align:middle;line-height:normal;padding-bottom:5px;padding-top:0}table.hui_formula_fields_above th{text-align:left}table.hui_formula_fields_above div.hui_tokenfield{margin-bottom:2px}form.hui_formula div.hui_field{font-size:0}form.hui_formula select{font-size:9pt;margin:0;width:100%}.hui_formula_text{border:0;padding:0;margin:0;margin-top:-1px;font-size:9pt;width:100%;background:transparent;font-family:Verdana,sans-serif;outline:0}div.hui_textarea_dummy{font-family:"Lucida Grande","Lucida Sans Unicode",sans-serif;font-size:9pt;line-height:16px;position:absolute;visibility:hidden;left:-10000px;top:-10000px;word-wrap:break-word;padding:1px 0}input.hui_formula_text{height:18px}span.hui_formula_text_multiline{display:block;font-size:0}textarea.hui_formula_text{text-indent:0;line-height:16px;padding:0;margin:0;padding:1px 0;resize:none;overflow-x:hidden}textarea.hui_formula_text::-webkit-scrollbar{height:14px;width:14px}textarea.hui_formula_text::-webkit-scrollbar-thumb{background:#ccc -webkit-gradient(linear,0 0,100% 0,from(#fff),to(#f0f0f0));border-radius:7px;border:1px solid #ddd}td.hui_formula_field{padding:0}div.hui_formula_field_body{padding-bottom:5px;font-size:0;line-height:0}div.hui_formula_field_compact div.hui_formula_field_body{padding:0}span.hui_field{display:inline-block;vertical-align:middle}span.hui_field_top,span.hui_field_top span{display:block;font-size:0;background:url(/hui/gfx/field.png) left -5px no-repeat;height:5px}span.hui_field_top span{background-position:100% -10px}span.hui_field_top span span{background-position:0 0;background-repeat:repeat;margin:0 5px}span.hui_field_bottom,span.hui_field_bottom span{display:block;font-size:0;background:url(/hui/gfx/field.png) 0 -20px no-repeat;height:5px}span.hui_field_bottom span{background-position:100% -25px;background-repeat:no-repeat}span.hui_field_bottom span span{background-position:0 -15px;background-repeat:repeat-x;margin:0 5px}span.hui_field_middle{display:block;background:url(/hui/gfx/field.png) 0 -30px no-repeat}span.hui_field_middle span.hui_field_middle{display:block;background:url(/hui/gfx/field.png) 100% -427px no-repeat}span.hui_field_content{display:block;margin:0 5px;background:#fff}.hui_field_focused span.hui_field_top{background-position:0 -832px}.hui_field_focused span.hui_field_top span{background-position:100% -837px}.hui_field_focused span.hui_field_top span span{background-position:100% -827px}.hui_field_focused span.hui_field_bottom{background-position:0 -847px}.hui_field_focused span.hui_field_bottom span{background-position:100% -852px}.hui_field_focused span.hui_field_bottom span span{background-position:100% -842px}.hui_field_focused span.hui_field_middle{background-position:0 -857px}.hui_field_focused span.hui_field_middle span.hui_field_middle{background-position:100% -1254px}span.hui_field_singleline{background:0;height:16px;font-size:0;display:block}em.hui_field_placeholder{position:absolute;font-family:"Lucida Grande","Lucida Sans Unicode",sans-serif;color:#999;font-style:normal;font-size:12px;margin-left:7px;margin-top:5px}.hui_field_focused em.hui_field_placeholder,.hui_field_dirty em.hui_field_placeholder{display:none}form.hui_formula div.hui_buttons_body{float:right}form.hui_formula a.hui_button{margin-left:2px;margin-right:0}table.hui_objectlist{margin-top:1px;width:100%}table.hui_objectlist th{text-align:left;font-size:11px;line-height:13px;color:#004a80;font-weight:normal;padding-left:4px}table.hui_objectlist th.hui_objectlist1{padding-left:2px}.hui_objectlist tbody input.text{border-color:#ccc #ddd #eee #ddd;width:100%}.hui_objectlist tbody td{padding:0 0 0 2px;vertical-align:top}.hui_objectlist tbody td.first{padding-left:0}a.hui_datetime{position:absolute;margin-left:-17px;margin-top:-1px;font-size:0;cursor:pointer;padding:2px}a.hui_datetime span,body .hui_context_dark a.hui_datetime span{background:url(../icons/input/datetime14.png) no-repeat;display:inline-block;width:14px;height:14px}a.hui_datetime:hover span,body .hui_context_dark a.hui_datetime:hover span{background-position:0 -14px}div.hui_window label.hui_formula_field{background:#e7e7e7}div.hui_formula_fieldset label.hui_formula_field,div.hui_window_news label.hui_formula_field{background:0}div.hui_window_light label.hui_formula_field{color:#004a80;background:0}body .hui_context_dark label.hui_formula_field,.hui_context_dark .hui_checkbox,.hui_context_dark span.hui_radiobutton_label,.hui_context_dark th.hui_objectlist{color:#fff;background:0}.hui_context_dark .hui_formula_text{color:#fff}.hui_context_dark div.hui_field span{background-image:url(/hui/gfx/field_dark.png)}.hui_context_dark div.hui_field span.hui_field_singleline,.hui_context_dark div.hui_field span.hui_formula_text_multiline{background:0}.hui_context_dark div.hui_field span.hui_field_content{background:#222}.hui_context_dark div.hui_field span.hui_field_singleline{background:0}.hui_context_dark a.hui_checkbox{color:#fff}div.hui_message{position:absolute;top:40%;left:50%;height:40px;background:url(/hui/gfx/overlay_24.png) left top no-repeat;padding-left:7px;font-family:"Lucida Grande","Lucida Sans Unicode",sans-serif;line-height:38px;font-size:14px;color:#fff}div.hui_message div{background:url(/hui/gfx/overlay_24.png) right -40px no-repeat;padding-right:7px;height:40px}div.hui_message div div{background:url(/hui/gfx/overlay_24.png) left -80px repeat-x;padding:0 10px}span.hui_message_busy{display:inline-block;width:24px;height:24px;background:url(/hui/gfx/progress/spinner_grey_24.gif);vertical-align:middle;margin-left:-5px;margin-top:-4px;margin-right:5px}div.hui_message span.hui_icon{margin-left:-5px;margin-top:-4px;margin-right:4px}span.hui_searchfield{display:inline-block;height:25px;background:url(/hui/gfx/search_field.png) left top no-repeat;text-align:left;position:relative;cursor:text;width:100px;font-size:0;line-height:0;vertical-align:middle}span.hui_searchfield span{display:block;height:25px;background:url(/hui/gfx/search_field.png) right -25px no-repeat}span.hui_searchfield span span{height:25px;background-position:right -50px;background-repeat:repeat-x;margin:0 13px 0 21px;padding:0 7px 0 2px}span.hui_searchfield input{border:0;background:0;font-size:12px;height:16px;margin:5px 0 0;padding:0;width:100%;outline:0}a.hui_searchfield_reset{position:absolute;right:6px;top:6px;width:13px;height:13px;display:none;font-size:0;background:url(/hui/gfx/search_field.png) left -300px;cursor:pointer}a.hui_searchfield_reset:hover{background-position:0 -313px}span.hui_searchfield_dirty a,span.hui_searchfield_focus_dirty a{display:block}em.hui_searchfield_placeholder{position:absolute;font-style:normal;color:#999;font-size:11px;line-height:17px;left:25px;height:17px;top:4px;font-family:"Lucida Grande","Lucida Sans Unicode",sans-serif}span.hui_searchfield_dirty em,span.hui_searchfield_focus em,span.hui_searchfield_focus_dirty em{display:none}span.hui_searchfield_adaptive{width:100%;min-width:30px}span.hui_searchfield_focus{background-position:0 -75px}span.hui_searchfield_focus span{background-position:right -100px}span.hui_searchfield_focus span span{background-position:0 -125px}span.hui_searchfield_dirty{background-position:0 -150px}span.hui_searchfield_dirty span{background-position:right -175px}span.hui_searchfield_dirty span span{background-position:0 -200px}span.hui_searchfield_focus_dirty{background-position:0 -225px}span.hui_searchfield_focus_dirty span{background-position:right -250px}span.hui_searchfield_focus_dirty span span{background-position:0 -275px}a.hui_checkbox{overflow:hidden;font-size:10pt;line-height:20px;cursor:pointer;color:#004a80;display:inline-block;outline:0;text-decoration:none;vertical-align:middle}a.hui_checkbox span{float:left;width:20px;height:20px;margin-right:2px}a.hui_checkbox span span{background:url(/hui/gfx/checkbox.png) no-repeat;margin:0}a.hui_checkbox:hover span span{background-position:0 -20px}a.hui_checkbox:active span span{background-position:0 -40px}a.hui_checkbox_focused{text-shadow:#36f 0 0 2px;color:#024}a.hui_checkbox_focused span{background:url(/hui/gfx/checkbox.png) 0 -120px no-repeat}a.hui_checkbox_selected span span{background-position:0 -60px}a.hui_checkbox_selected:hover span span{background-position:0 -80px}a.hui_checkbox_selected:active span span{background-position:0 -100px}div.hui_checkboxes{margin-bottom:5px;font-size:0}div.hui_checkboxes a.hui_checkbox{margin-right:10px}div.hui_checkboxes_vertical a.hui_checkbox{display:block;padding:1px 5px 0 0}.shared_frame_adaptive{width:100%;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box}.shared_frame_light{padding:4px;border:1px solid #ddd;border-bottom:1px solid #bbb;border-top:1px solid #eee;background:#fff;display:inline-block}.shared_frame_elegant{display:inline-block}.shared_frame_elegant_top{display:block;height:34px;padding-left:34px;background:url(/style/basic/gfx/frames/elegant.png) left top no-repeat}.shared_frame_elegant_top_inner{display:block;height:34px;padding-right:34px;background:url(/style/basic/gfx/frames/elegant.png) right -34px no-repeat}.shared_frame_elegant_top_innermost{display:block;height:34px;background:url(/style/basic/gfx/frames/elegant.png) left -68px repeat-x}.shared_frame_elegant_bottom{display:block;height:34px;padding-left:34px;background:url(/style/basic/gfx/frames/elegant.png) left -102px no-repeat}.shared_frame_elegant_bottom_inner{display:block;height:34px;padding-right:34px;background:url(/style/basic/gfx/frames/elegant.png) right -136px no-repeat}.shared_frame_elegant_bottom_innermost{display:block;height:34px;background:url(/style/basic/gfx/frames/elegant.png) left -170px repeat-x}.shared_frame_elegant_middle{padding-left:34px;background:url(/style/basic/gfx/frames/elegant.png) left -204px no-repeat;font-size:0;display:block}.shared_frame_elegant_middle_inner{padding-left:0;padding-right:34px;background:url(/style/basic/gfx/frames/elegant.png) right -1204px no-repeat;display:block}.shared_frame_elegant_content{display:block}.shared_frame_shadow_slant_top{display:block;height:4px;padding-left:5px;background:url(/style/basic/gfx/frames/shadow.png) left top no-repeat}.shared_frame_shadow_slant_top_inner{display:block;height:4px;padding-right:5px;background:url(/style/basic/gfx/frames/shadow.png) right -4px no-repeat}.shared_frame_shadow_slant_top_innermost{display:block;height:4px;background:url(/style/basic/gfx/frames/shadow.png) left -8px repeat-x}.shared_frame_shadow_slant{display:inline-block}.shared_frame_shadow_slant_middle{padding-left:5px;background:url(/style/basic/gfx/frames/shadow.png) 0 -32px;display:block}.shared_frame_shadow_slant_middle_inner{background:url(/style/basic/gfx/frames/shadow.png) 100% -32px;padding-right:5px;padding-left:0;display:block}.shared_frame_shadow_slant_content{padding:4px;display:block;background:#fff}.shared_frame_shadow_slant_bottom{display:block;position:relative;height:10px;text-align:left}.shared_frame_shadow_slant_bottom_inner{background:url(/style/basic/gfx/frames/shadow.png) 0 -12px;width:50%;display:block;height:10px}.shared_frame_shadow_slant_bottom_innermost{position:absolute;background:#fff url(/style/basic/gfx/frames/shadow.png) 100% -22px;width:50%;display:block;height:10px;padding-left:1px;right:0}.shared_frame_polaroid{background:url(/style/basic/gfx/backgrounds/lightpaperfibers.png);display:inline-block;padding:10px 10px 40px;box-shadow:0 2px 5px rgba(0,0,0,.2)}.shared_frame_polaroid_content{display:inline-block;font-size:0;position:relative;background:#fafafa}.shared_frame_polaroid_content:before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;box-shadow:inset 0 0 3px rgba(0,0,0,.5)}.document_row{border-collapse:collapse;border-spacing:0;width:100%;display:table}.document_row_body{display:table-row}.document_column{display:table-cell;padding-left:10px;vertical-align:top}.document_column_first{padding-left:0}div.part_file{overflow:hidden}div.part_file div{background:url(/style/basic/gfx/file_bg.png) top left repeat-x;height:55px;float:left;clear:both;margin-bottom:10px}div.part_file div div{background:url(/style/basic/gfx/file_left.png) top left no-repeat;height:55px}div.part_file div div div{background:url(/style/basic/gfx/file_right.png) top right no-repeat;height:55px;padding-right:20px}div.part_file p{margin:0;padding-left:60px}div.part_file p.part_file_title{margin-top:10px;font-size:10pt}div.part_file p.part_file_info{font-size:10px;color:#888;padding-top:2px}div.part_file a{color:#ccc}div.part_file a span{color:#004eff}div.part_file a:hover,div.part_file a:hover span{color:#03a}.part_formula_form{margin:0;display:block}div.part_formula p{margin:0}.part_formula_label{margin:0;font-weight:bold;font-size:11px;line-height:14px}.part_formula_required{color:#f06;font-size:21px;font-family:Georgia,'Times New Roman';position:absolute;margin:4px 0 0 3px;font-weight:normal}.part_formula_field{margin-bottom:5px}.part_formula_hint{margin:-1px 0 0;font-size:9px;color:#999}.part_formula_field_content{font-size:0}.part_formula_fieldset{margin-bottom:10px}.part_formula_fieldset_legend{font-size:12px;margin-bottom:5px;font-weight:bold}.part_formula_input,.part_formula_input{width:100%;box-sizing:border-box;-moz-box-sizing:border-box;border:1px solid #eee;border-color:#ccc #ddd #eee;font-size:11px;padding:1px 0;margin:0}.part_formula_input_lines{height:100px}.part_formula_columns{border-spacing:0;width:100%}.part_formula_column{vertical-align:top;padding:0 0 0 10px}.part_formula_column_first{padding:0}.part_header{margin:0}h1.part_header,textarea.part_header_1{font-size:20pt;line-height:1.2em}h2.part_header,textarea.part_header_2{font-size:15pt;line-height:1.133333333em}h3.part_header,textarea.part_header_3{font-size:13pt;line-height:1.076923077em}h4.part_header,textarea.part_header_4{font-size:12pt;line-height:1.166666667em}h5.part_header,textarea.part_header_5{font-size:11.5pt;line-height:1.043478261em}h6.part_header,textarea.part_header_6{font-size:11pt;line-height:1,090909091em}.part_section_header_1{padding-bottom:20px}.part_section_header_2{padding-top:10px;padding-bottom:5px}.part_section_header_3{padding-top:10px}.part_section_header_4{padding-top:10px}.part_section_header_5{padding-top:10px}.part_section_header_6{padding-top:10px}hr.part_horizontalrule{border:0;border-bottom:solid 2px #ddd}.part_image{font-size:0}.part_image_container{display:inline-block;position:relative;width:100%}.part_image_image{border-width:0}.part_image_adaptive{position:absolute;width:100%;height:auto}div.part_image p,div.part_image textarea{margin:0;font-size:8pt;color:#999}.part_imagegallery{font-size:0}.part_imagegallery_item{display:inline-block;margin:0 10px 10px 0;text-decoration:none}.part_imagegallery_title{background:#fff;background:rgba(255,255,255,.5);position:absolute;color:#333;font-size:10px;padding:1px 2px;position:absolute;line-height:12px}.part_imagegallery_image{border:0}.part_imagegallery_framed a{margin:0 10px 10px 0;border:1px solid #ddd;border-bottom:1px solid #bbb;border-top:1px solid #eee;background:#fff;font-size:0;line-height:0;position:relative;z-index:1}.part_imagegallery_framed .part_imagegallery_image{border:4px solid #fff}.part_imagegallery_framed .part_imagegallery_title{margin:4px}.part_imagegallery_masonry{font-size:0;position:relative}.part_imagegallery_masonry_item{display:inline-block;background:#eee;width:200px;height:100px;border:1px solid #fff;border-width:1px 1px 0 0;box-sizing:border-box;font-size:11px;background-size:cover;background-position:50% 50%;vertical-align:top;cursor:pointer;position:relative}.part_imagegallery_masonry_item_last{border-right:0}div.part_list_box{border:1px solid #e1e1e1;border-radius:5px;padding:0 7px}div.part_list_item{padding:5px 0;border-top:1px solid #ddd}div.part_list h2{margin:0;font-size:12pt;font-weight:normal;padding-bottom:3px}div.part_list h3{margin:0;font-size:13px;color:#333;padding-bottom:3px}p.part_list_text{margin:0 0 5px;font-size:12px;color:#666}p.part_list_nodata{margin:0;padding:5px 0;font-size:12px;color:#999;font-style:italic}p.part_list_source{margin:0;font-size:12px;color:#666}p.part_list_date{margin:0;font-size:11px;color:#999}div.part_list_busy{opacity:.5}.part_section_listing{padding:10px 0}.part_listing{font-size:8pt;color:#333}.part_listing ul{margin:0;color:#bbb;padding-left:25px}.part_listing_first{font-size:9pt;font-weight:bold}span.part_listing{color:#333}.part_listing li{margin-bottom:5px}div.part_mailinglist_box{border:1px solid #eee;border-radius:5px;background:#fafafa;padding:10px}div.part_mailinglist form{margin:0}div.part_mailinglist_subscribe{margin-bottom:10px}div.part_mailinglist h2{color:#000;font-weight:normal;margin:0;font-size:12pt}div.part_mailinglist p.success,div.part_mailinglist p.error{color:#605140;font-size:10pt;overflow:hidden;padding:5px 0;text-align:left}div.part_mailinglist p.error{color:red}div.part_mailinglist p{margin:5px 0 0}div.part_mailinglist label{display:block;font-size:9pt;color:#666}div.part_mailinglist p.buttons{text-align:right;padding-bottom:3px}div.part_mailinglist input.text{background:#fff;font-size:10pt;border:1px solid #ddd;width:100%;text-indent:2px;padding:2px 0}.part_map{font-size:0}.part_map_static{position:relative;display:inline-block;font-size:0}.part_map_static_pin{background:url(/style/basic/gfx/part_map_pin.png);position:absolute;width:29px;height:30px;left:50%;top:50%;margin:-15px 0 0 -15px}.part_map_static_content{display:inline-block;overflow:hidden}.part_map_static_text{position:absolute;white-space:pre;font-size:12px;left:0;top:0;background:#fff;padding:4px 7px;border:1px solid #eee}.part_map_interactive{display:inline-block}.part_map_bubble{font-size:12px;white-space:pre}.part_menu_header{margin:0}.part_menu_level{list-style:none;margin:0;padding:0}.part_menu_level_sub{padding-left:20px}.part_movie_body{background-color:#eee;cursor:pointer;position:relative}.part_movie_poster{top:0;left:0;position:absolute;width:100%;height:100%;background-size:cover;background-position:50% 50%;overflow:hidden}.part_movie_text{margin:0;position:absolute;left:0;right:0;text-align:center;top:50%;margin-top:70px;padding:0 10px;font-size:18px}.part_movie_text_inner{background:rgba(0,0,0,.5);color:#fff;padding:0 5px}.part_movie_poster:before{content:'\25B6';position:absolute;background:#000;background:rgba(0,0,0,.5);color:#fff;font-size:80px;line-height:128px;text-indent:12px;width:120px;height:120px;border-radius:50%;text-align:center;font-family:Arial,sans-serif;top:50%;left:50%;margin:-60px 0 0 -60px}.part_movie_embedded{top:0;left:0;position:absolute;width:100%;height:100%}.part_news_box_top{background:url(/style/basic/gfx/box.png) left top repeat-x;height:5px;font-size:0}.part_news_box_top div{background:url(/style/basic/gfx/box.png) left -5px no-repeat;height:5px;font-size:0}.part_news_box_top div div{background:url(/style/basic/gfx/box.png) right -10px no-repeat}.part_news_box_bottom{background:url(/style/basic/gfx/box.png) left -15px repeat-x;height:5px;font-size:0}.part_news_box_bottom div{background:url(/style/basic/gfx/box.png) left -20px no-repeat;height:5px;font-size:0}.part_news_box_bottom div div{background:url(/style/basic/gfx/box.png) right -25px no-repeat}.part_news_box_middle{border:1px solid #e1e1e1;border-width:0 1px;padding:0 8px}.part_news_title{font-size:12pt;color:#333;padding-bottom:3px}.part_news_item{font-size:9pt;line-height:13pt;border-top:1px solid #eee;padding:6px 0;overflow:hidden}.part_news_item_title{font-weight:bold;color:#333}.part_news_item_description{color:#333}.part_news_item_date{float:left;font-size:10px;color:#aaa}.part_news_item_links{color:#7894a5;text-align:right}.part_news_item_links a{margin-left:2px}div.part_section_person{padding-bottom:10px}div.part_person{overflow:hidden}div.part_person table{border-spacing:0;font-size:11px;line-height:15px}div.part_person div.fn{font-weight:bold;font-size:13px}div.part_person div.adr{font-style:italic}div.part_person table td{vertical-align:top;padding:0}div.part_person img{border:0;width:60px;height:80px}span.part_person_label{color:#999}td.part_person_image{width:80px}td.part_person_image a{background:url(/style/basic/gfx/person_placeholder.png) center center no-repeat;border:1px solid #ddd;border-bottom:1px solid #bbb;border-top:1px solid #eee;padding:4px;display:inline-block;font-size:0;line-height:0;margin-right:10px;width:60px;height:80px}div.part_poster{overflow:hidden;position:relative}p.part_poster_title{margin:0;font-size:14pt;font-weight:bold}p.part_poster_text{font-size:12px;margin:5px 0 5px}p.part_poster_link{font-size:12px;margin:5px 0 5px}div.part_poster_navigator{position:absolute;font-size:0;width:100%;text-align:center;margin-top:-15px}div.part_poster_navigator a{display:inline-block;width:10px;height:10px;border-radius:50%;background:#eee;margin-right:3px}div.part_poster_navigator a.part_poster_current{background:#aaa}div.part_poster_pages{overflow:hidden}div.part_poster_page_content{overflow:hidden;padding:0 0 10px 0}div.part_poster_page img{float:right;margin-left:20px}div.part_poster_light{border:1px solid #ddd;border-radius:4px}div.part_poster_light div.part_poster_page_content{padding:10px}div.part_poster_inset{background:#fafafa;box-shadow:inset 0 1px 7px rgba(0,0,0,.07);border-radius:4px;padding:5px}div.part_poster_inset div.part_poster_page_content{padding:10px}.part_richtext{font-size:9pt;line-height:1.4em;color:#333}div.part_table table{font-size:12px;border-spacing:0;border-collapse:collapse}div.part_table thead th{border-bottom:1px solid #eee;padding:1px 3px;text-align:left}div.part_table tfoot th{border-bottom:1px solid #eee;padding:1px 3px;text-align:left}div.part_table td{border-bottom:1px solid #eee;padding:1px 3px}.part_section_text{padding-bottom:10px}.part_text{font-size:9pt;line-height:1.4em;color:#333}div.part_text_image{overflow:hidden}div.part_text p{margin:10px 0 0}div.part_text p.part_text_first{margin:0}img.part_text_image_right{float:right;margin:0 0 10px 10px}img.part_text_image_left{float:left;margin:0 10px 10px 0}body.msie div.layout_info{box-shadow:inset 0 1px 4px 0 #DDD}form{margin:0}img{border:0}a{word-break:break-word}span.layout_languages{position:absolute;right:10px;top:7px;font-size:12px;line-height:16px}p.layout_translation{margin:-5px 0 0 0;font-size:11px}span.layout_languages a,p.layout_translation a{color:#ddd}span.layout_languages a span,p.layout_translation a span{color:#666}span.layout_languages a:hover,p.layout_translation a:hover,span.layout_languages a:hover span,p.layout_translation a:hover span{color:#0169b2}div.layout_footer{font-size:9pt;margin-top:-200px}div.layout_footer span{color:#666}a.layout_design{background:url(/style/in2isoft/gfx/graphics.png) -10px -725px;text-indent:-10000px;width:149px;height:47px;display:block;margin:0 auto;margin-bottom:10px}div.layout_links{height:30px}div.layout_links a span{color:#666}ul.layout_navigation li.first a{background:url(/style/in2isoft/gfx/graphics.png) -7px -637px}ul.layout_sub_navigation_selected a span{color:#999;font-weight:300}body.windows ul.layout_sub_navigation_selected a span{font-weight:400}ul.layout_sub_navigation_selected:hover a span{color:#333}ul.layout_sub_navigation a{text-decoration:none;color:#333;height:25px;display:inline-block;margin-right:4px;cursor:pointer;border-radius:3px}ul.layout_sub_navigation a span{display:inline-block;height:25px;padding:0 8px}ul.layout_sub_navigation a:hover{background:#f3f3f3;background:-webkit-linear-gradient(top,#fff 0,#eee 100%);background:-moz-linear-gradient(top,#fff 0,#eee 100%);background:-ms-linear-gradient(top,#fff 0,#eee 100%);box-shadow:inset 0 0 2px rgba(0,0,0,.15)}body.msie ul.layout_sub_navigation a:hover{box-shadow:inset 0 0 4px rgba(0,0,0,.15)}ul.layout_sub_navigation a:hover span{color:#333}ul.layout_sub_navigation a.selected,ul.layout_sub_navigation a.highlighted{box-shadow:inset 0 1px 2px rgba(0,0,0,.15);background:#f3f3f3;background:-webkit-linear-gradient(top,#eeeff0 0,#fafafa 100%);background:-moz-linear-gradient(top,#eeeff0 0,#fafafa 100%);background:-ms-linear-gradient(top,#eeeff0 0,#fafafa 100%)}body.msie ul.layout_sub_navigation a.selected,body.msie ul.layout_sub_navigation a.highlighted{box-shadow:inset 0 1px 3px rgba(0,0,0,.15)}ul.layout_sub_navigation a.selected span,ul.layout_sub_navigation a.highlighted span{color:#0072bc;font-weight:400}ul.layout_side_navigation{list-style:none;margin:0 0 30px 15px;padding:0;font-size:10pt;line-height:22px}ul.layout_side_navigation a{color:#333;text-decoration:none;padding:1px 5px}ul.layout_side_navigation_selected a{color:#999}ul.layout_side_navigation_selected:hover a{color:#333}ul.layout_side_navigation a:hover{color:#000;text-decoration:underline}ul.layout_side_navigation a.selected{color:#0072bc;font-weight:bold}ul.layout_side_navigation a.highlighted{font-weight:bold}ul.layout_side_navigation ul{list-style:none;padding-left:15px}div.layout_info h2{font-family:Lato,"Lucida Grande","Lucida Sans Unicode",sans-serif;font-weight:300;font-size:28px;margin:0 20px 10px 20px;height:28px;color:#666}div.layout_info div.about h2{background-position:-590px -695px;width:200px}div.layout_info p{margin:0 20px}div.layout_info div.about{float:left;width:66.4%;padding:15px 0}div.layout_info div.about p{font-family:Lato,"Lucida Grande","Lucida Sans Unicode",sans-serif;font-weight:300;font-size:12pt;line-height:16pt;margin-top:11px;color:#666}body.windows div.layout_info div.about p,body.windows div.layout_info div.about p{font-weight:normal}div.layout_info div.contact{float:left;width:33%;padding:15px 0}div.layout_info div.contact h2{background-position:-590px -722px;width:200px;margin-left:17px}div.layout_info div.contact p{font-size:9pt;color:#666;line-height:16pt}div.layout_info div.contact p.name{color:#999;font-size:12pt}div.layout_info div.contact p.name strong{font-weight:normal}div.layout_info div.about p.more{line-height:11px;font-size:11pt;margin-top:24px}div.layout_info a span{color:#666}div.layout_info:hover h2{color:#000}div.layout_info:hover div.about p{color:#333}div.layout_info:hover a span{color:#0079cd}div.layout_info:hover div.contact p.name{color:#333}p.layout_translation{float:right}div.layout_news{padding:0 5px 10px}div.layout_news h2{font-weight:normal;margin:0 0 5px;font-size:11pt;color:#999;border-bottom:1px solid #ddd;padding:0 10px 3px}div.layout_news_item{padding:4px 10px 8px;overflow:hidden}div.layout_news_item h3{font-size:9pt;color:#666;font-weight:normal;padding-bottom:3px;margin:0}p.layout_news_text{color:#999;font-size:8pt;line-height:10pt;margin:0 0 3px}div.layout_news:hover p.layout_news_text,div.layout_news:hover h3{color:#000}p.layout_news_date{font-weight:normal;color:#bbb;font-size:8pt;margin:2px 0 0;float:left}p.layout_news_links{text-align:right;font-size:8pt;margin-top:3px;margin:0}#poster{width:990px;height:310px;background:url(/style/in2isoft/gfx/poster_bg.png);cursor:pointer;overflow:hidden}#poster_body{width:990px;position:relative;left:50%;margin-left:-495px}#poster_left{float:left;height:310px;width:495px;overflow:hidden}#poster_right{float:left;height:310px;width:495px;overflow:hidden}#poster_left_inner{width:990px;height:310px;background:245px 0 no-repeat}#poster_right_inner{width:990px;height:310px;background:495px 0 no-repeat}#poster_loader{background:#000;color:#fff;left:50%;margin-left:-25px;position:absolute;text-align:center;top:150px;width:50px;height:24px;line-height:22px}div.layout_placards{overflow:hidden;height:170px;border-top:1px solid #ddd;padding-top:15px;display:none}a.layout_placard{margin-left:13px;float:left;width:312px;height:164px}a.layout_placard_center{background-position:left -164px}a.layout_placard_right{background-position:left -328px}ul.layout_placards{list-style:none;border-top:1px solid #ddd;background:#f8f8f9;margin:0;padding:0;border-radius:0 0 5px 5px;overflow:hidden}ul.layout_placards li{float:left;width:33.333333333%;font-size:0}ul.layout_placards a{display:block;border-right:1px solid #ddd;text-decoration:none;color:#333}ul.layout_placards a:hover{background:#f0f0f3}ul.layout_placards strong{display:block;font-size:30px;line-height:30px;font-weight:normal;color:#89a;padding:8px 0 10px;font-family:Lato,"Lucida Grande","Lucida Sans Unicode",sans-serif;font-weight:300;white-space:nowrap;width:100%;text-indent:10px;overflow:hidden;text-overflow:ellipsis}ul.layout_placards em{font-style:normal;color:#333537}ul.layout_placards span{display:block;font-size:18px;line-height:22px;padding:5px 20px 0 150px;height:120px;color:#567;font-family:Lato,"Lucida Grande","Lucida Sans Unicode",sans-serif;font-weight:300}ul.layout_placards li.onlineme a{border:0}ul.layout_placards li.onlineme span{background:url(/style/in2isoft/gfx/graphics.png) -44px -420px no-repeat}ul.layout_placards li.onlinepublisher span{background:url(/style/in2isoft/gfx/graphics.png) -40px -5px no-repeat}ul.layout_placards li.onlineobjects span{background:url(/style/in2isoft/gfx/graphics.png) -44px -200px no-repeat}body,.common_font,p.common,ul.common,h1.common,h2.common,h3.common,h4.common,h5.common,h6.common,.part_header,.part_text,div.part_richtext{font-family:'Helvetica Neue','Helvetica','Arial','Lucida Grande','Lucida Sans Unicode',sans-serif}body.font,.font .common_font,.font p.common,.font ul.common,.font h1.common,.font h2.common,.font h3.common,.font h4.common,.font h5.common,.font h6.common,.font .part_header,.font .part_text,.font div.part_richtext{font-family:Lato,'Helvetica Neue','Helvetica','Arial','Lucida Grande','Lucida Sans Unicode',sans-serif}a.common{color:#ddd;color:rgba(0,0,0,.15);cursor:pointer;font-weight:normal}a.common span{color:#0079cd}a.common:hover,a.common:hover span{color:#0169b2}h1.common,h2.common,h3.common,h4.common,h5.common,h6.common{margin:0;color:#333;font-weight:300}h1.common{color:#3080cb;font-weight:300}p.common,ul.common{font-size:12pt;line-height:160%;text-align:justify}.retina.webkit a.common{text-decoration:none;background-image:-moz-linear-gradient(top,rgba(0,0,0,0) 99%,rgba(0,0,0,0.5) 99%);background-image:-webkit-linear-gradient(top,rgba(0,0,0,0) 98%,rgba(0,0,0,0.5) 98%);background-image:linear-gradient(top,rgba(0,0,0,0) 98%,rgba(0,0,0,0.5) 98%);background-repeat:repeat-x}.part_header{color:#333;font-weight:300}.part_header_1{color:#3080cb}h1.part_header,textarea.part_header_1{font-size:38px;line-height:42px}h2.part_header,textarea.part_header_2{font-size:19pt;line-height:21pt}h3.part_header,textarea.part_header_3{font-size:14pt;line-height:16pt;font-weight:400}h4.part_header,textarea.part_header_4,h5.part_header,textarea.part_header_5,h6.part_header,textarea.part_header_6{font-weight:400}div.part_section_header_3{padding-bottom:5px}body.windows .part_header{font-weight:normal}body.windows h1.part_header{font-weight:300}p.part_poster_text{font-size:14px;font-weight:300}p.part_poster_link{font-size:14px}p.part_poster_title{font-weight:400}.part_text,div.part_richtext{font-size:12pt;font-weight:300;word-spacing:1px}.part_text strong,div.part_richtext strong{color:#000;font-weight:400}div.part_richtext p{margin:10px 0}div.part_listing,textarea.part_listing{font-size:11pt;font-weight:300;word-spacing:1px}div.part_listing span.part_listing_first{font-size:12pt;font-weight:normal}body.windows .part_text,body.windows .part_text{font-weight:normal}div.part_list h3{font-weight:normal}hr.part_horizontalrule{border:0;border-bottom:solid 1px #DDD}div.part_news_item_title{font-size:10pt;font-weight:400}body.windows div.part_news_item_title{font-weight:bold}div.part_news_item_description{font-size:10pt;font-weight:300}body.windows div.part_news_item_description{font-weight:400}body.retina ul.layout_navigation li.first a,body.retina a.layout_design,body.retina ul.layout_placards li span{background-image:url(/style/in2isoft/gfx/graphics_2x.png);background-size:400px 907px}@media only screen and (min-device-width:768px) and (max-device-width:1024px){body span.hui_searchfield input{-webkit-appearance:caret}body,.common_font,div.part_text,div.part_listing,ul.layout_sub_navigation_selected a span,ul.layout_navigation_selected li span{font-weight:400}div.part_text{line-height:1.2em}div.part_listing span.part_listing_first,div.part_news_item_title,h3.part_header,h4.part_header,h5.part_header,h6.part_header{font-weight:600}}@media screen and (max-width:990px){body ul.layout_navigation li.first a{margin-left:0}div.layout{width:100%}div.layout_middle{box-shadow:none;-webkit-box-shadow:none;border-top:1px solid #eee;border-bottom:1px solid #ddd;border-radius:0;margin-top:-1px}div.document{overflow:hidden}form.search{position:absolute;right:5px}div.layout_info{margin:0;border-bottom:1px solid #eee;box-shadow:none;-webkit-box-shadow:none}#poster{width:100%}}@media screen and (max-width:880px){ul.layout_placards li.onlineme span{background-position:-64px -420px}ul.layout_placards li.onlinepublisher span{background-position:-64px -5px}ul.layout_placards li.onlineobjects span{background-position:-64px -200px}ul.layout_placards span{padding-left:130px}}@media screen and (max-width:800px){h1.part_header{font-size:28px;line-height:1.2em}ul.layout_navigation li a{font-size:15px;margin-right:0}ul.layout_navigation li a span{padding:0 7px}ul.layout_sub_navigation li a{margin-right:2px}div.layout_inner_content{padding:10px 20px}ul.layout_placards span{font-size:16px;line-height:18px}div.layout_sidebar{position:static;border:0;border-bottom:1px solid #ddd;background:#fafafa;margin:0;padding:10px;width:auto;border-radius:0}div.layout_sidebar li{display:inline-block}ul.layout_side_navigation{margin:0}div.layout_content_sidebar div.layout_inner_content{margin-right:0}div.document{overflow:visible}}@media screen and (max-width:700px){ul.layout_navigation li.first{position:absolute;top:5px}ul.layout_navigation{padding-top:45px;height:auto;padding-bottom:10px}div.document_column{display:block;width:auto!important;padding:0!important}h1.part_header{font-size:22px}h2.part_header{font-size:18px}h3.part_header{font-size:14px}h4.part_header{font-size:12px}h5.part_header,h6.part_header{font-size:12px}div.part_text,div.part_listing{font-size:11pt}div.part_listing span.part_listing_first{font-size:11pt}ul.layout_placards li.onlineme span{background-position:-100px -420px}ul.layout_placards li.onlinepublisher span{background-position:-100px -5px}ul.layout_placards li.onlineobjects span{background-position:-100px -200px}ul.layout_placards span{padding-left:100px}}@media screen and (max-width:600px){#poster{display:none}div.layout_inner_content{padding:10px}ul.layout_navigation li a span{padding:0 5px}div.part_image img{max-width:100%;height:auto}div.layout_info div.contact,div.layout_info div.about{width:auto;float:none}div.layout_middle_top,ul.layout_sub_navigation{height:auto}ul.layout_navigation li{height:24px}ul.layout_navigation li a{height:24px;line-height:24px}ul.layout_placards li{float:none;width:auto}ul.layout_placards a{border:0;border-bottom:1px solid #ddd}ul.layout_placards span{font-size:16px;line-height:18px}ul.layout_placards strong{text-indent:103px;position:absolute;letter-spacing:-1px}ul.layout_placards li.onlineme span{background-position:-70px -410px}ul.layout_placards li.onlinepublisher span{background-position:-60px 5px}ul.layout_placards li.onlineobjects span{background-position:-70px -190px}ul.layout_placards span{padding-top:50px;padding-left:127px;height:80px}}ul.layout_navigation li.first a{background:url(/style/in2isoft/gfx/graphics.png) -140px -637px}a.layout_design{background:url(/style/in2isoft/gfx/graphics.png) -172px -709px;text-indent:-10000px;width:177px;height:191px;display:block;margin:0 auto;margin-bottom:10px}.call-to-action{font-family:Lato,'Helvetica Neue','Helvetica','Arial','Lucida Grande','Lucida Sans Unicode',sans-serif;background:#fafafa;border:1px solid #eee;border-radius:3px;padding:20px 10px;text-align:center}.call-to-action-text{margin:-5px 0 0;color:#666;font-weight:300;font-size:32px;line-height:1}.call-to-action-button{background:#3080cb;color:#fff;height:34px;line-height:34px;padding:0 20px;box-sizing:border-box;border:0;border-radius:3px;display:inline-block;font-size:18px;margin-top:20px;text-decoration:none;cursor:pointer}.call-to-action-button:hover{background:#206ab0}.call-to-action-button:active{-webkit-transform:scale(0.95);outline:0}</xsl:with-param>
        </xsl:call-template>
					
		<xsl:call-template name="util:style-ie6"/>
		<xsl:call-template name="util:style-lt-ie9"/>
		<xsl:call-template name="util:scripts-build"/>
        <xsl:call-template name="util:style-build">
			<xsl:with-param name="plain" select="'true'"/>
        </xsl:call-template>
		<xsl:call-template name="util:load-font">
			<xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Lato:300,400,700'"/>
            <xsl:with-param name="family" select="'Lato'"/>
            <xsl:with-param name="weights" select="'300,400,700'"/>
		</xsl:call-template>
	</head>
	<body>
        <xsl:call-template name="util:script-inline">
            <xsl:with-param name="file" select="'style/in2isoft/js/inline.js'"/>
            <xsl:with-param name="compiled"><![CDATA[_editor.defer(function(){hui.browser.windows&&hui.cls.add(document.body,"windows"),hui.browser.msie&&hui.cls.add(document.body,"msie"),hui.browser.webkit&&hui.cls.add(document.body,"webkit"),new hui.ui.SearchField({element:"search",expandedWidth:200})}),window.devicePixelRatio>1&&(document.body.className+=" retina")
]]></xsl:with-param>
        </xsl:call-template>
		<div class="layout">
			<div class="layout_head">
				<ul>
					<xsl:attribute name="class">
						<xsl:text>layout_navigation</xsl:text>
						<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id] and not(//p:page/@id=//p:context/p:home/@page)">
							<xsl:text> layout_navigation_selected</xsl:text>
						</xsl:if>
					</xsl:attribute>
					<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
				</ul>
				<xsl:call-template name="search"/>
			</div>
			<div class="layout_middle">
				<div class="layout_middle_top">
					<xsl:call-template name="secondlevel"/>
					<xsl:call-template name="util:languages"/>
					<xsl:comment/>
				</div>
				<div class="layout_body">
					<xsl:if test="//p:page/@id=//p:context/p:home/@page">
						<div id="poster">
							<div id="poster_body">
							<div id="poster_loader">0%</div>
							<div id="poster_left"><div id="poster_left_inner"><xsl:comment/></div></div>
							<div id="poster_right"><div id="poster_right_inner"><xsl:comment/></div></div>
							</div>
						</div>
                        <script type="text/javascript">require(['Poster'],function() {new Poster();});</script>
					</xsl:if>
					<xsl:apply-templates select="p:content"/>
					<xsl:choose>
						<xsl:when test="//p:page/@id=//p:context/p:home/@page">
							<div class="layout_placards">
								<a class="layout_placard layout_placard_left" href="{$path}produkter/onlinepublisher/"><xsl:comment/></a>
								<a class="layout_placard layout_placard_center" href="{$path}produkter/onlineobjects/"><xsl:comment/></a>
								<a class="layout_placard layout_placard_right" href="{$path}produkter/onlineme/"><xsl:comment/></a>
							</div>
							<ul class="layout_placards">
								<li class="onlinepublisher">
									<a href="{$path}produkter/onlinepublisher/"><strong>Humanise <em>Editor</em></strong> - <span>Simpelt værktøj til opbygning og redigering af hjemmesider</span></a>
								</li>
								<li class="onlineobjects">
									<a href="{$path}produkter/onlineobjects/"><strong>Online<em>Objects</em></strong> - <span>Fleksibelt grundsystem til web-applikationer</span></a>
								</li>
								<li class="onlineme">
									<a href="{$path}teknologi/hui/"><strong>Humanise <em>UI</em></strong> - <span>Intuitiv, avanceret og effektiv brugerflade</span></a>
								</li>
							</ul>
						</xsl:when>
					</xsl:choose>
				</div>
			</div>
			
			<div class="layout_base">
				<div class="layout_info">
					<div class="about">
						<xsl:choose>
							<xsl:when test="//p:page/p:meta/p:language='en'">
								<h2>About Humanise</h2>
								<p>We focus on user experience and design. We seek out the most simple and essential solution. 
									We believe that machines should work for people. We think that knowledge should be free and accessible to all. 
									We hope you agree :-)
								</p>
								<p class="more"><a href="{$path}om/" class="common"><span>More about Humanise »</span></a></p>
							</xsl:when>
							<xsl:otherwise>
                                <xsl:call-template name="util:parameter">
                                    <xsl:with-param name="name" select="'about'"/>
                                    <xsl:with-param name="default">
        								<h2>Om Humanise</h2>
        								<p>Vores focus er på brugeroplevelse og design. Vi leder altid efter
        									den mest enkle og essentielle løsning. Vi tror på at maskinen skal
        									arbejde for mennesket. Vi mener at viden bør være fri
        									og tilgængelig for alle. Vi håber du er enig :-)
        								</p>                                        
                                    </xsl:with-param>
                                </xsl:call-template>
                                <p class="more"><a href="{$path}om/" class="common"><span>Mere om Humanise »</span></a></p>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div class="contact">
						<xsl:choose>
							<xsl:when test="//p:page/p:meta/p:language='en'"><h2>Contact</h2></xsl:when>
							<xsl:otherwise><h2>Kontakt</h2></xsl:otherwise>
						</xsl:choose>
                        <xsl:call-template name="util:parameter">
                            <xsl:with-param name="name" select="'contact'"/>
                            <xsl:with-param name="default">
        						<p class="name"><strong>Jonas Brinkmann Munk</strong></p>
        						<p class="email"><a href="mailto:jonasmunk@me.com" class="common"><span>jonasmunk@me.com</span></a></p>
        						<p class="phone">+45 28 77 63 65</p>
        						<p class="name"><strong>Kenni Graversen</strong></p>
        						<p class="email"><a href="mailto:gr@versen.dk" class="common"><span>gr@versen.dk</span></a></p>
        						<p class="phone">+45 22 48 61 53</p>
                            </xsl:with-param>
                        </xsl:call-template>
					</div>
				</div>
			</div>
		</div>
		<div class="layout_footer">
			<a class="layout_design">Designet og udviklet af Humanise</a>
			<!--
				<xsl:apply-templates select="f:frame/f:text/f:bottom"/>
				<xsl:apply-templates select="f:frame/f:links/f:bottom"/>
				-->
		</div>
		<xsl:call-template name="util:googleanalytics"/>
	</body>
</html>
</xsl:template>

<xsl:template match="p:content">
<div>
	<xsl:choose>
		<xsl:when test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
			<xsl:attribute name="class">layout_content layout_content_sidebar</xsl:attribute>
		</xsl:when>
		<xsl:otherwise>
			<xsl:attribute name="class">layout_content</xsl:attribute>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:if test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
		<div class="layout_sidebar">
			<xsl:call-template name="thirdlevel"/>
			<xsl:apply-templates select="../f:frame/f:newsblock"/>
			<xsl:comment/>
		</div>
	</xsl:if>
	<div class="layout_inner_content">
		<xsl:if test="//p:context/p:translation">
			<p class="layout_translation">
			<xsl:for-each select="//p:context/p:translation">
				<a class="common">
					<xsl:call-template name="util:link"/>
					<xsl:choose>
						<xsl:when test="@language='da'">
							<span><xsl:text>Denne side på dansk</xsl:text></span>
						</xsl:when>
						<xsl:when test="@language='en'">
							<span><xsl:text>This page in english</xsl:text></span>
						</xsl:when>
						<xsl:otherwise>
							<span>This page in <xsl:value-of select="@language"/></span>
						</xsl:otherwise>
					</xsl:choose>
				</a>
			</xsl:for-each>
			</p>
		</xsl:if>
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
</div>
</xsl:template>


<!--            User status                 -->



<xsl:template match="f:userstatus">
	<xsl:choose>
		<xsl:when test="$userid>0">
			<span class="userstatus">Bruger: <strong><xsl:value-of select="$usertitle"/></strong></span>
			<xsl:text> · </xsl:text>
			<a href="./?id={@page}&amp;logout=true" class="common">Log ud</a>
		</xsl:when>
		<xsl:otherwise>
			<a href="./?id={@page}" class="common">Log ind</a>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>



<xsl:template match="h:hierarchy/h:item">
	<xsl:if test="not(@hidden='true')">
		<li>
			<xsl:attribute name="class">
			<xsl:choose>
				<xsl:when test="position()>1 and //p:page/@id=@page">selected</xsl:when>
				<xsl:when test="position()>1 and descendant-or-self::*/@page=//p:page/@id">highlighted</xsl:when>
				<xsl:when test="position()=1">first</xsl:when>
				<xsl:otherwise>normal</xsl:otherwise>
			</xsl:choose>
			</xsl:attribute>
			<a>
				<xsl:call-template name="util:link"/>
				<span><xsl:value-of select="@title"/></span>
			</a>
		</li>
	</xsl:if>
</xsl:template>

<xsl:template name="secondlevel">
	<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
		<ul>
			<xsl:attribute name="class">
				<xsl:text>layout_sub_navigation</xsl:text>
				<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[descendant-or-self::*/@page=//p:page/@id]">
					<xsl:text> layout_sub_navigation_selected</xsl:text>
				</xsl:if>
			</xsl:attribute>
			<xsl:apply-templates select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
		</ul>
	</xsl:if>
</xsl:template>

<xsl:template name="thirdlevel">
<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
	<ul>
		<xsl:attribute name="class">
			<xsl:text>layout_side_navigation</xsl:text>
			<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[descendant-or-self::*/@page=//p:page/@id]">
				<xsl:text> layout_side_navigation_selected</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates select="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
	</ul>
</xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item">
	<xsl:variable name="style">
		<xsl:choose>
			<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
			<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
			<xsl:otherwise>normal</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:if test="not(@hidden='true')">
		<li>
		<a class="{$style}">
			<xsl:call-template name="util:link"/>
			<span><xsl:value-of select="@title"/></span>
		</a>
		</li>
	</xsl:if>
</xsl:template>

<xsl:template match="h:item">
	<xsl:variable name="style">
		<xsl:choose>
			<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
			<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
			<xsl:otherwise>standard</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:if test="not(@hidden='true')">
		<li>
			<a class="{$style}">
				<xsl:call-template name="util:link"/>
				<span><xsl:value-of select="@title"/></span>
			</a>
		<xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
			<ul><xsl:apply-templates/></ul>
		</xsl:if>
		</li>
	</xsl:if>
</xsl:template>





<!--            Links              -->


<xsl:template match="f:links/f:top">
	<div class="links_top">
		<div>
			<xsl:apply-templates select="//f:frame/f:userstatus"/> · 
			<a title="Udskriv siden" class="common" href="?id={//p:page/@id}&amp;print=true">Udskriv</a>
			<xsl:apply-templates/>
		</div>
	</div>
</xsl:template>

<xsl:template match="f:links/f:bottom">
	<div class="layout_links">
		<xsl:apply-templates/>
		<xsl:if test="f:link"><span>&#160;&#183;&#160;</span></xsl:if>
		<a title="XHTML 1.1" class="common" href="http://validator.w3.org/check?uri=referer"><span>XHTML 1.1</span></a>
	</div>
</xsl:template>

<xsl:template match="f:links/f:bottom/f:link">
	<xsl:if test="position()>1"><span>&#160;&#183;&#160;</span></xsl:if>
	<a title="{@alternative}" class="common">
		<xsl:call-template name="util:link"/>
		<span><xsl:value-of select="@title"/></span>
	</a>
</xsl:template>

<xsl:template match="f:links/f:top/f:link">
	<span>&#160;&#183;&#160;</span>
	<a title="{@alternative}" class="common">
		<xsl:call-template name="util:link"/>
		<span><xsl:value-of select="@title"/></span>
	</a>
</xsl:template>



<!--            Text              -->





<xsl:template match="f:text/f:bottom">
	<span class="text">
		<xsl:comment/>
	<xsl:apply-templates/>
	</span>
</xsl:template>

<xsl:template match="f:text/f:bottom/f:break">
	<br/>
</xsl:template>


<xsl:template match="f:text/f:bottom/f:link">
	<a title="{@alternative}" class="common">
		<xsl:call-template name="util:link"/>
		<span><xsl:apply-templates/></span>
	</a>
</xsl:template>




<!--            News              -->





<xsl:template match="f:newsblock">
	<div class="layout_news">
		<h2><xsl:value-of select="@title"/></h2>
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="f:newsblock//o:object">
	<div class="layout_news_item">
		<h3><xsl:value-of select="o:title"/></h3>
		<p class="layout_news_text">
			<xsl:apply-templates select="o:note"/>
		</p>
		<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
		<xsl:apply-templates select="o:links"/>
	</div>
</xsl:template>

<xsl:template match="f:newsblock//o:links">
	<p class="layout_news_links">
		<xsl:apply-templates/>
	</p>
</xsl:template>

<xsl:template match="f:newsblock//o:note">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="f:newsblock//o:break">
	<br/>
</xsl:template>

<xsl:template match="f:newsblock//n:startdate">
	<p class="layout_news_date"> <xsl:value-of select="@day"/>/<xsl:value-of select="@month"/><!--/<xsl:value-of select="substring(@year,3,2)"/>--></p>
</xsl:template>

<xsl:template match="f:newsblock//o:link">
	<xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
	<a title="{@alternative}" class="common">
		<xsl:call-template name="util:link"/>
		<span>
			<xsl:value-of select="@title"/>
		</span>
	</a>
</xsl:template>



<!--                  Search                     -->


<xsl:template name="search">
	<xsl:if test="f:frame/f:search">
		<form action="{$path}" method="get" class="search" accept-charset="UTF-8">
			<div>
				<span class="hui_searchfield" id="search"><em class="hui_searchfield_placeholder">Søg her...</em><a href="javascript:void(0);" class="hui_searchfield_reset" tabindex="-1"><xsl:comment/></a><span><span><input type="text" class="text" name="query"/></span></span></span>
				<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
				<xsl:for-each select="f:frame/f:search/f:types/f:type">
				<input type="hidden" name="{@unique}" value="on"/>
				</xsl:for-each>
				<input type="submit" class="submit" value="Søg"/>
			</div>
		</form>
	</xsl:if>
</xsl:template>



<!--                    Widgets                 -->

<xsl:template match="widget:happy-xmas">
    <div class="happy-xmas">
        <h1 style="color: red;"><xsl:value-of select="widget:title"/></h1>
    </div>
</xsl:template>

<xsl:template match="widget:call-to-action">
    <div class="call-to-action">
        <p class="call-to-action-text"><xsl:value-of select="widget:text"/></p>
        <xsl:apply-templates select="widget:button"/>
    </div>
</xsl:template>

<xsl:template match="widget:call-to-action/widget:button">
    <a class="call-to-action-button">
        <xsl:call-template name="util:link-href"/>
        <xsl:value-of select="."/>
    </a>
</xsl:template>

</xsl:stylesheet>