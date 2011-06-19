<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:gui="uri:hui"
    version="1.0"
    exclude-result-prefixes="gui"
>

<xsl:template match="gui:view" name="gui:view">
	<table cellspacing="0" cellpadding="0">
		<xsl:if test="@width"><xsl:attribute name="style">width: <xsl:value-of select="@width"/>px; margin: 0 auto;</xsl:attribute></xsl:if>
		<xsl:attribute name="class">
			<xsl:text>hui_view</xsl:text>
			<xsl:if test="@variant='box'"> hui_view_box</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates select="gui:toolbar"/>
		<xsl:apply-templates select="gui:content"/>
		<xsl:apply-templates select="gui:foot"/>
	</table>
</xsl:template>

<xsl:template match="gui:view/gui:content">
	<tr><td>
		<xsl:attribute name="class">view_content<xsl:if test="../@variant='box'"> view_content_box</xsl:if><xsl:if test="@background='true'"> view_content_background</xsl:if></xsl:attribute>
		<xsl:if test="@padding"><xsl:attribute name="style">padding: <xsl:value-of select="@padding"/>px;</xsl:attribute></xsl:if>
		<xsl:apply-templates/>
	</td></tr>
</xsl:template>

<xsl:template match="gui:view/gui:toolbar">
	<tr class="view_toolbar"><td class="view_toolbar">
		<xsl:call-template name="gui:toolbar" />
	</td></tr>
</xsl:template>

<xsl:template match="gui:view[@variant='box']/gui:toolbar">
	<tr class="view_toolbar view_toolbar_box"><td>
		<div class="view_toolbar_box"><div class="view_toolbar_box">
		<xsl:call-template name="gui:toolbar" />
		</div></div>
	</td></tr>
</xsl:template>

<xsl:template match="gui:view/gui:foot">
	<tr class="view_foot"><td>
		<xsl:comment/>
	</td></tr>
</xsl:template>

<xsl:template match="gui:view[@variant='box']/gui:foot">
	<tr class="view_foot view_foot_box"><td>
		<div class="view_foot_box"><div class="view_foot_box"><xsl:comment/></div></div>
	</td></tr>
</xsl:template>

</xsl:stylesheet>
