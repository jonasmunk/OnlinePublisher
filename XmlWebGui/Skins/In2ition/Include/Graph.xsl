<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:g="uri:Graph"
    version="1.0"
    exclude-result-prefixes="g"
    >

<xsl:template match="g:graph">
<xsl:variable name="unique" select="generate-id()"/>
<div style="width:{@width}px;height:{@height}px;">
<canvas id="{$unique}_canvas" width="{@width}" height="{@height}"></canvas>
</div>
<script src="{$root}Scripts/In2iGraph.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
function <xsl:value-of select="$unique"/>_init() {
	var <xsl:value-of select="$unique"/>_object = new N2i.Graph();
	<xsl:value-of select="$unique"/>_object.body.width = <xsl:value-of select="@width"/>;
	<xsl:value-of select="$unique"/>_object.body.height = <xsl:value-of select="@height"/>;
	<xsl:apply-templates select="g:body"/>
	<xsl:apply-templates select="g:x-axis"/>
	<xsl:apply-templates select="g:y-axis"/>
	<xsl:apply-templates select="g:data"/>
	var renderer = new N2i.Graph.Renderer(<xsl:value-of select="$unique"/>_object);
	renderer.render('<xsl:value-of select="$unique"/>_canvas');
}
if (N2i.Browser.isIE()) {
	document.body.onload = <xsl:value-of select="$unique"/>_init;
} else {
	<xsl:value-of select="$unique"/>_init();
}
</script>
</xsl:template>

<xsl:template match="g:data[@type='line']">
<xsl:variable name="unique" select="generate-id(..)"/>
<xsl:value-of select="$unique"/>_object.addData({type:'line',color:'<xsl:value-of select="@color"/>'<xsl:if test="@width">,width:<xsl:value-of select="@width"/></xsl:if>,values:[<xsl:value-of select="@values"/>]});
</xsl:template>

<xsl:template match="g:data[@type='column']">
<xsl:variable name="unique" select="generate-id(..)"/>
<xsl:value-of select="$unique"/>_object.addData({type:'column',color:'<xsl:value-of select="@color"/>',values:[<xsl:value-of select="@values"/>]});
</xsl:template>

<xsl:template match="g:x-axis">
<xsl:variable name="unique" select="generate-id(..)"/>
<xsl:if test="@labels">
<xsl:value-of select="$unique"/>_object.setXaxisLabels([<xsl:value-of select="@labels"/>]);
</xsl:if>
</xsl:template>

<xsl:template match="g:y-axis">
<xsl:variable name="unique" select="generate-id(..)"/>
<xsl:if test="@steps">
<xsl:value-of select="$unique"/>_object.yAxis.steps = <xsl:value-of select="@steps"/>;
</xsl:if>
<xsl:if test="@min">
<xsl:value-of select="$unique"/>_object.yAxis.min = <xsl:value-of select="@min"/>;
</xsl:if>
<xsl:if test="@max">
<xsl:value-of select="$unique"/>_object.yAxis.max = <xsl:value-of select="@max"/>;
</xsl:if>
</xsl:template>

<xsl:template match="g:body">
<xsl:variable name="unique" select="generate-id(..)"/>
<xsl:if test="@top">
<xsl:value-of select="$unique"/>_object.body.paddingTop = <xsl:value-of select="@top"/>;
</xsl:if>
<xsl:if test="@bottom">
<xsl:value-of select="$unique"/>_object.body.paddingBottom = <xsl:value-of select="@bottom"/>;
</xsl:if>
<xsl:if test="@left">
<xsl:value-of select="$unique"/>_object.body.paddingLeft = <xsl:value-of select="@left"/>;
</xsl:if>
<xsl:if test="@right">
<xsl:value-of select="$unique"/>_object.body.paddingRight = <xsl:value-of select="@right"/>;
</xsl:if>
<xsl:if test="@vertical">
<xsl:value-of select="$unique"/>_object.body.innerPaddingVertical = <xsl:value-of select="@vertical"/>;
</xsl:if>
<xsl:if test="@horizontal">
<xsl:value-of select="$unique"/>_object.body.innerPaddingHorizontal = <xsl:value-of select="@horizontal"/>;
</xsl:if>
</xsl:template>

</xsl:stylesheet>