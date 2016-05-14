<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="no" encoding="UTF-8"/>
<xsl:include href="../../xslt/gui.xsl"/>

<xsl:variable name="context">../../..</xsl:variable>
<xsl:variable name="dev">true</xsl:variable>
<xsl:variable name="version">1</xsl:variable>
<xsl:variable name="pathVersion"></xsl:variable>
<xsl:variable name="profile">false</xsl:variable>
<xsl:variable name="language">en</xsl:variable>

<xsl:template match="/"><xsl:apply-templates/></xsl:template>
</xsl:stylesheet>