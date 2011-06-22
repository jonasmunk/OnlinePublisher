<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:e="http://uri.in2isoft.com/onlinepublisher/publishing/error/1.0/"
 exclude-result-prefixes="e"
 >

<xsl:template match="e:message">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
		<title><xsl:value-of select="e:title"/></title>
		<style>
			body
			{
				background-color: #fafafa;
				font-family: Helvetica,Arial,Verdana,sans-serif;
				text-align: center;
			}
			div
			{
				border: solid 1px #eee;
				width: 400px;
				margin: 30px auto 0 auto;
				background-color: #fff;
				padding: 20px 20px 30px 20px;
				border-radius: 5px;
				}
			h1 {
				font-size: 18pt;
				font-weight: normal;
				color: #666;
				margin: 0px;
			}
			p
			{
				font-size: 11pt;
				margin: 5px 0px 0px 0px;
				color: #666;
			}
			strong {
				display: block;
				width: 440px;
				font-weight: normal;
				font-size: 8pt;
				color: #aab;
				text-align: center;
				margin: 2px auto 0px auto;
				letter-spacing: 1px;
			}
			a
			{
				color: #aaa;
				text-decoration: none;
			}
			a span
			{
				color: #ccc;
				text-decoration: underline;
			}
			a span span
			{
				color: #666;
				text-decoration: none;
			}
			a:hover span
			{
				color: #36f;
			}
			p.back
			{
				margin-top: 20px;
			}
		</style>
	</head>
	<body>
	<div>
	<xsl:apply-templates/>
		<p class="back"><a href="javascript:history.back()">« <span><span>Go back</span></span></a></p>
	</div>
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