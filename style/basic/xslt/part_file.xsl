<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/part/file/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/class/file/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 exclude-result-prefixes="f p o"
 >

<xsl:template match="p:file">
	<xsl:variable name="href">
		<xsl:choose>
			<xsl:when test="$editor='true'">
				<xsl:text>javascript:void(0);</xsl:text>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="$path"/>files/<xsl:value-of select=".//f:file/f:filename"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<div class="part_file common_font" id="{generate-id()}"><div><div><div>
		<p class="part_file_title"><a href="{$href}"><span>
		<xsl:choose>
			<xsl:when test="p:text">
				<xsl:value-of select="p:text"/>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select=".//o:title"/>
			</xsl:otherwise>
		</xsl:choose>
		</span></a></p>
		<p class="part_file_info"><xsl:call-template name="p:size"/><xsl:if test="p:info/@type!=''">, <xsl:value-of select="p:info/@type"/></xsl:if><xsl:comment/></p>
	</div></div></div></div>
</xsl:template>

<xsl:template name="p:size">
	<xsl:if test=".//f:file/f:size">
	<xsl:variable name="size" select="number(.//f:file/f:size)"/>
	<xsl:choose>
		<xsl:when test="$size>=1048576">
			<xsl:value-of select="round($size div 10485.76) div 100"/><xsl:text> Mb</xsl:text>
		</xsl:when>
		<xsl:when test="$size>=1024">
			<xsl:value-of select="round($size div 10.24) div 100"/><xsl:text> Kb</xsl:text>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="$size"/><xsl:text> byte</xsl:text>
		</xsl:otherwise>
	</xsl:choose>
	</xsl:if>
</xsl:template>

</xsl:stylesheet>