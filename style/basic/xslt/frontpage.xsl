<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:fp="http://uri.in2isoft.com/onlinepublisher/publishing/frontpage/1.0/"
 xmlns:pt="http://uri.in2isoft.com/onlinepublisher/part/1.0/"
 xmlns:txt="http://uri.in2isoft.com/onlinepublisher/part/text/1.0/"
 xmlns:html="http://uri.in2isoft.com/onlinepublisher/part/html/1.0/"
 exclude-result-prefixes="fp pt txt html"
 >

<xsl:include href="part_header.xsl"/>
<xsl:include href="part_text.xsl"/>
<xsl:include href="part_html.xsl"/>
<xsl:include href="part_horizontalrule.xsl"/>
<xsl:include href="part_image.xsl"/>
<xsl:include href="part_listing.xsl"/>
<xsl:include href="part_news.xsl"/>
<xsl:include href="part_person.xsl"/>
<xsl:include href="part_richtext.xsl"/>

<xsl:template match="fp:content">
<table width="100%" cellpadding="0" cellspacing="10" border="0">
<xsl:apply-templates/>
</table>
</xsl:template>

<xsl:template match="fp:row">
<tr><xsl:apply-templates/></tr>
</xsl:template>

<xsl:template match="fp:cell">
<td class="FrontPageCell">
<xsl:call-template name="fp:cellatts"/>
<xsl:if test="@title">
<div class="FrontPageCellTitle"><xsl:value-of select="@title"/></div>
</xsl:if>
<xsl:apply-templates/>
</td>
</xsl:template>

<xsl:template match="fp:cell[@type='box']">
<td class="FrontPageCellBox">
<xsl:call-template name="fp:cellatts"/>
<xsl:if test="@title">
<div class="FrontPageCellBoxTitle"><xsl:value-of select="@title"/></div>
</xsl:if>
<xsl:apply-templates/>
</td>
</xsl:template>

<xsl:template name="fp:cellatts">
<xsl:attribute name="colspan"><xsl:value-of select="@columns"/></xsl:attribute>
<xsl:attribute name="rowspan"><xsl:value-of select="@rows"/></xsl:attribute>
<xsl:attribute name="valign">top</xsl:attribute>
<xsl:if test="@width"><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute></xsl:if>
<xsl:if test="@height"><xsl:attribute name="style">height: <xsl:value-of select="@height"/>px;</xsl:attribute></xsl:if>
</xsl:template>

<xsl:template match="fp:section">
<div class="FrontPageSection">
<xsl:apply-templates/>
</div>
</xsl:template>



<!--          Part            -->

<xsl:template match="pt:part">
<xsl:apply-templates select="pt:sub/*"/>
</xsl:template>




</xsl:stylesheet>