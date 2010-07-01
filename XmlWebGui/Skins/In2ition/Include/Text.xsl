<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Text"
    version="1.0"
    exclude-result-prefixes="xwg"
    >

<xsl:template match="xwg:text">
<div class="Text{@size}">
<xsl:attribute name="style">
<xsl:if test="@top!=''">margin-top: <xsl:value-of select="@top"/>px; </xsl:if>
<xsl:if test="@bottom!=''">margin-bottom: <xsl:value-of select="@bottom"/>px; </xsl:if>
<xsl:if test="@right!=''">margin-left: <xsl:value-of select="@right"/>px; </xsl:if>
<xsl:if test="@left!=''">margin-left: <xsl:value-of select="@left"/>px; </xsl:if>
<xsl:if test="@align">text-align: <xsl:value-of select="@align"/>;</xsl:if>
</xsl:attribute>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="xwg:break"><br/></xsl:template>

<xsl:template match="xwg:strong">
<strong class="Text"><xsl:apply-templates/></strong>
</xsl:template>

<xsl:template match="xwg:em">
<em class="Text"><xsl:apply-templates/></em>
</xsl:template>

<xsl:template match="xwg:big">
<big class="Text"><xsl:apply-templates/></big>
</xsl:template>

<xsl:template match="xwg:small">
<small class="Text"><xsl:apply-templates/></small>
</xsl:template>

<xsl:template match="xwg:link">
<a>
<xsl:call-template name="link"/>
<xsl:apply-templates/>
</a>
</xsl:template>

</xsl:stylesheet>