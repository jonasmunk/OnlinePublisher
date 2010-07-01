<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:m="uri:Matrix"
    version="1.0"
    exclude-result-prefixes="m"
    >

<xsl:template match="m:matrix">
<input type="hidden" name="added" id="matrix_{generate-id()}_added"/>
<input type="hidden" name="removed" id="matrix_{generate-id()}_removed"/>
<script src="{$root}Scripts/Matrix.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript">
var matrix_<xsl:value-of select="generate-id()"/> = new XWGMatrix('<xsl:value-of select="generate-id()"/>');
</script>
<table border="0" width="100%" cellspacing="0">
<xsl:apply-templates/>
</table>
</xsl:template>

<xsl:template match="m:columns">
<tr>
	<th style="border-right: solid 1px #aaa; border-bottom: solid 1px #aaa; width: 1%; font-size: 10pt; text-align: left; padding-left: 6px; color: #999;"><xsl:value-of select="@header"/></th>
	<xsl:apply-templates select="m:column"/>
</tr>
</xsl:template>

<xsl:template match="m:column">
<th style="border-bottom: solid 1px #aaa; border-right: solid 1px #eee; font-size: 10pt;"><xsl:value-of select="@title"/></th>
</xsl:template>

<xsl:template match="m:row">
<tr>
	<th style="border-right: solid 1px #aaa; text-align: left; padding: 2px 6px 2px 6px; border-bottom: solid 1px #eee;">
		<table cellspacing="2" cellpadding="0" border="0"><tr>
		<xsl:if test="@icon">
		<td><img border="0" width="16" height="16" src="{$iconset}{@icon}Standard1.gif" style="margin-right: 2px;"/></td>
		</xsl:if>
		<td style=" white-space: nowrap; font-size: 10pt; font-weight: normal;"><xsl:value-of select="@title"/></td>
	</tr></table>
	</th>
	<xsl:apply-templates select="m:cell"/>
</tr>
</xsl:template>

<xsl:template match="m:cell">
<td style="text-align: center; border-bottom: solid 1px #eee;border-right: solid 1px #eee;"><xsl:apply-templates/></td>
</xsl:template>

<xsl:template match="m:boolean">
	<xsl:variable name="matrix_object">
		<xsl:text>matrix_</xsl:text><xsl:value-of select="generate-id(../../..)"/>
	</xsl:variable>
	<input type="checkbox" value="{@value}" id="matrix_boolean_{generate-id()}" onclick="{$matrix_object}.toggleBoolean('matrix_boolean_{generate-id()}')">
	<xsl:if test="@selected='true'">
	<xsl:attribute name="checked">checked</xsl:attribute>
	</xsl:if>
	</input>
</xsl:template>

</xsl:stylesheet>