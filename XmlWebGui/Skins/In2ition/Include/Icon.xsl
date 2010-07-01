<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Icon"
    xmlns:menu="uri:Menu"
    version="1.0"
    exclude-result-prefixes="xwg menu"
    >

<xsl:template match="xwg:group">
<table border="0">
<xsl:if test="@spacing"><xsl:attribute name="cellspacing"><xsl:value-of select="@spacing"/></xsl:attribute></xsl:if>
<xsl:if test="not(@spacing)"><xsl:attribute name="cellspacing">0</xsl:attribute></xsl:if>
<xsl:if test="@padding"><xsl:attribute name="cellpadding"><xsl:value-of select="@padding"/></xsl:attribute></xsl:if>
<xsl:if test="not(@padding)"><xsl:attribute name="cellpadding">0</xsl:attribute></xsl:if>
<xsl:if test="@width"><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute></xsl:if>
<xsl:apply-templates/>
<script type="text/javascript" src="{$root}Scripts/IconGroup.js"></script>
<script type="text/javascript">
var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.IconGroup('<xsl:value-of select="generate-id()"/>');
with (<xsl:value-of select="generate-id()"/>_obj) {
<xsl:for-each select="xwg:row/xwg:icon">
	registerIcon('<xsl:value-of select="generate-id()"/>_base','<xsl:value-of select="@unique"/>');
</xsl:for-each>
}
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="generate-id()"/>_obj;
</xsl:if>
</script>
</table>
</xsl:template>

<xsl:template match="xwg:row">
<tr>
<xsl:apply-templates/>
</tr>
</xsl:template>

<xsl:template match="xwg:pile">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<xsl:if test="@margin"><xsl:attribute name="style">margin: <xsl:value-of select="@margin"/>px;</xsl:attribute></xsl:if>
<tr><td><xsl:apply-templates/></td></tr>
</table>
</xsl:template>




<xsl:template match="xwg:icon">
<xsl:apply-templates/>

<xsl:if test="name(parent::*)='row'">
<td valign="top">
<xsl:if test="../../@cellwidth">
<xsl:attribute name="width"><xsl:value-of select="../../@cellwidth"/></xsl:attribute>
</xsl:if>

<xsl:if test="../../@titles='under' or not(../../@titles)">
<xsl:attribute name="align">center</xsl:attribute>
<table border="0" cellpadding="0" cellspacing="0" class="icon" id="{generate-id()}_base">
<xsl:call-template name="xwg:dragdrop"/>
<tr><td align="center">
<xsl:call-template name="xwg:image"><xsl:with-param name="size" select="../../@size"/></xsl:call-template>
</td></tr>
<tr>
<td align="center">
<xsl:if test="../../@wrapping='false'"><xsl:attribute name="nowrap">nowrap</xsl:attribute></xsl:if>
<xsl:call-template name="xwg:text"><xsl:with-param name="size" select="../../@size"/></xsl:call-template>
</td>
</tr>
</table>
</xsl:if>

<xsl:if test="../../@titles='right'">
<xsl:attribute name="align">left</xsl:attribute>
<table border="0" cellpadding="0" cellspacing="0" id="{generate-id()}_base">
<xsl:call-template name="xwg:dragdrop"/>
<tr><td align="center">
<xsl:call-template name="xwg:image"><xsl:with-param name="size" select="../../@size"/></xsl:call-template>
</td><td align="left" style="padding-left: 2px;">
<xsl:if test="../../@wrapping='false'"><xsl:attribute name="nowrap">nowrap</xsl:attribute></xsl:if>
<xsl:call-template name="xwg:text"><xsl:with-param name="size" select="../../@size"/></xsl:call-template>
</td>
</tr>
</table>
</xsl:if>
</td>
</xsl:if>

<xsl:if test="name(parent::*)='pile'">
<table border="0" cellpadding="0" cellspacing="0" class="IconPile">
<xsl:call-template name="xwg:dragdrop"/>
<xsl:if test="../@iconmargin"><xsl:attribute name="style">margin: <xsl:value-of select="../@iconmargin"/>px;</xsl:attribute></xsl:if>
<xsl:if test="../@titles='under' or not(../@titles)">
<tr>
<td align="center">
<xsl:call-template name="xwg:image"><xsl:with-param name="size" select="../@size"/></xsl:call-template>
</td></tr>
<tr>
<td align="center" style="padding-left: 2px; padding-right: 2px;">
<xsl:if test="../@wrapping='false'"><xsl:attribute name="nowrap">nowrap</xsl:attribute></xsl:if>
<xsl:call-template name="xwg:text"><xsl:with-param name="size" select="../@size"/></xsl:call-template>
</td>
</tr>
</xsl:if>
<xsl:if test="../@titles='right'">
<tr><td valign="top">
<xsl:call-template name="xwg:image"><xsl:with-param name="size" select="../@size"/></xsl:call-template>
</td><td align="left" style="padding-left: 1px; padding-right: 3px;">
<xsl:if test="../@wrapping='false'"><xsl:attribute name="nowrap">nowrap</xsl:attribute></xsl:if>
<xsl:call-template name="xwg:text"><xsl:with-param name="size" select="../@size"/></xsl:call-template>
</td>
</tr>
</xsl:if>
</table>
</xsl:if>

<xsl:if test="not(name(parent::*)='pile') and not(name(parent::*)='row')">
<table border="0" cellpadding="0" cellspacing="0">
<xsl:call-template name="xwg:dragdrop"/>
<tr><td align="center">
<xsl:call-template name="xwg:image"><xsl:with-param name="size" select="@size"/></xsl:call-template>
</td></tr>
<tr><td align="center">
<xsl:call-template name="xwg:text"><xsl:with-param name="size" select="@size"/></xsl:call-template></td>
</tr>
</table>
</xsl:if>

<xsl:if test="@drop">
<script language="javascript">registerDropArea("<xsl:value-of select="@drop"/>");</script>
</xsl:if>
<xsl:if test="@drag">
<script language="javascript">registerDragObject("<xsl:value-of select="@drag"/>");</script>
</xsl:if>
<xsl:if test="@dragdrop">
<script language="javascript">registerDragObject("<xsl:value-of select="@dragdrop"/>");</script>
<script language="javascript">registerDropArea("<xsl:value-of select="@dragdrop"/>");</script>
</xsl:if>

</xsl:template>



<!-- Support templates -->


<xsl:template name="xwg:dragdrop">
<xsl:if test="@drag or @drop or @dragdrop">
<xsl:attribute name="id"><xsl:value-of select="@drag"/><xsl:value-of select="@drop"/><xsl:value-of select="@dragdrop"/></xsl:attribute>
</xsl:if>
</xsl:template>

<xsl:template name="xwg:text">
<xsl:param name="size"/>
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<a>
<xsl:attribute name="class">Icon<xsl:call-template name="style"/> Icon<xsl:value-of select="$size"/></xsl:attribute>
<xsl:call-template name="xwg:menu"/>
<xsl:if test="@link and not(menu:menu or @menu)">
<xsl:call-template name="link"/>
</xsl:if>
<xsl:if test="not(@link) and not(menu:menu or @menu)">
<xsl:attribute name="style">cursor: default;</xsl:attribute>
</xsl:if>
<xsl:if test="(@style='Standard' or not(@style)) and (@link or @menu or menu:menu) and not(@overlay) and @icon">
<xsl:attribute name="onmouseout">document.getElementById('<xsl:value-of select="generate-id()"/>-icon').src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/><xsl:value-of select="$size"/>.gif';</xsl:attribute>
<xsl:attribute name="onmouseover">document.getElementById('<xsl:value-of select="generate-id()"/>-icon').src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited<xsl:value-of select="$size"/>.gif';</xsl:attribute>
</xsl:if>
<xsl:if test="(@style='Standard' or not(@style)) and (@link or @menu or menu:menu) and @overlay and @icon">
<xsl:attribute name="onmouseout">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/><xsl:value-of select="$size"/>.gif\')';</xsl:attribute>
<xsl:attribute name="onmouseover">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited<xsl:value-of select="$size"/>.gif\')';</xsl:attribute>
</xsl:if>
<xsl:value-of select="@title"/>
<xsl:if test="@description"><br/><span class="IconDescription{$size}"><xsl:value-of select="@description"/></span></xsl:if>
</a>
</xsl:template>

<xsl:template name="xwg:image">
<xsl:param name="size"/>
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<xsl:if test="not(@overlay)">
<xsl:if test="@icon">
<a>
<xsl:if test="@link">
<xsl:call-template name="link"/>
</xsl:if>
<xsl:if test="not(@link)">
<xsl:call-template name="xwg:menu"/>
</xsl:if>
<img border="0" width="{$size*16}" height="{$size*16}" src="{$iconset}{@icon}{$style}{$size}.gif" id="{generate-id()}-icon">
<xsl:if test="(@style='Standard' or not(@style)) and (@link or @menu or menu:menu)">
<xsl:attribute name="onmouseout">this.src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/><xsl:value-of select="$size"/>.gif';</xsl:attribute>
<xsl:attribute name="onmouseover">this.src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited<xsl:value-of select="$size"/>.gif';</xsl:attribute>
</xsl:if>
</img>
</a>
</xsl:if>
<xsl:if test="@image">
<table width="{$size*16}" height="{$size*16}" border="0" cellpadding="0" cellspacing="0">
<tr><td valign="middle" align="center">
<a>
<xsl:if test="@link">
<xsl:call-template name="link"/>
</xsl:if>
<xsl:if test="not(@link)">
<xsl:call-template name="xwg:menu"/>
</xsl:if>
<img border="0" src="{@image}"/>
</a>
</td></tr></table>
</xsl:if>
</xsl:if>
<xsl:if test="@overlay">
<table border="0" cellpadding="0" cellspacing="0" width="{$size*16}" height="{$size*16}" style="display: inline;"><tr><td>
<xsl:attribute name="id"><xsl:value-of select="generate-id()"/></xsl:attribute>
<xsl:if test="@icon">
<xsl:attribute name="background"><xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:call-template name="style"/><xsl:value-of select="$size"/>.gif</xsl:attribute>
</xsl:if>
<xsl:if test="@image">
<xsl:attribute name="background"><xsl:value-of select="@image"/></xsl:attribute>
<xsl:attribute name="style">background-position: center center; background-repeat: no-repeat;</xsl:attribute>
</xsl:if>
<a>
<xsl:if test="@link">
<xsl:call-template name="link"/>
</xsl:if>
<xsl:if test="not(@link)">
<xsl:call-template name="xwg:menu"/>
</xsl:if>
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute>
<img border="0" width="{$size*16}" height="{$size*16}" src="{$iconset}Overlay/{@overlay}{$style}{$size}.gif">
<xsl:if test="(@style='Standard' or not(@style)) and (@link or @menu or menu:menu) and @icon">
<xsl:attribute name="onmouseout">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/><xsl:value-of select="$size"/>.gif\')';</xsl:attribute>
<xsl:attribute name="onmouseover">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited<xsl:value-of select="$size"/>.gif\')';</xsl:attribute>
</xsl:if>
</img>
</a>
</td></tr></table>
</xsl:if>
</xsl:template>

<xsl:template name="xwg:menu">
<xsl:if test="@menu">
<xsl:attribute name="href">#</xsl:attribute>
<xsl:attribute name="onfocus">MenuHandler.showMenu(<xsl:value-of select="@menu"/>, this)</xsl:attribute>
<xsl:attribute name="onblur">MenuHandler.hideMenu(<xsl:value-of select="@menu"/>)</xsl:attribute>
<xsl:attribute name="onclick">return false</xsl:attribute>
</xsl:if>
<xsl:if test="menu:menu">
<xsl:attribute name="href">#</xsl:attribute>
<xsl:attribute name="onfocus">MenuHandler.showMenu(<xsl:value-of select="generate-id(menu:menu)"/>, this)</xsl:attribute>
<xsl:attribute name="onblur">MenuHandler.hideMenu(<xsl:value-of select="generate-id(menu:menu)"/>)</xsl:attribute>
<xsl:attribute name="onclick">return false</xsl:attribute>
</xsl:if>
</xsl:template>

</xsl:stylesheet>