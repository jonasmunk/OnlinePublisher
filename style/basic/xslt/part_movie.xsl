<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/part/movie/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/class/file/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 exclude-result-prefixes="f p o"
 >

<xsl:template match="p:movie">
    <xsl:variable name="size">
        <xsl:if test="p:style/@width">
            <xsl:text>width:</xsl:text><xsl:value-of select="p:style/@width"/><xsl:text>;</xsl:text>                            
        </xsl:if>
        <xsl:if test="p:style/@height">
            <xsl:text>height:</xsl:text><xsl:value-of select="p:style/@height"/><xsl:text>;</xsl:text>
        </xsl:if>
    </xsl:variable>
	<div class="part_movie" id="{generate-id()}">
        <div class="part_movie_body" style="{$size}background: #eee;">
            <xsl:if test="$editor!='true'">
                <xsl:choose>
                    <xsl:when test=".//f:file">
          				<video id="{generate-id()}_video" controls="true" preload="auto" style="{$size}">
          					<source type="video/mp4"><!--{.//f:file/f:mimetype}-->
          						<xsl:attribute name="src">
          							<xsl:value-of select="$path"/>
                                      <xsl:text>files/</xsl:text>
                                      <xsl:value-of select=".//f:file/f:filename"/>
          						</xsl:attribute>
          					</source>
                        </video>                    
                    </xsl:when>
                    <xsl:when test="p:code">
                        <xsl:value-of select="p:code"/>
                    </xsl:when>
                    <xsl:otherwise>

                    </xsl:otherwise>
                </xsl:choose>
            </xsl:if>
        </div>
	</div>
</xsl:template>

</xsl:stylesheet>