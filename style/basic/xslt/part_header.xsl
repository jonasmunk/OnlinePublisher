<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="h util"
 >

	<xsl:template match="h:header[@level=1]">
		<h1 class="part_header common"><xsl:apply-templates/><xsl:comment/></h1>
	</xsl:template>

	<xsl:template match="h:header[@level=2]">
		<h2 class="part_header common"><xsl:apply-templates/><xsl:comment/></h2>
	</xsl:template>

	<xsl:template match="h:header[@level=3]">
		<h3 class="part_header common"><xsl:apply-templates/><xsl:comment/></h3>
	</xsl:template>

	<xsl:template match="h:header[@level=4]">
		<h4 class="part_header common"><xsl:apply-templates/><xsl:comment/></h4>
	</xsl:template>

	<xsl:template match="h:header[@level=5]">
		<h5 class="part_header common"><xsl:apply-templates/><xsl:comment/></h5>
	</xsl:template>

	<xsl:template match="h:header[@level=6]">
		<h6 class="part_header common"><xsl:apply-templates/><xsl:comment/></h6>
	</xsl:template>

	<xsl:template match="h:style">
		<xsl:attribute name="style">
			<xsl:if test="@font-size">font-size: <xsl:value-of select="@font-size"/>;</xsl:if>
			<xsl:if test="@font-family">font-family: <xsl:value-of select="@font-family"/>;</xsl:if>
			<xsl:if test="@font-style">font-style: <xsl:value-of select="@font-style"/>;</xsl:if>
			<xsl:if test="@font-weight">font-weight: <xsl:value-of select="@font-weight"/>;</xsl:if>
			<xsl:if test="@color">color: <xsl:value-of select="@color"/>;</xsl:if>
			<xsl:if test="@line-height">line-height: <xsl:value-of select="@line-height"/>;</xsl:if>
			<xsl:if test="@text-align">text-align: <xsl:value-of select="@text-align"/>;</xsl:if>
			<xsl:if test="@word-spacing">word-spacing: <xsl:value-of select="@word-spacing"/>;</xsl:if>
			<xsl:if test="@letter-spacing">letter-spacing: <xsl:value-of select="@letter-spacing"/>;</xsl:if>
			<xsl:if test="@text-indent">text-indent: <xsl:value-of select="@text-indent"/>;</xsl:if>
			<xsl:if test="@text-transform">text-transform: <xsl:value-of select="@text-transform"/>;</xsl:if>
			<xsl:if test="@font-variant">font-variant: <xsl:value-of select="@font-variant"/>;</xsl:if>
			<xsl:if test="@text-decoration">text-decoration: <xsl:value-of select="@text-decoration"/>;</xsl:if>
		</xsl:attribute>
	</xsl:template>

	<xsl:template match="h:break">
		<br/>
	</xsl:template>

	<xsl:template match="h:strong">
		<strong>
			<xsl:apply-templates/>
		</strong>
	</xsl:template>

	<xsl:template match="h:em">
		<em>
			<xsl:apply-templates/>
		</em>
	</xsl:template>

	<xsl:template match="h:del">
		<del>
			<xsl:apply-templates/>
		</del>
	</xsl:template>

	<xsl:template match="h:link">
		<a class="common">
			<xsl:call-template name="util:link"/>
			<span><xsl:apply-templates/></span>
		</a>
	</xsl:template>

</xsl:stylesheet>