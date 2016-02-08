<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:l="http://uri.in2isoft.com/onlinepublisher/part/listing/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="l util"
 >

	<xsl:template match="l:listing">
		<div class="part_listing common_font">
			<xsl:apply-templates/>
			<xsl:comment/>
		</div>
	</xsl:template>

	<xsl:template match="l:list[l:item]">
		<xsl:choose>
			<xsl:when test="@type='1'">
				<ol class="part_listing_list part_listing_list-decimal"><xsl:apply-templates/></ol>
			</xsl:when>
			<xsl:when test="@type='a'">
				<ol class="part_listing_list part_listing_list-loweralpha"><xsl:apply-templates/></ol>
			</xsl:when>
			<xsl:when test="@type='A'">
				<ol class="part_listing_list part_listing_list-upperalpha"><xsl:apply-templates/></ol>
			</xsl:when>
			<xsl:when test="@type='i'">
				<ol class="part_listing_list part_listing_list-lowerroman"><xsl:apply-templates/></ol>
			</xsl:when>
			<xsl:when test="@type='I'">
				<ol class="part_listing_list part_listing_list-upperroman"><xsl:apply-templates/></ol>
			</xsl:when>
			<xsl:when test="@type='disc'">
				<ul class="part_listing_list part_listing_list-disc"><xsl:apply-templates/></ul>
			</xsl:when>
			<xsl:when test="@type='circle'">
				<ul class="part_listing_list part_listing_list-circle"><xsl:apply-templates/></ul>
			</xsl:when>
			<xsl:when test="@type='square'">
				<ul class="part_listing_list part_listing_list-square"><xsl:apply-templates/></ul>
			</xsl:when>
			<xsl:otherwise>
				<ul class="part_listing_list" style="list-style-type: {@type};"><xsl:apply-templates/></ul>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="l:item">
		<li>
      <xsl:attribute name="class">part_listing_item part_listing_item-<xsl:value-of select="position()"/></xsl:attribute>
      <span class="part_listing_text"><xsl:apply-templates/></span>
    </li>
	</xsl:template>

	<xsl:template match="l:style">
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

	<xsl:template match="l:break">
		<br/>
	</xsl:template>

	<xsl:template match="l:strong">
		<strong>
			<xsl:apply-templates/>
		</strong>
	</xsl:template>

	<xsl:template match="l:em">
		<em>
			<xsl:apply-templates/>
		</em>
	</xsl:template>

	<xsl:template match="l:del">
		<del>
			<xsl:apply-templates/>
		</del>
	</xsl:template>

	<xsl:template match="l:first">
		<span class="part_listing_first">
			<xsl:apply-templates/>
		</span>
	</xsl:template>

	<xsl:template match="l:link">
		<a class="common common_link">
			<xsl:call-template name="util:link"/>
			<span class="common_link_text">
				<xsl:apply-templates/>
			</span>
		</a>
	</xsl:template>

</xsl:stylesheet>