<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
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
<xsl:output encoding="UTF-8" method="xml" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:include href="../../basic/xslt/util.xsl"/>


<xsl:template match="p:page">
<html xmlns="http://www.w3.org/1999/xhtml">
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
	<link href='http://fonts.googleapis.com/css?family=Neuton:300,700,400,200' rel='stylesheet' type='text/css'/>
	<link href='http://fonts.googleapis.com/css?family=Merriweather:400,300' rel='stylesheet' type='text/css'/>
	<link href='http://fonts.googleapis.com/css?family=Crimson+Text' rel='stylesheet' type='text/css'/>
	<link href='http://fonts.googleapis.com/css?family=Ovo' rel='stylesheet' type='text/css'/>
	<xsl:call-template name="util:style"/>
	<xsl:call-template name="util:style-ie6"/>
	<xsl:call-template name="util:style-ie7"/>
	<xsl:call-template name="util:style-ie8"/>
	<xsl:call-template name="util:scripts"/>
	
</head>
<body>
	<div class="layout_bg">
	<div class="layout">
		<div class="layout_top">
			<div class="layout_top_fade"><xsl:comment/></div>
			<p>Østerlide ferielejlighed</p>
			<div class="layout_navigation">
				<ul class="layout_navigation">
					<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
				</ul>
			</div>
			<xsl:call-template name="util:languages"/>
		</div>
		<!--
		<xsl:if test="//p:page/p:context/p:home[@page=//p:page/@id]">
			<div class="layout_front"><xsl:comment/></div>
		</xsl:if>
		-->
		<xsl:apply-templates select="p:content"/>
		<div class="layout_bottom">
			<div class="layout_bottom_effect"><xsl:comment/></div>
			<p>Designet og udviklet af <a href="http://www.in2isoft.dk/" title="In2iSoft"><span>In2iSoft</span></a></p>
		</div>
	</div>
	</div>
	<xsl:call-template name="util:googleanalytics"/>
	<script>
		hui.onReady(function() {
			var fade = hui.get.firstByClass(document.body,'layout_top_fade');
			var top = fade.parentNode;
			hui.style.set(fade,{opacity:0,visibility:'visible'});
			hui.listen(top,'mouseover',function() {
				hui.animate({node:fade,css:{opacity:1},duration:200,ease:hui.ease.slowFast});
			})
			hui.listen(top,'mouseout',function() {
				hui.animate({node:fade,css:{opacity:0},duration:2000,ease:hui.ease.slowFast});
			})
		})
	</script>
</body>
</html>
</xsl:template>

<xsl:template match="hr:horizontalrule">
	<span><xsl:comment/></span>
	<div><xsl:comment/></div>
	<hr class="part_horizontalrule"/>
</xsl:template>


<xsl:template match="p:content">
	<div class="layout_content">
		<xsl:apply-templates/>
		<xsl:comment/>
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