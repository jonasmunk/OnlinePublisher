<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:g="uri:Gallery"
    version="1.0"
    exclude-result-prefixes="g"
    >

<xsl:template match="g:gallery">
<div class="Gallery" style="padding: 5px;" id="{generate-id()}_container">
	<xsl:apply-templates/>
</div>
<script type="text/javascript" src="{$root}Scripts/Gallery.js"></script>
<script type="text/javascript">
var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Gallery({element:'<xsl:value-of select="generate-id()"/>'});
with (<xsl:value-of select="generate-id()"/>_obj) {
<xsl:for-each select="g:image">
	registerImage('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@unique"/>');
</xsl:for-each>
}
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="generate-id()"/>_obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="g:image">
<table width="140" height="140" style="float: left;" id="{generate-id()}_base">
<tr><td style="border: 1px solid #eee; text-align: center;">
<span style="position: absolute; background-color: #036; opacity: 0.5; color: #fff; padding: 1px 3px 1px 3px; font: 8pt Verdana,Tahoma,sans-serif;"><xsl:value-of select="@title"/></span>
<span style="position: absolute; color: #fff; padding: 1px 3px 1px 3px; font: 8pt Verdana,Tahoma,sans-serif;"><xsl:value-of select="@title"/></span>
<a>
<xsl:if test="@link">
<xsl:call-template name="link"/>
</xsl:if>
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute>
<img src="{@source}" border="0"/>
</a>
</td></tr></table>
</xsl:template>

</xsl:stylesheet>