<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:html="http://uri.in2isoft.com/onlinepublisher/publishing/html/1.0/"
 exclude-result-prefixes="html"
 >

<xsl:template match="html:html">
<div class="html">
<xsl:apply-templates select="html:title"/>
<xsl:apply-templates select="html:content"/>
</div>
</xsl:template>

<xsl:template match="html:title">
	<h1 class="common"><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="html:content[@valid='true'] | html:content[not(@valid)]">
	<xsl:copy-of select="child::*|child::text()"/>
</xsl:template>

<xsl:template match="html:content[@valid='false']">
	<xsl:value-of select="." disable-output-escaping = "yes"/>
</xsl:template>


</xsl:stylesheet>