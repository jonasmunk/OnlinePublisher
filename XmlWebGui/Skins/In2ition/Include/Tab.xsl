<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Tab"
    version="1.0"
    exclude-result-prefixes="xwg"
    >


<xsl:template match="xwg:tabgroup">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td valign="bottom" align="{@align}" bgcolor="#ECF4F9" height="35">
        <xsl:apply-templates select="xwg:super"/>
    </td>
    </tr><tr>
    <td valign="top" align="{@align}" background="{$graphics}TabSubBG.gif" height="20">
        <xsl:apply-templates select="xwg:sub"/>
    </td>
</tr></table>
</xsl:template>


<xsl:template match="xwg:super">
<table border="0" cellspacing="0" cellpadding="0" height="24">
<tr>
<xsl:apply-templates/>
</tr>
</table>
</xsl:template>


<xsl:template match="xwg:sub">
<table border="0" cellspacing="0" cellpadding="0" height="16" style="margin-top: 1px;">
<tr>
<xsl:apply-templates/>
</tr></table>
</xsl:template>


<xsl:template match="xwg:space">
<xsl:if test="name(parent::*)='super'">
<td width="10"></td>
</xsl:if>
<xsl:if test="name(parent::*)='sub'">
<td width="5"></td>
</xsl:if>
</xsl:template>


<xsl:template match="xwg:sub/xwg:tab">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td nowrap="true" valign="top">
<xsl:if test="$style='Hilited'">
<xsl:attribute name="background"><xsl:value-of select="$graphics"/>TabSubHilited.gif</xsl:attribute>
</xsl:if>
<a class="TabSub TabSub{$style}" title="{@help}">
<xsl:if test="not(@link)"><xsl:attribute name="style">cursor: default;</xsl:attribute></xsl:if>
<xsl:if test="@link">
<xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:if>
<xsl:value-of select="@title"/>
</a>
</td>
</xsl:template>


<xsl:template match="xwg:super/xwg:tab">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<xsl:if test="position()>1 and (name(preceding-sibling::node()[position()=1])!='super.space')">
<td width="1"></td>
</xsl:if>
<td><img src="{$graphics}TabSuper{$style}Left.gif" width="6" height="24" border="0"/></td>
<td nowrap="true" valign="middle" background="{$graphics}TabSuper{$style}BG.gif">
<a title="{@help}" class="TabSuper TabSuper{$style}">
<xsl:if test="not(@link)"><xsl:attribute name="style">cursor: default;</xsl:attribute></xsl:if>
<xsl:if test="@link">
<xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:if>
<xsl:value-of select="@title"/>
</a></td>
<td><img src="{$graphics}TabSuper{$style}Right.gif" width="6" height="24" border="0"/></td>
</xsl:template>


</xsl:stylesheet>