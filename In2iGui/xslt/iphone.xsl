<?xml version="1.0"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:i="uri:In2iPhone"
    xmlns:gui="uri:In2iGui"
    version="1.0"
    exclude-result-prefixes="i gui"
>

<xsl:template match="i:iphone">
	<html>

	<head>
	<title><xsl:value-of select="@title"/></title>
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	<xsl:choose>
		<xsl:when test="$dev='true'">
			<link rel="stylesheet" href="{$context}/In2iGui/iphone/css/iphone.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		</xsl:when>
		<xsl:otherwise>
			<link rel="stylesheet" href="{$context}/In2iGui/iphone/css/iphone.min.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		</xsl:otherwise>
	</xsl:choose>

	<xsl:choose>
		<xsl:when test="$dev='true'">
			<script src="{$context}/In2iGui/lib/prototype.min.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			<script src="{$context}/In2iGui/lib/In2iScripts/In2iScripts.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			<script src="{$context}/In2iGui/js/In2iGui.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
			<script src="{$context}/In2iGui/iphone/js/In2iPhone.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		</xsl:when>
		<xsl:otherwise>
			<script src="{$context}/In2iGui/iphone/bin/minimized.js" type="text/javascript" charset="utf-8"><xsl:comment/></script>
		</xsl:otherwise>
	</xsl:choose>

	<xsl:for-each select="i:controller">
	<script src="{@source}" type="text/javascript" charset="utf-8"><xsl:comment/></script>
	</xsl:for-each>
	<script type="text/javascript">
	In2iGui.state = '<xsl:value-of select="@state"/>';
	In2iGui.context = '<xsl:value-of select="$context"/>';
	<xsl:for-each select="i:controller">
		In2iGui.get().listen(<xsl:value-of select="@name"/>);
	</xsl:for-each>
	</script>
	<xsl:call-template name="dwr-setup"/>
	</head>
	<body>
		<xsl:apply-templates/>
	</body>
</html>
</xsl:template>


<xsl:template match="i:pages">
	<div class="pages">
		<div style="width: {count(i:page)*100}%" class="pages_content">
			<xsl:apply-templates select="i:page"/>
		</div>
	</div>
</xsl:template>

<xsl:template match="i:page">
	<div id="{generate-id()}">
		<xsl:attribute name="class">page <xsl:if test="position()>1"> hidden_right</xsl:if></xsl:attribute>
		<xsl:apply-templates/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iPhone.Page('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@name"/>');
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="i:header">
	<h1 class="header">
		<xsl:value-of select="."/>
	</h1>
</xsl:template>

<xsl:template match="i:toolbar">
	<div class="toolbar">
		<xsl:apply-templates/><xsl:comment/>
	</div>
</xsl:template>


<xsl:template match="i:buttonlist">
	<div class="buttonlist">
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="i:button">
	<a class="button" id="{generate-id()}">
		<xsl:attribute name="class">button<xsl:if test="@disabled='true'"> disabled</xsl:if></xsl:attribute>
		<strong>
		<xsl:if test="@icon">
		<span class="icon" >
			<xsl:attribute name="style">background-image: url('<xsl:value-of select="$context"/>/In2iGui/icons/<xsl:value-of select="@icon"/>2.png');</xsl:attribute>
		</span>
		</xsl:if>
		<xsl:value-of select="@title"/>
		</strong>
		</a>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iPhone.Button('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@name"/>');
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

</xsl:stylesheet>
