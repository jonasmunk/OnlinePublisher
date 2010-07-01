<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 exclude-result-prefixes="p f h o n"
 >
<xsl:output encoding="UTF-8"/>

<xsl:template match="p:page">
<html>
<head>
<title><xsl:value-of select="f:frame/@title"/> :: <xsl:value-of select="@title"/></title>
<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/others/stylesheet.css"/>
<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/others/{$template}.css"/>
<link rel="icon" href="{$path}style/{$design}/grafik/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="{$path}style/{$design}/grafik/favicon.ico" type="image/x-icon" />
<script type="text/javascript"><xsl:comment>
var id=<xsl:value-of select="@id"/>;
var template='<xsl:value-of select="$template"/>';
var path='<xsl:value-of select="$path"/>';
</xsl:comment></script>
<script type="text/javascript" src="{$path}XmlWebGui/Scripts/In2iScripts.js"/>
<script type="text/javascript" src="{$path}XmlWebGui/Scripts/In2iBase.js"/>
<script type="text/javascript" src="{$path}style/basic/scripts/Authentication.js"/>
<script type="text/javascript" src="{$path}style/basic/scripts/In2iInlineImage.js"/>
</head>
<body>
<table cellpadding="0" cellspacing="0" border="0" align="center" width="780" height="100%">
<tr><td class="FrameTop"><xsl:apply-templates select="f:frame/f:links/f:top"/>
	<xsl:call-template name="languages"/>
</td></tr>
<tr><td class="FrameMiddle">
<table height="100%" cellpadding="0" cellspacing="0" align="center" width="100%">
<tr><td height="64" class="FrameBrand">
<table height="100%" cellpadding="0" cellspacing="0" align="center" width="100%"><tr><td>
<img src="{$path}style/{$design}/grafik/Logo.gif" id="FrameLogo"/>
</td><td rowspan="2" align="right"><img src="{$path}style/{$design}/grafik/Hightech.gif"/></td></tr>
<tr><td valign="bottom"><xsl:call-template name="bubble"/></td></tr></table>
</td></tr>
<xsl:if test="f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
<tr><td height="1"></td></tr>
</xsl:if>
<tr><td style="background-color: #E8EAF3;">
<table width="100%" height="100%" cellspacing="10" cellpadding="0"><tr>

<xsl:if test="f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or f:frame/f:newsblock or f:frame/f:search or //p:page/p:design/p:parameter[@key='images']/o:object">
<td class="FrameSidebar">
<xsl:if test="f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"> 
<div class="FrameMenu">
<xsl:apply-templates select="f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
</div>
</xsl:if>
<xsl:call-template name="p:gallery"/>
<xsl:call-template name="f:search"/>
<xsl:apply-templates select="f:frame/f:newsblock"/>
</td>
</xsl:if>

<td class="FrameContent">
<xsl:apply-templates select="child::*[name()='content']"/>
</td>
</tr></table>
</td></tr>
<tr><td height="25"><xsl:apply-templates select="f:frame/f:text/f:bottom"/></td></tr>
</table>
</td>
</tr>
<tr><td class="FrameBottom">
<xsl:apply-templates select="f:frame/f:links/f:bottom"/>
<a href="http://www.in2isoft.dk/" class="PoweredByLink">Powered by In2isoft OnlinePublisher</a>
</td></tr>
</table>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-7774587-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>
</xsl:template>


<xsl:template name="p:gallery">
<xsl:if test="//p:page/p:design/p:parameter[@key='images']/o:object">
<div class="FrameImageGallery"><div id="FrameImageGallery2"></div><div id="FrameImageGallery1"></div></div>
<script type="text/javascript" src="{$path}style/basic/js/In2iMiniGallery.js"/>
<script type="text/javascript">
var gallery = new In2iMiniGallery('FrameImageGallery');
<xsl:for-each select="//p:page/p:design/p:parameter[@key='images']/o:object">
gallery.addImage("<xsl:value-of select="$path"/>util/images/?id=<xsl:value-of select="@id"/>&amp;maxheight=200");
</xsl:for-each>
gallery.start();
</script>
</xsl:if>
</xsl:template>

<xsl:template name="bubble">
<table cellspacing="0" cellpadding="0" class="FrameMenuTabs"><tr>
<td><img src="{$path}style/{$design}/grafik/BarLeft.gif"/></td>	
<td class="FrameMenuTabs">
<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
</td>
<td><img src="{$path}style/{$design}/grafik/BarRight.gif"/></td>	
</tr></table>
</xsl:template>



<!--                Search                   -->

<xsl:template name="f:search">
<xsl:variable name="search_teaser">		
<xsl:choose>
	<xsl:when test="//p:page/p:meta/p:language='de'">Hier suchen!</xsl:when>
	<xsl:when test="//p:page/p:meta/p:language='en'">Search here!</xsl:when>
	<xsl:otherwise>Søg her!</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="f:frame/f:search">
<form action="." method="get" style="margin: 4px 3px 0px 0px; font-size: 12px;" accept-charset="UTF-8">
<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
<xsl:for-each select="f:frame/f:search/f:types/f:type">
<input type="hidden" name="{@unique}" value="on"/>
</xsl:for-each>
<div class="FrameSearch">
<div>
<xsl:choose>
	<xsl:when test="//p:page/p:meta/p:language='de'">Suchen</xsl:when>
	<xsl:when test="//p:page/p:meta/p:language='en'">Search</xsl:when>
	<xsl:otherwise>Søgning</xsl:otherwise>
</xsl:choose>
</div>
<input class="FrameSearchField" name="query" onfocus="searchFocus(this);" onblur="searchBlur(this);" value="{$search_teaser}"/>
<input type="submit" value="{f:frame/f:search/f:button/@title}" class="FrameSearchButton"/>
</div>
</form>
<script type="text/javascript"><xsl:comment>
function searchFocus(obj) {
	if (obj.value=='Søg her!') {
		obj.value='';
		obj.style.color='#000';
	}
	else {
		obj.select();
	}
}
function searchBlur(obj) {
	if (obj.value=='') {
		obj.style.color='';
		obj.value='Søg her!';
	}
}
</xsl:comment>
</script>
</xsl:if>
</xsl:template>

<!--             International               -->

<xsl:template name="languages">
<span class="FrameLanguages">
<!--<xsl:call-template name="language-flag"><xsl:with-param name="code" select="//p:page/p:meta/p:language"/><xsl:with-param name="class" select="'FrameFlag FrameFlagSelected'"/></xsl:call-template>-->
<xsl:for-each select="//p:page/p:context/p:home[@language and @language!=//p:page/p:meta/p:language and not(@language=//p:page/p:context/p:translation/@language)]">
<a href="?id={@page}" class="FrameFlag"><xsl:call-template name="language-flag"><xsl:with-param name="code" select="@language"/><xsl:with-param name="class" select="'FrameFlag'"/></xsl:call-template></a>
</xsl:for-each>
<xsl:for-each select="//p:page/p:context/p:translation">
<a href="?id={@page}" class="FrameFlag"><xsl:call-template name="language-flag"><xsl:with-param name="code" select="@language"/><xsl:with-param name="class" select="'FrameFlag'"/></xsl:call-template></a>
</xsl:for-each>
</span>
</xsl:template>

<xsl:template name="language-flag">
<xsl:param name="code"/>
<xsl:param name="class"/>
<xsl:choose>
<xsl:when test="$code='en'"><img src="{$path}style/basic/graphics/flags/gb.gif" class="{$class}" alt="" width="16" height="11" border="0"/></xsl:when>
<xsl:when test="$code='da'"><img src="{$path}style/basic/graphics/flags/dk.gif" class="{$class}" alt="" width="16" height="11" border="0"/></xsl:when>
<xsl:when test="$code='sv'"><img src="{$path}style/basic/graphics/flags/se.gif" class="{$class}" alt="" width="16" height="11" border="0"/></xsl:when>
<xsl:otherwise><img src="{$path}style/basic/graphics/flags/{$code}.gif" class="{$class}" alt="" width="16" height="11" border="0"/></xsl:otherwise>
</xsl:choose>
</xsl:template>



<!--               Hierarchy                  -->

<xsl:template match="h:hierarchy/h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>Selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>Hilited</xsl:text></xsl:when>
<xsl:otherwise>Standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>

<!--<xsl:if test="position()>1">&#183;</xsl:if>-->
<a class="FrameMenuTab FrameMenuTab{$style}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>Selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>Hilited</xsl:text></xsl:when>
<xsl:otherwise>Standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<a class="FrameMenu FrameMenu{$style}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id">
<div style="padding-left: 10px;">
<xsl:apply-templates/>
</div>
</xsl:if>
</xsl:template>




<!--            Links              -->


<xsl:template match="f:links/f:top">
<span class="FrameLinksTop">
<xsl:apply-templates/>
</span>
</xsl:template>

<xsl:template match="f:links/f:bottom">
<span class="FrameLinksBottom">
<xsl:apply-templates/>
</span>
</xsl:template>

<xsl:template match="f:link">
<xsl:if test="position()>1"> | </xsl:if>
<a title="{@alternative}" class="FrameLink">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>




<!--            Text              -->





<xsl:template match="f:text/f:bottom">
<span class="FrameTextBottom">
<xsl:apply-templates/>
</span>
</xsl:template>


<xsl:template match="f:text/f:bottom/f:link">
<a title="{@alternative}" class="FrameLink">
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
<div class="FrameNewsDescription">
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
<span class="FrameNewsDate"><xsl:value-of select="number(@day)"/>/<xsl:value-of select="number(@month)"/>/<xsl:value-of select="substring(@year,3,2)"/></span><xsl:text>: </xsl:text>
</xsl:template>

<xsl:template match="f:newsblock//o:link">
<xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
<a title="{@alternative}" class="FrameNewsLink">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>


<!-- support templates -->


<xsl:template name="link">
<xsl:attribute name="title"><xsl:value-of select="@alternative"/></xsl:attribute>
<xsl:choose>
<xsl:when test="@page">
<xsl:attribute name="href">?id=<xsl:value-of select="@page"/></xsl:attribute>
</xsl:when>
<xsl:when test="@page-reference">
<xsl:attribute name="href">?id=<xsl:value-of select="@page-reference"/></xsl:attribute>
</xsl:when>
<xsl:when test="@url">
<xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
</xsl:when>
<xsl:when test="@file">
<xsl:attribute name="href">?file=<xsl:value-of select="@file"/></xsl:attribute>
</xsl:when>
<xsl:when test="@email">
<xsl:attribute name="href">mailto:<xsl:value-of select="@email"/></xsl:attribute>
</xsl:when>
</xsl:choose>
<xsl:if test="@target and @target!='_self'">
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:if>
</xsl:template>


</xsl:stylesheet>