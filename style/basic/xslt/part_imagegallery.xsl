<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:ig="http://uri.in2isoft.com/onlinepublisher/part/imagegallery/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 exclude-result-prefixes="o i ig"
 >


	<xsl:template match="ig:imagegallery">
		<div id="part_image_{generate-id()}">
			<xsl:attribute name="class">
				<xsl:text>part_imagegallery</xsl:text>
				<xsl:if test="ig:display/@framed='true'"><xsl:text> part_imagegallery_framed</xsl:text></xsl:if>
				<xsl:if test="ig:display/@variant='changing'"><xsl:text> part_imagegallery_changing</xsl:text></xsl:if>
			</xsl:attribute>
			<xsl:if test="ig:display/@variant='changing'">
				<xsl:attribute name="style">
					height: <xsl:value-of select="ig:display/@height+10"/>px;
				</xsl:attribute>
			</xsl:if>
			<xsl:apply-templates/>
			<xsl:comment/>
		</div>
		<script type="text/javascript">
			(function() {
				var part = new op.part.ImageGallery({element:'part_image_<xsl:value-of select="generate-id()"/>',variant:'<xsl:value-of select="ig:display/@variant"/>',editor:<xsl:value-of select="$editor='true'"/>});
				with (part) {
					<xsl:for-each select="o:object">
						registerImage('part_image_<xsl:value-of select="generate-id()"/>',{id:<xsl:value-of select="@id"/>,width:<xsl:value-of select="o:sub/i:image/i:width"/>,height:<xsl:value-of select="o:sub/i:image/i:height"/>,text:'<xsl:value-of select="o:note"/>'});
					</xsl:for-each>
					init();
				}
			})();
		</script>
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
		<xsl:variable name="url">
			<xsl:choose>
				<xsl:when test="$editor='true'">javascript:void();</xsl:when>
				<xsl:otherwise><xsl:value-of select="$path"/>services/images/?id=<xsl:value-of select="@id"/></xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<a href="{$url}">
			<xsl:if test="../ig:display/@variant='changing' and position()=2">
				<xsl:attribute name="style">
					display: inline-block;
				</xsl:attribute>
			</xsl:if>
			<xsl:if test="../ig:display/@show-title='true'">
				<span class="common_font"><xsl:value-of select="o:title"/></span>
			</xsl:if>
			<img src="{$path}services/images/?id={@id}&amp;height={$height}" style="height: {$height}px; width: {$width}px;" alt="" id="part_image_{generate-id()}"/>
		</a>
	</xsl:template>

</xsl:stylesheet>