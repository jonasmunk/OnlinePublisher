<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Frame"
    xmlns:webgui="uri:XmlWebGui"
    xmlns:s="uri:Script"
    version="1.0"
    exclude-result-prefixes="xwg webgui s"
    >

<xsl:template match="xwg:interface">
<head>
<link rel="stylesheet" type="text/css" href="{$skin}Stylesheet.css"></link>
<script type="text/javascript" src="{$root}Scripts/In2iScripts.js"> </script>
<xsl:apply-templates select="../webgui:meta"/>
<xsl:apply-templates select="s:script"/>
</head>
<xsl:apply-templates select="xwg:dock"/>
</xsl:template>

<xsl:template match="xwg:dock">
<frameset border="0" frameborder="0" framespacing="0" id="{@align}">
<xsl:if test="@align='bottom'"><xsl:attribute name="rows">*,<xsl:if test="@tabs">70</xsl:if><xsl:if test="not(@tabs)">55</xsl:if></xsl:attribute></xsl:if>
<xsl:if test="@align='top'"><xsl:attribute name="rows"><xsl:if test="@tabs">70</xsl:if><xsl:if test="not(@tabs)">55</xsl:if>,*</xsl:attribute></xsl:if>
<xsl:apply-templates/>
</frameset>
</xsl:template>

<xsl:template match="xwg:frame">
<frame border="0" marginwidth="0" marginheight="0" frameborder="0">
<xsl:if test="@name"><xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute></xsl:if>
<xsl:if test="@source"><xsl:attribute name="src"><xsl:value-of select="@source"/></xsl:attribute></xsl:if>
<xsl:if test="not(@scrolling)"><xsl:attribute name="scrolling">auto</xsl:attribute></xsl:if>
<xsl:if test="@scrolling='true'"><xsl:attribute name="scrolling">yes</xsl:attribute></xsl:if>
<xsl:if test="@scrolling='false'"><xsl:attribute name="scrolling">no</xsl:attribute></xsl:if>
<xsl:if test="not(@resize='true')"><xsl:attribute name="noresize">noresize</xsl:attribute></xsl:if>
<xsl:apply-templates/>
</frame>
</xsl:template>

<xsl:template match="xwg:iframe">
<iframe ALLOWTRANSPARENCY="true" id="{generate-id()}" style="width: 100%; height: 100%; border-width: 0px; background-image: url({$graphics}FrameLoader.gif); background-repeat: no-repeat; background-position: center center; background-color: white;" frameborder="0" src="{@source}" onload="this.style.backgroundImage=''">
<xsl:if test="@name"><xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute></xsl:if>
<xsl:if test="@scrolling">
<xsl:attribute name="scrolling">
<xsl:if test="@scrolling='false'">no</xsl:if>
<xsl:if test="@scrolling='true'">yes</xsl:if>
</xsl:attribute>
</xsl:if>
<xsl:text> </xsl:text>
</iframe>
<xsl:if test="@object">
<script src="{$root}Scripts/Iframe.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new XWGIframe('<xsl:value-of select="generate-id()"/>');
</script>
</xsl:if>
</xsl:template>

</xsl:stylesheet>