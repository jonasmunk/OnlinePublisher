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
        <xsl:if test="p:style/@height">
            <xsl:text>padding-bottom:</xsl:text><xsl:value-of select="p:style/@height"/><xsl:text>;</xsl:text>
        </xsl:if>
    </xsl:variable>
	<div class="part_movie" id="{generate-id()}">
        <xsl:if test="p:style/@width">
            <xsl:attribute name="style">
            <xsl:text>width:</xsl:text><xsl:value-of select="p:style/@width"/><xsl:text>;</xsl:text>
			</xsl:attribute>                         
        </xsl:if>
        <div class="part_movie_body">
            <xsl:attribute name="style">
                <xsl:value-of select="$size"/>
            </xsl:attribute>

			<div class="part_movie_poster">
            <xsl:if test="p:poster//img:image">
            		<xsl:attribute name="style">
                		background-image: url('<xsl:value-of select="$path"/>services/images/?id=<xsl:value-of select="p:poster//o:object/@id"/>');
					</xsl:attribute>
            </xsl:if>
				<xsl:if test="p:text">
					<h2 class="part_movie_text"><span class="part_movie_text_inner"><xsl:value-of select="p:text"/></span></h2>
				</xsl:if>
					<xsl:comment/>
				</div>
            <xsl:if test="$editor!='true'">
                <xsl:choose>
                    <xsl:when test="p:source[@type='vimeo']">
                        <noscript>
							<iframe class="part_movie_embedded"
								src="//player.vimeo.com/video/{p:source/@id}?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;autoplay=1"
								width="100%" height="100%" frameborder="0" 
								webkitallowfullscreen="webkitallowfullscreen" mozallowfullscreen="mozallowfullscreen" allowfullscreen="allowfullscreen"><xsl:text> </xsl:text></iframe>
						</noscript>
					</xsl:when>
                    <xsl:when test="p:source[@type='youtube']">
                        <noscript>
							<iframe class="part_movie_embedded"
								src="//www.youtube.com/embed/{p:source/@id}?autoplay=1&amp;showinfo=0&amp;autohide=1"
								width="100%" height="100%" frameborder="0" 
								webkitallowfullscreen="webkitallowfullscreen" mozallowfullscreen="mozallowfullscreen" allowfullscreen="allowfullscreen"><xsl:text> </xsl:text></iframe>
						</noscript>
					</xsl:when>
                    <xsl:when test="p:source[@type='file']">
                        <noscript>
          				<video class="part_movie_embedded" id="{generate-id()}_video" controls="true" preload="auto" style="{$size}">
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
                    <xsl:when test="p:source[@type='code']">
                        <noscript>
                            <div class="part_movie_embedded"><xsl:value-of select="p:source[@type='code']" disable-output-escaping="yes"/></div>
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