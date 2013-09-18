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
 xmlns:hr="http://uri.in2isoft.com/onlinepublisher/part/horizontalrule/1.0/"
 exclude-result-prefixes="p f h n o util hr"
 >
<xsl:output encoding="UTF-8" method="xml"/>

<xsl:include href="../../basic/xslt/util.xsl"/>
<xsl:include href="front.xsl"/>

<xsl:template match="p:page">
	<xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html&gt;</xsl:text>
	<html>
		<xsl:call-template name="util:html-attributes"/>
		<head>
			<title>
				<xsl:if test="not(//p:page/@id=//p:context/p:home/@page)"> 
					<xsl:value-of select="@title"/>
					<xsl:text> - </xsl:text>
				</xsl:if>
				<xsl:value-of select="f:frame/@title"/>
			</title>
			<xsl:call-template name="util:metatags"/>
			<xsl:call-template name="util:style"/>
			<link rel="stylesheet" href="{$path}style/lottemunk{$timestamp-url}/fonts/Lotte-Munk/style.css{$timestamp-query}" type="text/css" media="screen" title="no title" charset="utf-8"/>
			<xsl:call-template name="util:style-ie6"/>
			<xsl:call-template name="util:style-ie7"/>
			<xsl:call-template name="util:style-ie8"/>
			<xsl:call-template name="util:scripts"/>
			<script src="{$path}hui{$timestamp-url}/js/hui_parallax.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<xsl:if test="//p:page/p:context/p:home[@page=//p:page/@id]">
				<script src="{$path}style/lottemunk{$timestamp-url}/js/script.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			</xsl:if>
		</head>
		<body>
			<xsl:choose>
			<xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id]">
				<xsl:attribute name="class">front</xsl:attribute>
				<xsl:call-template name="front"/>
			</xsl:when>
			<xsl:otherwise>
				<xsl:call-template name="page"/>
			</xsl:otherwise>
			</xsl:choose>
			<xsl:call-template name="util:googleanalytics"/>
		</body>
	</html>
</xsl:template>

<xsl:template name="page">
	<div class="layout">
		<header id="head">
			<h1 id="title">Lotte Munk</h1>
			<p>Skuespiller</p>
		</header>
		<nav id="navigation">
			<ul>
				<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
			</ul>				
		</nav>
		<div class="layout_content">
			<xsl:apply-templates select="p:content"/>
			<xsl:comment/>
		</div>
		<footer>
			<p><a href="http://www.humanise.dk/" title="Humanise"><span>Designet og udviklet af Humanise</span></a></p>
		</footer>
	</div>
</xsl:template>




<xsl:template match="h:hierarchy/h:item">
	<xsl:if test="not(@hidden='true')">
		<xsl:variable name="style">
			<xsl:choose>
				<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
				<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
				<xsl:otherwise>normal</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<li class="{$style}">
			<a>
				<xsl:call-template name="util:link"/>
				<span><xsl:value-of select="@title"/></span>
			</a>
		</li>
	</xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item">
	<xsl:variable name="style">
		<xsl:choose>
			<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
			<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
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
	<div class="case_links">
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









</xsl:stylesheet>