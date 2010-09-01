<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:rt="http://uri.in2isoft.com/onlinepublisher/part/richtext/1.0/"
 exclude-result-prefixes="rt"
 >

<xsl:template match="rt:richtext">
<div class="PartRichtext">
<xsl:apply-templates mode="copy-no-ns" select="node()"/>
<xsl:comment/>
</div>
</xsl:template>

<xsl:template mode="copy-no-ns" match="*">
  <xsl:element name="{name(.)}">
    <xsl:copy-of select="@*"/>
    <xsl:apply-templates mode="copy-no-ns"/>
  </xsl:element>
</xsl:template>

</xsl:stylesheet>