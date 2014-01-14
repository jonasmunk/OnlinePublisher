<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p f h n o"
 >
	<xsl:output encoding="UTF-8" method="xml" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

	<xsl:include href="../../basic/xslt/util.xsl"/>

	<xsl:template match="p:page">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<xsl:call-template name="util:html-attributes"/>
			<head> 
				<title><xsl:if test="not(//p:page/@id=//p:context/p:home/@page)"><xsl:value-of select="@title"/> - </xsl:if><xsl:value-of select="f:frame/@title"/></title>
				<xsl:call-template name="util:metatags"/>
				<xsl:call-template name="util:style-build"/>
				<xsl:call-template name="util:scripts-build"/>
				<xsl:if test="//p:design/p:parameter[@key='background-color']">
					<style>
						body {
							background-color: <xsl:value-of select="//p:design/p:parameter[@key='background-color']"/>;
						}
					</style>
				</xsl:if>
			</head>
			<body>
				<xsl:attribute name="class">
					<xsl:if test="//p:design/p:parameter[@key='background-image']">
						<xsl:text>design_bg_</xsl:text><xsl:value-of select="//p:design/p:parameter[@key='background-image']"/>
					</xsl:if>
					<xsl:if test="//p:design/p:parameter[@key='background-texture']">
						<xsl:text> design_texture_</xsl:text><xsl:value-of select="//p:design/p:parameter[@key='background-texture']"/>
					</xsl:if>
				</xsl:attribute>
				<div class="layout">
					<div class="layout_header">
						<xsl:call-template name="logo"/>
						<p class="layout_logo">
							<xsl:choose>
								<xsl:when test="//p:design/p:parameter[@key='title']">
									<xsl:value-of select="//p:design/p:parameter[@key='title']"/>
								</xsl:when>
								<xsl:otherwise>
									<xsl:value-of select="f:frame/@title"/>
								</xsl:otherwise>
							</xsl:choose>
						<xsl:comment/></p>
					<div class="layout_navigation">
						<xsl:call-template name="util:hierarchy-first-level"/>
					</div>
					</div>
					<div class="layout_sidebar">
						<xsl:call-template name="util:hierarchy-after-first-level"/>
						<xsl:comment/>
					</div>
					<div class="layout_content">
						<xsl:apply-templates select="p:content"/>
					</div>
				</div>
				<xsl:call-template name="util:googleanalytics"/>
			</body>
		</html>
	</xsl:template>
	
	<xsl:template name="logo">
		<xsl:if test="//p:design/p:parameter[@key='logo-image']">
			<xsl:choose>
				<xsl:when test="//p:design/p:parameter[@key='logo-image']/p:image/@height>58">
					<img class="layout_logo">
						<xsl:attribute name="src">
							<xsl:value-of select="$path"/>
							<xsl:text>services/images/?id=</xsl:text>
							<xsl:value-of select="//p:design/p:parameter[@key='logo-image']/p:image/@id"/>
							<xsl:text>&amp;height=58</xsl:text>
						</xsl:attribute>
					</img>
					
				</xsl:when>
				<xsl:otherwise>
					<img class="layout_logo" width="{//p:design/p:parameter[@key='logo-image']/p:image/@width}" height="{//p:design/p:parameter[@key='logo-image']/p:image/@height}">
						<xsl:attribute name="src">
							<xsl:value-of select="$path"/>
							<xsl:text>services/images/?id=</xsl:text>
							<xsl:value-of select="//p:design/p:parameter[@key='logo-image']/p:image/@id"/>
						</xsl:attribute>
					</img>
			</xsl:otherwise>
			</xsl:choose>
		</xsl:if>
	</xsl:template>
	
</xsl:stylesheet>