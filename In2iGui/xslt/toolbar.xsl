<?xml version="1.0"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:gui="uri:In2iGui"
    version="1.0"
    exclude-result-prefixes="gui"
>



<!--                  Toolbar                   -->


<xsl:template match="gui:toolbar" name="gui:toolbar">
	<div>
		<xsl:attribute name="class">
			<xsl:text>in2igui_toolbar</xsl:text>
			<xsl:if test="@labels='false'"><xsl:text> in2igui_toolbar_nolabels</xsl:text></xsl:if>
			<xsl:if test="@border='top'"><xsl:text> in2igui_toolbar_border_top</xsl:text></xsl:if>
			<xsl:if test="@border='bottom'"><xsl:text> in2igui_toolbar_border_bottom</xsl:text></xsl:if>
			<xsl:if test="@centered='true'"><xsl:text> in2igui_toolbar_centered</xsl:text></xsl:if>
		</xsl:attribute>
		<div>
			<xsl:attribute name="class">
				<xsl:text>in2igui_toolbar_body</xsl:text>
				<xsl:if test="@fixed-height='true'">
					<xsl:text> in2igui_toolbar_body_fixed_height</xsl:text>
				</xsl:if>
			</xsl:attribute>
			<xsl:apply-templates select="child::*[not(name()='right')]"/>
			<xsl:apply-templates select="gui:right"/>
		</div>
	</div>
</xsl:template>


<xsl:template match="gui:toolbar/gui:right">
	<div class="in2igui_toolbar_right"><xsl:apply-templates /></div>
</xsl:template>

<xsl:template match="gui:toolbar//gui:divider">
	<span class="in2igui_divider"><xsl:comment /></span>
</xsl:template>

<xsl:template match="gui:toolbar//gui:icon">
	<a id="{generate-id()}" href="javascript:void(0)">
		<xsl:attribute name="class">
			<xsl:text>in2igui_toolbar_icon</xsl:text>
			<xsl:if test="@selected='true'"> in2igui_toolbar_icon_selected</xsl:if>
			<xsl:if test="@disabled='true'"> in2igui_toolbar_icon_disabled</xsl:if>
		</xsl:attribute>
		<span class="in2igui_toolbar_inner_icon">
			<span class="in2igui_toolbar_inner_icon">
			<span class="in2igui_icon" style="background-image: url('{$context}/In2iGui/icons/{@icon}2.png')">
				<xsl:if test="@overlay">
					<span class="in2igui_icon_overlay" style="background-image: url('{$context}/In2iGui/icons/overlay/{@overlay}2.png')"><xsl:comment/></span>
				</xsl:if>
				<xsl:comment/>
			</span>
			<strong><xsl:value-of select="@title"/></strong>
			</span>
		</span>
	</a>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Toolbar.Icon({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>'
			<xsl:if test="gui:confirm">
				,confirm:{text:'<xsl:value-of select="gui:confirm/@text"/>',okText:'<xsl:value-of select="gui:confirm/@ok"/>',cancelText:'<xsl:value-of select="gui:confirm/@cancel"/>'}
			</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
		<xsl:if test="@action">
			<xsl:value-of select="generate-id()"/>_obj.listen({$click:function() {<xsl:value-of select="@action"/>}});
		</xsl:if>
		<xsl:if test="@click">
			<xsl:value-of select="generate-id()"/>_obj.listen({$click:function() {<xsl:value-of select="@click"/>}});
		</xsl:if>
	</script>
</xsl:template>

<xsl:template match="gui:searchfield" name="gui:searchfield">
	<span class="in2igui_searchfield" id="{generate-id()}">
		<xsl:if test="@width"><xsl:attribute name="style">width:<xsl:value-of select="@width"/>px;</xsl:attribute></xsl:if>
		<em class="in2igui_searchfield_placeholder"><xsl:value-of select="@placeholder"/><xsl:comment/></em>
		<a href="javascript:void(0);" class="in2igui_searchfield_reset"><xsl:comment/></a>
		<span><span><input type="text"/></span></span>
	</span>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.SearchField({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'<xsl:if test="@expandedWidth">,expandedWidth:<xsl:value-of select="@expandedWidth"/></xsl:if>});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:toolbar//gui:searchfield[@title]">
	<span class="in2igui_toolbar_search">
		<xsl:call-template name="gui:searchfield"/>
		<span class="in2igui_toolbar_search_label"><xsl:value-of select="@title"/><xsl:value-of select="@label"/></span>
	</span>
</xsl:template>

<xsl:template match="gui:toolbar//gui:badge">
	<div id="{generate-id()}" class="in2igui_toolbar_badge">
		<div class="in2igui_toolbar_inner_badge"><div class="in2igui_toolbar_inner_badge">
		<xsl:if test="@icon">
			<div class="in2igui_toolbar_badge_icon" style="background-image: url('{$context}/In2iGui/icons/{@icon}1.png')"><xsl:comment/></div>
		</xsl:if>
		<strong><xsl:value-of select="@label"/><xsl:comment/></strong>
		<span><xsl:value-of select="@text"/><xsl:comment/></span>
		</div></div>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Toolbar.Badge({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!-- ///////////////////// table ///////////////////// -->


<xsl:template match="gui:toolbar//gui:grid">
	<span class="in2igui_toolbar_grid">
	<table class="in2igui_toolbar_grid">
		<xsl:if test="@left">
			<xsl:attribute name="style">margin-left:<xsl:value-of select="@left"/>px;</xsl:attribute>
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
	<label class="in2igui_toolbar_grid"><xsl:apply-templates/></label>
</xsl:template>
<!-- Inputs -->

<xsl:template match="gui:toolbar//gui:checkboxes">
	<span class="in2igui_toolbar_item">
		<span class="in2igui_toolbar_item_body"><xsl:call-template name="gui:checkboxes"/></span>
		<span class="in2igui_toolbar_label"><xsl:value-of select="@title"/><xsl:value-of select="@label"/></span>
	</span>
</xsl:template>

<xsl:template match="gui:toolbar//gui:number">
	<span class="in2igui_toolbar_item">
		<span class="in2igui_toolbar_item_body"><xsl:call-template name="gui:number"/></span>
		<span class="in2igui_toolbar_label"><xsl:value-of select="@title"/><xsl:value-of select="@label"/></span>
	</span>
</xsl:template>

<xsl:template match="gui:toolbar//gui:style-length">
	<span class="in2igui_toolbar_item">
		<span class="in2igui_toolbar_item_body"><xsl:call-template name="gui:style-length"/></span>
		<span class="in2igui_toolbar_label"><xsl:value-of select="@title"/><xsl:value-of select="@label"/></span>
	</span>
</xsl:template>

<xsl:template match="gui:toolbar//gui:dropdown[@title] | gui:toolbar//gui:dropdown[@label]">
	<span class="in2igui_toolbar_item">
		<span class="in2igui_toolbar_item_body"><xsl:call-template name="gui:dropdown"/></span>
		<span class="in2igui_toolbar_label"><xsl:value-of select="@title"/><xsl:value-of select="@label"/></span>
	</span>
</xsl:template>

<xsl:template match="gui:toolbar//gui:segmented[@label]">
	<span class="in2igui_toolbar_item">
		<span class="in2igui_toolbar_item_body"><xsl:call-template name="gui:segmented"/></span>
		<span class="in2igui_toolbar_label"><xsl:value-of select="@label"/></span>
	</span>
</xsl:template>

<xsl:template match="gui:toolbar//gui:textfield">
	<span class="in2igui_toolbar_item">
		<span class="in2igui_toolbar_item_body"><xsl:call-template name="gui:text"/></span>
		<span class="in2igui_toolbar_label"><xsl:value-of select="@label"/></span>
	</span>
</xsl:template>

<xsl:template match="gui:toolbar//gui:cell/gui:number">
	<xsl:call-template name="gui:number"/>
</xsl:template>

<xsl:template match="gui:toolbar//gui:cell/gui:textfield">
	<xsl:call-template name="gui:text"/>
</xsl:template>

<xsl:template match="gui:toolbar//gui:cell/gui:dropdown">
	<xsl:call-template name="gui:dropdown"/>
</xsl:template>


<!-- Bar -->


<xsl:template match="gui:bar">
	<div class="in2igui_bar">
		<div class="in2igui_bar_body">
			<xsl:apply-templates select="gui:right"/>
			<div class="in2igui_bar_left">
				<xsl:apply-templates select="child::*[not(name()='right')]"/>
				<xsl:comment/>
			</div>
		</div>
	</div>
</xsl:template>

<xsl:template match="gui:bar/gui:right">
	<div class="in2igui_bar_right">
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
</xsl:template>

<xsl:template match="gui:bar//gui:button">
	<xsl:variable name="class">
		<xsl:text>in2igui_bar_button</xsl:text>
		<xsl:if test="@highlighted='true'"><xsl:text> in2igui_bar_button_highlighted</xsl:text></xsl:if>
	</xsl:variable>
	<a id="{generate-id()}" class="{$class}" href="javascript:void(0);">
		<span class="in2igui_icon_1" style="background-image: url('{$context}/In2iGui/icons/{@icon}1.png')"><xsl:comment/></span>
		<span class="in2igui_bar_button_text"><xsl:value-of select="@text"/></span>
	</a>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Bar.Button({
			element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>'
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

</xsl:stylesheet>
