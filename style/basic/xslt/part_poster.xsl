<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/part/poster/1.0/"
 exclude-result-prefixes="p"
 >

<xsl:template match="p:poster">
	<div class="part_poster" id="{generate-id()}">
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
	<xsl:if test="$editor!='true'">
		<script type="text/javascript">
		try {
			new op.part.Poster({element:'<xsl:value-of select="generate-id()"/>'});
		} catch (e) {
			n2i.log(e)
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
			<img style="float: right;">
				<xsl:attribute name="src">
					<xsl:value-of select="$path"/><xsl:text>services/images/?id=</xsl:text><xsl:value-of select="p:image/@id"/><xsl:text>&amp;height=200</xsl:text>
				</xsl:attribute>
			</img>
		</xsl:if>
		<p class="part_poster_title"><xsl:value-of select="p:title"/></p>
		<p class="part_poster_text"><xsl:value-of select="p:text"/></p>
	</div>
</xsl:template>

</xsl:stylesheet>