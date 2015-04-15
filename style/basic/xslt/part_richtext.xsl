<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:rt="http://uri.in2isoft.com/onlinepublisher/part/richtext/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="rt util"
 >

	<xsl:template match="rt:richtext">
		<div class="part_richtext common_font">
		<xsl:choose>
			<xsl:when test="@valid='false'">
				<xsl:value-of select="." disable-output-escaping = "yes"/>
			</xsl:when>
			<xsl:otherwise>
				<xsl:apply-templates mode="copy-no-ns" select="node()"/>
				<xsl:comment/>				
			</xsl:otherwise>
		</xsl:choose>
		</div>
	</xsl:template>

	<xsl:template mode="copy-no-ns" match="rt:richtext//*">
		<xsl:choose>
			<xsl:when test="name(.)='link'">
				<a data="{@data}">
					<xsl:call-template name="util:link"/>
					<xsl:apply-templates mode="copy-no-ns"/>
				</a>
			</xsl:when>
			<xsl:otherwise>
				<xsl:element name="{name(.)}">
					<xsl:copy-of select="@*"/>
					<xsl:apply-templates mode="copy-no-ns"/>
				</xsl:element>			
		  </xsl:otherwise>
		</xsl:choose>
	</xsl:template>

</xsl:stylesheet>