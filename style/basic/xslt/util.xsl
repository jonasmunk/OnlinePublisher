<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 >

<xsl:template name="link">
<xsl:attribute name="title"><xsl:value-of select="@alternative"/></xsl:attribute>
<xsl:choose>
<xsl:when test="@path and $preview='false'">
<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/><xsl:value-of select="@path"/></xsl:attribute>
</xsl:when>
<xsl:when test="@page">
<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page"/></xsl:attribute>
</xsl:when>
<xsl:when test="@page-reference">
<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page-reference"/></xsl:attribute>
</xsl:when>
<xsl:when test="@url">
<xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
</xsl:when>
<xsl:when test="@file">
<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?file=<xsl:value-of select="@file"/><xsl:if test="@target='_download'">&amp;download=true</xsl:if></xsl:attribute>
</xsl:when>
<xsl:when test="@email">
<xsl:attribute name="href">mailto:<xsl:value-of select="@email"/></xsl:attribute>
</xsl:when>
</xsl:choose>
<xsl:choose>
<xsl:when test="@target='_blank'">
<xsl:attribute name="onclick">try {window.open(this.getAttribute('href')); return false;} catch (igonre) {}</xsl:attribute>
</xsl:when>
<xsl:when test="@target and @target!='_self' and @target!='_download'">
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:when>
</xsl:choose>
</xsl:template>

<xsl:template name="link-href">
<xsl:choose>
<xsl:when test="@path and $preview='false'">
<xsl:value-of select="$navigation-path"/><xsl:value-of select="@path"/>
</xsl:when>
<xsl:when test="@page">
<xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page"/>
</xsl:when>
<xsl:when test="@page-reference">
<xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page-reference"/>
</xsl:when>
<xsl:when test="@url">
<xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
</xsl:when>
<xsl:when test="@file">
<xsl:value-of select="$navigation-path"/>?file=<xsl:value-of select="@file"/><xsl:if test="@target='_download'">&amp;download=true</xsl:if>
</xsl:when>
<xsl:when test="@email">
mailto:<xsl:value-of select="@email"/>
</xsl:when>
</xsl:choose>
</xsl:template>

<xsl:template name="oo-script">
	<link rel="stylesheet" type="text/css" href="{$path}In2iGui/bin/minimized.css"/>
	<xsl:comment><![CDATA[[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><![CDATA[In2iGui/css/msie6.css"> </link>
	<![endif]]]></xsl:comment>
	<xsl:comment><![CDATA[[if IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><![CDATA[In2iGui/css/msie7.css"> </link>
	<![endif]]]></xsl:comment>
	<script src="{$path}In2iGui/bin/minimized.site.js" type="text/javascript"><xsl:comment/></script>
	<script src="{$path}style/basic/js/OnlinePublisher.js" type="text/javascript"><xsl:comment/></script>
	<script type="text/javascript"><xsl:comment>
		In2iGui.context = '<xsl:value-of select="$path"/>';
		op.page.id=<xsl:value-of select="@id"/>;
		op.page.template='<xsl:value-of select="$template"/>';
		op.page.path='<xsl:value-of select="$path"/>';
		op.page.pagePath='<xsl:value-of select="$page-path"/>';
		op.preview=<xsl:value-of select="$preview"/>;
		op.ignite();
	</xsl:comment></script>
	<xsl:if test="$preview='true'">
		<script src="{$path}In2iGui/js/Window.js" type="text/javascript"><xsl:comment/></script>
		<script src="{$path}In2iGui/js/Formula.js" type="text/javascript"><xsl:comment/></script>
		<script src="{$path}In2iGui/js/Button.js" type="text/javascript"><xsl:comment/></script>
		<script src="{$path}In2iGui/js/Overlay.js" type="text/javascript"><xsl:comment/></script>
		<script src="{$path}In2iGui/js/Editor.js" type="text/javascript"><xsl:comment/></script>
		<script src="editor.js" type="text/javascript"><xsl:comment/></script>
	</xsl:if>
</xsl:template>

<xsl:template name="oo-meta">
	<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
	<meta name="robots" content="index,follow"></meta>
</xsl:template>

<xsl:template name="oo-style">
	<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/stylesheet.css"/>
	<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/{$template}.css"/>
	<xsl:comment><![CDATA[[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie6.css"> </link>
	<![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:weekday">
	<xsl:param name="node"/>
	<xsl:choose>
		<xsl:when test="$node/@weekday=0">Søndag</xsl:when>
		<xsl:when test="$node/@weekday=1">Mandag</xsl:when>
		<xsl:when test="$node/@weekday=2">Tirsdag</xsl:when>
		<xsl:when test="$node/@weekday=3">Onsdag</xsl:when>
		<xsl:when test="$node/@weekday=4">Torsdag</xsl:when>
		<xsl:when test="$node/@weekday=5">Fredag</xsl:when>
		<xsl:when test="$node/@weekday=6">Lørdag</xsl:when>
	</xsl:choose>
</xsl:template>

<xsl:template name="util:month">
	<xsl:param name="node"/>
	<xsl:choose>
		<xsl:when test="number($node/@month)=1">januar</xsl:when>
		<xsl:when test="number($node/@month)=2">februar</xsl:when>
		<xsl:when test="number($node/@month)=3">marts</xsl:when>
		<xsl:when test="number($node/@month)=4">april</xsl:when>
		<xsl:when test="number($node/@month)=5">maj</xsl:when>
		<xsl:when test="number($node/@month)=6">juni</xsl:when>
		<xsl:when test="number($node/@month)=7">juli</xsl:when>
		<xsl:when test="number($node/@month)=8">august</xsl:when>
		<xsl:when test="number($node/@month)=9">september</xsl:when>
		<xsl:when test="number($node/@month)=10">oktober</xsl:when>
		<xsl:when test="number($node/@month)=11">november</xsl:when>
		<xsl:when test="number($node/@month)=12">december</xsl:when>
	</xsl:choose>
</xsl:template>

<xsl:template name="util:long-date-time">
	<xsl:param name="node"/>
		<xsl:call-template name="util:weekday"><xsl:with-param name="node" select="$node"/></xsl:call-template>
		<xsl:text> d. </xsl:text>
		<xsl:value-of select="number($node/@day)"/><xsl:text>. </xsl:text>
		<xsl:call-template name="util:month"><xsl:with-param name="node" select="$node"/></xsl:call-template>
		<xsl:if test="number($node/@hour)>0 or number($node/@minute)>0">
			<xsl:text> kl. </xsl:text><xsl:value-of select="$node/@hour"/>:<xsl:value-of select="$node/@minute"/>
		</xsl:if>
</xsl:template>

<xsl:template name="util:googleanalytics">
	<xsl:param name="code"/>
	<xsl:if test="not($preview='true')">
		<script	src="http://www.google-analytics.com/ga.js" type="text/javascript"><xsl:comment/></script>
		<script type="text/javascript">
		try {
			if (document.location.hostname!=="localhost") {
				var pageTracker = _gat._getTracker("<xsl:value-of select="$code"/>");
				pageTracker._trackPageview();
			}
		} catch(ex) {}
		</script>
	</xsl:if>
</xsl:template>

<xsl:template name="util:metatags">
	<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
	<xsl:if test="p:meta/p:description">
		<meta name="Description" content="{p:meta/p:description}"></meta>
	</xsl:if>
	<meta name="robots" content="index,follow"></meta>
</xsl:template>

<xsl:template name="util:languages">
	<span class="layout_languages">
		<xsl:for-each select="//p:page/p:context/p:home[@language and @language!=//p:page/p:meta/p:language and not(@language=//p:page/p:context/p:translation/@language)]">
			<xsl:call-template name="util:language"/>
		</xsl:for-each>
		<xsl:for-each select="//p:page/p:context/p:translation">
			<xsl:call-template name="util:language"/>
		</xsl:for-each>
		<xsl:comment/>
	</span>
</xsl:template>

<xsl:template name="util:language">
	<a class="layout_language_{@language}">
		<xsl:call-template name="link"/>
		<span>
		<xsl:choose>
			<xsl:when test="@language='da'">Dansk version</xsl:when>
			<xsl:when test="@language='en'">English version</xsl:when>
			<xsl:otherwise><xsl:value-of select="@language"/></xsl:otherwise>
		</xsl:choose>
		</span>
	</a><xsl:text> </xsl:text>
</xsl:template>

</xsl:stylesheet>