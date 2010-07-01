<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Button"
    version="1.0"
    exclude-result-prefixes="xwg"
    >

<xsl:template match="xwg:group">
<div align="{@align}">
<xsl:if test="@margin or @top or @left or @right or @bottom">
<xsl:attribute name="style">
<xsl:if test="@margin">padding:<xsl:value-of select="@margin"/>px;</xsl:if>
<xsl:if test="@top">padding-top:<xsl:value-of select="@top"/>px;</xsl:if>
<xsl:if test="@bottom">padding-bottom:<xsl:value-of select="@bottom"/>px;</xsl:if>
<xsl:if test="@left">padding-left:<xsl:value-of select="@left"/>px;</xsl:if>
<xsl:if test="@right">padding-right:<xsl:value-of select="@right"/>px;</xsl:if>
</xsl:attribute></xsl:if>
<table border="0" cellpadding="0" cellspacing="0"><tr><xsl:apply-templates/></tr></table></div>
</xsl:template>

<xsl:template match="xwg:button[../@size='Large' and name(parent::*)='group']">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<!--<td><img src="{$graphics}ButtonLarge{$style}Left.gif" width="5" height="22"/></td>-->
<td nowrap="nowrap" background="{$graphics}ButtonLarge{$style}BG.gif">
<a class="ButtonLarge{$style} ButtonLarge">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a></td>
<!--<td><img src="{$graphics}ButtonLarge{$style}Right.gif" width="5" height="22"/></td>-->
<xsl:if test="not(position()=last())"><td style="padding-left: 4px;"></td></xsl:if>
</xsl:template>

<xsl:template match="xwg:button[../@size='Small' and name(parent::*)='group']">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<!--<td><img src="{$graphics}ButtonSmall{$style}Left.gif" width="5" height="18"/></td>-->
<td nowrap="nowrap" background="{$graphics}ButtonSmall{$style}BG.gif">
<a class="ButtonSmall{$style} ButtonSmall">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a></td>
<!--<td><img src="{$graphics}ButtonSmall{$style}Right.gif" width="5" height="18"/></td>-->
<xsl:if test="not(position()=last())"><td width="2"></td></xsl:if>
</xsl:template>

<xsl:template match="xwg:button[@size='Large' and not(name(parent::*)='group')]">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<table border="0" cellpadding="0" cellspacing="0"><tr>
<td nowrap="nowrap">
<a class="ButtonLarge{$style} ButtonLarge">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a></td>
</tr></table>
</xsl:template>

<xsl:template match="xwg:button[@size='Small' and not(name(parent::*)='group')]">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<table border="0" cellpadding="0" cellspacing="0"><tr>
<td nowrap="nowrap">
<a class="ButtonSmall{$style} ButtonSmall">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a></td>
</tr></table>
</xsl:template>

<xsl:template match="xwg:direction[../@size='Large']">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td>
<a>
<xsl:if test="@link">
<xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:if>
<img src="{$graphics}ButtonLarge{@direction}{$style}.gif" width="24" height="22" border="0">
<xsl:if test="$style='Standard' and @link">
<xsl:attribute name="onmouseover">this.src='<xsl:value-of select="$graphics"/>ButtonLarge<xsl:value-of select="@direction"/>Hilited.gif';</xsl:attribute>
<xsl:attribute name="onmouseout">this.src='<xsl:value-of select="$graphics"/>ButtonLarge<xsl:value-of select="@direction"/>Standard.gif';</xsl:attribute>
</xsl:if>
</img>
</a></td>
<xsl:if test="not(position()=last())"><td style="padding-left: 4px;"></td></xsl:if>
</xsl:template>

<xsl:template match="xwg:direction[../@size='Small']">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td>
<a>
<xsl:call-template name="link"/>
<img src="{$graphics}ButtonSmall{@direction}{$style}.gif" width="20" height="18" border="0">
<xsl:if test="$style='Standard' and @link">
<xsl:attribute name="onmouseover">this.src='<xsl:value-of select="$graphics"/>ButtonSmall<xsl:value-of select="@direction"/>Hilited.gif';</xsl:attribute>
<xsl:attribute name="onmouseout">this.src='<xsl:value-of select="$graphics"/>ButtonSmall<xsl:value-of select="@direction"/>Standard.gif';</xsl:attribute>
</xsl:if>
</img>
</a></td>
<xsl:if test="not(position()=last())"><td width="2"></td></xsl:if>
</xsl:template>

</xsl:stylesheet>