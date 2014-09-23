<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:m="http://uri.in2isoft.com/onlinepublisher/part/menu/1.0/"
 exclude-result-prefixes="m"
 >

<xsl:template match="m:menu">
    <xsl:apply-templates/>
</xsl:template>

<xsl:template match="m:items">
    <ul><xsl:apply-templates/></ul>
</xsl:template>

<xsl:template match="m:item">
    <li><xsl:value-of select="@text"/></li>
</xsl:template>

</xsl:stylesheet>