<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Tile"
    version="1.0"
    exclude-result-prefixes="xwg"
    >

<xsl:template match="xwg:tiles">
<table width="{@width}" class="Tiles" cellpadding="0" cellspacing="0">
<xsl:if test="@height='auto' and xwg:content">
<xsl:attribute name="height">100%</xsl:attribute>
</xsl:if>
<xsl:if test="@height!='auto'">
<xsl:attribute name="height"><xsl:value-of select="@height"/></xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</table>
</xsl:template>

<xsl:template match="xwg:tile">
<tr><td valign="top" height="1%">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="Tile" height="17" background="{$graphics}TileBG.gif"><tr>
<xsl:if test="@arrow">
<td style="padding: 0px 0 0 3px;" width="11">
<a><xsl:call-template name="link"/>
<img src="{$graphics}TileArrow{@arrow}.gif" width="11" height="11" border="0"/>
</a>
</td>
</xsl:if>
<td class="Tile">
<a class="Tile"><xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</td>
<td align="right" class="Tile">
<xsl:apply-templates/>
</td>
</tr></table>
</td></tr>
</xsl:template>

<xsl:template match="xwg:link">
<a class="TileLink">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="xwg:content">
<tr><td class="TileContent">
<xsl:apply-templates/>
</td></tr>
</xsl:template>

</xsl:stylesheet>