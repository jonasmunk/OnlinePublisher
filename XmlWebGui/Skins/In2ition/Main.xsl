<?xml version="1.0"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:XmlWebGui"
    version="1.0"
    exclude-result-prefixes="xwg"
    >
<!--
<xsl:output encoding="UTF-8" doctype-public="-//W3C//DTD HTML 4.01 Transitional//EN" 
doctype-system="http://www.w3.org/TR/html4/loose.dtd"/>
-->
<xsl:variable name="path"><xsl:value-of select="/xwg:xmlwebgui/xwg:configuration/@path"/></xsl:variable>
<xsl:variable name="root" select="concat($path,'XmlWebGui/')"/>
<xsl:variable name="skin" select="concat($root,'Skins/In2ition/')"/>
<xsl:variable name="graphics" select="concat($skin,'Graphics/')"/>
<xsl:variable name="iconsetroot" select="concat($root,'Iconsets/')"/>
<xsl:variable name="iconset"><xsl:if test="/xwg:xmlgui/xwg:configuration/@iconset"><xsl:value-of select="$iconsetroot"/><xsl:value-of select="/xwg:xmlwebgui/xwg:configuration/@iconset"/>/</xsl:if><xsl:if test="not(/xwg:xmlwebgui/xwg:configuration/@iconset)"><xsl:value-of select="$iconsetroot"/>Basic/</xsl:if>
</xsl:variable>

<xsl:template match="xwg:xmlwebgui">
<html>
<xsl:apply-templates select="*[name()!='meta']"/>
</html>
</xsl:template>

<xsl:template match="xwg:interface">
<head>
<link rel="stylesheet" type="text/css" href="{$skin}Stylesheet.css?20060614"> </link>
<xsl:comment><![CDATA[[if IE]>
<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$skin"/><![CDATA[StylesheetIE.css?20060614"> </link>
<![endif]]]></xsl:comment>
<xsl:comment><![CDATA[[if IE]>
<script type="text/javascript" src="]]><xsl:value-of select="$root"/><![CDATA[Scripts/excanvas.js"> </script>
<![endif]]]></xsl:comment>
<script type="text/javascript" src="{$root}Scripts/In2iScripts.js"> </script>
<script type="text/javascript" src="{$root}Scripts/In2iRequest.js"> </script>
<script type="text/javascript" src="{$root}Scripts/In2iScripts/In2iAnimation.js"> </script>
<script type="text/javascript" src="{$root}Scripts/In2iBase.js"> </script>
<script type="text/javascript">
	function hilite(img) {
		img.src=img.src.replace('Standard','Hilited');
	}
	function unHilite(img) {
		img.src=img.src.replace('Hilited','Standard');
	}
	var In2iGui = {};
	In2iGui.paths = {iconset:'<xsl:value-of select="$iconset"/>',graphics:'<xsl:value-of select="$graphics"/>'};
</script>
<xsl:apply-templates select="../xwg:meta"/>
</head>
<body style="margin: 0px;">
<xsl:if test="@background"><xsl:attribute name="class">Background<xsl:value-of select="@background"/></xsl:attribute></xsl:if>
<xsl:if test="@onload"><xsl:attribute name="onload"><xsl:value-of select="@onload"/></xsl:attribute></xsl:if>
<xsl:apply-templates/>
</body>
</xsl:template>

<xsl:template match="xwg:meta">
<xsl:if test="xwg:title">
<title><xsl:value-of select="xwg:title"/></title>
</xsl:if>
<xsl:if test="../xwg:interface/@title">
<title><xsl:value-of select="../xwg:interface/@title"/></title>
</xsl:if>
</xsl:template>

<xsl:template name="link">
<xsl:if test="not(@link)">
<xsl:attribute name="style">cursor: default;</xsl:attribute>
</xsl:if>
<xsl:if test="@link">
<xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:if>
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute>
</xsl:template>

<xsl:template name="style">
<xsl:if test="@style">
<xsl:value-of select="@style"/>
</xsl:if>
<xsl:if test="not(@style)">Standard</xsl:if>
</xsl:template>

<xsl:template name="onchange">
<xsl:if test="@onchange">
<xsl:attribute name="onchange"><xsl:value-of select="@onchange"/></xsl:attribute>
</xsl:if>
</xsl:template>

<xsl:template name="onblur">
<xsl:if test="@onblur">
<xsl:attribute name="onblur"><xsl:value-of select="@onblur"/></xsl:attribute>
</xsl:if>
</xsl:template>

<xsl:template name="onfocus">
<xsl:if test="@onfocus">
<xsl:attribute name="onfocus"><xsl:value-of select="@onfocus"/></xsl:attribute>
</xsl:if>
</xsl:template>

<xsl:template name="onmouseover">
<xsl:if test="@onmouseover">
<xsl:attribute name="onmouseover"><xsl:value-of select="@onmouseover"/></xsl:attribute>
</xsl:if>
</xsl:template>

<xsl:template name="onmouseout">
<xsl:if test="@onmouseout">
<xsl:attribute name="onmouseout"><xsl:value-of select="@onmouseout"/></xsl:attribute>
</xsl:if>
</xsl:template>

<xsl:template name="events">
<xsl:call-template name="onchange"/>
<xsl:call-template name="onblur"/>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
<xsl:if test="@onclick">
<xsl:attribute name="onclick"><xsl:value-of select="@onclick"/></xsl:attribute>
</xsl:if>
<xsl:if test="@ondblclick">
<xsl:attribute name="ondblclick"><xsl:value-of select="@ondblclick"/></xsl:attribute>
</xsl:if>
<xsl:if test="@onmousedown">
<xsl:attribute name="onmousedown"><xsl:value-of select="@onmousedown"/></xsl:attribute>
</xsl:if>
<xsl:if test="@onmousemove">
<xsl:attribute name="onmousemove"><xsl:value-of select="@onmousemove"/></xsl:attribute>
</xsl:if>
<xsl:if test="@onmouseup">
<xsl:attribute name="onmouseup"><xsl:value-of select="@onmouseup"/></xsl:attribute>
</xsl:if>
</xsl:template>

</xsl:stylesheet>