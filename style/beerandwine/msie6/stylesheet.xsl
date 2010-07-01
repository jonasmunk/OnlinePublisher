<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 exclude-result-prefixes="p f h"
 >
<xsl:variable name="skin">beerandwine</xsl:variable>

<xsl:template match="p:page">
<html>
<head>
<title><xsl:value-of select="f:frame/@title"/> :: <xsl:value-of select="@title"/></title>
<link rel="stylesheet" type="text/css" href="style/{$skin}/msie6/stylesheet.css"/>
<link rel="stylesheet" type="text/css" href="style/{$skin}/others/document.css"/>
<link rel="stylesheet" type="text/css" href="style/{$skin}/others/search.css"/>
<script language="JavaScript" type="text/javascript">
var id=<xsl:value-of select="@id"/>;
<![CDATA[
function keypresshandler(e) {
	if(document.all) e=window.event;
	if(e.keyCode==13 && e.shiftKey==true) {
		window.location=("Editor/index.php?page="+id);
	}
	return true;
}
]]>
document.onkeypress=keypresshandler;
</script>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="1%" valign="top" style="padding-left: 34px;"><img src="style/{$skin}/grafik/Logo1.gif" width="88" height="25" border="0"/></td>
<td></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%" background="style/{$skin}/grafik/TopBG.gif">
<tr>
<td width="10" valign="top"><img src="style/{$skin}/grafik/TopLeft.gif" border="0" width="10" height="10"/></td>
<td style="padding-left: 3px;" width="134"><img src="style/{$skin}/grafik/Logo2.gif" width="130" height="47" border="0"/></td>
<td>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="47">
<tr>
<td><img src="style/{$skin}/grafik/Logo.gif" border="0" width="324" height="27"/></td>
<td width="10" valign="top"><img src="style/{$skin}/grafik/TopRight.gif" border="0" width="10" height="10"/></td>
</tr>
</table>
</td>
</tr>
<tr>
<td/>
<td style="padding-left: 3px;"><img src="style/{$skin}/grafik/Logo3.gif" width="130" height="26" border="0"/></td>
<td class="bar">
<xsl:apply-templates select="f:frame/h:hierarchy/h:item"/>
<xsl:call-template name="search"/>
</td></tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" height="400"><tr>
<xsl:apply-templates select="f:frame/h:hierarchy"/>
<td valign="top" class="content" width="100%">
<div style="height: 1px; padding-top: 5px; text-align: right;"><a href="?id={//p:page/@id}&amp;print=true" class="print">udskriv</a></div>
<xsl:apply-templates select="child::*[name()='content']"/>&#160;
</td></tr></table>
</body>
</html>
</xsl:template>

<xsl:template match="h:hierarchy/h:item">
<a>
<xsl:call-template name="link"/>
<xsl:attribute name="class">
<xsl:text>bar</xsl:text>
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text> bar_selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text> bar_hilited</xsl:text></xsl:when>
</xsl:choose>
</xsl:attribute>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="h:hierarchy">
<xsl:if test="h:item/h:item[parent::*/@page=/p:page/@id or descendant-or-self::*/@page=/p:page/@id]">
<td valign="top" width="140" height="100%" background="style/{$skin}/grafik/MenuBG.gif" class="menu">
<table border="0" cellpadding="0" cellspacing="0" width="100%"  height="100%">
<tr>
<td height="1"><img src="style/{$skin}/grafik/MenuTop.gif" border="0" width="157" height="18"/></td>
</tr>
<tr>
<td valign="top" width="100%">
<xsl:for-each select="h:item">
<xsl:if test="descendant-or-self::*/@page=/p:page/@id">
<xsl:apply-templates/>
</xsl:if>
</xsl:for-each>
</td>
</tr>
<tr>
<td height="1"><img src="style/{$skin}/grafik/MenuBottom.gif" border="0" width="157" height="18"/></td>
</tr>
</table>
</td>
</xsl:if>
</xsl:template>

<xsl:template match="h:item">
<a>
<xsl:call-template name="link"/>
<xsl:attribute name="class">
<xsl:text>menu </xsl:text>
<xsl:if test="//p:page/@id=@page"><xsl:text> menu_selected</xsl:text></xsl:if>
<xsl:if test="descendant::*/@page=//p:page/@id"><xsl:text> menu_hilited</xsl:text></xsl:if>
</xsl:attribute>
<xsl:value-of select="@title"/>
</a>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id">
<div style="padding-left: 10px;"><xsl:apply-templates select="h:item"/></div>
</xsl:if>
</xsl:template>

<xsl:template match="h:item/h:item/h:item/h:item"><a>
<xsl:call-template name="link"/>
<xsl:attribute name="class">
<xsl:text>menuSmall </xsl:text>
<xsl:if test="//p:page/@id=@page"><xsl:text> menu_selected</xsl:text></xsl:if>
<xsl:if test="descendant::*/@page=//p:page/@id"><xsl:text> menu_hilited</xsl:text></xsl:if>
</xsl:attribute>
<xsl:value-of select="@title"/>
</a>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id">
<div style="padding-left: 10px;"><xsl:apply-templates select="h:item"/></div>
</xsl:if>
</xsl:template>

<xsl:template name="search">
<xsl:if test="f:frame/f:search">
<form action="." method="get" style="margin: 0px;">
<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
<xsl:for-each select="f:frame/f:search/f:types/f:type">
<input type="hidden" name="{@unique}" value="on"/>
</xsl:for-each>
<table style="float: right; margin-top: 1px; margin-right: 1px;" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<img src="style/{$skin}/grafik/SearchLeft.gif"/>
</td><td background="style/{$skin}/grafik/SearchMiddle.gif">
<input class="FrameSearchField" name="query"></input>
<!--
<input type="submit" value="{f:frame/f:search/f:button/@title}" class="FrameSearchButton"/>
-->
</td>
<td>
<img src="style/{$skin}/grafik/SearchRight.gif"/>
</td></tr>
</table>
</form>
</xsl:if>
</xsl:template>

<xsl:template name="link">
<xsl:attribute name="title"><xsl:value-of select="@alternative"/></xsl:attribute>
<xsl:choose>
<xsl:when test="@page">
<xsl:attribute name="href">?id=<xsl:value-of select="@page"/></xsl:attribute>
</xsl:when>
<xsl:when test="@url">
<xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
<xsl:attribute name="target">_blank</xsl:attribute>
</xsl:when>
<xsl:when test="@email">
<xsl:attribute name="href">mailto:<xsl:value-of select="@email"/></xsl:attribute>
</xsl:when>
</xsl:choose>
</xsl:template>

</xsl:stylesheet>
