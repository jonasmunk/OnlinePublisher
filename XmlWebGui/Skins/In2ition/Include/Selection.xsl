<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:s="uri:Selection"
    version="1.0"
    exclude-result-prefixes="s"
    >

<xsl:template match="s:selection">
<xsl:variable name="object-name"><xsl:value-of select="generate-id()"/>_object</xsl:variable>
<div class="Selection">
<xsl:apply-templates/>
</div>
<script src="{$root}Scripts/Selection.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$object-name"/> = new In2iGui.Selection('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="@object"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$object-name"/>;
</xsl:if>
<xsl:for-each select="s:item">
	<xsl:value-of select="$object-name"/>.registerItem('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@value"/>');
</xsl:for-each>
</script>
</xsl:template>

<xsl:template match="s:title">
<div class="Title">
	<div><span><xsl:value-of select="."/></span></div>
</div>
</xsl:template>

<xsl:template match="s:item">
<div id="{generate-id()}">
<xsl:attribute name="class">
<xsl:text>Item</xsl:text>
<xsl:if test="../@value=@value"> Selected</xsl:if>
</xsl:attribute>
	<xsl:if test="@icon">
		<img border="0" width="16" height="16" src="{$iconset}{@icon}Standard1.gif"/>
	</xsl:if>
	<span><xsl:value-of select="@title"/></span>
	<xsl:if test="@badge"><strong><xsl:value-of select="@badge"/></strong></xsl:if>
</div>
</xsl:template>

</xsl:stylesheet>