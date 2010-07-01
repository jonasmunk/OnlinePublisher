<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:m="uri:Menu"
    version="1.0"
    exclude-result-prefixes="m"
    >

<xsl:template match="m:menu">
<xsl:if test="generate-id(//m:menu[1])=generate-id()">
<script language="JavaScript" src="{$root}Scripts/In2iMenu.js"></script>
<script>
var in2iMenuImagePath='<xsl:value-of select="$graphics"/>Menu/';
</script>
</xsl:if>
<script language="JavaScript">
<xsl:if test="@id">
var <xsl:value-of select="@id"/> = new In2iMenu;
<xsl:apply-templates>
<xsl:with-param name="parent"><xsl:value-of select="@id"/></xsl:with-param>
</xsl:apply-templates>
document.write(<xsl:value-of select="@id"/>);
</xsl:if>
<xsl:if test="not(@id)">
var <xsl:value-of select="generate-id()"/> = new In2iMenu;
<xsl:if test="@width">
	<xsl:value-of select="generate-id()"/>.width = <xsl:value-of select="@width"/>;
</xsl:if>
<xsl:apply-templates>
<xsl:with-param name="parent"><xsl:value-of select="generate-id()"/></xsl:with-param>
</xsl:apply-templates>
document.write(<xsl:value-of select="generate-id()"/>);
</xsl:if>
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="generate-id()"/>;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="m:item">
<xsl:param name="parent"/>
<xsl:apply-templates/>
<xsl:value-of select="$parent"/>.add(new In2iMenuItem("<xsl:value-of select="@title"/>",
<xsl:if test="@link">"<xsl:value-of select="@link"/>"</xsl:if>
<xsl:if test="not(@link)">null</xsl:if>,
<xsl:if test="@help">"<xsl:value-of select="@help"/>"</xsl:if>
<xsl:if test="not(@help)">null</xsl:if>,
<xsl:if test="m:menu"><xsl:value-of select="generate-id(m:menu)"/></xsl:if>
<xsl:if test="not(m:menu)">null</xsl:if>,
<xsl:if test="@target">"<xsl:value-of select="@target"/>"</xsl:if>
<xsl:if test="not(@target)">null</xsl:if>
));
</xsl:template>

<xsl:template match="m:item/m:menu">
var <xsl:value-of select="generate-id()"/> = new In2iMenu;
<xsl:if test="@width">
	<xsl:value-of select="generate-id()"/>.width = <xsl:value-of select="@width"/>;
</xsl:if>
<xsl:apply-templates>
<xsl:with-param name="parent"><xsl:value-of select="generate-id()"/></xsl:with-param>
</xsl:apply-templates>
</xsl:template>

<xsl:template match="m:separator">
<xsl:param name="parent"/>
<xsl:value-of select="$parent"/>.add(new In2iMenuSeparator());
</xsl:template>

</xsl:stylesheet>