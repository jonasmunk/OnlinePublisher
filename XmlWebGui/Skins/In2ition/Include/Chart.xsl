<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:c="uri:Chart"
    version="1.0"
    exclude-result-prefixes="c"
    >

<xsl:template match="c:chart">
<xsl:variable name="unique" select="generate-id()"/>
<div style="width:{@width}px;height:{@height}px;">
<canvas id="{$unique}_canvas" width="{@width}" height="{@height}"></canvas>
</div>
<script src="{$root}Scripts/In2iChart.js" type="text/javascript"></script>
<script type="text/javascript">
N2i.Event.addLoadListener(
	function() {
		var <xsl:value-of select="$unique"/>_object = new N2i.Chart();
		<xsl:value-of select="$unique"/>_object.body.width = <xsl:value-of select="@width"/>;
		<xsl:value-of select="$unique"/>_object.body.height = <xsl:value-of select="@height"/>;
		<xsl:apply-templates select="c:body"/>
		<xsl:apply-templates select="c:x-axis"/>
		<xsl:apply-templates select="c:y-axis"/>
		<xsl:apply-templates select="c:data"/>
		var renderer = new N2i.Chart.Renderer(<xsl:value-of select="$unique"/>_object);
		renderer.render('<xsl:value-of select="$unique"/>_canvas');
	}
);
</script>
</xsl:template>

<xsl:template match="c:data">
<xsl:variable name="chart" select="generate-id(..)"/>
<xsl:value-of select="generate-id()"/>_dataset = new N2i.Chart.DataSet('<xsl:value-of select="@type"/>');
<xsl:value-of select="generate-id()"/>_dataset.setValues(<xsl:value-of select="$chart"/>_object,[<xsl:value-of select="@values"/>]);
<xsl:value-of select="$chart"/>_object.addDataSet(<xsl:value-of select="generate-id()"/>_dataset);
</xsl:template>

<xsl:template match="c:x-axis">
<xsl:variable name="unique" select="generate-id(..)"/>
<xsl:if test="@labels">
<xsl:value-of select="$unique"/>_object.setXaxisLabels([<xsl:value-of select="@labels"/>]);
</xsl:if>
<xsl:if test="@max-labels">
<xsl:value-of select="$unique"/>_object.xAxis.maxLabels=<xsl:value-of select="@max-labels"/>;
</xsl:if>
</xsl:template>

<xsl:template match="c:y-axis">
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

<xsl:template match="c:body">
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