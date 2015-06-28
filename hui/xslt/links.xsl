<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:gui="uri:hui"
    version="1.0"
    exclude-result-prefixes="gui"
    >

<xsl:template match="gui:links">
	<div class="hui_links" id="{generate-id()}">
		<div class="hui_links_list"><xsl:comment/></div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Links({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@pageSource">,pageSource:<xsl:value-of select="@pageSource"/></xsl:if>
			<xsl:if test="@fileSource">,fileSource:<xsl:value-of select="@fileSource"/></xsl:if>
			});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

</xsl:stylesheet>