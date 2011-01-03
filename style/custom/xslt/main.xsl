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
				<xsl:call-template name="oo-script"/>
				<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/stylesheet.css"/>
				<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/{$template}.css"/>
				<xsl:comment><![CDATA[[if lt IE 7]>
				<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie6.css"> </link>
				<![endif]]]></xsl:comment>
			</head>
			<body>
				<xsl:call-template name="util:hierarchy-first-level"/>
				<xsl:call-template name="util:hierarchy-after-first-level"/>
				<xsl:apply-templates select="p:content"/>
			</body>
		</html>
	</xsl:template>
	
</xsl:stylesheet>