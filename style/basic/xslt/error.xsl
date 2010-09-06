<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:e="http://uri.in2isoft.com/onlinepublisher/publishing/error/1.0/"
 exclude-result-prefixes="e"
 >

<xsl:template match="e:message">
<html>
<head>
<title><xsl:value-of select="e:title"/></title>
<style>
	* {font-family: Tahoma,Verdana,sans-serif}
	body {background-color: #fafafa;}
	div {
		border: solid 1px #ddd;
		width: 400px;
		margin: 30px auto 0 auto;
		background-color: #fff;
		padding: 30px 20px 30px 20px;
		border-radius: 5px;
		}
	h1 {font-size: 14pt; color: #aaa; margin: 0px;}
	p {font-size: 10pt; margin: 5px 0px 0px 0px; color: #333;}
	strong {display: block; width: 440px; font-weight: normal; font-size: 8pt; color: #aab; text-align: center; margin: 2px auto 0px auto; letter-spacing: 1px;}
</style>
</head>
<body>
<div align="center">
<xsl:apply-templates/>
</div>
<strong>In2iSoft OnlinePublisher</strong>
</body>
</html>
</xsl:template>

<xsl:template match="e:title">
<h1><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="e:note">
<p><xsl:apply-templates/></p>
</xsl:template>
	
</xsl:stylesheet>