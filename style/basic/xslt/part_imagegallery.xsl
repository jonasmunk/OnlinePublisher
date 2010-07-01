<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:ig="http://uri.in2isoft.com/onlinepublisher/part/imagegallery/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 exclude-result-prefixes="o i ig"
 >


<xsl:template match="ig:imagegallery">
<div>
	<xsl:attribute name="class">
		<xsl:text>part_imagegallery</xsl:text>
		<xsl:if test="ig:display/@framed='true'"><xsl:text> part_imagegallery_framed</xsl:text></xsl:if>
	</xsl:attribute>
	<xsl:apply-templates/>
	<script type="text/javascript">
		try {
			var <xsl:value-of select="generate-id()"/> = new op.part.ImageGallery();
			with (<xsl:value-of select="generate-id()"/>) {
				<xsl:for-each select="o:object">
					registerImage('<xsl:value-of select="generate-id()"/>',{id:<xsl:value-of select="@id"/>,width:<xsl:value-of select="o:sub/i:image/i:width"/>,height:<xsl:value-of select="o:sub/i:image/i:height"/>,text:'<xsl:value-of select="o:note"/>'});
				</xsl:for-each>
			}
		} catch (ignore) {}
	</script>
</div>
</xsl:template>

<xsl:template match="o:object[@type='image']">
	<xsl:variable name="height">
		<xsl:choose>
			<xsl:when test="../ig:display/@height"><xsl:value-of select="../ig:display/@height"/></xsl:when>
			<xsl:otherwise>64</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<xsl:variable name="width">
		<xsl:value-of select="round(number(o:sub/i:image/i:width) div number(o:sub/i:image/i:height) * $height)"/>
	</xsl:variable>
	<a href="{$path}util/images/?id={@id}">
		<xsl:if test="../ig:display/@show-title='true'"><span><xsl:value-of select="o:title"/></span></xsl:if>
		<img src="{$path}util/images/?id={@id}&amp;maxheight={$height}" style="height: {$height}px; width: {$width}px;" alt="" id="{generate-id()}"/>
	</a>
</xsl:template>

</xsl:stylesheet>