<?xml version="1.0"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:gui="uri:In2iGui"
    version="1.0"
    exclude-result-prefixes="gui"
    >

<xsl:template match="gui:space | gui:block">
	<div class="in2igui_space">
		<xsl:attribute name="style">
			<xsl:if test="@all">padding: <xsl:value-of select="@all"/>px;</xsl:if>
			<xsl:if test="@left">padding-left: <xsl:value-of select="@left"/>px;</xsl:if>
			<xsl:if test="@right">padding-right: <xsl:value-of select="@right"/>px;</xsl:if>
			<xsl:if test="@top">padding-top: <xsl:value-of select="@top"/>px;</xsl:if>
			<xsl:if test="@bottom">padding-bottom: <xsl:value-of select="@bottom"/>px;</xsl:if>
			<xsl:if test="@align">text-align: <xsl:value-of select="@align"/>;</xsl:if>
			<xsl:if test="@height">height: <xsl:value-of select="@height"/>px; font-size: 0px;</xsl:if>
		</xsl:attribute>
		<xsl:comment/>
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="gui:columns">
	<table cellspacing="0" cellpadding="0" class="in2igui_columns">
		<tr>
			<xsl:apply-templates select="gui:column"/>
		</tr>
	</table>
</xsl:template>

<xsl:template match="gui:columns/gui:column">
	<td class="in2igui_columns_column">
		<xsl:if test="(position()>1 and ../@space) or @width">
			<xsl:attribute name="style">
				<xsl:if test="position()>1 and ../@space">padding-left: <xsl:value-of select="../@space"/>px;</xsl:if>
				<xsl:if test="@width">width: <xsl:value-of select="@width"/>;</xsl:if>
			</xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
	</td>
</xsl:template>

<xsl:template match="gui:header">
	<h2 class="in2igui_header">
		<xsl:if test="@icon"><span class="in2igui_icon_2" style="background-image: url('{$context}/hui/icons/{@icon}2.png')"><xsl:comment/></span></xsl:if>
		<xsl:apply-templates/></h2>
</xsl:template>


<xsl:template match="gui:split">
<table class="split" cellpadding="0" cellspacing="0">
<tr>
	<xsl:apply-templates select="gui:sidebar"/>
	<xsl:for-each select="gui:content">
		<td class="split_content">
			<xsl:apply-templates/>
		</td>
	</xsl:for-each>
</tr>
</table>
</xsl:template>

<xsl:template match="gui:split/gui:sidebar">
	<td class="split_sidebar"><xsl:apply-templates/><div class="split_sidebar"><xsl:comment/></div></td>
</xsl:template>

<xsl:template match="gui:overflow">
<div id="{generate-id()}">
	<xsl:attribute name="class">
		<xsl:text>in2igui_overflow</xsl:text>
	<xsl:if test="@background">
		<xsl:text> in2igui_bg_</xsl:text><xsl:value-of select="@background"/>
	</xsl:if>
	</xsl:attribute>
	<xsl:attribute name="style">
		<xsl:choose>
			<xsl:when test="@height or @max-height or @min-height">
				<xsl:if test="@height">height: <xsl:value-of select="@height"/>px;</xsl:if>
				<xsl:if test="@max-height">max-height: <xsl:value-of select="@max-height"/>px;</xsl:if>
				<xsl:if test="@min-height">min-height: <xsl:value-of select="@min-height"/>px;</xsl:if>
			</xsl:when>
			<xsl:otherwise>
				<xsl:text>height: 0px;</xsl:text>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:attribute>
	<xsl:apply-templates/>
</div>
<script type="text/javascript">
	var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Overflow({
		element:'<xsl:value-of select="generate-id()"/>',
		dynamic:<xsl:value-of select="not(@height or @max-height or @min-height or @vertical)"/>
		<xsl:if test="@vertical">,vertical:<xsl:value-of select="@vertical"/></xsl:if>
	});
	<xsl:call-template name="gui:createobject"/>
</script>
</xsl:template>

<xsl:template match="gui:box">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>in2igui_box</xsl:text>
			<xsl:if test="@variant"><xsl:text> in2igui_box_</xsl:text><xsl:value-of select="@variant"/></xsl:if>
			<xsl:if test="@absolute='true'"><xsl:text> in2igui_box_absolute</xsl:text></xsl:if>
		</xsl:attribute>
		<xsl:attribute name="style">
		<xsl:if test="@width">width: <xsl:value-of select="@width"/>px;</xsl:if>
		<xsl:if test="@top">padding-top: <xsl:value-of select="@top"/>px;</xsl:if>
		</xsl:attribute>
		<xsl:if test="@closable='true'"><a class="in2igui_box_close" href="#"><xsl:comment/></a></xsl:if>
		<div class="in2igui_box_top"><div><div><xsl:comment/></div></div></div>
		<div class="in2igui_box_middle"><div class="in2igui_box_middle">
			<xsl:if test="@title or gui:toolbar">
				<div class="in2igui_box_header">
					<xsl:attribute name="class">in2igui_box_header<xsl:if test="gui:toolbar"> in2igui_box_header_toolbar</xsl:if></xsl:attribute>
					<xsl:apply-templates select="gui:toolbar"/>
					<strong class="in2igui_box_title"><xsl:value-of select="@title"/></strong>
				</div>
			</xsl:if>
			<div class="in2igui_box_body">
				<xsl:if test="@padding"><xsl:attribute name="style">padding: <xsl:value-of select="@padding"/>px;</xsl:attribute></xsl:if>
				<xsl:apply-templates select="child::*[not(name()='toolbar')]"/>
			</div>
		</div></div>
		<div class="in2igui_box_bottom"><div><div><xsl:comment/></div></div></div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Box({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@modal='true'">,modal:true</xsl:if>
			<xsl:if test="@absolute='true'">,absolute:true</xsl:if>
			<xsl:if test="@state">,state:'<xsl:value-of select="@state"/>'</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:wizard">
	<div class="in2igui_wizard" id="{generate-id()}">
		<table class="in2igui_wizard"><tr>
			<th class="in2igui_wizard">
				<ul class="in2igui_wizard">
				<xsl:for-each select="gui:step">
					<li>
						<a href="#">
							<xsl:attribute name="class">
								<xsl:text>in2igui_wizard_selection</xsl:text>
						<xsl:if test="position()=1">
							<xsl:text> in2igui_selected</xsl:text>
						</xsl:if>
						</xsl:attribute>
						<xsl:if test="@icon"><span class="in2igui_icon_1" style="background-image: url('{$context}/hui/icons/{@icon}1.png');')"><xsl:comment/></span></xsl:if>
						<span><xsl:value-of select="@title"/></span>
						</a>
					</li>
				</xsl:for-each>
				</ul>
			</th>
			<td class="in2igui_wizard">
				<div class="in2igui_wizard_steps">
				<xsl:for-each select="gui:step">
					<div>
						<xsl:attribute name="class">
							<xsl:text>in2igui_wizard_step</xsl:text>
							<xsl:if test="@frame='true'"><xsl:text> in2igui_wizard_step_frame</xsl:text></xsl:if>
						</xsl:attribute>
						<xsl:attribute name="style">
						<xsl:if test="@padding">
							<xsl:text>padding: </xsl:text><xsl:value-of select="@padding"/><xsl:text>px;</xsl:text>
						</xsl:if>
						<xsl:if test="position()!=1"><xsl:text>display: none;</xsl:text></xsl:if>
						</xsl:attribute>
						<xsl:apply-templates/>
					</div>
				</xsl:for-each>
				</div>
			</td>
			</tr>
		</table>
	</div>
	<script type="text/javascript">
		(function() {
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Wizard({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'});
			<xsl:call-template name="gui:createobject"/>
		})();
	</script>
</xsl:template>



<xsl:template match="gui:layout">
	<table class="in2igui_layout" id="{generate-id()}">
		<xsl:apply-templates/>
	</table>
	<script type="text/javascript">
		(function() {
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Layout({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'});
			<xsl:call-template name="gui:createobject"/>
		})();
	</script>
</xsl:template>

<xsl:template match="gui:layout/gui:top">
	<thead class="in2igui_layout">
		<tr><td class="in2igui_layout_top">
			<div class="in2igui_layout_top"><div class="in2igui_layout_top"><div class="in2igui_layout_top">
				<xsl:apply-templates/>
				<xsl:comment/>
			</div></div></div>
		</td></tr>
	</thead>
</xsl:template>

<xsl:template match="gui:layout/gui:middle">
	<tbody class="in2igui_layout">
		<tr class="in2igui_layout_middle">
			<td class="in2igui_layout_middle">
			<table class="in2igui_layout_middle">
				<tr>
					<xsl:apply-templates/>
				</tr>
			</table>
		</td></tr>
	</tbody>
</xsl:template>

<xsl:template match="gui:layout/gui:middle/gui:left">
	<td class="in2igui_layout_left">
		<xsl:apply-templates/>
		<div class="in2igui_layout_left"><xsl:comment/></div>
	</td>
</xsl:template>

<xsl:template match="gui:layout/gui:middle/gui:center">
	<td class="in2igui_layout_center">
		<xsl:if test="@padding">
			<xsl:attribute name="style">padding: <xsl:value-of select="@padding"/>px;</xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
	</td>
</xsl:template>

<xsl:template match="gui:layout/gui:bottom">
	<tfoot class="in2igui_layout">
		<tr><td class="in2igui_layout_bottom">
			<div class="in2igui_layout_bottom"><div class="in2igui_layout_bottom"><div class="in2igui_layout_bottom">
				<xsl:apply-templates/>
			</div></div></div>
		</td></tr>
	</tfoot>
</xsl:template>



<xsl:template match="gui:fragment">
<div id="{generate-id()}">
	<xsl:attribute name="style">
		<xsl:if test="@state and @state!=//gui:gui/@state">
			<xsl:text>display:none;</xsl:text>
		</xsl:if>
		<xsl:if test="@height='full'">
			<xsl:text>min-height: 100%;</xsl:text>
		</xsl:if>
	</xsl:attribute>
	<xsl:if test="@background">
		<xsl:attribute name="class">in2igui_bg_<xsl:value-of select="@background"/></xsl:attribute>
	</xsl:if>
	<xsl:apply-templates/>
</div>
<script type="text/javascript">
	var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Fragment({
		element:'<xsl:value-of select="generate-id()"/>',
		name:'<xsl:value-of select="@name"/>',
		state:'<xsl:value-of select="@state"/>'
	});
	<xsl:call-template name="gui:createobject"/>
</script>
</xsl:template>


<xsl:template match="gui:structure">
	<div style="position:fixed; top: {@top}px; bottom: 0; width: 100%">
		<xsl:apply-templates/>
	</div>
</xsl:template>

</xsl:stylesheet>