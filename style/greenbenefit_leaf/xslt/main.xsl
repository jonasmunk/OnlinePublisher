<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
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
<html>
	<xsl:call-template name="util:html-attributes"/>
<head>
	<title><xsl:value-of select="@title"/> » <xsl:value-of select="f:frame/@title"/></title>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
	<link rel="icon" href="/favicon.ico" type="image/x-icon"/>
	<xsl:call-template name="util:metatags"/>
	<xsl:call-template name="util:style"/>
	<xsl:call-template name="util:style-ie6"/>
	<xsl:call-template name="util:scripts"/>
</head>
<body>
	<div class="layout">
		<div class="layout_head">
			<div class="layout_container">
				<a class="layout_logo"><xsl:comment/></a>
				<xsl:call-template name="util:languages"/>
			</div>
		</div>
		<div class="layout_middle">
			<div class="layout_middle_body">
			<div class="layout_container">
				<div class="layout_navigation_bar">
					<ul class="layout_navigation"><xsl:apply-templates select="f:frame/h:hierarchy/h:item"/></ul>
					<div class="layout_box layout_contact">
						<div class="layout_box_top"><div><xsl:comment/></div></div>
						<div class="layout_box_middle">
							<h2>Greenbenefit.dk</h2>
							<p>Helsingforsgade 25</p>
							<p>8200 Århus N</p>
							<p>Denmark</p>
							<p><a href="mailto:info@greenbenefit.dk"><span>info@greenbenefit.dk</span></a></p>
							<p>Tlf: 70 20 60 77</p>
						</div>
						<div class="layout_box_bottom"><div><xsl:comment/></div></div>
					</div>
				</div>
				<xsl:apply-templates select="p:content"/>
			</div>
			</div>
		</div>
	</div>
	<div class="layout_footer">
		<a class="layout_design" href="http://www.in2isoft.dk/">Designet og udviklet af In2iSoft</a>
		<xsl:apply-templates select="f:frame/f:text/f:bottom"/>
		<xsl:apply-templates select="f:frame/f:links/f:bottom"/>
	</div>
	<xsl:call-template name="util:googleanalytics"><xsl:with-param name="code" select="'UA-420000-11'"/></xsl:call-template>
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
				<xsl:apply-templates select="../f:frame/f:newsblock"/>
				<xsl:comment/>
			</div>
		</xsl:if>
		<div class="layout_inner_content">
			<xsl:apply-templates/>
		<xsl:comment/>
		</div>
	</div>
</xsl:template>


<!--            User status                 -->



<xsl:template match="h:hierarchy/h:item">
<xsl:if test="not(@hidden='true')">
<xsl:variable name="class">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
<xsl:otherwise>normal</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<li>
<a class="{$class}">
<xsl:call-template name="util:link"/>
<span><xsl:value-of select="@title"/></span>
</a>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
<ul><xsl:apply-templates/></ul>
</xsl:if>
</li>
</xsl:if>
</xsl:template>

<xsl:template match="h:item">
<xsl:variable name="class">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
<xsl:otherwise>standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<li>
<a class="{$class}">
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
		<h3>
			<xsl:value-of select="o:title"/>
		</h3>
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




</xsl:stylesheet>