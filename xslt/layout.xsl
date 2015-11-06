<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:gui="uri:hui"
    version="1.0"
    exclude-result-prefixes="gui"
    >


<!--doc title:'Icon' class:'hui.ui.Icon' module:'action'
<icon name="«text»" icon="«icon»" size="«pixels»" text="«text»"/>
-->
<xsl:template match="gui:icon">
	<span id="{generate-id()}">
		<xsl:attribute name="style">background-image: url('<xsl:value-of select="$context"/>/hui/icons/<xsl:value-of select="@icon"/><xsl:value-of select="@size"/>.png');</xsl:attribute>
		<xsl:attribute name="class">hui_icon_<xsl:value-of select="@size"/></xsl:attribute>
		<xsl:comment/>
	</span>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Icon({
			element : '<xsl:value-of select="generate-id()"/>',
			icon : '<xsl:value-of select="@icon"/>',
			size : <xsl:value-of select="@size"/>
			<xsl:if test="@name">,name:'<xsl:value-of select="@name"/>'</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>


<xsl:template match="gui:icon[@text]">
	<a id="{generate-id()}" href="javascript://" class="hui_icon_labeled hui_icon_labeled_{@size}">
		<xsl:if test="@click">
			<xsl:attribute name="onclick"><xsl:value-of select="@click"/></xsl:attribute>
		</xsl:if>
		<span>
			<xsl:attribute name="style">background-image: url('<xsl:value-of select="$context"/>/hui/icons/<xsl:value-of select="@icon"/><xsl:value-of select="@size"/>.png');</xsl:attribute>
			<xsl:attribute name="class">hui_icon_<xsl:value-of select="@size"/></xsl:attribute>
			<xsl:comment/>
		</span>
		<strong><xsl:value-of select="@text"/></strong>
	</a>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Icon({
			element : '<xsl:value-of select="generate-id()"/>',
			icon : '<xsl:value-of select="@icon"/>',
			size : <xsl:value-of select="@size"/>
			<xsl:if test="@name">,name:'<xsl:value-of select="@name"/>'</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>
	




<!--doc title:'Space' module:'layout'
<space all="«pixels»" left="«pixels»" right="«pixels»" top="«pixels»" bottom="«pixels»" align="«left | center | right»" height="«pixels»" width="«pixels»">
    ···
</space>
-->
<xsl:template match="gui:space">
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


<xsl:template match="gui:space[@width]">
	<span style="display: inline-block; font-size: 0; width: 20px;"><xsl:comment/></span>
</xsl:template>





<!--doc title:'Columns' module:'layout'
<columns space="«pixels»" flexible="«boolean»" height="«'full'»">
    <column width="«css-length»">
        ···
    </column>
    <column width="«css-length»">
    ···
    </column>
</columns>
-->

<xsl:template match="gui:columns">
	<div class="hui_columns" id="{generate-id()}">
		<xsl:apply-templates/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Columns({
			element : '<xsl:value-of select="generate-id()"/>',
			<xsl:if test="@name">,name:'<xsl:value-of select="@name"/>'</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:columns/gui:column">
	<div class="hui_columns_column">
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
</xsl:template>

<xsl:template match="gui:columns[@flexible='true']">
	<table cellspacing="0" cellpadding="0">
		<xsl:attribute name="class">
			<xsl:text>hui_columns</xsl:text>
			<xsl:if test="@height='full'">
				<xsl:text> hui_columns_full</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<tr>
			<xsl:apply-templates select="gui:column"/>
		</tr>
	</table>
</xsl:template>

<xsl:template match="gui:columns[@flexible='true']/gui:column">
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


<!--doc title:'Rows' module:'layout'
<rows space="«pixels»" flexible="«boolean»" height="«'full'»">
    <column width="«css-length»">
        ···
    </column>
    <column width="«css-length»">
    ···
    </column>
</row>
-->
<xsl:template match="gui:rows">
  <div id="{generate-id()}">
    <xsl:attribute name="class">
      <xsl:text>hui_rows</xsl:text>
    </xsl:attribute>
    <xsl:apply-templates select="gui:row"/>
  </div>
	<script type="text/javascript">
    (function() {
  		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Rows({
  			element : '<xsl:value-of select="generate-id()"/>',
  			<xsl:if test="@name">,name:'<xsl:value-of select="@name"/>'</xsl:if>
  		});
  		<xsl:call-template name="gui:createobject"/>
    })()
	</script>
</xsl:template>

<xsl:template match="gui:rows/gui:row">
  <div>
    <xsl:attribute name="class">
      <xsl:text>hui_rows_row</xsl:text>
      <xsl:if test="@size='adapt'">
        <xsl:text> hui_rows_row-adapt</xsl:text>        
      </xsl:if>
      <xsl:if test="@size='min'">
        <xsl:text> hui_rows_row-min</xsl:text>        
      </xsl:if>
    </xsl:attribute>
    <xsl:attribute name="data">
      <xsl:text>{"min":"</xsl:text>
      <xsl:value-of select="@min"/>
      <xsl:text>","max":"</xsl:text>
      <xsl:value-of select="@max"/>
      <xsl:text>","height":"</xsl:text>
      <xsl:value-of select="@height"/>
      <xsl:text>"}</xsl:text>
    </xsl:attribute>
    <xsl:apply-templates/>
		<xsl:comment/>
  </div>
</xsl:template>

<!--doc title:'Header' module:'layout'
<header icon="«icon»">«text»</header>
-->
<xsl:template match="gui:header">
	<h2 class="hui_header">
		<xsl:if test="@icon">
			<span class="hui_icon_2" style="background-image: url('{$context}/hui/icons/{@icon}32.png')">
				<xsl:comment/>
			</span>
		</xsl:if>
		<xsl:apply-templates/>
	</h2>
</xsl:template>





<!--doc title:'Overflow' class:'hui.ui.Overflow' module:'layout'
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
		<xsl:if test="@shadow-variant">
			<xsl:text> hui_overflow_shadow_</xsl:text><xsl:value-of select="@shadow-variant"/>
		</xsl:if>
	</xsl:attribute>
	<xsl:attribute name="style">
		<xsl:choose>
			<xsl:when test="@height or @max-height or @min-height or @full='true'">
				<xsl:if test="@full='true'">height: 100%;</xsl:if>
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
	<div class="hui_overflow_top">
		<xsl:comment/></div>
	<xsl:apply-templates/>
	<div class="hui_overflow_bottom"><xsl:comment/></div>
</div>
<script type="text/javascript">
	var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Overflow({
		element : '<xsl:value-of select="generate-id()"/>',
		dynamic : <xsl:value-of select="not(@height or @max-height or @min-height or @vertical or @full='true')"/>
		<xsl:if test="@vertical">,vertical:<xsl:value-of select="@vertical"/></xsl:if>
		<xsl:if test="@state">,state:'<xsl:value-of select="@state"/>'</xsl:if>
		<xsl:if test="@name">,name:'<xsl:value-of select="@name"/>'</xsl:if>
	});
	<xsl:call-template name="gui:createobject"/>
</script>
</xsl:template>




<!--doc title:'Split' module:'layout'
<split>
    <row>···</row>
    <row>···</row>
</split>
-->
<xsl:template match="gui:split">
	<div class="hui_split" id="{generate-id()}">
		<xsl:apply-templates select="gui:row"/>
	</div>
	<script type="text/javascript">
		(function() {
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Split({
				element : '<xsl:value-of select="generate-id()"/>'
				<xsl:if test="@name">,name:'<xsl:value-of select="@name"/>'</xsl:if>
			});
			<xsl:call-template name="gui:createobject"/>		
		})()
	</script>
</xsl:template>

<xsl:template match="gui:split/gui:row">
	<div class="hui_split_row">
		<xsl:if test="@height">
			<xsl:attribute name="data-height">
				<xsl:value-of select="@height"/>
			</xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
</xsl:template>



<!--doc title:'Box' class:'hui.ui.Box' module:'layout'
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





<!--doc title:'Wizard' class:'hui.ui.Wizard' module:'layout'
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
				<xsl:if test="@selection-width">
				</xsl:if>
				<ul class="hui_wizard">
					<xsl:attribute name="style">
						<xsl:text>width: </xsl:text>
						<xsl:value-of select="@selection-width"/>
						<xsl:text>px;</xsl:text>
					</xsl:attribute>
				<xsl:for-each select="gui:step">
					<li>
						<a href="javascript://">
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
				element : '<xsl:value-of select="generate-id()"/>',
				name : '<xsl:value-of select="@name"/>'
			});
			<xsl:call-template name="gui:createobject"/>
		})();
	</script>
</xsl:template>





<!--doc title:'Layout' class:'hui.ui.Layout' module:'layout'
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




<!--doc title:'Fragment' class:'hui.ui.Fragment' module:'layout'
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
  <xsl:comment/>
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




<!--doc title:'Pages' class:'hui.ui.Pages' module:'layout'
<pages name="«name»" height="«'full'»">
    <page>
        ···
    </page>
    <page>
        ···
    </page>
</pages>
-->
<xsl:template match="gui:pages">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_pages</xsl:text>
			<xsl:if test="@height='full'">
				<xsl:text> hui_pages_full</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:for-each select="gui:page">
			<div class="hui_pages_page">
				<xsl:attribute name="class">
					<xsl:text>hui_pages_page</xsl:text>
					<xsl:if test="@background">
						<xsl:text> hui_bg_</xsl:text><xsl:value-of select="@background"/>
					</xsl:if>
				</xsl:attribute>
				<xsl:if test="@key">
					<xsl:attribute name="data-key"><xsl:value-of select="@key"/></xsl:attribute>
				</xsl:if>
				<xsl:if test="position()>1">
					<xsl:attribute name="style">display:none;</xsl:attribute>
				</xsl:if>
				<xsl:apply-templates/>
				<xsl:comment/>
			</div>			
		</xsl:for-each>
		<xsl:apply-templates/>
	</div>
	<script type="text/javascript">
	var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Pages({
		element : '<xsl:value-of select="generate-id()"/>'
		<xsl:if test="@name">,name : '<xsl:value-of select="@name"/>'</xsl:if>
	});
	<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:pages/gui:page">
</xsl:template>





<!--doc title:'Tiles' class:'hui.ui.Tiles' module:'layout'
<tiles name="«name»" reveal="«boolean»">
    <tile width="«percent»" height="«percent»" left="«percent»" top="«percent»" padding="«pixels»" background="«css-color»" variant="«'light'»">
        <title>«text»</title>
        <actions>
            <icon icon="«icon»" key="«text»"/>
            <icon icon="«icon»" key="«text»"/>
        </actions>
        ···
    </tile>
</pages>
-->
<xsl:template match="gui:tiles">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_tiles</xsl:text>
			<xsl:if test="@reveal='true'">
				<xsl:text> hui_tiles_revealing</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates/>
	</div>
	<script type="text/javascript">
	var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Tiles({
		element : '<xsl:value-of select="generate-id()"/>'
		<xsl:if test="@name">,name : '<xsl:value-of select="@name"/>'</xsl:if>
		<xsl:if test="@reveal='true'">,reveal : true</xsl:if>
	});
	<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:tiles/gui:tile">
	<div class="hui_tile" id="{generate-id()}">
		<xsl:attribute name="style">
			width: <xsl:value-of select="@width"/>%;
			height: <xsl:value-of select="@height"/>%; 
			left: <xsl:value-of select="@left"/>%; 
			top: <xsl:value-of select="@top"/>%; 
			padding: <xsl:value-of select="../@space div 2"/>px;
		</xsl:attribute>
		<div>
			<xsl:attribute name="class">
				<xsl:text>hui_tile_body</xsl:text>
				<xsl:if test="@background">
					<xsl:text> hui_tile_color</xsl:text>
				</xsl:if>
				<xsl:if test="@variant">
					<xsl:text> hui_tile_</xsl:text><xsl:value-of select="@variant"/>
				</xsl:if>
			</xsl:attribute>
			<xsl:if test="@background">
				<xsl:attribute name="style">
					background-color: <xsl:value-of select="@background"/>;
				</xsl:attribute>
			</xsl:if>
			<xsl:choose>
				<xsl:when test="gui:title">
					<div class="hui_tile_title">
						<xsl:value-of select="gui:title"/>
					</div>
					<div class="hui_tile_content">
						<xsl:apply-templates/>
					</div>
				</xsl:when>
				<xsl:otherwise>
					<xsl:apply-templates/>
				</xsl:otherwise>
			</xsl:choose>
		</div>
	</div>
	<script type="text/javascript">
	var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Tile({
		element : '<xsl:value-of select="generate-id()"/>'
		<xsl:if test="@name">,name : '<xsl:value-of select="@name"/>'</xsl:if>
	});
	<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:tile/gui:title">
</xsl:template>

<xsl:template match="gui:tile/gui:actions">
	<div class="hui_tile_actions">
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="gui:tile/gui:actions/gui:icon">
	<a class="hui_icon_16 hui_tile_icon" style="background-image: url('{$context}/hui/icons/{@icon}16.png')">
		<xsl:if test="@key">
			<xsl:attribute name="data-hui-key">
				<xsl:value-of select="@key"/>
			</xsl:attribute>
		</xsl:if>
		<xsl:comment/>
	</a>
</xsl:template>





<!--doc title:'Structure' class:'hui.ui.Structure' module:'layout'
<structure name="«name»" height="«pixels»">
    <top>
        ···
    </top>
    <middle>
        <left>
            ···
        </left>
        <center>
            ···
        </center>
        <right>
            ···
        </right>
    </middle>
    <bottom>
        ···
    </bottom>
</structure>
-->
<xsl:template match="gui:structure">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_structure</xsl:text>
			<xsl:if test="not(@height)">
				<xsl:text> hui_structure_full</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:if test="@height"><xsl:attribute name="style">height:<xsl:value-of select="@height"/>px;</xsl:attribute></xsl:if>
		
		<xsl:apply-templates/>
	</div>
	<script type="text/javascript">
	var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Structure({
		element : '<xsl:value-of select="generate-id()"/>'
		<xsl:if test="@name">,name : '<xsl:value-of select="@name"/>'</xsl:if>
	});
	<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:structure/gui:top">
	<div class="hui_structure_top">
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="gui:structure/gui:middle">
	<div class="hui_structure_middle">
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="gui:structure/gui:middle/gui:left">
	<div class="hui_structure_left hui_context_sidebar">
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="gui:structure/gui:middle/gui:center">
	<div class="hui_structure_center">
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="gui:structure/gui:middle/gui:right">
	<div class="hui_structure_right">
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="gui:structure/gui:bottom">
	<div class="hui_structure_bottom">
		<xsl:apply-templates/>
	</div>
</xsl:template>


<!--doc title:'Skeleton' class:'hui.ui.Skeleton' module:'layout'
-->

<xsl:template match="gui:skeleton">
  <div class="hui_skeleton" id="{generate-id()}">
    <div class="hui_skeleton_navigation hui_context_sidebar">
      <div class="hui_skeleton_resize hui_skeleton_resize_navigation"></div>
      <xsl:apply-templates select="gui:navigation"/>
    </div>
    <div class="hui_skeleton_results">
      <div class="hui_skeleton_resize hui_skeleton_resize_results"></div>
      <xsl:apply-templates select="gui:results"/>
    </div>
    <div class="hui_skeleton_actions">
      <xsl:apply-templates select="gui:actions"/>
    </div>
    <div class="hui_skeleton_content">
      <xsl:apply-templates select="gui:content"/>
    </div>
  </div>
	<script type="text/javascript">
    (function() {
    	var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Skeleton({
    		element : '<xsl:value-of select="generate-id()"/>'
    		<xsl:if test="@name">,name : '<xsl:value-of select="@name"/>'</xsl:if>
    	});
    	<xsl:call-template name="gui:createobject"/>
    })()
	</script>
</xsl:template>

</xsl:stylesheet>