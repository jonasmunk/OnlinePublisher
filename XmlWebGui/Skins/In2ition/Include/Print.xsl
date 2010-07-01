<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Print"
    version="1.0"
    exclude-result-prefixes="xwg"
    >

<xsl:template match="xwg:text">
<div><xsl:apply-templates/></div>
</xsl:template>

<xsl:template match="xwg:break">
<br/>
</xsl:template>

<xsl:template match="xwg:interface">
<head>
<style>
* {font-family: Verdana;}
table {border: 1px solid black;}
th {font-size: 12px; border-right: 1px solid #777;}
td {font-size: 9px; border-width: 1px 1px 0 0; border-style: solid; border-color: #777;}
h1 {font-size: 18pt; margin-bottom: 10px;}
div {margin: 5px 0 15px 0; font-size: 10px;}
</style>
</head>
<body>
<xsl:if test="@autoprint='true'">
<xsl:attribute name="onLoad">window.print();</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</body>
</xsl:template>

<xsl:template match="xwg:title">
<h1><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="xwg:list">
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<xsl:apply-templates/>
</table>
</xsl:template>

<xsl:template match="xwg:header">
<th>
<xsl:if test="position()=last()">
<xsl:attribute name="style">border-right-width: 0;</xsl:attribute>
</xsl:if>
<xsl:apply-templates/></th>
</xsl:template>

<xsl:template match="xwg:row">
<tr>
<xsl:apply-templates/>
</tr>
</xsl:template>

<xsl:template match="xwg:cell">
<td>
<xsl:if test="position()=last()">
<xsl:attribute name="style">border-right-width: 0;</xsl:attribute>
</xsl:if>
<xsl:if test=".=''">&#160;</xsl:if>
<xsl:apply-templates/></td>
</xsl:template>

<xsl:template match="xwg:overview">
<table border="0" width="100%">
<xsl:apply-templates/>
</table>
</xsl:template>

<xsl:template match="xwg:overview/xwg:group">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="xwg:block">
<tr>
<th width="1"><xsl:value-of select="@badge"/></th>
<td><xsl:apply-templates/><xsl:if test=".=''">&#160;</xsl:if></td>
</tr>
</xsl:template>

</xsl:stylesheet>