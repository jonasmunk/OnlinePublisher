<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:sm="http://uri.in2isoft.com/onlinepublisher/publishing/sitemap/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 exclude-result-prefixes="sm"
 >

<xsl:template match="sm:sitemap">
<div class="sitemap">
<xsl:apply-templates/><br/>
</div>
</xsl:template>

<xsl:template match="sm:title">
<h1 class="common"><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="sm:text">
<p class="common"><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="sm:break">
<br/>
</xsl:template>

<xsl:template match="sm:group">
<div class="group">
<h2 class="common"><xsl:value-of select="@title"/></h2>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="sm:sitemap//h:hierarchy">
<ul class="common"><xsl:apply-templates/></ul>
</xsl:template>

<xsl:template match="sm:sitemap//h:item">
<li>
<a class="common">
<xsl:call-template name="util:link"/>
<xsl:value-of select="@title"/>
</a>
<xsl:if test="h:item">
<ul><xsl:apply-templates select="h:item"/></ul>
</xsl:if>
</li>
</xsl:template>


</xsl:stylesheet>