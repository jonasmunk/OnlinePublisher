<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:map="http://uri.in2isoft.com/onlinepublisher/part/map/1.0/"
 exclude-result-prefixes="map"
 >

	<xsl:template match="map:map[@frame]">
		<span>
			<xsl:attribute name="class">
				<xsl:text>part_map shared_frame_</xsl:text><xsl:value-of select="@frame"/>
				<xsl:if test="not(@width) and @provider='google-interactive'">
					<xsl:text> shared_frame_adaptive</xsl:text>
				</xsl:if>
			</xsl:attribute>
			<span class="shared_frame_{@frame}_top"><span><span><xsl:comment/></span></span></span>
			<span class="shared_frame_{@frame}_middle">
				<span class="shared_frame_{@frame}_middle">
					<span class="shared_frame_{@frame}_content">
						<xsl:call-template name="map:internal"/>
					</span>
				</span>
			</span>
			<span class="shared_frame_{@frame}_bottom"><span><span><xsl:comment/></span></span></span>
		</span>
	</xsl:template>

	<xsl:template match="map:map">
		<xsl:call-template name="map:internal"/>
	</xsl:template>

	<xsl:template name="map:internal">
		<xsl:choose>
			<xsl:when test="@provider='google-interactive'">
				<xsl:call-template name="map:google-interactive"/>			
			</xsl:when>
			<xsl:otherwise>
				<xsl:call-template name="map:google-static"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template name="map:google-static">
		<xsl:variable name="height">
			<xsl:choose>
				<xsl:when test="number(@height)&gt;=640">
					<xsl:value-of select="number(640)"/>
				</xsl:when>
				<xsl:when test="number(@height)&gt;0 and number(@height)&lt;=640">
					<xsl:value-of select="number(@height)"/>
				</xsl:when>
				<xsl:otherwise>400</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:variable name="fake-height">
			<xsl:choose>
				<xsl:when test="$height&lt;610">
					<xsl:value-of select="$height+30"/>
				</xsl:when>
				<xsl:otherwise><xsl:value-of select="$height"/></xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:variable name="width">
			<xsl:choose>
				<xsl:when test="number(@width)&gt;=640">
					<xsl:value-of select="number(640)"/>
				</xsl:when>
				<xsl:when test="number(@width)&gt;0">
					<xsl:value-of select="number(@width)"/>
				</xsl:when>
				<xsl:otherwise>640</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<span class="part_map_static">
			<a class="part_map_static_pin"><xsl:comment/></a>
			<span class="part_map_static_effect"><xsl:comment/></span>
			<span class="part_map_static_content" style="height: {$height}px;">
				<img src="http://maps.googleapis.com/maps/api/staticmap?center={@latitude},{@longitude}&amp;zoom={@zoom}&amp;size={$width}x{$fake-height}&amp;sensor=false&amp;maptype={@maptype}" style="width: {$width}px; height: {$fake-height}px;"/>
			</span>
			<xsl:if test="map:text">
				<span class="part_map_static_text"><xsl:value-of select="map:text"/></span>
			</xsl:if>
		</span>
	</xsl:template>
	
	<xsl:template name="map:google-interactive">
		<span class="part_map_interactive" id="map_{../../@id}">
			<xsl:attribute name="style">
				<xsl:text>min-height: 30px;</xsl:text>
				<xsl:if test="@width">
					<xsl:text>width: </xsl:text><xsl:value-of select="@width"/><xsl:text>;</xsl:text>
				</xsl:if>
				<xsl:if test="not(@width)">
					<xsl:text>width:100%;</xsl:text>
				</xsl:if>
				<xsl:if test="@height">
					<xsl:text>height: </xsl:text><xsl:value-of select="@height"/><xsl:text>;</xsl:text>
				</xsl:if>
				<xsl:if test="not(@height)">
					<xsl:text>height: 300px;</xsl:text>
				</xsl:if>
			</xsl:attribute>
			<xsl:comment/>
		</span>
		<xsl:if test="map:text">
			<span class="part_map_text"><xsl:value-of select="map:text"/></span>
		</xsl:if>
		<script type="text/javascript">
			(function() {
				var options = {
					element : 'map_<xsl:value-of select="../../@id"/>',
					markers : [],
					zoom : <xsl:value-of select="@zoom"/>,
					type : '<xsl:value-of select="@maptype"/>'
					<xsl:if test="@longitude and @latitude">
						,center : {latitude:<xsl:value-of select="@latitude"/>,longitude:<xsl:value-of select="@longitude"/>}
					</xsl:if>
				};
				<xsl:for-each select="map:marker">
					options.markers.push({
						text : '<xsl:value-of select="@text"/>',
						latitude : <xsl:value-of select="@latitude"/>,
						longitude : <xsl:value-of select="@longitude"/>
					})
				</xsl:for-each>
				new op.part.Map(options);
			})()
		</script>
	</xsl:template>

</xsl:stylesheet>