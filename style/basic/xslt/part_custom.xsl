<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:html="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:custom="http://uri.in2isoft.com/onlinepublisher/part/custom/1.0/"
 exclude-result-prefixes="custom"
 >

	<xsl:template match="custom:custom">
    <xsl:for-each select="html:rendered">
      <xsl:copy-of select="child::*|child::text()"/><xsl:comment/>
    </xsl:for-each>
	</xsl:template>

</xsl:stylesheet>