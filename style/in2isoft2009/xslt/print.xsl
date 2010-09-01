<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 exclude-result-prefixes="p f h"
 >
<xsl:output encoding="UTF-8"/>

<xsl:template match="p:page">
<html>
<head>
<title><xsl:value-of select="f:frame/@title"/> :: <xsl:value-of select="@title"/></title>
<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/others/stylesheet.css"/>
<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/others/{$template}.css"/>
<style>
	body {background-image: none; background-color: #fff;}
</style>
</head>
<body style="margin: 20px;">

<xsl:apply-templates select="child::*[name()='content']"/>

</body>
</html>
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
<xsl:when test="@file">
<xsl:attribute name="href">files/<xsl:value-of select="@filename"/></xsl:attribute>
<xsl:attribute name="target">_blank</xsl:attribute>
</xsl:when>
<xsl:when test="@email">
<xsl:attribute name="href">mailto:<xsl:value-of select="@email"/></xsl:attribute>
</xsl:when>
</xsl:choose>
</xsl:template>

</xsl:stylesheet>