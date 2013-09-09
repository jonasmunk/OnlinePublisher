<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/part/movie/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/class/file/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 exclude-result-prefixes="f p o"
 >

<xsl:template match="p:movie">
	<div class="part_movie" id="{generate-id()}">
		<xsl:choose>
			<xsl:when test="$editor='true'">
				<div style="width: 640px; height: 480px; background: #eee;">
					<xsl:choose>
						<xsl:when test="p:text">
							<xsl:value-of select="p:text"/>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select=".//o:title"/>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:comment/>
				</div>
			</xsl:when>
			<xsl:otherwise>
				<video id="{generate-id()}_video" class="video-js vjs-default-skin"
					controls="true" preload="auto" width="640" height="480"
					poster="http://video-js.zencoder.com/oceans-clip.png">
					<xsl:attribute name="data-setup">
						<xsl:text>{"example_option":true}</xsl:text>
					</xsl:attribute>
					<source type='video/mp4'>
						<xsl:attribute name="src">
							<xsl:value-of select="$path"/><xsl:text>files/</xsl:text><xsl:value-of select=".//f:file/f:filename"/>
						</xsl:attribute>
					</source>
				</video>
			</xsl:otherwise>
		</xsl:choose>
	</div>
</xsl:template>

</xsl:stylesheet>