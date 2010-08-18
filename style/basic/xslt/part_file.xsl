<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/part/file/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/class/file/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 exclude-result-prefixes="f"
 >

<xsl:template match="p:file">
	<xsl:variable name="href"><xsl:value-of select="$path"/>files/<xsl:value-of select=".//f:file/f:filename"/></xsl:variable>
	<div class="part_file common_font" id="{generate-id()}"><div><div><div>
		<p class="part_file_title"><a href="{$href}"><span><xsl:value-of select=".//o:title"/></span></a></p>
		<p class="part_file_info"><xsl:call-template name="p:size"/><xsl:if test=".//f:file/f:mimetype">, <xsl:call-template name="p:type"/></xsl:if><xsl:comment/></p>
	</div></div></div></div>
	<xsl:if test=".//f:file/f:mimetype='video/quicktime'">
		<script	type="text/javascript">
			$('<xsl:value-of select="generate-id()"/>').observe('click',function() {
				OP.showVideo({file:'<xsl:value-of select="$href"/>',width:300,height:200});
			});
		</script>
	</xsl:if>
</xsl:template>

<xsl:template name="p:size">
	<xsl:if test=".//f:file/f:size">
	<xsl:variable name="size" select="number(.//f:file/f:size)"/>
	<xsl:choose>
		<xsl:when test="$size>=1048576">
			<xsl:value-of select="round($size div 10485.76) div 100"/> Mb
		</xsl:when>
		<xsl:when test="$size>=1024">
			<xsl:value-of select="round($size div 10.24) div 100"/> Kb
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="$size"/> byte
		</xsl:otherwise>
	</xsl:choose>
	</xsl:if>
</xsl:template>

<xsl:template name="p:type">
	<xsl:variable name="type" select=".//f:file/f:mimetype"/>
	<xsl:choose>
		<xsl:when test="$type='application/x-photoshop'">
			Adobe Photoshop format
		</xsl:when>
		<xsl:when test="$type='application/zip'">
			ZIP-compressed format
		</xsl:when>
		<xsl:when test="$type='application/pdf'">
			PDF format
		</xsl:when>
		<xsl:when test="$type='image/png'">
			PNG format
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="$type"/>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>