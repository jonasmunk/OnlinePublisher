<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p f h n o util"
 >
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:include href="util.xsl"/>

<xsl:template match="p:page">
<html>
	<xsl:attribute name="xmlns">http://www.w3.org/1999/xhtml</xsl:attribute>
	<head>
		<title><xsl:value-of select="@title"/> :: <xsl:value-of select="f:frame/@title"/></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
		<meta name="robots" content="index,follow"></meta>
		<xsl:call-template name="oo-script"/>
		<link href='http://fonts.googleapis.com/css?family=Molengo' rel='stylesheet' type='text/css'/>
		<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/stylesheet.css"/>
		<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/{$template}.css"/>
	</head>
	<body>
		<div class="layout">
			<div class="layout_top">
				<xsl:apply-templates select="f:frame/f:links/f:top"/>
				<xsl:call-template name="util:userstatus"/>
			</div>
			<div class="layout_navigation">
				<xsl:call-template name="util:hierarchy-first-level"/>
			</div>
			<div class="layout_middle">
				<xsl:call-template name="search"/>
				<xsl:apply-templates select="f:frame/f:userstatus"/>
				<div class="layout_left">
					<xsl:call-template name="util:hierarchy-after-first-level"/>
					<xsl:comment/>
					&#160;
				</div>
				<div class="layout_center">
					<xsl:apply-templates select="p:content"/>
				</div>
				<div class="layout_right">
					<xsl:apply-templates select="f:frame/f:newsblock"/>
				</div>
			</div>
			<div class="layout_bottom">
				<xsl:apply-templates select="f:frame/f:text/f:bottom"/>
				<xsl:apply-templates select="f:frame/f:links/f:bottom"/>
			</div>
		</div>
	</body>
</html>
</xsl:template>


<!--            Links              -->


<xsl:template match="f:links/f:top">
<span>
<a title="Udskriv siden" href="{$page-path}print=true">Udskriv</a>
<xsl:apply-templates/>
</span>
</xsl:template>

<xsl:template match="f:links/f:bottom">
<span>
<xsl:apply-templates/>
<xsl:if test="f:link"><span>&#160;|&#160;</span></xsl:if>
<a title="XHTML 1.1" href="http://validator.w3.org/check?uri=referer">XHTML 1.1</a>
</span>
</xsl:template>

<xsl:template match="f:links/f:bottom/f:link">
<xsl:if test="position()>1"><span>&#160;|&#160;</span></xsl:if>
<a title="{@alternative}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="f:links/f:top/f:link">
<span>&#160;|&#160;</span>
<a title="{@alternative}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>



<!--            Text              -->





<xsl:template match="f:text/f:bottom">
<xsl:apply-templates/>
<xsl:text> </xsl:text>
</xsl:template>


<xsl:template match="f:text/f:bottom/f:link">
<a title="{@alternative}">
<xsl:call-template name="link"/>
<xsl:apply-templates/>
</a>
</xsl:template>




<!--            News              -->





<xsl:template match="f:newsblock">
	<div class="layout_news">
		<h2>
			<xsl:value-of select="@title"/>
		</h2>
		<xsl:if test="o:object">
			<ul>
				<xsl:apply-templates/>
			</ul>
		</xsl:if>
	</div>
</xsl:template>

<xsl:template match="f:newsblock//o:object">
	<li>
		<h3><xsl:value-of select="o:title"/></h3>
		<p>
			<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
			<xsl:apply-templates select="o:note"/>
		</p>
		<xsl:if test="o:links">
			<div>
			<xsl:apply-templates select="o:links/o:link"/>
			</div>
		</xsl:if>
	</li>
</xsl:template>

<xsl:template match="f:newsblock//o:note">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="f:newsblock//o:break">
<br/>
</xsl:template>

<xsl:template match="f:newsblock//n:startdate">
<em><xsl:value-of select="@day"/>/<xsl:value-of select="@month"/></em><xsl:text>: </xsl:text>
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
<form action="." method="get" accept-charset="UTF-8">
<div>
<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
<xsl:for-each select="f:frame/f:search/f:types/f:type">
<input type="hidden" name="{@unique}" value="on"/>
</xsl:for-each>
</div>
<div>
<input name="query"></input>
<input type="submit" value="S&#248;g"/>
</div>
</form>
</xsl:if>
</xsl:template>

</xsl:stylesheet>