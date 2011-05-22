<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:ig="http://uri.in2isoft.com/onlinepublisher/publishing/imagegallery/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 exclude-result-prefixes="ig o i"
 >

<xsl:template match="ig:imagegallery">
<div class="imagegallery">
<xsl:apply-templates/>
</div>

<script type="text/javascript" src="{$path}hui/js/ImageViewer.js"><xsl:comment/></script>
<script type="text/javascript" src="{$path}style/basic/js/templates/ImageGallery.js"><xsl:comment/></script>
<script type="text/javascript">
	with (OP.ImageGallery.get()) {
		<xsl:for-each select="ig:images/o:object">
			addImage({id:'<xsl:value-of select="@id"/>','width':'<xsl:value-of select="o:sub/i:image/i:width"/>','height':'<xsl:value-of select="o:sub/i:image/i:height"/>'});
		</xsl:for-each>
	}
</script>
</xsl:template>

<xsl:template match="ig:imagegallery/ig:title">
<h1 class="common"><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="ig:custom">
</xsl:template>

<xsl:template match="ig:text">
<p class="common"><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="ig:break">
<br/>
</xsl:template>

<xsl:template match="ig:images">
<div><xsl:apply-templates select="o:object"/></div>
</xsl:template>

<xsl:template match="ig:images/o:object">
	<xsl:variable name="id" select="@id"/>
	<xsl:variable name="title">
		<xsl:choose>
			<xsl:when test="../../ig:custom/ig:image/@id=$id">
				<xsl:value-of select="../../ig:custom/ig:image[@id=$id]/ig:title"/>
			</xsl:when>
			<xsl:otherwise><xsl:value-of select="o:title"/></xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:variable name="note">
		<xsl:choose>
			<xsl:when test="../../ig:custom/ig:image/@id=$id">
				<xsl:value-of select="../../ig:custom/ig:image[@id=$id]/ig:note"/>
			</xsl:when>
			<xsl:otherwise><xsl:value-of select="o:note"/></xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:variable name="maxsize">
		<xsl:value-of select="../../ig:display/@size"/>
	</xsl:variable>
	<div class="image" style="width: {$maxsize}px; height: {$maxsize}px;" id="{generate-id()}">
	<xsl:if test="../../ig:display/@show-title='true' and $title!=''">
		<div class="title"><xsl:value-of select="$title"/></div>
	</xsl:if>
	<a href="{$path}services/images/?id={$id}" onclick="OP.ImageGallery.get().showImage({@id}); return false;">
	<xsl:if test="$highquality='false'">
		<img src="{$path}services/images/?id={$id}&amp;width={$maxsize}&amp;height={$maxsize}&amp;rotate={../../ig:display/@rotate}&amp;format=jpg" alt="">
			<xsl:choose>
				<xsl:when test="number(o:sub/i:image/i:width)>number(o:sub/i:image/i:height)">
					<xsl:attribute name="width"><xsl:value-of select="$maxsize"/></xsl:attribute>
				</xsl:when>
				<xsl:otherwise>
					<xsl:attribute name="height"><xsl:value-of select="$maxsize"/></xsl:attribute>
				</xsl:otherwise>
			</xsl:choose>
		</img>
	</xsl:if>
	<xsl:if test="$highquality='true'">
		<img src="{$path}services/images/?id={$id}&amp;rotate={../../ig:display/@rotate}" alt="">
			<xsl:choose>
				<xsl:when test="number(o:sub/i:image/i:width)>number(o:sub/i:image/i:height)">
					<xsl:attribute name="width"><xsl:value-of select="$maxsize"/></xsl:attribute>
				</xsl:when>
				<xsl:otherwise>
					<xsl:attribute name="height"><xsl:value-of select="$maxsize"/></xsl:attribute>
				</xsl:otherwise>
			</xsl:choose>
		</img>
	</xsl:if>
	</a>
	<xsl:if test="../../../../ig:display/@show-note='true' and $note!=''">
		<div><xsl:value-of select="$note"/></div>
	</xsl:if>
	</div>
</xsl:template>

</xsl:stylesheet>