<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:doc="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/class/person/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:part="http://uri.in2isoft.com/onlinepublisher/part/1.0/"
 xmlns:ph="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"
 exclude-result-prefixes="doc n p i o part ph"
 >

<xsl:include href="../../basic/others/part_header.xsl"/>
<xsl:include href="../../basic/others/part_text.xsl"/>
<xsl:include href="../../basic/others/part_html.xsl"/>
<xsl:include href="../../basic/others/part_horizontalrule.xsl"/>
<xsl:include href="../../basic/others/part_image.xsl"/>
<xsl:include href="../../basic/others/part_listing.xsl"/>
<xsl:include href="part_news.xsl"/>
<xsl:include href="part_person.xsl"/>
<xsl:include href="../../basic/others/part_richtext.xsl"/>

<xsl:template match="doc:content">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="doc:row">
<table width="100%">
<tr>
<xsl:apply-templates/>
</tr>
</table>
</xsl:template>

<xsl:template match="doc:column">
<td valign="top">
<xsl:attribute name="class">
<xsl:text>DocumentColumn</xsl:text>
<xsl:if test="position()=1"> DocumentColumnFirst</xsl:if>
</xsl:attribute>
<xsl:choose>
<xsl:when test="@width='min'">
<xsl:attribute name="style">width: 1%;</xsl:attribute>
</xsl:when>
<xsl:when test="@width='max'">
<xsl:attribute name="style">width: 100%;</xsl:attribute>
</xsl:when>
<xsl:when test="@width">
<xsl:attribute name="style">width: <xsl:value-of select="@width"/>;</xsl:attribute>
</xsl:when>
</xsl:choose>
<xsl:apply-templates/>
</td>
</xsl:template>

<xsl:template match="doc:section">
<xsl:variable name="style">
<xsl:if test="@left"> padding-left: <xsl:value-of select="@left"/>;</xsl:if>
<xsl:if test="@right"> padding-right: <xsl:value-of select="@right"/>;</xsl:if>
<xsl:if test="@top"> padding-top: <xsl:value-of select="@top"/>;</xsl:if>
<xsl:if test="@bottom"> padding-bottom: <xsl:value-of select="@bottom"/>;</xsl:if>
</xsl:variable>
<div style="{$style}" class="DocumentSection">
<xsl:attribute name="class">
<xsl:choose>
<xsl:when test="part:part">DocumentSection DocumentSectionPart-<xsl:value-of select="part:part/@type"/>
<!-- Hack to make headers margins work -->
<xsl:if test="part:part/@type='header'"> DocumentSectionPart-header-<xsl:value-of select="part:part/part:sub/ph:header/@level"/></xsl:if>
</xsl:when>
</xsl:choose>
</xsl:attribute>
<xsl:apply-templates/>
</div>
</xsl:template>


<!--          Part            -->

<xsl:template match="part:part">
<xsl:apply-templates select="part:sub/*"/>
</xsl:template>
</xsl:stylesheet>