<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p f h n o"
 >
<xsl:output encoding="UTF-8" method="xml" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:include href="../../basic/xslt/util.xsl"/>

<xsl:template match="p:page">
<html xmlns="http://www.w3.org/1999/xhtml">
	<xsl:call-template name="util:html-attributes"/>
<head>
	<xsl:call-template name="util:metatags"/>
	<title><xsl:value-of select="@title"/> : <xsl:value-of select="f:frame/@title"/></title>
	<xsl:call-template name="util:style"/>
	<xsl:call-template name="util:scripts"/>
	<xsl:if test="@id=1">
		<script type="text/javascript" src="{$path}style/terrazzo/js/Poster.js"><xsl:text> </xsl:text></script>
	</xsl:if>
</head>
<body>
	<div class="base">
		<div class="inner_base">
				<div class="brand">
					<xsl:comment/>
					<xsl:call-template name="search"/>
				</div>
				<div class="bar">
					<ul class="navigation"><xsl:apply-templates select="f:frame/h:hierarchy/h:item"/></ul>
				</div>
				<xsl:if test="@id=1">
					<div class="images">
						<div id="image-1"><div class="image-1"><xsl:comment/></div></div>
						<div id="image-2"><div class="image-2"><xsl:comment/></div></div>
						<div id="image-3"><div class="image-3"><xsl:comment/></div></div>
						<div id="image-4"><div class="image-4"><xsl:comment/></div></div>
					</div>
				</xsl:if>
				<xsl:apply-templates select="p:content"/>
			<div class="clear"><xsl:comment/></div>
		</div>
	</div>
	<div class="footer">
		<xsl:apply-templates select="f:frame/f:text/f:bottom"/>
		<xsl:apply-templates select="f:frame/f:links/f:bottom"/>
	</div>
</body>
</html>
</xsl:template>


<xsl:template match="p:content">
<div class="content content_sidebar">
<div class="sidebar">
	<xsl:call-template name="secondlevel"/>
	<xsl:apply-templates select="../f:frame/f:newsblock"/>
	<div class="info">
		<h2><em>Terrazzo</em>Forum</h2>
		<p>Produktionsvirksomhed med speciale i formstøbt terrazzo og beton.</p>
		<address>
			<strong>Contec Prefab A/S</strong>
			<br/>Dennis Rafn Serwin
			<br/>Axel Kiers Vej 18 J
			<br/>8270 Højbjerg - DK
			<br/><a class="common" href="mailto:prefab@contec-aps.dk">prefab@contec-aps.dk</a>
			<br/><strong class="phone">+45 23 999 367</strong>
		</address>
	</div>
</div>
<div class="inner_content">
<xsl:comment/>
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
		<a href="./?id={@page}&amp;logout=true" class="common">Log ud</a>
		</xsl:when>
		<xsl:otherwise>
		<a href="./?id={@page}" class="common">Log ind</a>
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
<xsl:call-template name="util:link"/>
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
<xsl:call-template name="util:link"/>
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
<xsl:call-template name="util:link"/>
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
<xsl:call-template name="util:link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="f:links/f:top/f:link">
<span>&#160;&#183;&#160;</span>
<a title="{@alternative}" class="common">
<xsl:call-template name="util:link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>



<!--            Text              -->





<xsl:template match="f:text/f:bottom">
<span class="text">
<xsl:apply-templates/>
</span>
</xsl:template>

<xsl:template match="f:text/f:bottom/f:break">
	<br/>
</xsl:template>


<xsl:template match="f:text/f:bottom/f:link">
<a title="{@alternative}" class="common">
<xsl:call-template name="util:link"/>
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
<xsl:call-template name="util:link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>



<!--                  Search                     -->


<xsl:template name="search">
<xsl:if test="f:frame/f:search">
<form action="." method="get" class="search">
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

</xsl:stylesheet>