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

<xsl:template match="p:page">
<html>
<head>
<title><xsl:value-of select="f:frame/@title"/> :: <xsl:value-of select="@title"/></title>
<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/others/stylesheet.css"/>
<xsl:comment><![CDATA[[if IE]>
<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>style/<xsl:value-of select="$design"/><![CDATA[/msie6/stylesheet.css"> </link>
<![endif]]]></xsl:comment>
<link rel="stylesheet" type="text/css" id="{$template}Stylesheet" href="{$path}style/{$design}/others/{$template}.css"/>
<script type="text/javascript"><xsl:comment>
var id=<xsl:value-of select="@id"/>;
var template='<xsl:value-of select="$template"/>';
var path='<xsl:value-of select="$path"/>';
</xsl:comment></script>
<script type="text/javascript" src="{$path}XmlWebGui/Scripts/In2iBase.js"><xsl:text> </xsl:text></script>
<script type="text/javascript" src="{$path}XmlWebGui/Scripts/In2iScripts.js"><xsl:text> </xsl:text></script>
<script type="text/javascript" src="{$path}style/basic/js/OnlinePublisher.js"><xsl:text> </xsl:text></script>
<script type="text/javascript" src="{$path}style/basic/scripts/Authentication.js"><xsl:text> </xsl:text></script>
<script type="text/javascript" src="{$path}style/basic/scripts/In2iInlineImage.js"><xsl:text> </xsl:text></script>
</head>
<body>
<div class="Frame">
<div class="FrameTop"><xsl:call-template name="logo"/><xsl:call-template name="toplevel"/></div>
<div class="FrameSub"><xsl:apply-templates select="f:frame/f:links/f:top"/><span style="float: left;">&#160;</span><xsl:call-template name="secondlevel"/></div>
<div class="FrameMiddle">
<div style="float: right;">
<xsl:call-template name="sidebar"/>
</div>
<div class="FrameContent"><xsl:apply-templates select="child::*[name()='content']"/>&#160;</div>
<div style="clear: both"/>
</div>
<div class="FrameFooter"><xsl:call-template name="languages"/>
<a href="http://www.in2isoft.dk/" class="FrameLinkBottom" style="float: left;">Powered by In2iSoft OnlinePublisher</a>
<xsl:apply-templates select="f:frame/f:links/f:bottom"/></div>
</div>
</body>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-420000-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>
</html>
</xsl:template>

<xsl:template name="logo">
<span class="FrameLogo">
<xsl:choose>
<xsl:when test="//p:page/p:design/p:parameter[@key='titletext']">
<xsl:value-of select="//p:page/p:design/p:parameter[@key='titletext']"/>
</xsl:when>
<xsl:otherwise>
JonasMunk.dk
</xsl:otherwise>
</xsl:choose>
</span>	
</xsl:template>

<xsl:template name="toplevel">
<ul class="FrameTopMenu">
<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
</ul>
</xsl:template>




<!--           Language              -->




<xsl:template name="languages">
<span class="FrameFlag">
<xsl:call-template name="language-flag"><xsl:with-param name="code" select="//p:page/p:meta/p:language"/><xsl:with-param name="class" select="'FrameFlag FrameFlagSelected'"/></xsl:call-template>
<xsl:for-each select="//p:page/p:context/p:home[@language and @language!=//p:page/p:meta/p:language and not(@language=//p:page/p:context/p:translation/@language)]">
<a href="?id={@page}" title="home:{@language}" class="FrameFlag"><xsl:call-template name="language-flag"><xsl:with-param name="code" select="@language"/><xsl:with-param name="class" select="'FrameFlag'"/></xsl:call-template></a>
</xsl:for-each>
<xsl:for-each select="//p:page/p:context/p:translation">
<a href="?id={@page}" title="trans:{@language}" class="FrameFlag"><xsl:call-template name="language-flag"><xsl:with-param name="code" select="@language"/><xsl:with-param name="class" select="'FrameFlag'"/></xsl:call-template></a>
</xsl:for-each>
</span>
</xsl:template>

<xsl:template name="language-flag">
<xsl:param name="code"/>
<xsl:param name="class"/>
<xsl:choose>
<xsl:when test="$code='en'"><img src="{$path}style/basic/graphics/flags/gb.gif" class="{$class}" alt=""/></xsl:when>
<xsl:when test="$code='da'"><img src="{$path}style/basic/graphics/flags/dk.gif" class="{$class}" alt=""/></xsl:when>
<xsl:otherwise><xsl:value-of select="$code"/></xsl:otherwise>
</xsl:choose>
</xsl:template>



<!--           Hierarchy              -->



<xsl:template match="h:hierarchy/h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>Selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>Hilited</xsl:text></xsl:when>
<xsl:otherwise>Standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<li class="FrameTopMenu">
<a class="FrameTopMenu FrameTopMenu{$style}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</li>
</xsl:template>

<xsl:template name="secondlevel">
<xsl:if test="f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
<ul class="FrameSecondMenu">
<xsl:apply-templates select="f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
</ul>
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
<li class="FrameSecondMenu">
<a class="FrameSecondMenu FrameSecondMenu{$style}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</li>
</xsl:template>



<!--              Menu                -->



<xsl:template name="sidebar">
<xsl:if test="f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or f:frame/f:newsblock">
<div class="FrameSidebar">
<xsl:if test="f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"> 
<div class="FrameMenuTitle">Navigation</div>
<ul class="FrameMenu">
<xsl:apply-templates select="f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
</ul>
</xsl:if>
<xsl:call-template name="search"/>
<xsl:apply-templates select="f:frame/f:newsblock"/>
</div>
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
<li>
<a class="FrameMenu FrameMenu{$style}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
<ul class="FrameMenuSub"><xsl:apply-templates/></ul>
</xsl:if>
</li>
</xsl:template>




<!--            Search             -->



<xsl:template name="search">
<xsl:if test="/p:page/f:frame/f:search">
<div class="FrameSearchTitle">S&#248;gning</div>
<div class="FrameSearchBody">
<form action="." method="get" style="margin: 0;">
<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
<xsl:for-each select="f:frame/f:search/f:types/f:type">
<input type="hidden" name="{@unique}" value="on"/>
</xsl:for-each>
<input class="FrameSearchField" name="query" id="searchfield" onfocus="searchFocus(this);" onblur="searchBlur(this);" value="S&#248;g her!"></input>
<input class="FrameSearchButton" type="image" src="{$path}style/{$design}/grafik/SearchButton.gif"/>
</form>
</div>
<script type="text/javascript"><xsl:comment>
new op.SearchField({element:'searchfield'});
</xsl:comment>
</script>
</xsl:if>
</xsl:template>



<!--            Links              -->



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
<xsl:attribute name="href">files/<xsl:value-of select="@filename"/></xsl:attribute>
</xsl:when>
<xsl:when test="@email">
<xsl:attribute name="href">mailto:<xsl:value-of select="@email"/></xsl:attribute>
</xsl:when>
</xsl:choose>
</xsl:template>

<xsl:template match="f:links/f:top">
<xsl:if test="f:link">
<span class="FrameLinksTop">
<xsl:apply-templates/>
</span>
</xsl:if>
</xsl:template>

<xsl:template match="f:links/f:bottom">
<span class="FrameLinksBottom">
<xsl:apply-templates/>
</span>
</xsl:template>

<xsl:template match="f:top/f:link">
<xsl:if test="position()>1"> &#183; </xsl:if>
<a title="{@alternative}" class="FrameLinkTop">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="f:bottom/f:link">
<xsl:if test="position()>1"> &#183; </xsl:if>
<a title="{@alternative}" class="FrameLinkBottom">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>




<!--            News              -->


<xsl:template match="f:newsblock">
<div class="FrameNewsBlock">
<div class="FrameNewsBlockTitle">
<xsl:value-of select="@title"/>
</div>
<div class="FrameNewsBlockBody">
<xsl:apply-templates/>
</div>
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

<xsl:template match="f:newsblock//o:break">
<br/>
</xsl:template>

<xsl:template match="f:newsblock//n:startdate">
<span class="FrameNewsDate"><xsl:value-of select="number(@day)"/>/<xsl:value-of select="number(@month)"/>/<xsl:value-of select="substring(@year,3,2)"/></span>
</xsl:template>

<xsl:template match="f:newsblock//o:link">
<xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
<a title="{@alternative}" class="FrameNewsLink">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

</xsl:stylesheet>