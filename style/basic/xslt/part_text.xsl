<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:txt="http://uri.in2isoft.com/onlinepublisher/part/text/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="txt o util"
 >

<xsl:template match="txt:text">
	<div class="part_text common_font">
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="txt:style">
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


<xsl:template match="txt:image">
	<xsl:variable name="src"><xsl:call-template name="txt:buildsrc"/></xsl:variable>
	
	<img src="{$src}">
	<xsl:attribute name="class">
		<xsl:choose>
			<xsl:when test="@float='left'"><xsl:text>part_text_image_left</xsl:text></xsl:when>
			<xsl:otherwise><xsl:text>part_text_image_right</xsl:text></xsl:otherwise>
		</xsl:choose>
	</xsl:attribute>
	</img>
</xsl:template>

<xsl:template name="txt:buildsrc">
	<xsl:value-of select="$path"/><xsl:text>services/images/?id=</xsl:text>
	<xsl:value-of select="o:object/@id"/>
	<xsl:if test="@width">
		<xsl:text>&amp;width=</xsl:text><xsl:value-of select="@width"/>
	</xsl:if>
	<xsl:if test="@height">
		<xsl:text>&amp;height=</xsl:text><xsl:value-of select="@height"/>
	</xsl:if>
</xsl:template>


<xsl:template match="txt:p">
	<p>
		<xsl:if test="position()=2 or (../txt:image and position()=3)">
			<xsl:attribute name="class">part_text_first</xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
		<xsl:comment/>
	</p>
</xsl:template>

<xsl:template match="txt:break">
	<br/>
</xsl:template>

<xsl:template match="txt:strong">
	<strong>
		<xsl:apply-templates/>
	</strong>
</xsl:template>

<xsl:template match="txt:em">
	<em>
		<xsl:apply-templates/>
	</em>
</xsl:template>

<xsl:template match="txt:del">
	<del>
		<xsl:apply-templates/>
	</del>
</xsl:template>

<xsl:template match="txt:link">
	<a class="common part_text_link">
		<xsl:call-template name="util:link"/>
		<span><xsl:apply-templates/></span>
	</a>
</xsl:template>


</xsl:stylesheet>