<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p f h n o util"
 >
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:include href="util.xsl"/>

<xsl:template match="p:page">
<html>
	<xsl:call-template name="util:html-attributes"/>
	<head>
		<title><xsl:value-of select="@title"/> :: <xsl:value-of select="f:frame/@title"/></title>
		<xsl:call-template name="util:metatags"/>
		<xsl:call-template name="util:style"/>
		<xsl:call-template name="util:scripts"/>
		<style>
			body
			{
				-webkit-transform: scale(.5);
				-webkit-transform-origin: 0 0;
				width: 600px;
				text-align: left;
				background: #fff;
			}
			
			div.document
			{
				padding: 10px 20px;
			}
		</style>
	</head>
	<body>
		<xsl:apply-templates select="p:content"/>			
	</body>
</html>
</xsl:template>

</xsl:stylesheet>