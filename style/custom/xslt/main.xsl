<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
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
		<html>
			<head> 
				<title><xsl:if test="not(//p:page/@id=//p:context/p:home/@page)"><xsl:value-of select="@title"/> - </xsl:if><xsl:value-of select="f:frame/@title"/></title>
				<xsl:call-template name="util:metatags"/>
				<xsl:call-template name="util:style"/>
				<xsl:call-template name="util:scripts"/>
			</head>
			<body>
				<div class="layout">
					<div class="layout_header">
						<p class="layout_logo"><xsl:value-of select="f:frame/@title"/><xsl:comment/></p>
					</div>
					<div class="layout_navigation">
						<xsl:call-template name="util:hierarchy-first-level"/>
					</div>
					<div class="layout_sidebar">
						<xsl:call-template name="util:hierarchy-after-first-level"/>
						<xsl:comment/>
					</div>
					<div class="layout_content">
						<xsl:apply-templates select="p:content"/>
					</div>
				</div>
			</body>
		</html>
	</xsl:template>
	
</xsl:stylesheet>