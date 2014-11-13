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
 exclude-result-prefixes="p f h n o util"
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
					<xsl:text>Humanise : </xsl:text>
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
		<style type="text/css">
			body
			{
				padding: 0px;
				margin: 0px;
				background: #fafafa;
				text-align: center;
                font-family: Arial,'Helvetica',sans-serif;
			}

			body,html
			{
				height: 100%;
			}
			div.layout
			{
				margin: 0px auto;
				width: 990px;
				min-height: 100%;
				position: relative;
				text-align: left;
			}

			div.document
			{
				text-align: left;
			}

			div.layout_base
			{
				padding-bottom: 220px;
				width: 100%;
			}

			div.layout_head
			{
				overflow: hidden;
			}

			div.layout_middle
			{
				box-shadow: 0px 1px 3px 0px #999;
				-webkit-box-shadow: 0px 1px 3px #999;
				background: #fff;
				border-radius: 5px;
			}

			body.msie div.layout_middle
			{
				box-shadow: 0px 1px 6px 0px #bbb;
			}

			div.layout_info
			{
				margin-top: 20px;
				box-shadow: inset 0 1px 3px #DDD;
				-webkit-box-shadow: inset 0 1px 3px #DDD;
				background: #F6F6F6;
				border-radius: 5px;
				overflow: hidden;
			}

			div.layout_middle_top
			{
				border-bottom: 1px solid #ddd;
				height: 31px;
				position: relative;
			}

			div.layout_content
			{
				overflow: hidden;
				min-height: 400px;
			}

			div.layout_content_sidebar
			{
				padding-right: 0;
				position: relative;
			}

			div.layout_inner_content
			{
				padding: 20px;
			}

			div.layout_content_sidebar div.layout_inner_content
			{
				margin-right: 200px;
			}

			div.layout_sidebar
			{
				width: 199px;
				position: absolute;
				right: 0;
				bottom: 0;
				top: 0;
				font-size: 11pt;
				padding-top: 10px;
				border-left: 1px solid #ddd;
			}
			
			ul.layout_navigation
			{
				float: left;
				margin: 0px;
				padding: 5px 0 0 6px;
				display: block;
				overflow: hidden;
				height: 44px;
				cursor: default;
				font-size: 0;
			}

			ul.layout_navigation li
			{
				float: left;
				list-style: none;
				height: 30px;
				padding-top: 5px;
			}

			ul.layout_navigation li a
			{
				display: inline-block;
				color: #333;
				text-decoration: none;
				font-size: 13pt;
				height: 30px;
				line-height: 30px;
				margin-right: 5px;
				cursor: pointer;
				border-radius: 3px;
				font-weight: 200;
			}
			

			form.search
			{
				margin-top: 13px;
				position: absolute;
				right: 0;
			}


			form.search .submit
			{
				position: absolute;
				top: 0px;
				left: -1000px;
				width: 1px;
				display: none;
			}
			
			ul.layout_sub_navigation
			{
				margin: 0;
				padding: 3px 0 3px 3px;
				display: block;
				overflow: hidden;
				height: 28px;
				line-height: 24px;
				list-style: none;
				font-size: 10pt;
				cursor: default;
			}

			ul.layout_sub_navigation li
			{
				float: left;
			}
            
            ul.layout_navigation li span
            {
            	display: inline-block;
            	height: 30px;
            	padding: 0px 8px;
            }

            ul.layout_navigation_selected li span
            {
            	color: #8f99aa;
            	font-weight: 300;
            }

            body.windows ul.layout_navigation_selected li span
            {
            	font-weight: 400;
            }


            ul.layout_navigation_selected:hover li span
            {
            	color: #333;
            }

            ul.layout_navigation li.normal a:hover
            {
            	background: #eee;
            	background: -moz-linear-gradient(top, #ffffff 0%, #eeeeee 100%); /* FF3.6+ */
            	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#eeeeee)); /* Chrome,Safari4+ */
            	background: -webkit-linear-gradient(top, #ffffff 0%,#eeeeee 100%); /* Chrome10+,Safari5.1+ */
            	background: -o-linear-gradient(top, #ffffff 0%,#eeeeee 100%); /* Opera11.10+ */
            	background: -ms-linear-gradient(top, #ffffff 0%,#eeeeee 100%); /* IE10+ */
            	background: linear-gradient(top, #ffffff 0%,#eeeeee 100%); /* W3C */
            	box-shadow: inset 0px 0px 2px rgba(0,0,0,.15);
            }



            body.msie ul.layout_navigation li.normal a:hover
            {
            	box-shadow: inset 0px 0px 6px rgba(0,0,0,.1);
            }

            ul.layout_navigation li.normal a:hover span
            {
            	color: #000;
            }

            ul.layout_navigation li.selected a, ul.layout_navigation li.highlighted a
            {
            	box-shadow: inset 0 1px 4px rgba(0,0,0,.15);
            	background: #eee;
            	background: -webkit-linear-gradient(top, #dedfe0 0%,#f4f4f4 100%); /* Chrome10+,Safari5.1+ */
            	background: -moz-linear-gradient(top, #dedfe0 0%,#f4f4f4 100%);
            }

            body.msie ul.layout_navigation li.selected a, 
            body.msie ul.layout_navigation li.highlighted a
            {
            	box-shadow: inset 0 1px 6px rgba(0,0,0,.15);
            }

            ul.layout_navigation li.selected a
            {
            	color: #0072bc;
            }

            ul.layout_navigation li.selected a span, ul.layout_navigation li.highlighted a span
            {
            	color: #0072BC;
            	font-weight: 400;
            }

            ul.layout_navigation li.first a
            {
            	width: 170px;
            	height: 40px;
            	margin: -7px 10px 0 -6px;
            	border-radius: 0;
            	box-shadow: none;
            	-webkit-box-shadow: none;
            }


            ul.layout_navigation li.first span
            {
            	display: none;
            }
		</style>
		
			
		<xsl:call-template name="util:style-ie6"/>
		<xsl:call-template name="util:style-lt-ie9"/>
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:300,400,700" type="text/css" media="screen" title="no title"/>
		<xsl:call-template name="util:scripts-build"/>
		<xsl:call-template name="util:style-build"/>
		<!--
		<xsl:call-template name="util:lazy-fonts">
			<xsl:with-param name="google" select="'Lato:300,400,700'"/>
		</xsl:call-template>
		-->
	</head>
	<body>
		<script type="text/javascript">
            _editor.defer(function() {
    			if (hui.browser.windows) {
    				hui.cls.add(document.body,'windows');
    			}
    			if (hui.browser.msie) {
    				hui.cls.add(document.body,'msie');
    			}
    			if (hui.browser.webkit) {
    				hui.cls.add(document.body,'webkit');
    			}
    			if (window.devicePixelRatio==2) {
    				hui.cls.add(document.body,'retina');
    			}            
            })
		</script>
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
                        <script type="text/javascript">
                            _editor.defer(function() {new Poster();});
                        </script>
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
                                <div>
                                    <xsl:attribute name="data-editable">{"name":"about"}</xsl:attribute>
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
                                </div>
								<p class="more"><a href="{$path}om/" class="common"><span>Mere om Humanise »</span></a></p>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					<div class="contact">
						<xsl:choose>
							<xsl:when test="//p:page/p:meta/p:language='en'"><h2>Contact</h2></xsl:when>
							<xsl:otherwise><h2>Kontakt</h2></xsl:otherwise>
						</xsl:choose>
						<p class="name"><strong>Jonas Brinkmann Munk</strong></p>
						<p class="email"><a href="mailto:jonasmunk@me.com" class="common"><span>jonasmunk@me.com</span></a></p>
						<p class="phone">+45 28 77 63 65</p>
						<p class="name"><strong>Kenni Graversen</strong></p>
						<p class="email"><a href="mailto:gr@versen.dk" class="common"><span>gr@versen.dk</span></a></p>
						<p class="phone">+45 22 48 61 53</p>
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
				<script type="text/javascript">
                    _editor.defer(function() {
                        new hui.ui.SearchField({element:'search',expandedWidth:200});                    
                    })
				</script>
				<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
				<xsl:for-each select="f:frame/f:search/f:types/f:type">
				<input type="hidden" name="{@unique}" value="on"/>
				</xsl:for-each>
				<input type="submit" class="submit" value="Søg"/>
			</div>
		</form>
	</xsl:if>
</xsl:template>




</xsl:stylesheet>