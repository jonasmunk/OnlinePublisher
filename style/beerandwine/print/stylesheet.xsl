<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 exclude-result-prefixes="p"
 >
<xsl:variable name="skin">beerandwine</xsl:variable>


<xsl:template match="p:page">
<html>
<head>
<title><xsl:value-of select="f:frame/@title"/> :: <xsl:value-of select="@title"/></title>
<link rel="stylesheet" type="text/css" href="style/{$skin}/others/document.css"/>
</head>
<body onload="window.print()">
<xsl:apply-templates select="child::*[name()='content']"/>
</body>
</html>
</xsl:template>

<xsl:template name="link">
</xsl:template>

</xsl:stylesheet>