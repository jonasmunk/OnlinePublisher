<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 exclude-result-prefixes="p f h n o"
 >
<xsl:output encoding="UTF-8" method="xml" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:param name="header-font-color">#00f</xsl:param>

<xsl:template match="p:page">
<html>
<head>
<title><xsl:value-of select="@title"/> :: <xsl:value-of select="f:frame/@title"/></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
<meta name="robots" content="index,follow"></meta>
<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/others/stylesheet.css"/>
<xsl:comment><![CDATA[[if IE]>
<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>style/<xsl:value-of select="$design"/><![CDATA[/msie6/stylesheet.css"> </link>
<![endif]]]></xsl:comment>
<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/others/{$template}.css"/>
<link rel="icon" href="{$path}style/{$design}/grafik/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="{$path}style/{$design}/grafik/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="{$path}style/basic/js/OnlinePublisher.js"><xsl:text> </xsl:text></script>
<script type="text/javascript"><xsl:comment>
OP.Page.id=<xsl:value-of select="@id"/>;
OP.Page.template='<xsl:value-of select="$template"/>';
OP.Page.path='<xsl:value-of select="$path"/>';
</xsl:comment></script>
<script type="text/javascript" src="{$path}XmlWebGui/Scripts/In2iScripts.js"><xsl:text> </xsl:text></script>
<script type="text/javascript" src="{$path}XmlWebGui/Scripts/In2iBase.js"><xsl:text> </xsl:text></script>
<script type="text/javascript" src="{$path}style/basic/scripts/In2iInlineImage.js"><xsl:text> </xsl:text></script>
<xsl:if test="not($preview='true')">
<script type="text/javascript" src="{$path}style/basic/scripts/Authentication.js"><xsl:text> </xsl:text></script>
</xsl:if>
<script type="text/javascript">
<xsl:comment>
	N2i.Event.addLoadListener(
		function() {
			N2i.Event.addListener(window,'resize',resizer);
			resizer()
		}
	);
	function resizer() {
		var width = N2i.Window.getInnerWidth();
		if (width&lt;620) {
			document.body.className='Small Tiny';
		} else if (width&lt;800) {
			document.body.className='Small';
		} else {
			document.body.className='';
		}
	}
</xsl:comment>
</script>
</head>
<body>
<table cellpadding="0" cellspacing="0" class="FrameBase">
<tr><td class="FrameLeftShadow"><div/></td>
<td style="background: #fff; vertical-align: top;">
<table cellpadding="0" cellspacing="0" style="width: 100%; height: 100%; margin: 0 auto;">
<tr><td class="FrameTopBar"><img src="{$path}style/{$design}/grafik/TopLogo.gif" style="float: left; margin-left: 3px;" alt="In2iSoft"/>
<xsl:apply-templates select="f:frame/f:links/f:top"/></td></tr>
<tr><td style="height: 2px;"></td></tr>
<xsl:if test="not(p:design/p:parameter[@name='show-header']/text()='false')">
<tr><td class="BrandBar"><div><xsl:apply-templates select="f:frame/f:userstatus"/></div></td></tr>
</xsl:if>
<tr><td class="FrameMenuBar">
<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/><xsl:call-template name="search"/>
</td></tr>
<tr><td style="background: #fff;">
<table style="width: 100%; height: 100%;"><tr>
<td class="FrameContent">
<xsl:apply-templates select="child::*[name()='content']"/>
</td>
<xsl:if test="f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or f:frame/f:newsblock">
<td class="FrameSidebar">
<xsl:call-template name="secondlevel"/>
<xsl:apply-templates select="f:frame/f:newsblock"/>
</td>
</xsl:if>
</tr></table>
</td></tr>
</table>
</td>
<td class="FrameRightShadow"><div/></td>
</tr>
<tr>
<td class="FrameLeftShadow"></td>
<td class="FrameBottomBar"><xsl:apply-templates select="f:frame/f:text/f:bottom"/><xsl:apply-templates select="f:frame/f:links/f:bottom"/></td>
<td class="FrameRightShadow"></td>
</tr>
</table>
</body>
</html>
</xsl:template>



<!--            User status                 -->



<xsl:template match="f:userstatus">
<span class="FrameUserStatus">
	<xsl:choose>
	<xsl:when test="$userid>0">
	<span><strong><xsl:value-of select="$usertitle"/></strong></span>
	<a href="./?id={@page}&amp;logout=true">log ud</a>
	</xsl:when>
	<xsl:otherwise>
	<span>Ikke logget ind</span>
	<a href="./?id={@page}&amp;page={//p:page/@id}">log ind</a>
	</xsl:otherwise>
	</xsl:choose>
</span>
</xsl:template>



<xsl:template match="h:hierarchy/h:item">
<xsl:if test="not(@hidden='true')">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>Selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>Hilited</xsl:text></xsl:when>
<xsl:otherwise>Standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>

<a class="FrameMenuBar{$style}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:if>
</xsl:template>

<xsl:template name="secondlevel">
<xsl:if test="f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
<div class="FrameNavigation">
<div class="FrameNavigationTitle">Navigation</div>
<xsl:apply-templates select="f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
</div>
</xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>Selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>Hilited</xsl:text></xsl:when>
<xsl:otherwise>Standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<a class="FrameNavigation FrameNavigation{$style}">
<xsl:call-template name="link"/>
<b>&gt; </b><span><xsl:value-of select="@title"/></span>
</a>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
<div style="padding-left: 12px; padding-bottom: 5px;"><xsl:apply-templates select="h:item"/></div>
</xsl:if>
</xsl:if>
</xsl:template>

<xsl:template match="h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>Selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>Hilited</xsl:text></xsl:when>
<xsl:otherwise>Standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<a class="FrameNavigation FrameNavigation{$style} FrameNavigationSub">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
<div style="padding-left: 12px;">
<xsl:apply-templates/>
</div>
</xsl:if>
</xsl:if>
</xsl:template>





<!--            Links              -->


<xsl:template match="f:links/f:top">
<span class="FrameLinksTop">
<a title="Udskriv siden" class="FrameLink" href="?id={//p:page/@id}&amp;print=true"><img src="{$path}style/{$design}/grafik/Print.gif" alt="Udskrift"/>Udskriv</a>
<xsl:apply-templates/>
</span>
</xsl:template>

<xsl:template match="f:links/f:bottom">
<span class="FrameLinksBottom">
<xsl:apply-templates/>
<xsl:if test="f:link"><span>&#160;|&#160;</span></xsl:if>
<a title="XHTML 1.1" class="FrameLink" href="http://validator.w3.org/check?uri=referer">XHTML 1.1</a>
<!--
<xsl:if test="f:link"><span>&#160;|&#160;</span></xsl:if>
<a title="Vis som PDF" class="FrameLink" href="{$path}util/pages/preview/?id={//p:page/@id}&amp;format=pdf&amp;print=true">Vis som PDF</a>
-->
</span>
</xsl:template>

<xsl:template match="f:links/f:bottom/f:link">
<xsl:if test="position()>1"><span>&#160;|&#160;</span></xsl:if>
<a title="{@alternative}" class="FrameLink">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="f:links/f:top/f:link">
<span>&#160;|&#160;</span>
<a title="{@alternative}" class="FrameLink">
<xsl:call-template name="link"/>
<xsl:if test="@title='Søgning'"><img src="{$path}style/{$design}/grafik/Search.gif" alt="Søgning"/></xsl:if>
<xsl:if test="@title='Kontakt'"><img src="{$path}style/{$design}/grafik/Contact.gif" alt="Kontakt"/></xsl:if>
<xsl:if test="@title='Oversigt'"><img src="{$path}style/{$design}/grafik/Sitemap.gif" alt="Oversigt"/></xsl:if>
<xsl:value-of select="@title"/>
</a>
</xsl:template>



<!--            Text              -->





<xsl:template match="f:text/f:bottom">
<span class="FrameTextBottom">&#160;
<xsl:apply-templates/>
</span>
</xsl:template>


<xsl:template match="f:text/f:bottom/f:link">
<a title="{@alternative}" class="common">
<xsl:call-template name="link"/>
<xsl:apply-templates/>
</a>
</xsl:template>




<!--            News              -->





<xsl:template match="f:newsblock">
<div class="NewsBlock">
<div class="NewsBlockTitle">
<xsl:value-of select="@title"/>
</div>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="f:newsblock//o:object">
<div class="FrameNews">
<div class="FrameNewsTitle">
<xsl:value-of select="o:title"/>
</div>
<div class="FrameNewsNote">
<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
<xsl:apply-templates select="o:note"/>
</div>
<xsl:if test="o:links">
<div class="FrameNewsLinks">
<xsl:apply-templates select="o:links/o:link"/>
</div>
</xsl:if>
</div>
</xsl:template>

<xsl:template match="f:newsblock//o:note">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="f:newsblock//o:break">
<br/>
</xsl:template>

<xsl:template match="f:newsblock//n:startdate">
<span class="FrameNewsDate"> <xsl:value-of select="@day"/>/<xsl:value-of select="@month"/><!--/<xsl:value-of select="substring(@year,3,2)"/>--></span>
</xsl:template>

<xsl:template match="f:newsblock//o:link">
<xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
<a title="{@alternative}" class="common FrameNewsLink">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>



<!--                  Search                     -->


<xsl:template name="search">
<xsl:if test="f:frame/f:search">
<form action="." method="get" style="float: right; font-size: 12px;" class="FrameSearch" accept-charset="UTF-8">
<div>
<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
<xsl:for-each select="f:frame/f:search/f:types/f:type">
<input type="hidden" name="{@unique}" value="on"/>
</xsl:for-each>
</div>
<div>
<input class="FrameSearchField" name="query" id="searchfield"></input>
<input type="submit" class="FrameSearchButton" value="Søg"/>
</div>
</form>
<script type="text/javascript"><xsl:comment>
new op.SearchField({element:'searchfield',placeholder:'Søg her'});
</xsl:comment>
</script>
</xsl:if>
</xsl:template>



<!--                 Support templates                  -->



<xsl:template name="link">
<xsl:attribute name="title"><xsl:value-of select="@alternative"/></xsl:attribute>
<xsl:choose>
<xsl:when test="@path and $preview='false'">
<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/><xsl:value-of select="@path"/></xsl:attribute>
</xsl:when>
<xsl:when test="@page">
<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page"/></xsl:attribute>
</xsl:when>
<xsl:when test="@page-reference">
<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page-reference"/></xsl:attribute>
</xsl:when>
<xsl:when test="@url">
<xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
</xsl:when>
<xsl:when test="@file">
<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?file=<xsl:value-of select="@file"/><xsl:if test="@target='_download'">&amp;download=true</xsl:if></xsl:attribute>
</xsl:when>
<xsl:when test="@email">
<xsl:attribute name="href">mailto:<xsl:value-of select="@email"/></xsl:attribute>
</xsl:when>
</xsl:choose>
<xsl:choose>
<xsl:when test="@target='_blank'">
<xsl:attribute name="onclick">try {window.open(this.getAttribute('href')); return false;} catch (igonre) {}</xsl:attribute>
</xsl:when>
<xsl:when test="@target and @target!='_self' and @target!='_download'">
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:when>
</xsl:choose>
</xsl:template>

</xsl:stylesheet>