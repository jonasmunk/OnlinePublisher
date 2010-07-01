<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:r="uri:Result"
    version="1.0"
    exclude-result-prefixes="r"
    >

<xsl:template match="r:result">
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
	<tr>
		<xsl:apply-templates select="r:content"/>
		<xsl:apply-templates select="r:sidebar"/>
	</tr>
</table>
</xsl:template>

<xsl:template match="r:sidebar">
<td class="ResultSidebar" valign="top">
<div class="Fixed"><xsl:apply-templates/></div>&#160;</td>
</xsl:template>

<xsl:template match="r:block">
<xsl:if test="position()>1"><hr/></xsl:if>
<div class="Block">
<xsl:if test="@title"><strong><xsl:value-of select="@title"/></strong></xsl:if>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="r:selection">
<xsl:variable name="object-name"><xsl:value-of select="generate-id()"/>_object</xsl:variable>
<div class="Selection"><xsl:apply-templates/></div>
<script src="{$root}Scripts/ResultSelection.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$object-name"/> = new In2iGui.ResultSelection('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="@object"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$object-name"/>;
</xsl:if>
<xsl:for-each select="r:item">
	<xsl:value-of select="$object-name"/>.registerItem('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@value"/>');
</xsl:for-each>
</script>
</xsl:template>

<xsl:template match="r:selection/r:item">
<a id="{generate-id()}">
<xsl:if test="../@value=@value">
<xsl:attribute name="class">Selected</xsl:attribute>
</xsl:if>
<xsl:value-of select="@title"/></a>
</xsl:template>

<xsl:template match="r:content">
<td valign="top">
<xsl:apply-templates/>
<xsl:if test="not(node())">&#160;</xsl:if>
</td>
</xsl:template>

<xsl:template match="r:group">
<xsl:variable name="image">
<xsl:choose>
<xsl:when test="@open='false'">ResultGroupClosed.gif</xsl:when>
<xsl:otherwise>ResultGroupOpen.gif</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:variable name="object-name"><xsl:value-of select="generate-id()"/>_object</xsl:variable>
<div class="ResultGroupTitle" onclick="{generate-id()}_object.toggle()"><img src="{$graphics}{$image}" id="{generate-id()}_group_title_disclosure" style="width: 10px; height: 10px; border: 0px; margin: 0px 3px 0px 3px;"/><xsl:value-of select="@title"/></div>
<div id="{generate-id()}_group_content">
<xsl:if test="@open='false'"><xsl:attribute name="style">display: none;</xsl:attribute></xsl:if>
<xsl:apply-templates/>
</div>
<script src="{$root}Scripts/ResultGroup.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$object-name"/> = new In2iGui.ResultGroup('<xsl:value-of select="generate-id()"/>');
<xsl:if test="@open-action">
<xsl:value-of select="$object-name"/>.openAction = '<xsl:value-of select="@open-action"/>';
</xsl:if>
<xsl:if test="@close-action">
<xsl:value-of select="$object-name"/>.closeAction = '<xsl:value-of select="@close-action"/>';
</xsl:if>
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$object-name"/>;
</xsl:if>
</script>
</xsl:template>

</xsl:stylesheet>