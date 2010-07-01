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
	<xsl:comment><![CDATA[[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>style/<xsl:value-of select="$design"/><![CDATA[/msie6/stylesheet.css"> </link>
	<![endif]]]></xsl:comment>
	<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/others/{$template}.css"/>
	<script type="text/javascript" src="{$path}XmlWebGui/Scripts/In2iScripts.js"><xsl:text> </xsl:text></script>
	<script src="{$path}style/basic/js/OnlinePublisher.js"><xsl:text> </xsl:text></script>
	<script type="text/javascript"><xsl:comment>
		OP.Page.id=<xsl:value-of select="@id"/>;
		OP.Page.template='<xsl:value-of select="$template"/>';
		OP.Page.path='<xsl:value-of select="$path"/>';
	</xsl:comment></script>
	<xsl:if test="not($preview='true')">
		<script type="text/javascript" src="{$path}style/basic/scripts/Authentication.js"><xsl:text> </xsl:text></script>
	</xsl:if>
	<script type="text/javascript" src="{$path}style/basic/scripts/In2iInlineImage.js"><xsl:text> </xsl:text></script>
</head>
<body>
	<xsl:if test="p:design/p:parameter[@key='variant']!=''">
		<xsl:attribute name="class"><xsl:value-of select="p:design/p:parameter[@key='variant']"/></xsl:attribute>
	</xsl:if>
<xsl:apply-templates select="f:frame/f:links/f:top"/>
<div class="top_bg">
	<div class="top_base">
		<div class="top_base">
			<div class="top"><div class="left"><div class="right">&#160;</div></div></div>
			<div class="brand">
				<div class="logo">&#160;</div>
				<div class="logo_text">&#160;</div>
				<xsl:call-template name="search"/>
			</div>
		</div>
	</div>
</div>
<div class="bar_bg">
	<div class="bar_base">
		<div class="bar_base">
			<div class="bar">
				<ul class="navigation"><xsl:apply-templates select="f:frame/h:hierarchy/h:item"/></ul>
			</div>
		</div>
	</div>
</div>
<div class="middle">
	<div class="base_middle">
		<div class="base_middle">
			<div class="body">
				<xsl:apply-templates select="p:content"/>
			</div>
		</div>
	</div>
</div>
<div class="end">
	<div class="left">
		<div class="right">&#160;</div>
	</div>
</div>
<div class="footer">
	<xsl:apply-templates select="f:frame/f:text/f:bottom"/>
	<xsl:apply-templates select="f:frame/f:links/f:bottom"/>
</div>
<div class="preload">
	<img src="{$path}style/{$design}/graphics/BallBadmintonHover.gif"/>
	<img src="{$path}style/{$design}/graphics/BallFloorballHover.gif"/>
	<img src="{$path}style/{$design}/graphics/BallGymnasticsHover.gif"/>
	<img src="{$path}style/{$design}/graphics/BallHandballHover.gif"/>
	<img src="{$path}style/{$design}/graphics/BallSoccerHover.gif"/>
	<img src="{$path}style/{$design}/graphics/BallTennisHover.gif"/>
	<img src="{$path}style/{$design}/graphics/BallVolleyballHover.gif"/>
</div>
</body>
</html>
</xsl:template>


<xsl:template match="p:content">
<div>
<xsl:choose>
<xsl:when test="../f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
<xsl:attribute name="class">content content_sidebar</xsl:attribute>
</xsl:when>
<xsl:otherwise>
<xsl:attribute name="class">content</xsl:attribute>
</xsl:otherwise>
</xsl:choose>
<div class="sidebar">
<xsl:call-template name="secondlevel"/>
<xsl:apply-templates select="../f:frame/f:newsblock"/>&#160;
</div>
<div class="inner_content">
<xsl:apply-templates/>
<br/>
</div>
<div style="clear: both; font-size: 1px;">&#160;</div>
</div>
</xsl:template>


<!--            User status                 -->



<xsl:template match="f:userstatus">
	<xsl:choose>
		<xsl:when test="$userid>0">
		<span class="userstatus">Bruger: <strong><xsl:value-of select="$usertitle"/></strong></span>
		<xsl:text> · </xsl:text>
		<a href="./?id={@page}&amp;logout=true&amp;page={//p:page/@id}" class="common">Log ud</a>
		</xsl:when>
		<xsl:otherwise>
		<a href="./?id={@page}&amp;page={//p:page/@id}" class="common">Log ind</a>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>



<xsl:template match="h:hierarchy/h:item">
<xsl:if test="not(@hidden='true')">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
<xsl:otherwise></xsl:otherwise>
</xsl:choose>
</xsl:variable>
<li class="{$style}">
<a>
<xsl:call-template name="link"/>
<xsl:choose>
<xsl:when test="@title='Forside' or @title='Fodbold'">
<xsl:attribute name="class">icon soccer</xsl:attribute>
</xsl:when>
<xsl:when test="@title='OnlinePublisher' or @title='Håndbold'">
<xsl:attribute name="class">icon handball</xsl:attribute>
</xsl:when>
<xsl:when test="@title='Skabeloner' or @title='Badminton'">
<xsl:attribute name="class">icon badminton</xsl:attribute>
</xsl:when>
<xsl:when test="@title='Test cases' or @title='Tennis'">
<xsl:attribute name="class">icon tennis</xsl:attribute>
</xsl:when>
<xsl:when test="@title='Referencer' or @title='Volleyball'">
<xsl:attribute name="class">icon volleyball</xsl:attribute>
</xsl:when>
<xsl:when test="@title='Floorball'">
<xsl:attribute name="class">icon floorball</xsl:attribute>
</xsl:when>
<xsl:when test="@title='Gymnastik'">
<xsl:attribute name="class">icon gymnastics</xsl:attribute>
</xsl:when>
<xsl:otherwise>
<xsl:attribute name="class">text</xsl:attribute>
</xsl:otherwise>
</xsl:choose>
<span><xsl:value-of select="@title"/></span>
</a>
</li>
</xsl:if>
</xsl:template>

<xsl:template name="secondlevel">
<xsl:if test="../f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
<div class="side_block">
<xsl:apply-templates select="../f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
</div>
</xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>hilited</xsl:text></xsl:when>
<xsl:otherwise>standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<a class="navigation navigation_{$style}">
<xsl:call-template name="link"/>
<span><xsl:value-of select="@title"/></span>
</a>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
<div style="padding-left: 12px; padding-bottom: 5px;"><xsl:apply-templates select="h:item"/></div>
</xsl:if>
</xsl:if>
</xsl:template>

<xsl:template match="h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>hilited</xsl:text></xsl:when>
<xsl:otherwise>standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<a class="navigation navigation_{$style} navigation_sub">
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
<div class="links_top">
<div>
<xsl:apply-templates select="//f:frame/f:userstatus"/> · 
<a title="Udskriv siden" class="common" href="?id={//p:page/@id}&amp;print=true">Udskriv</a>
<xsl:apply-templates/>
</div>
</div>
</xsl:template>

<xsl:template match="f:links/f:bottom">
<span class="links_bottom">
<xsl:apply-templates/>
<xsl:if test="f:link"><span>&#160;&#183;&#160;</span></xsl:if>
<a title="XHTML 1.1" class="common" href="http://validator.w3.org/check?uri=referer">XHTML 1.1</a>
</span>
</xsl:template>

<xsl:template match="f:links/f:bottom/f:link">
<xsl:if test="position()>1"><span>&#160;&#183;&#160;</span></xsl:if>
<a title="{@alternative}" class="common">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="f:links/f:top/f:link">
<span>&#160;&#183;&#160;</span>
<a title="{@alternative}" class="common">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>



<!--            Text              -->





<xsl:template match="f:text/f:bottom">
<span class="text">
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
<div class="side_block">
<strong class="title"><xsl:value-of select="@title"/></strong>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="f:newsblock//o:object">
<div class="news">
<div class="title">
<xsl:value-of select="o:title"/>
</div>
<div class="note">
<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
<xsl:apply-templates select="o:note"/>
</div>
<xsl:if test="o:links">
<div class="links">
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
<span class="date"> <xsl:value-of select="@day"/>/<xsl:value-of select="@month"/><!--/<xsl:value-of select="substring(@year,3,2)"/>--></span>
</xsl:template>

<xsl:template match="f:newsblock//o:link">
<xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
<a title="{@alternative}" class="common link">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>



<!--                  Search                     -->


<xsl:template name="search">
<xsl:if test="f:frame/f:search">
<form action="." method="get" style="float: right; font-size: 12px;" class="search">
<div>
<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
<xsl:for-each select="f:frame/f:search/f:types/f:type">
<input type="hidden" name="{@unique}" value="on"/>
</xsl:for-each>
</div>
<div>
<input class="field" name="query" id="searchfield"></input>
<input type="submit" class="button" value="Søg"/>
</div>
</form>
<script type="text/javascript"><xsl:comment>
new op.SearchField({element:'searchfield',placeholder:'Søg her!'});
</xsl:comment>
</script>
</xsl:if>
</xsl:template>



<!--                 Support templates                  -->



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
<xsl:attribute name="href">?file=<xsl:value-of select="@file"/><xsl:if test="@target='_download'">&amp;download=true</xsl:if></xsl:attribute>
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