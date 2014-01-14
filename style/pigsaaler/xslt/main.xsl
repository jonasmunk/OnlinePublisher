<?xml version="1.0" encoding="UTF-8"?>
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
<xsl:output encoding="UTF-8" method="xml" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:include href="../../basic/xslt/util.xsl"/>


<xsl:template match="p:page">
<html xmlns="http://www.w3.org/1999/xhtml">
	<xsl:call-template name="util:html-attributes"/>
<head>
	<title><xsl:value-of select="@title"/> » <xsl:value-of select="f:frame/@title"/></title>
	<xsl:call-template name="util:metatags"/>
	<xsl:call-template name="util:style"/>
	<xsl:call-template name="util:style-ie6"/>
	<xsl:call-template name="util:style-ie7"/>
	<xsl:call-template name="util:style-ie8"/>
	<xsl:call-template name="util:scripts"/>
	<script type="text/javascript" src="{$path}style/{$design}/js/bestilling.js" charset="utf-8"><xsl:comment/></script>
	<script type="text/javascript" src="{$path}hui/js/Alert.js"><xsl:comment/></script>
	<script type="text/javascript" src="{$path}hui/js/Button.js"><xsl:comment/></script>
</head>
<body>
	<div class="case">
		<div class="case_head">
			<div class="case_head_body">
				<p style="position: absolute; margin: 0; font-size: 36px; font-weight: bold; top: 10px; left: 20px; color: #fff;">GB Pigsåler</p>
				<ul class="case_navigation"><xsl:apply-templates select="f:frame/h:hierarchy/h:item"/></ul>
			</div>
		</div>
		<div class="case_body">
			<div class="case_sidebar">
				<xsl:call-template name="thirdlevel"/>
				<div class="case_contact">
					<h2>GB Pigsåler</h2>
					<p><em>Tlf:</em> 23 62 33 59 </p>
					<p><em>E-post: </em> <a href="mailto:info@pigsaaler.dk"><span>gbpigsaaler@gbpigsaaler.dk</span></a></p>
					<blockquote>
						"Kontakt os venligst hvis du er interesseret."
					</blockquote>
				</div>
			</div>
			<div class="case_sidebar_content">
				<xsl:call-template name="secondlevel"/><xsl:comment/>
				<xsl:apply-templates select="p:content"/>
			</div>
		</div>
	</div>
	<div class="layout_footer"><xsl:comment/></div>
	<xsl:call-template name="util:googleanalytics"/>
</body>
</html>
</xsl:template>


<xsl:template match="p:content">
	<div class="case_content">
		<xsl:apply-templates/>
		<xsl:comment/>
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
				<xsl:when test="position()=1 and (//p:page/@id=@page or descendant-or-self::*/@page=//p:page/@id)"><xsl:text>first_selected</xsl:text></xsl:when>
				<xsl:when test="position()=last() and (//p:page/@id=@page or descendant-or-self::*/@page=//p:page/@id)"><xsl:text>last_selected</xsl:text></xsl:when>
				<xsl:when test="//p:page/@id=@page or descendant-or-self::*/@page=//p:page/@id"><xsl:text>selected</xsl:text></xsl:when>
				<xsl:when test="position()=1"><xsl:text>first</xsl:text></xsl:when>
				<xsl:when test="position()=last()"><xsl:text>last</xsl:text></xsl:when>
				<xsl:otherwise><xsl:text>middle</xsl:text></xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<li class="{$style}">
			<a>
			<xsl:call-template name="util:link"/>
			<span><span><xsl:value-of select="@title"/></span></span>
			</a>
		</li>
	</xsl:if>
</xsl:template>

<xsl:template name="secondlevel">
<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
	<ul class="case_sub_navigation">
		<xsl:apply-templates select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
	</ul>
</xsl:if>
</xsl:template>

<xsl:template name="thirdlevel">
<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
	<ul class="case_side_navigation">
		<xsl:apply-templates select="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
	</ul>
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