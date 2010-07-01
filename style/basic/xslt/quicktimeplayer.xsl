<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"

 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:qtp="http://uri.in2isoft.com/onlinepublisher/publishing/quicktimeplayer/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/class/file/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 exclude-result-prefixes="qtp f o"
 >

<xsl:template match="qtp:quicktimeplayer">
<div class="quicktimeplayer">
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="qtp:title[.!='']">
<h1 class="common"><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="qtp:text[.!='']">
<p class="common"><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="qtp:break">
<br/>
</xsl:template>

<xsl:template match="qtp:quicktimeplayer/o:object">
<xsl:apply-templates select="o:sub"/>
</xsl:template>

<xsl:template match="qtp:quicktimeplayer/o:object/o:sub/f:file">
<xsl:variable name="width"><xsl:value-of select="//qtp:display/@width"/></xsl:variable>
<xsl:variable name="height"><xsl:value-of select="//qtp:display/@height+16"/></xsl:variable>
<xsl:variable name="source"><xsl:value-of select="$path"/>files/<xsl:value-of select="f:filename"/></xsl:variable>
<object codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="{$width}" height="{$height}" id="Movie">
	<param name="cache" value="true"/>
	<param name="src" value="{$source}"/>
	<param name="autoplay" value="false"/>
	<param name="controller" value="true"/>
	<embed pluginspage="http://www.apple.com/quicktime/download/" src="{$source}" type="video/quicktime" controller="false" autoplay="false" cache="true" name="Movie" width="{$width}" height="{$height}"/>
</object>
<br/>
<a href="#" class="common restart">Genstart</a> ·
<a href="#" class="common play">Afspil</a> ·
<a href="#" class="common stop">Stop</a>
<script language="javascript">
</script>
<script src="{$path}style/basic/js/templates/QuicktimePlayer.js" type="text/javascript" charset="utf-8"></script>
</xsl:template>

</xsl:stylesheet>