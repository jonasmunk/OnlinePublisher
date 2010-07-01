<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Overview"
    version="1.0"
    exclude-result-prefixes="xwg"
    >

<xsl:template match="xwg:overview">
<table width="{@width}" cellpadding="0" cellspacing="1"><tr><td>
<xsl:if test="@margin">
<xsl:attribute name="style">padding: <xsl:value-of select="@margin"/>px;</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</td></tr>
</table>
</xsl:template>

<xsl:template match="xwg:group">
<table width="100%" cellpadding="0" cellspacing="0" class="Overview"><xsl:apply-templates/></table>
</xsl:template>

<xsl:template match="xwg:block">
<tr>
<td align="right" valign="{@valign}" class="OverviewBadge" width="1%"><xsl:value-of select="@badge"/></td>
<td class="OverviewContent">
<xsl:apply-templates/>
<xsl:if test="not(node()) and not(child::text())">&#160;</xsl:if>
</td>
</tr>
<tr><td height="2"></td></tr>
</xsl:template>

<xsl:template match="xwg:space">
<tr>
<td height="5"></td>
</tr>
</xsl:template>

<xsl:template match="xwg:strong">
<strong style="color: #666;"><xsl:apply-templates/></strong>
</xsl:template>

<xsl:template match="xwg:link">
<a href="{@link}" target="{@target}" title="{@help}" style="text-decoration: none;"><xsl:apply-templates/></a>
</xsl:template>

<xsl:template match="xwg:pre">
<pre style="margin:0px;"><xsl:apply-templates/></pre>
</xsl:template>

<xsl:template match="xwg:overflow">
<div style="overflow: auto; height: 200px; width: 100%;"><xsl:apply-templates/></div>
</xsl:template>

<xsl:template match="xwg:break">
<br/>
</xsl:template>

</xsl:stylesheet>