<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:pn="http://uri.in2isoft.com/onlinepublisher/part/news/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 exclude-result-prefixes="pn n o"
 >

<xsl:template match="pn:news">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="pn:box">
<div class="PartNewsBox">
<xsl:if test="pn:title">
<div class="PartNewsBoxTitle">
<xsl:value-of select="pn:title"/>
</div>
</xsl:if>
<xsl:apply-templates select="o:object"/>
</div>
</xsl:template>

<xsl:template match="pn:list">
<div class="PartNewsList">
<xsl:if test="pn:title">
<div class="PartNewsListTitle">
<xsl:value-of select="pn:title"/>
</div>
</xsl:if>
<xsl:apply-templates select="o:object"/>
</div>
</xsl:template>

<xsl:template match="pn:box/o:object | pn:list/o:object">
<div class="PartNews PartNews{(position() mod 2)+1}">
<div class="PartNewsTitle">
<xsl:value-of select="o:title"/>
</div>
<div class="PartNewsDescription">
<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
<xsl:apply-templates select="o:note"/>
<xsl:comment/>
</div>
<xsl:if test="o:links">
<div class="PartNewsLinks">
<xsl:apply-templates select="o:links/o:link"/>
</div>
</xsl:if>
<div class="clear"><xsl:comment/></div>
</div>
</xsl:template>

<xsl:template match="pn:news//o:break">
<br/>
</xsl:template>

<xsl:template match="pn:news//n:startdate">
<span class="PartNewsDate"><xsl:value-of select="number(@day)"/>/<xsl:value-of select="number(@month)"/></span>
</xsl:template>

<xsl:template match="pn:news//o:link">
<xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
&#187;<a title="{@alternative}" class="common">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

</xsl:stylesheet>