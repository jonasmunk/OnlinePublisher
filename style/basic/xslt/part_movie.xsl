<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/part/movie/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/class/file/1.0/"
 xmlns:img="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
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
        <div class="part_movie_body">
            <xsl:attribute name="style">
                <xsl:if test="$editor='true'">
                    <xsl:text>background-color: #eee;</xsl:text>
                </xsl:if>
                <xsl:value-of select="$size"/>
                <xsl:if test="p:poster//img:image">
                    background-image: url('<xsl:value-of select="$path"/>services/images/?id=<xsl:value-of select="p:poster//o:object/@id"/>');
                </xsl:if>
            </xsl:attribute>
            <xsl:if test="$editor!='true'">
                <xsl:choose>
                    <xsl:when test=".//f:file">
                        <noscript class="part_movie_video">
          				<video id="{generate-id()}_video" controls="true" preload="auto" style="{$size}">
          					<source type="video/mp4"><!--{.//f:file/f:mimetype}-->
          						<xsl:attribute name="src">
          							<xsl:value-of select="$path"/>
                                      <xsl:text>files/</xsl:text>
                                      <xsl:value-of select=".//f:file/f:filename"/>
          						</xsl:attribute>
          					</source>
                        </video>
                        </noscript>
                    </xsl:when>
                    <xsl:when test="p:code">
                        <noscript class="part_movie_code">
                            <xsl:value-of select="p:code" disable-output-escaping="yes"/>
                        </noscript>
                    </xsl:when>
                    <xsl:otherwise>
                        
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:if>
        </div>
	</div>
    <script type="text/javascript">
        _editor.defer(function() {
            new op.part.Movie({
                element : '<xsl:value-of select="generate-id()"/>'
            })
        });
    </script>
</xsl:template>

</xsl:stylesheet>