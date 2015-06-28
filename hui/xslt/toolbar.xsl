<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:gui="uri:hui"
    version="1.0"
    exclude-result-prefixes="gui"
>



<!--                  Toolbar                   -->

<!--doc title:'Toolbar' class:'hui.ui.Toolbar' module:'bar'
<toolbar name="«name»" variant="«?»" border="«'top' | 'bottom'»" labels="«boolean»" fixed-height="«boolean»">
    ···
    <right>
        ···
    </right>
</toolbar>
-->
<xsl:template match="gui:toolbar" name="gui:toolbar">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_toolbar</xsl:text>
			<xsl:if test="@labels='false'"><xsl:text> hui_toolbar_nolabels</xsl:text></xsl:if>
			<xsl:if test="@border='top'"><xsl:text> hui_toolbar_border_top</xsl:text></xsl:if>
			<xsl:if test="@border='bottom'"><xsl:text> hui_toolbar_border_bottom</xsl:text></xsl:if>
			<xsl:if test="@centered='true'"><xsl:text> hui_toolbar_centered</xsl:text></xsl:if>
			<xsl:if test="@variant"><xsl:text> hui_toolbar_</xsl:text><xsl:value-of select="@variant"/></xsl:if>
		</xsl:attribute>
		<div>
			<xsl:attribute name="class">
				<xsl:text>hui_toolbar_body</xsl:text>
				<xsl:if test="@fixed-height='true'">
					<xsl:text> hui_toolbar_body_fixed_height</xsl:text>
				</xsl:if>
			</xsl:attribute>
			<xsl:if test="@left">
				<xsl:attribute name="style">
					<xsl:text>padding-left: </xsl:text><xsl:value-of select="@left"/><xsl:text>px;</xsl:text>
				</xsl:attribute>
			</xsl:if>
			<xsl:apply-templates select="child::*[not(name()='right')]"/>
			<xsl:apply-templates select="gui:right"/>
		</div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Toolbar({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>


<xsl:template match="gui:toolbar/gui:right">
	<div class="hui_toolbar_right"><xsl:apply-templates /></div>
</xsl:template>

<xsl:template match="gui:toolbar//gui:divider">
	<span class="hui_divider"><xsl:comment /></span>
</xsl:template>

<xsl:template match="gui:toolbar//gui:more">
	<span class="hui_toolbar_more" id="{generate-id()}">
		<span class="hui_toolbar_more_content">
			<xsl:apply-templates/>
			<xsl:comment/>
		</span>
		<xsl:if test="@text">
			<a class="hui_toolbar_more"><xsl:value-of select="@text"/></a>
		</xsl:if>
		<xsl:if test="not(@text)">
			<a class="hui_toolbar_more hui_toolbar_more_default">···</a>
		</xsl:if>
	</span>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Toolbar.More({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!--doc title:'Toolbar icon' class:'hui.ui.Toolbar.Icon' module:'bar'
<toolbar···>
    ···
    <icon name="«name»" icon="«icon»" overlay="«overlay»" text="«text»" selected="«boolean»" disabled="«boolean»" click="«script»">
        <confirm text="«text»" ok="«text»" cancel="«text»"/>
    </icon>
    ···
</toolbar>
-->
<xsl:template match="gui:toolbar//gui:icon">
	<a id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_toolbar_icon</xsl:text>
			<xsl:if test="@selected='true'"> hui_toolbar_icon_selected</xsl:if>
			<xsl:if test="@disabled='true'"> hui_toolbar_icon_disabled</xsl:if>
		</xsl:attribute>
		<span class="hui_toolbar_inner_icon">
			<span class="hui_toolbar_inner_icon">
			<span class="hui_icon" style="background-image: url('{$context}/hui/icons/{@icon}32.png')">
				<xsl:if test="@overlay">
					<span class="hui_icon_overlay" style="background-image: url('{$context}/hui/icons/overlay/{@overlay}32.png')"><xsl:comment/></span>
				</xsl:if>
				<xsl:if test="@badge!=''">
					<span class="hui_icon_badge"><xsl:value-of select="@badge"/></span>
				</xsl:if>
				<xsl:comment/>
			</span>
			<strong><xsl:value-of select="@title"/><xsl:value-of select="@text"/></strong>
			</span>
		</span>
	</a>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Toolbar.Icon({
			element : '<xsl:value-of select="generate-id()"/>',
			name : '<xsl:value-of select="@name"/>'
			<xsl:if test="@key">
				,key : '<xsl:value-of select="@key"/>'
			</xsl:if>
			<xsl:if test="gui:confirm">
				,confirm:{text:'<xsl:value-of select="gui:confirm/@text"/>',okText:'<xsl:value-of select="gui:confirm/@ok"/>',cancelText:'<xsl:value-of select="gui:confirm/@cancel"/>'}
			</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
		<xsl:if test="@click">
			<xsl:value-of select="generate-id()"/>_obj.listen({$click:function() {<xsl:value-of select="@click"/>}});
		</xsl:if>
	</script>
</xsl:template>

<!--doc title:'Search field' class:'hui.ui.SearchField' module:'input'
<searchfield name="«text»" adaptive="«true»" width="«pixels»" expanded-width="«pixels»"/>
-->
<xsl:template match="gui:searchfield" name="gui:searchfield">
	<span id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_searchfield</xsl:text>
			<xsl:if test="@adaptive='true'">
				<xsl:text> hui_searchfield_adaptive</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:if test="@width">
			<xsl:attribute name="style">width:<xsl:value-of select="@width"/>px;</xsl:attribute>
		</xsl:if>
		<em class="hui_searchfield_placeholder"><xsl:value-of select="@placeholder"/><xsl:comment/></em>
		<a class="hui_searchfield_reset"><xsl:comment/></a>
		<span><span><input type="text"/></span></span>
	</span>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.SearchField({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@expanded-width">,expandedWidth:<xsl:value-of select="@expanded-width"/></xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!--doc title:'Toolbar badge' class:'hui.ui.Toolbar.Badge' module:'bar'
<badge name="«text»" label="«text»" text="«text»" icon="«icon»"/>
-->
<xsl:template match="gui:toolbar//gui:badge">
	<div id="{generate-id()}" class="hui_toolbar_badge">
		<div class="hui_toolbar_inner_badge"><div class="hui_toolbar_inner_badge">
		<xsl:if test="@icon">
			<div class="hui_toolbar_badge_icon" style="background-image: url('{$context}/hui/icons/{@icon}16.png')"><xsl:comment/></div>
		</xsl:if>
		<strong><xsl:value-of select="@label"/><xsl:comment/></strong>
		<span><xsl:value-of select="@text"/><xsl:comment/></span>
		</div></div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Toolbar.Badge({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>


<!--doc title:'Toolbar field' module:'bar'
<toolbar···>
    ···
    <field label="«text»">
        ···
    </field>
    ···
</toolbar>
-->
<xsl:template match="gui:toolbar//gui:field">
	<span class="hui_toolbar_item">
		<span class="hui_toolbar_item_body"><xsl:apply-templates/></span>
		<span class="hui_toolbar_label"><xsl:value-of select="@label"/></span>
	</span>
</xsl:template>





<!--doc title:'Toolbar grid' module:'bar'
<toolbar···>
    ···
    <grid>
        <row>
            <cell width="«pixels»" left="«pixels»" right="«pixels»">
                ···
            </cell>
            <cell···><label>«text»</label></cell>
        </row>
        <row>
            <cell···>
            </cell>
        </row>
    </grid>
    ···
</toolbar>
-->
<xsl:template match="gui:toolbar//gui:grid">
	<span class="hui_toolbar_grid">
	<table class="hui_toolbar_grid">
		<xsl:if test="@left or @right">
		<xsl:attribute name="style">
			<xsl:if test="@left">
				margin-left:<xsl:value-of select="@left"/>px;
			</xsl:if>
			<xsl:if test="@right">
				margin-right:<xsl:value-of select="@right"/>px;
			</xsl:if>
		</xsl:attribute>
		</xsl:if>
		<xsl:apply-templates/>
	</table>
	</span>
</xsl:template>

<xsl:template match="gui:toolbar//gui:grid/gui:row">
	<tr>
		<xsl:apply-templates/>
	</tr>
</xsl:template>

<xsl:template match="gui:toolbar//gui:grid/gui:row/gui:cell">
	<xsl:if test="@label">
		<th><xsl:value-of select="@label"/></th>
	</xsl:if>
	<td>
		<xsl:attribute name="style">
			<xsl:if test="@width">width:<xsl:value-of select="@width"/>px;</xsl:if>
			<xsl:if test="@left">padding-left:<xsl:value-of select="@left"/>px;</xsl:if>
			<xsl:if test="@right">padding-right:<xsl:value-of select="@right"/>px;</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates/>
	</td>
</xsl:template>

<xsl:template match="gui:toolbar//gui:grid/gui:row/gui:cell/gui:label">
	<label class="hui_toolbar_grid"><xsl:apply-templates/></label>
</xsl:template>








<!--doc title:'Bar' class:'hui.ui.Bar' module:'bar'
<bar name="«text»" state="«text»">
    ···
    <right>
        ···
    </right>
</bar>
-->
<xsl:template match="gui:bar">
	<div id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_bar</xsl:text>
			<xsl:if test="@variant">
				<xsl:text> hui_bar_</xsl:text><xsl:value-of select="@variant"/>
			</xsl:if>
		</xsl:attribute>
		<xsl:if test="(@state and (not(//gui:gui/@state) or @state!=//gui:gui/@state)) or @visible='false'">
			<xsl:attribute name="style">display:none;</xsl:attribute>
		</xsl:if>
		<div class="hui_bar_body">
			<xsl:apply-templates select="gui:right"/>
			<div class="hui_bar_left">
				<xsl:apply-templates select="child::*[not(name()='right')]"/>
				<xsl:comment/>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Bar({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="@state">,state : '<xsl:value-of select="@state"/>'</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:bar/gui:right">
	<div class="hui_bar_right">
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
</xsl:template>




<!--doc title:'Bar button' class:'hui.ui.Bar.Button' module:'bar'
<bar···>
    <button name="«text»" icon="«icon»" text="«text»" selected="«boolean»" click="«script»"/>
</bar>
-->
<xsl:template match="gui:bar//gui:button[@icon]">
	<xsl:variable name="class">
		<xsl:text>hui_bar_button</xsl:text>
		<xsl:if test="@selected='true'"><xsl:text> hui_bar_button_selected</xsl:text></xsl:if>
	</xsl:variable>
	<a id="{generate-id()}" class="{$class}">
		<xsl:if test="@icon">
			<span class="hui_icon_16" style="background-image: url('{$context}/hui/icons/{@icon}16.png')"><xsl:comment/></span>
		</xsl:if>
		<xsl:if test="@text">
			<span class="hui_bar_button_text"><xsl:value-of select="@text"/></span>
		</xsl:if>
	</a>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Bar.Button({
			element : '<xsl:value-of select="generate-id()"/>',
			name : '<xsl:value-of select="@name"/>'
			<xsl:if test="@key">,key : '<xsl:value-of select="@key"/>'</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
		<xsl:if test="@click">
			<xsl:value-of select="generate-id()"/>_obj.listen({$click:function() {<xsl:value-of select="@click"/>}});
		</xsl:if>
	</script>
</xsl:template>




<!--doc title:'Bar text' class:'hui.ui.Bar.Text' module:'bar'
<bar···>
    <text name="«text»" text="«text»" variant="«?»"/>
</bar>
-->
<xsl:template match="gui:bar//gui:text">
	<span class="hui_bar_text" id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_bar_text</xsl:text>
			<xsl:if test="@variant">
				<xsl:text> hui_bar_text_</xsl:text><xsl:value-of select="@variant"/>
			</xsl:if>
		</xsl:attribute>
		<xsl:value-of select="@text"/>
		<xsl:comment/>
	</span>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Bar.Text({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>




<!--doc title:'Bar space' module:'bar'
<bar | toolbar···>
    <space width="«pixels»"/>
</bar | toolbar>
-->
<xsl:template match="gui:bar//gui:space | gui:toolbar//gui:space">
	<span class="hui_bar_space">
		<xsl:if test="@width">
			<xsl:attribute name="style">width:<xsl:value-of select="@width"/>px;</xsl:attribute>
		</xsl:if>
		<xsl:comment/>
	</span>
</xsl:template>

</xsl:stylesheet>
