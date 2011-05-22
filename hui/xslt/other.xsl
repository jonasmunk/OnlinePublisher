<?xml version="1.0"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:gui="uri:In2iGui"
    version="1.0"
    exclude-result-prefixes="gui"
>

<xsl:template match="gui:graph">
	<div class="in2igui_graph" id="{generate-id()}" style="height: 400px;"><xsl:comment/></div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Graph({
			element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>',layout:'<xsl:value-of select="@layout"/>'
			<xsl:if test="@source">,source:<xsl:value-of select="@source"/></xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

</xsl:stylesheet>