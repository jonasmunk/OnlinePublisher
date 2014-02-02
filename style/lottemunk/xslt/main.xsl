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
			<xsl:call-template name="util:style-build"/>
			<xsl:call-template name="util:style-ie6"/>
			<xsl:call-template name="util:style-ie7"/>
			<xsl:call-template name="util:style-ie8"/>
			<xsl:call-template name="util:lazy-style">
	            <xsl:with-param name="href">
					<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/><xsl:text>style/lottemunk/fonts/Lotte-Munk/style.css</xsl:text>
				</xsl:with-param>
	        </xsl:call-template>
			<xsl:call-template name="util:lazy-style">
	            <xsl:with-param name="href" select="'http://fonts.googleapis.com/css?family=Cinzel|Merriweather:400,300,300italic,400italic,700|Gloria+Hallelujah'"/>
	        </xsl:call-template>
			<xsl:call-template name="util:scripts-build"/>
			<xsl:if test="//p:page/p:context/p:home[@page=//p:page/@id]">
			<meta name="viewport" content="user-scalable=yes, width=device-width, initial-scale = 1, maximum-scale = 10, minimum-scale = 0.2"/>
			<meta name="viewport" content="user-scalable=yes, initial-scale = 1, maximum-scale = 10, minimum-scale = 0.2"/>
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
			<footer>
				<p><a href="http://www.humanise.dk/" title="Humanise" id="handmade"><span>Designet og udviklet af Humanise</span></a></p>
			</footer>
			<xsl:call-template name="util:googleanalytics"/>
		</body>
	</html>
</xsl:template>

<xsl:template name="page">
	<div class="layout">
		<header id="head">
			<h1 id="title">Lotte Munk</h1>
			<p>Skuespiller</p>
			<nav id="navigation">
				<ul>
					<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
				</ul>				
			</nav>
		</header>
		<div class="layout_content">
			<xsl:apply-templates select="p:content"/>
			<xsl:comment/>
		</div>
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
				<xsl:attribute name="data-path"><xsl:value-of select="@path"/></xsl:attribute>
				<xsl:call-template name="util:link"/>
				<span><xsl:value-of select="@title"/></span>
			</a>
		</li>
	</xsl:if>
</xsl:template>





</xsl:stylesheet>