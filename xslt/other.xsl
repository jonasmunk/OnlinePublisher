<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:gui="uri:hui"
    version="1.0"
    exclude-result-prefixes="gui"
>

<!--doc title:'Graph' class:'hui.ui.Graph' module:'visalization'
<graph name="«name»" layout="«?»" source="«source»"/>
-->
<xsl:template match="gui:graph">
	<div class="hui_graph" id="{generate-id()}">
		<xsl:if test="@state and (not(//gui:gui/@state) or @state!=//gui:gui/@state)">
			<xsl:attribute name="style">display:none</xsl:attribute>
		</xsl:if>
		<xsl:comment/></div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Graph({
			element : '<xsl:value-of select="generate-id()"/>',
			name : '<xsl:value-of select="@name"/>',
			layout : '<xsl:value-of select="@layout"/>',
			state : '<xsl:value-of select="@state"/>'
			<xsl:if test="@source">,source : <xsl:value-of select="@source"/></xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!--doc title:'TimeLine' class:'hui.ui.Graph' module:'visalization'
<graph name="«name»" layout="«?»" source="«source»"/>
-->
<xsl:template match="gui:timeline">
	<div class="hui_timeline" id="{generate-id()}">
		<xsl:comment/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.TimeLine({
			element : '<xsl:value-of select="generate-id()"/>',
			name : '<xsl:value-of select="@name"/>'
			<xsl:if test="@source">,source : <xsl:value-of select="@source"/></xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!--doc title:'DatePicker' class:'hui.ui.DatePicker' module:'input'
<datepicker name="«name»"/>
-->
<xsl:template match="gui:datepicker">

  <div class="hui_datepicker" id="{generate-id()}">
    <div class="hui_datepicker_header">
      <a class="hui_datepicker_next"></a>
      <a class="hui_datepicker_previous"></a>
      <strong>Juni 2015</strong>
    </div>
    <table>
      <thead><tr><th>Søn</th><th>Man</th><th>Tir</th><th>Ons</th><th>Tor</th><th>Fre</th><th>Lør</th></tr></thead>
      <tbody>
        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
      </tbody>
    </table>
  </div>
	<script type="text/javascript">
    (function() {
  		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.DatePicker({
  			element : '<xsl:value-of select="generate-id()"/>',
  			name : '<xsl:value-of select="@name"/>'
  		});
  		<xsl:call-template name="gui:createobject"/>
    })()
	</script>

</xsl:template>

</xsl:stylesheet>