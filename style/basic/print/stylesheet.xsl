<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p f h util"
 >
<xsl:variable name="skin">in2isoft</xsl:variable>

<xsl:template match="p:page">
<html>
<head>
<title><xsl:value-of select="f:frame/@title"/> :: <xsl:value-of select="@title"/></title>
<link rel="stylesheet" type="text/css" href="style/{$skin}/others/stylesheet.css"/>
<link rel="stylesheet" type="text/css" href="style/{$skin}/others/document.css"/>
</head>
<body>

<xsl:apply-templates/>

</body>
</html>
</xsl:template>


<xsl:template name="util:link">
	
</xsl:template>

</xsl:stylesheet>