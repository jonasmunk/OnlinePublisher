<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 >
<xsl:template match="icons">
<html>
<head>
<style>
th {
	font: 10px Verdana;
	text-align: left;
}
</style>
</head>
<body>
<xsl:apply-templates/>
</body>
</html>
</xsl:template>

<xsl:template match="group">
<table width="500">
<xsl:apply-templates/>
</table>
</xsl:template>

<xsl:template match="icon">
<xsl:variable name="icon"><xsl:value-of select="concat(../@unique,'/',.)"/></xsl:variable>
<tr>
<th><xsl:value-of select="$icon"/></th>
<td width="16" height="16"><img src="Basic/{$icon}Standard1.gif" width="16" height="16"/></td>
<td width="16" height="16"><img src="Basic/{$icon}Disabled1.gif" width="16" height="16"/></td>
<td width="16" height="16"><img src="Basic/{$icon}Hilited1.gif" width="16" height="16"/></td>
<td width="32" height="32"><img src="Basic/{$icon}Standard2.gif" width="32" height="32"/></td>
<td width="32" height="32"><img src="Basic/{$icon}Disabled2.gif" width="32" height="32"/></td>
<td width="32" height="32"><img src="Basic/{$icon}Hilited2.gif" width="32" height="32"/></td>
<td width="48" height="48"><img src="Basic/{$icon}Standard3.gif" width="48" height="48"/></td>
<td width="48" height="48"><img src="Basic/{$icon}Disabled3.gif" width="48" height="48"/></td>
<td width="48" height="48"><img src="Basic/{$icon}Hilited3.gif" width="48" height="48"/></td>
</tr>
</xsl:template>
</xsl:stylesheet>