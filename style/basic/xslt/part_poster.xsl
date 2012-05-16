<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/part/poster/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p util"
 >

<xsl:template match="p:poster">
	<div class="part_poster" id="{generate-id()}">
		<div class="part_poster_pages">
			<xsl:apply-templates/>
		</div>
		<xsl:comment/>
	</div>
	<xsl:if test="$editor!='true'">
		<script type="text/javascript">
		try {
			new op.part.Poster({element:'<xsl:value-of select="generate-id()"/>'});
		} catch (e) {
			hui.log(e)
		}
		</script>
	</xsl:if>
</xsl:template>

<xsl:template match="p:recipe">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="p:page">
	<div class="part_poster_page">
		<xsl:if test="position()!=1">
			<xsl:attribute name="style">display:none;</xsl:attribute>
		</xsl:if>
		<xsl:if test="p:image">
			<img>
				<xsl:attribute name="src">
					<xsl:value-of select="$path"/><xsl:text>services/images/?id=</xsl:text><xsl:value-of select="p:image/@id"/><xsl:text>&amp;format=png</xsl:text>
					<xsl:choose>
						<xsl:when test="p:image/@height">
							<xsl:text>&amp;height=</xsl:text><xsl:value-of select="p:image/@height"/>
						</xsl:when>
						<xsl:otherwise>
							<xsl:text>&amp;height=200</xsl:text>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:attribute>
			</img>
		</xsl:if>
		<p class="part_poster_title"><xsl:value-of select="p:title"/></p>
		<p class="part_poster_text"><xsl:value-of select="p:text"/></p>
		<xsl:for-each select="p:link">
			<p class="part_poster_link">				
				<a class="common"><xsl:call-template name="util:link"/>
					<span><xsl:value-of select="."/></span>
				</a>
			</p>
		</xsl:for-each>
	</div>
</xsl:template>

</xsl:stylesheet>