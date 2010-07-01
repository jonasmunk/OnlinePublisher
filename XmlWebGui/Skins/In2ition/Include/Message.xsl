<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:m="uri:Message"
    version="1.0"
    exclude-result-prefixes="m"
    >

<xsl:template match="m:message">
<xsl:variable name="width"><xsl:choose>
<xsl:when test="@width"><xsl:value-of select="@width"/></xsl:when>
<xsl:otherwise>100%</xsl:otherwise>
</xsl:choose></xsl:variable>
<table cellspacing="7" cellpadding="0" border="0" width="{$width}">
<tr>
<xsl:if test="@icon">
<td valign="top" width="64"><img border="0" src="{$graphics}Alert{@icon}.gif" width="64" height="64"/></td>
</xsl:if>
<td valign="top">
<xsl:for-each select="text()">
<div style="font: 12px Verdana;"><xsl:value-of select="."/></div>
</xsl:for-each>
<xsl:apply-templates select="*[node() and name()!='buttongroup']"/>
</td>
</tr>
<xsl:if test="m:buttongroup">
<tr><td>
<xsl:if test="@icon">
<xsl:attribute name="colspan">2</xsl:attribute>
</xsl:if>
<xsl:apply-templates select="m:buttongroup"/>
</td></tr>
</xsl:if>
</table>
</xsl:template>

<xsl:template match="m:title">
<div style="font: 13px Verdana; font-weight: bold;"><xsl:apply-templates/></div>
</xsl:template>

<xsl:template match="m:description">
<div style="font: 11px Verdana; margin-bottom: 6px; margin-top: 2px;"><xsl:apply-templates/></div>
</xsl:template>

<xsl:template match="m:error">
<a href="JavaScript: switchError{generate-id()}();" style="font-size: 10px; color: #aaa; text-decoration: none; margin-top: 6px;">
<xsl:value-of select="@badge"/>
</a>
<script>
function switchError<xsl:value-of select="generate-id()"/>() {
  var obj = document.getElementById('<xsl:value-of select="generate-id()"/>');
  var disp=obj.style.display;
	if (disp=='block') {
	obj.style.display='none'
	}
	else {
	obj.style.display='block'
	}
}
</script>
<div style="display: none;" id="{generate-id()}"><textarea class="FormInputSmall" style="height: 120px;"><xsl:apply-templates/></textarea></div>
</xsl:template>

<xsl:template match="m:break"><br/></xsl:template>

<xsl:template match="m:link">
<a class="Text" href="{@link}" target="{@target}" title="{@help}">
<xsl:apply-templates/>
</a>
</xsl:template>

<xsl:template match="m:buttongroup">
<div align="right">
<table border="0" cellpadding="0" cellspacing="0"><tr><xsl:apply-templates/></tr></table>
</div>
</xsl:template>

<xsl:template match="m:button[../@size='Large']">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<!--<td><img src="{$graphics}ButtonLarge{$style}Left.gif" width="5" height="22"/></td>-->
<td nowrap="nowrap">
<xsl:if test="@object">
<script src="{$root}Scripts/Button.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new In2iGui.Button('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="$style"/>');
</script>
</xsl:if>
<a class="ButtonLarge{$style} ButtonLarge" id="{generate-id()}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a></td>
<!--<td><img src="{$graphics}ButtonLarge{$style}Right.gif" width="5" height="22"/></td>-->
<xsl:if test="not(position()=last())"><td width="4"></td></xsl:if>
</xsl:template>

<xsl:template match="m:button[../@size='Small']">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<!--<td><img src="{$graphics}ButtonSmall{$style}Left.gif" width="5" height="18"/></td>-->
<td nowrap="nowrap">
<a class="ButtonSmall{$style} ButtonSmall" id="{generate-id()}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a></td>
<!--<td><img src="{$graphics}ButtonSmall{$style}Right.gif" width="5" height="18"/></td>-->
<xsl:if test="not(position()=last())"><td width="2"></td></xsl:if>
</xsl:template>

</xsl:stylesheet>