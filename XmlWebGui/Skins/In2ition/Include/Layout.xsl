<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Layout"
    version="1.0"
    exclude-result-prefixes="xwg"
    >

<xsl:template match="xwg:layout">
<table border="0">
<xsl:if test="@width"><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute></xsl:if>
<xsl:if test="@height"><xsl:attribute name="height"><xsl:value-of select="@height"/></xsl:attribute></xsl:if>
<xsl:if test="@padding"><xsl:attribute name="cellpadding"><xsl:value-of select="@padding"/></xsl:attribute></xsl:if>
<xsl:if test="@spacing"><xsl:attribute name="cellspacing"><xsl:value-of select="@spacing"/></xsl:attribute></xsl:if>
<xsl:apply-templates/>
</table>
</xsl:template>

<xsl:template match="xwg:row">
<tr>
<xsl:apply-templates/>
</tr>
</xsl:template>

<xsl:template match="xwg:cell">
<td>
<xsl:if test="@width"><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute></xsl:if>
<xsl:if test="@height"><xsl:attribute name="height"><xsl:value-of select="@height"/></xsl:attribute></xsl:if>
<xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>
<xsl:if test="@valign"><xsl:attribute name="valign"><xsl:value-of select="@valign"/></xsl:attribute></xsl:if>
<xsl:if test="@colspan"><xsl:attribute name="colspan"><xsl:value-of select="@colspan"/></xsl:attribute></xsl:if>
<xsl:if test="@rowspan"><xsl:attribute name="rowspan"><xsl:value-of select="@rowspan"/></xsl:attribute></xsl:if>
<xsl:attribute name="style">
<xsl:if test="@padding">padding: <xsl:value-of select="@padding"/>px;</xsl:if>
<xsl:if test="@top">padding-top: <xsl:value-of select="@top"/>px;</xsl:if>
<xsl:if test="@bottom">padding-bottom: <xsl:value-of select="@bottom"/>px;</xsl:if>
<xsl:if test="@left">padding-left: <xsl:value-of select="@left"/>px;</xsl:if>
<xsl:if test="@right">padding-right: <xsl:value-of select="@right"/>px;</xsl:if>
<xsl:if test="@border-right">border-right: <xsl:value-of select="@border-right"/>px solid #ccc;</xsl:if>
</xsl:attribute>
<xsl:apply-templates/>
</td>
</xsl:template>

<xsl:template match="xwg:overflow">
<div style="height: {@height}px; overflow: auto;"><xsl:apply-templates/></div>	
</xsl:template>

</xsl:stylesheet>