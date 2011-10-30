<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:gui="uri:hui"
    version="1.0"
    exclude-result-prefixes="gui"
    >

<!--doc title:'Space'
<space all="«pixels»" left="«pixels»" right="«pixels»" top="«pixels»" bottom="«pixels»" align="«left | center | right»" height="«pixels»">
    ···
</space>
-->
<xsl:template match="gui:space | gui:block">
	<div class="hui_space">
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

<!--doc title:'Columns'
<columns space="«pixels»">
    <column width="«css-length»">
        ···
    </column>
    <column width="«css-length»">
    ···
    </column>
</columns>
-->
<xsl:template match="gui:columns">
	<table cellspacing="0" cellpadding="0" class="hui_columns">
		<tr>
			<xsl:apply-templates select="gui:column"/>
		</tr>
	</table>
</xsl:template>

<xsl:template match="gui:columns/gui:column">
	<td class="hui_columns_column">
		<xsl:if test="(position()>1 and ../@space) or @width">
			<xsl:attribute name="style">
				<xsl:if test="position()>1 and ../@space">padding-left: <xsl:value-of select="../@space"/>px;</xsl:if>
				<xsl:if test="@width">width: <xsl:value-of select="@width"/>;</xsl:if>
			</xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
	</td>
</xsl:template>

<!--doc title:'Header'
<header icon="«icon»">«text»</header>
-->
<xsl:template match="gui:header">
	<h2 class="hui_header">
		<xsl:if test="@icon"><span class="hui_icon_2" style="background-image: url('{$context}/hui/icons/{@icon}32.png')"><xsl:comment/></span></xsl:if>
		<xsl:apply-templates/></h2>
</xsl:template>

<!--doc title:'Split'
<split>
    <sidebar>
        ···
    </sidebar>
    <content>
        ···
    </content>
    <content>
        ···
    </content>
</split>
-->
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

<!--doc title:'Overflow' class:'hui.ui.Overflow'
<overflow background="«background»" vertical="«pixels»" height="«pixels»" min-height="«pixels»" max-height="«pixels»" state="«text»">
    ···
</overflow>
-->
<xsl:template match="gui:overflow">
<div id="{generate-id()}">
	<xsl:attribute name="class">
		<xsl:text>hui_overflow</xsl:text>
		<xsl:if test="@background">
			<xsl:text> hui_bg_</xsl:text><xsl:value-of select="@background"/>
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
		<xsl:if test="@state and (not(//gui:gui/@state) or @state!=//gui:gui/@state)">
			<xsl:text>display:none;</xsl:text>
		</xsl:if>
	</xsl:attribute>
	<xsl:apply-templates/>
</div>
<script type="text/javascript">
	var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Overflow({
		element : '<xsl:value-of select="generate-id()"/>',
		dynamic : <xsl:value-of select="not(@height or @max-height or @min-height or @vertical)"/>
		<xsl:if test="@vertical">,vertical:<xsl:value-of select="@vertical"/></xsl:if>
		<xsl:if test="@state">,state:'<xsl:value-of select="@state"/>'</xsl:if>
		<xsl:if test="@name">,name:'<xsl:value-of select="@name"/>'</xsl:if>
	});
	<xsl:call-template name="gui:createobject"/>
</script>
</xsl:template>

<!--doc title:'Box' class:'hui.ui.Box'
<box variant="«?»" closable="«boolean»" absolute="«boolean»" width="«pixels»" top="«pixels»" name="«name»" title="«text»" state="«text»" modal="«boolean»">
    <toolbar/>
    ···
</box>
-->
<xsl:template match="gui:box">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_box</xsl:text>
			<xsl:if test="@variant"><xsl:text> hui_box_</xsl:text><xsl:value-of select="@variant"/></xsl:if>
			<xsl:if test="@absolute='true'"><xsl:text> hui_box_absolute</xsl:text></xsl:if>
		</xsl:attribute>
		<xsl:attribute name="style">
		<xsl:if test="@width">width: <xsl:value-of select="@width"/>px;</xsl:if>
		<xsl:if test="@top">padding-top: <xsl:value-of select="@top"/>px;</xsl:if>
		</xsl:attribute>
		<xsl:if test="@closable='true'"><a class="hui_box_close" href="javascript://"><xsl:comment/></a></xsl:if>
		<div class="hui_box_top"><div><div><xsl:comment/></div></div></div>
		<div class="hui_box_middle"><div class="hui_box_middle">
			<xsl:if test="@title or gui:toolbar">
				<div class="hui_box_header">
					<xsl:attribute name="class">hui_box_header<xsl:if test="gui:toolbar"> hui_box_header_toolbar</xsl:if></xsl:attribute>
					<xsl:apply-templates select="gui:toolbar"/>
					<strong class="hui_box_title"><xsl:value-of select="@title"/></strong>
				</div>
			</xsl:if>
			<div class="hui_box_body">
				<xsl:if test="@padding"><xsl:attribute name="style">padding: <xsl:value-of select="@padding"/>px;</xsl:attribute></xsl:if>
				<xsl:apply-templates select="child::*[not(name()='toolbar')]"/>
				<xsl:comment/>
			</div>
		</div></div>
		<div class="hui_box_bottom"><div><div><xsl:comment/></div></div></div>
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

<!--doc title:'Wizard' class:'hui.ui.Wizard'
<wizard name="«name»">
    <step frame="«boolean»" icon="«icon»" title="«text»">
        ···
    </step>
</wizard>
-->
<xsl:template match="gui:wizard">
	<div class="hui_wizard" id="{generate-id()}">
		<table class="hui_wizard"><tr>
			<th class="hui_wizard">
				<ul class="hui_wizard">
				<xsl:for-each select="gui:step">
					<li>
						<a href="#">
							<xsl:attribute name="class">
								<xsl:text>hui_wizard_selection</xsl:text>
								<xsl:if test="position()=1">
									<xsl:text> hui_selected</xsl:text>
								</xsl:if>
							</xsl:attribute>
							<xsl:if test="@icon"><span class="hui_icon_16" style="background-image: url('{$context}/hui/icons/{@icon}16.png');')"><xsl:comment/></span></xsl:if>
							<span><xsl:value-of select="@title"/></span>
						</a>
					</li>
				</xsl:for-each>
				</ul>
			</th>
			<td class="hui_wizard">
				<div class="hui_wizard_steps">
				<xsl:for-each select="gui:step">
					<div>
						<xsl:attribute name="class">
							<xsl:text>hui_wizard_step</xsl:text>
							<xsl:if test="@frame='true'"><xsl:text> hui_wizard_step_frame</xsl:text></xsl:if>
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
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Wizard({
				element:'<xsl:value-of select="generate-id()"/>',
				name:'<xsl:value-of select="@name"/>'
			});
			<xsl:call-template name="gui:createobject"/>
		})();
	</script>
</xsl:template>


<!--doc title:'Layout' class:'hui.ui.Layout'
<layout name="«name»">
    <top>
        ···
    </top>
    <middle>
        <left>
            ···
        </left>
        <center padding="«pixels»">
            ···
        </center>
    </middle>
    <bottom>
        ···
    </botttom>
</layout>
-->
<xsl:template match="gui:layout">
	<table class="hui_layout" id="{generate-id()}">
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
	<thead class="hui_layout">
		<tr><td class="hui_layout_top">
			<div class="hui_layout_top"><div class="hui_layout_top"><div class="hui_layout_top">
				<xsl:apply-templates/>
				<xsl:comment/>
			</div></div></div>
		</td></tr>
	</thead>
</xsl:template>

<xsl:template match="gui:layout/gui:middle">
	<tbody class="hui_layout">
		<tr class="hui_layout_middle">
			<td class="hui_layout_middle">
			<table class="hui_layout_middle">
				<tr>
					<xsl:apply-templates/>
				</tr>
			</table>
		</td></tr>
	</tbody>
</xsl:template>

<xsl:template match="gui:layout/gui:middle/gui:left">
	<td class="hui_layout_left hui_context_sidebar">
		<xsl:apply-templates/>
		<div class="hui_layout_left"><xsl:comment/></div>
	</td>
</xsl:template>

<xsl:template match="gui:layout/gui:middle/gui:center">
	<td class="hui_layout_center">
		<xsl:if test="@padding">
			<xsl:attribute name="style">padding: <xsl:value-of select="@padding"/>px;</xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
	</td>
</xsl:template>

<xsl:template match="gui:layout/gui:bottom">
	<tfoot class="hui_layout">
		<tr><td class="hui_layout_bottom">
			<div class="hui_layout_bottom"><div class="hui_layout_bottom"><div class="hui_layout_bottom">
				<xsl:apply-templates/>
			</div></div></div>
		</td></tr>
	</tfoot>
</xsl:template>



<!--doc title:'Fragment' class:'hui.ui.Fragment'
<fragment name="«name»" state="«text»" height="«'full'»" background="«background»" visible="«boolean»">
    ···
</fragment>
-->
<xsl:template match="gui:fragment">
<div id="{generate-id()}">
	<xsl:attribute name="style">
		<xsl:if test="@state and (not(//gui:gui/@state) or @state!=//gui:gui/@state) or @visible='false'">
			<xsl:text>display:none;</xsl:text>
		</xsl:if>
		<xsl:if test="@height='full'">
			<xsl:text>min-height: 100%;</xsl:text>
		</xsl:if>
	</xsl:attribute>
	<xsl:if test="@background">
		<xsl:attribute name="class">hui_bg_<xsl:value-of select="@background"/></xsl:attribute>
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


<!--doc title:'Structure'
<structure top="«pixels»">
    ···
</structure>
-->
<xsl:template match="gui:structure">
	<div style="position:fixed; top: {@top}px; bottom: 0; width: 100%">
		<xsl:apply-templates/>
	</div>
</xsl:template>

</xsl:stylesheet>