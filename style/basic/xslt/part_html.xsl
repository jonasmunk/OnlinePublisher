<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:html="http://uri.in2isoft.com/onlinepublisher/part/html/1.0/"
 exclude-result-prefixes="html"
 >

<xsl:template match="html:html">
<div class="part_html"><xsl:value-of select="." disable-output-escaping = "yes"/></div>
</xsl:template>

</xsl:stylesheet>