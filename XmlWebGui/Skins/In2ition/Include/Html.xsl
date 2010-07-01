<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Html"
    version="1.0"
    exclude-result-prefixes="xwg"
    >

<xsl:template match="xwg:html">
<xsl:copy-of select="child::*"/>
</xsl:template>

</xsl:stylesheet>