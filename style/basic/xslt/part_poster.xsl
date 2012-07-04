<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/part/poster/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p util"
 >

<xsl:template match="p:poster">
	<div id="part_poster_{../../@id}">
		<xsl:attribute name="class">
			<xsl:text>part_poster</xsl:text>
			<xsl:if test="p:recipe/p:pages/@variant">
				<xsl:text> part_poster_</xsl:text><xsl:value-of select="p:recipe/p:pages/@variant"/>
			</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
	<script type="text/javascript">
	try {
		new op.part.Poster({
			element : 'part_poster_<xsl:value-of select="../../@id"/>',
			name : 'part_poster_<xsl:value-of select="../../@id"/>',
			editmode : <xsl:value-of select="$editor='true'"/>
		});
	} catch (e) {
		hui.log(e)
	}
	</script>
</xsl:template>

<xsl:template match="p:invalid">
	<span style="color: #999; font-size: 12px;">Invalid</span>
</xsl:template>


<xsl:template match="p:recipe">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="p:pages">
	<div class="part_poster_pages">
		<xsl:apply-templates select="p:page"/>
	</div>
</xsl:template>


<xsl:template match="p:page">
	<div data-label="{@label}">
		<xsl:attribute name="style">
			<xsl:if test="position()!=1">
				<xsl:text>display:none;</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:attribute name="class">
			<xsl:text>part_poster_page</xsl:text>
		</xsl:attribute>
		<div class="part_poster_page_content">
			<xsl:if test="../@height">
				<xsl:attribute name="style">
				height:<xsl:value-of select="../@height"/>px;
				</xsl:attribute>
			</xsl:if>

		<xsl:if test="p:image[@id]">
			<img>
				<xsl:attribute name="src">
					<xsl:value-of select="$path"/><xsl:text>services/images/?id=</xsl:text><xsl:value-of select="p:image/@id"/><xsl:text>&amp;format=png</xsl:text>
					<xsl:choose>
						<xsl:when test="p:image/@height">
							<xsl:text>&amp;height=</xsl:text><xsl:value-of select="p:image/@height"/>
						</xsl:when>
						<xsl:when test="../@height">
							<xsl:text>&amp;height=</xsl:text><xsl:value-of select="../@height"/>
						</xsl:when>
						<xsl:otherwise>
							<xsl:text>&amp;height=200</xsl:text>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:attribute>
				<xsl:attribute name="style">
					<xsl:choose>
						<xsl:when test="p:image/@height">
							<xsl:text>height:</xsl:text><xsl:value-of select="p:image/@height"/><xsl:text>px;</xsl:text>
						</xsl:when>
						<xsl:when test="../@height">
							<xsl:text>height:</xsl:text><xsl:value-of select="../@height"/><xsl:text>px;</xsl:text>
						</xsl:when>
						<xsl:otherwise>
							<xsl:text>height: 200px;</xsl:text>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:attribute>
			</img>
		</xsl:if>
		<p class="part_poster_title"><xsl:value-of select="p:title"/><xsl:comment/></p>
		<p class="part_poster_text"><xsl:value-of select="p:text"/><xsl:comment/></p>
		<xsl:for-each select="p:link">
			<p class="part_poster_link">				
				<a class="common"><xsl:call-template name="util:link"/>
					<span><xsl:value-of select="."/></span>
				</a>
			</p>
		</xsl:for-each>
		</div>
	</div>
</xsl:template>

</xsl:stylesheet>