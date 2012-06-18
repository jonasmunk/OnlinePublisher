<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:map="http://uri.in2isoft.com/onlinepublisher/part/map/1.0/"
 exclude-result-prefixes="map"
 >

	<xsl:template match="map:map">
		<div class="part_map">
			<a class="part_map_pin"><xsl:comment/></a>
			<div class="part_map_effect"><xsl:comment/></div>
			<div style="overflow: hidden; height: 400px;">
				<xsl:choose>
					<xsl:when test="map:marker">
				<img src="http://maps.googleapis.com/maps/api/staticmap?center={map:marker/@latitude},{map:marker/@longitude}&amp;zoom={@zoom}&amp;size=640x430&amp;sensor=false&amp;maptype={@maptype}" style="width: 640px; height: 430px;"/>
					</xsl:when>
					<xsl:otherwise>
						<img src="http://maps.googleapis.com/maps/api/staticmap?center=-15.800513,-47.91378&amp;zoom={@zoom}&amp;size=640x430&amp;sensor=false&amp;maptype={@maptype}" style="width: 640px; height: 430px;"/>
					</xsl:otherwise>
				</xsl:choose>
			</div>
		</div>
	</xsl:template>

</xsl:stylesheet>