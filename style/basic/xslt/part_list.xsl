<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:l="http://uri.in2isoft.com/onlinepublisher/part/list/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="l"
 >

<xsl:template match="l:list">
	<div class="part_list common_font">
		<div class="part_list_box">
		<div class="part_list_box_top"><div><div><xsl:comment/></div></div></div>
		<div class="part_list_box_middle">
		<xsl:apply-templates/>
		<xsl:if test="not(l:item)">
			<p class="part_list_nodata">Der findes pt. ingen begivenheder</p>
		</xsl:if>
		<xsl:comment/>
		</div>
		<div class="part_list_box_bottom"><div><div><xsl:comment/></div></div></div>
		</div>
	</div>
</xsl:template>

<xsl:template match="l:list/l:title">
	<h2><xsl:apply-templates/></h2>
</xsl:template>

<xsl:template match="l:item">
	<div class="part_list_item">
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="l:item/l:title">
	<h3><xsl:apply-templates/></h3>
</xsl:template>

<xsl:template match="l:item/l:text">
	<p class="part_list_text"><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="l:item/l:source">
	<p class="part_list_source"><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="l:item/l:date">
	<p class="part_list_date">
		<xsl:choose>
			<xsl:when test="//p:page/p:meta/p:language='en'"><xsl:text>From: </xsl:text></xsl:when>
			<xsl:otherwise><xsl:text>Fra: </xsl:text></xsl:otherwise>
		</xsl:choose>
		<xsl:call-template name="util:long-date-time"><xsl:with-param name="node" select="."/></xsl:call-template>
	</p>
</xsl:template>

<xsl:template match="l:item/l:end-date">
	<p class="part_list_date">
		<xsl:choose>
			<xsl:when test="//p:page/p:meta/p:language='en'"><xsl:text>To: </xsl:text></xsl:when>
			<xsl:otherwise><xsl:text>Til: </xsl:text></xsl:otherwise>
		</xsl:choose>
		<xsl:call-template name="util:long-date-time"><xsl:with-param name="node" select="."/></xsl:call-template>
	</p>
</xsl:template>

</xsl:stylesheet>