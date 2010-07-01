<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Area"
    xmlns:t="uri:Toolbar"
    version="1.0"
    exclude-result-prefixes="xwg t"
    >

<xsl:template match="xwg:area">
<xsl:variable name="background"><xsl:choose>
<xsl:when test="ancestor::*[name()='window'] and xwg:content/@background='true'">Window</xsl:when>
<xsl:when test="xwg:content/@background='true'">BG</xsl:when>
</xsl:choose></xsl:variable>
<xsl:variable name="topmargin">
<xsl:if test="not(@margin) and not(@top)"><xsl:value-of select="0"/></xsl:if>
<xsl:if test="@margin and not(@top)"><xsl:value-of select="@margin"/></xsl:if>
<xsl:if test="@top"><xsl:value-of select="@top"/></xsl:if>
</xsl:variable>
<xsl:variable name="height">
<xsl:if test="not(xwg:tabgroup)"><xsl:value-of select="1+$topmargin"/></xsl:if>
<xsl:if test="xwg:tabgroup/@size='Small'or (xwg:tabgroup and not(xwg:tabgroup/@size))"><xsl:value-of select="17+$topmargin"/></xsl:if>
<xsl:if test="xwg:tabgroup/@size='Large'"><xsl:value-of select="21+$topmargin"/></xsl:if>
</xsl:variable>
<table border="0" cellpadding="0" cellspacing="0" align="{@align}" width="{@width}" height="{@height}">
<tr><td height="99%">
<xsl:if test="@margin or @top or @left or @right or @bottom">
<xsl:attribute name="style">
<xsl:if test="@margin">padding:<xsl:value-of select="@margin"/>px;</xsl:if>
<xsl:if test="@top">padding-top:<xsl:value-of select="@top"/>px;</xsl:if>
<xsl:if test="@bottom">padding-bottom:<xsl:value-of select="@bottom"/>px;</xsl:if>
<xsl:if test="@left">padding-left:<xsl:value-of select="@left"/>px;</xsl:if>
<xsl:if test="@right">padding-right:<xsl:value-of select="@right"/>px;</xsl:if>
</xsl:attribute></xsl:if>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
<xsl:apply-templates select="xwg:titlebar"/>
<xsl:apply-templates select="xwg:tabgroup"/>
<xsl:if test="t:toolbar">
<tr><td colspan="3" height="1"><table border="0" cellpadding="0" cellspacing="0" width="100%">
<xsl:apply-templates select="t:toolbar"><xsl:with-param name="height"><xsl:value-of select="$height"/></xsl:with-param></xsl:apply-templates>
</table></td></tr>
</xsl:if>
<xsl:apply-templates select="xwg:content"/>
<xsl:apply-templates select="xwg:bottom"/>
</table></td></tr></table>
</xsl:template>

<xsl:template match="xwg:titlebar">
<tr>
<td height="1" colspan="3" class="AreaTitlebar">
<xsl:value-of select="@title"/>
</td>
</tr>
</xsl:template>

<xsl:template match="xwg:bottom">
<tr>
<td colspan="3" class="AreaBottom">
<xsl:if test="not(node())">&#160;</xsl:if>
<xsl:apply-templates/>
</td>
</tr>
</xsl:template>

<xsl:template match="xwg:tabgroup[@size='Small' or not(@size)]">
<xsl:variable name="background"><xsl:choose>
<xsl:when test="ancestor::*[name()='window'] and ../xwg:content/@background='true'">Window</xsl:when>
<xsl:when test="../t:toolbar">Toolbar</xsl:when>
<xsl:when test="../xwg:content/@background='true'">BG</xsl:when>
</xsl:choose></xsl:variable>
<xsl:variable name="align">
<xsl:if test="@align"><xsl:value-of select="@align"/></xsl:if>
<xsl:if test="not(@align)">center</xsl:if>
</xsl:variable>
<tr>
<td width="5"><img src="{$graphics}AreaTabgroup{$background}SmallLeft.gif" width="5" height="16" border="0"/></td>
<td height="1" align="{$align}" background="{$graphics}AreaTabgroup{$background}SmallBG.gif" width="100%">
<table border="0" cellspacing="0" cellpadding="0"><tr>
<xsl:apply-templates/>
</tr></table></td>
<td width="5"><img src="{$graphics}AreaTabgroup{$background}SmallRight.gif" width="5" height="16" border="0"/></td>
</tr>
</xsl:template>

<xsl:template match="xwg:tabgroup[@size='Large']">
<xsl:variable name="background"><xsl:choose>
<xsl:when test="ancestor::*[name()='window'] and ../xwg:content/@background='true'">Window</xsl:when>
<xsl:when test="../t:toolbar">Toolbar</xsl:when>
<xsl:when test="../xwg:content/@background='true'">BG</xsl:when>
</xsl:choose></xsl:variable>
<xsl:variable name="align">
<xsl:if test="@align"><xsl:value-of select="@align"/></xsl:if>
<xsl:if test="not(@align)">center</xsl:if>
</xsl:variable>
<tr>
<td width="1"><img src="{$graphics}AreaTabgroup{$background}LargeLeft.gif" width="5" height="20" border="0"/></td>
<td height="1" align="{$align}" background="{$graphics}AreaTabgroup{$background}LargeBG.gif" width="100%">
<table border="0" cellspacing="0" cellpadding="0"><tr>
<xsl:apply-templates/>
</tr></table></td>
<td width="1"><img src="{$graphics}AreaTabgroup{$background}LargeRight.gif" width="5" height="20" border="0"/></td>
</tr>
</xsl:template>

<xsl:template match="xwg:tabgroup[../xwg:titlebar]">
	<tr><td class="AreaTitlebarTabgroup"><xsl:apply-templates/></td></tr>
</xsl:template>

<xsl:template match="xwg:space">
<xsl:if test="../@size='Large'">
<td width="8"></td>
</xsl:if>
<xsl:if test="../@size='Small' or not(../@size)">
<td width="5"></td>
</xsl:if>
</xsl:template>

<xsl:template match="xwg:tab[../@size='Small' or not(../@size)]">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td nowrap="true">
<xsl:attribute name="class"><xsl:text>TabSmall TabSmall</xsl:text><xsl:value-of select="$style"/><xsl:if test="position()=last() or not(name(following-sibling::*)='tab')"> TabSmallLast</xsl:if></xsl:attribute>
<a class="TabSmall TabSmall{$style}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/></a>
</td>
</xsl:template>


<xsl:template match="xwg:tab[../@size='Large']">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td nowrap="true">
<xsl:attribute name="class">
	<xsl:text>TabLarge Tablarge</xsl:text>
	<xsl:value-of select="$style"/>
	<xsl:if test="position()=last() or not(name(following-sibling::*)='tab')"> TabLargeLast</xsl:if>
</xsl:attribute>
<a class="TabLarge TabLarge{$style}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a></td>
</xsl:template>


<xsl:template match="xwg:tab[../../xwg:titlebar]">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<xsl:variable name="class">
	<xsl:value-of select="$style"/>
	<xsl:if test="following-sibling::*[1][@style='Hilited']"> PreceedsHilited</xsl:if>
	<xsl:if test="position()=1 and $style='Hilited'"> FirstHilited</xsl:if>
</xsl:variable>
<a class="{$class}">
<xsl:call-template name="link"/>
<span><xsl:value-of select="@title"/></span></a>
</xsl:template>

<xsl:template match="xwg:content">
<xsl:variable name="background"><xsl:choose>
<xsl:when test="ancestor::*[name()='window'] and @background='true'">Window</xsl:when>
<xsl:when test="@background='true'">Background</xsl:when>
</xsl:choose></xsl:variable>
<tr>
<td height="{@height}" colspan="3">
<xsl:attribute name="class">
<xsl:if test="not(../xwg:tabgroup) and not(../xwg:titlebar)">WindowContentTop </xsl:if>
WindowContent WindowContent<xsl:value-of select="$background"/>
<xsl:if test="../xwg:bottom">
AreaContentHasBottom
</xsl:if>
</xsl:attribute>
<xsl:if test="@padding">
<xsl:attribute name="style">padding:<xsl:value-of select="@padding"/>px;</xsl:attribute>
</xsl:if>
<xsl:attribute name="valign">
<xsl:choose>
<xsl:when test="@valign"><xsl:value-of select="@valign"/></xsl:when>
<xsl:otherwise>top</xsl:otherwise>
</xsl:choose>
</xsl:attribute>
<xsl:attribute name="align">
<xsl:choose>
<xsl:when test="@align"><xsl:value-of select="@align"/></xsl:when>
<xsl:otherwise>left</xsl:otherwise>
</xsl:choose>
</xsl:attribute>
<xsl:if test="@width">
<xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
<xsl:if test="not(node())">&#160;</xsl:if>
</td>
</tr>
</xsl:template>

</xsl:stylesheet>
