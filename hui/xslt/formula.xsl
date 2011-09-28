<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:fn="http://www.w3.org/2005/xpath-functions"
    xmlns:gui="uri:hui"
    version="1.0"
    exclude-result-prefixes="gui fn"
    >

<xsl:template match="gui:formula">
	<form class="hui_formula hui_formula" id="{generate-id()}">
		<xsl:attribute name="style">
			<xsl:if test="@state and (not(//gui:gui/@state) or @state!=//gui:gui/@state)">
				<xsl:text>display:none;</xsl:text>
			</xsl:if>
			<xsl:if test="@padding">padding: <xsl:value-of select="@padding"/>px;</xsl:if>
		</xsl:attribute>
		<xsl:apply-templates/>
	</form>
	<script type="text/javascript">
		(function() {
			var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Formula({
				element:'<xsl:value-of select="generate-id()"/>',
				name:'<xsl:value-of select="@name"/>'
				<xsl:if test="@state">
					,state:'<xsl:value-of select="@state"/>'
				</xsl:if>
			});
			<xsl:call-template name="gui:createobject"/>		
		}());
	</script>
</xsl:template>

<xsl:template match="gui:formula//gui:header">
	<div class="hui_formula_header"><xsl:apply-templates/></div>
</xsl:template>

<xsl:template match="gui:formula//gui:group">
	<table class="hui_formula_group">
		<xsl:apply-templates/>
	</table>
</xsl:template>

<xsl:template match="gui:formula//gui:group[@labels='above']">
	<table class="hui_formula_group hui_formula_group_above">
		<xsl:apply-templates/>
	</table>
</xsl:template>

<xsl:template match="gui:formula//gui:group[@legend]">
	<fieldset>
		<legend><xsl:value-of select="@legend"/></legend>
		<table class="group">
			<xsl:apply-templates/>
		</table>
	</fieldset>
</xsl:template>

<xsl:template match="gui:formula//gui:fieldset">
	<div class="hui_formula_fieldset">
		<strong class="hui_formula_fieldset"><xsl:value-of select="@legend"/></strong>
		<xsl:apply-templates/>
	</div>
</xsl:template>

<xsl:template match="gui:group/gui:custom">
	<tr>
		<th><label><xsl:value-of select="@label"/></label></th>
		<td><xsl:apply-templates/></td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:custom">
	<tr><td>
		<xsl:if test="@label"><label><xsl:value-of select="@label"/></label></xsl:if>
		<xsl:apply-templates/>
	</td></tr>
</xsl:template>

<!-- Field -->

<xsl:template match="gui:group/gui:field">
	<tr>
		<th><label><xsl:value-of select="@label"/></label></th>
		<td class="hui_formula_group">
			<div class="hui_formula_item"><xsl:apply-templates/></div>
			<xsl:if test="@hint"><p class="hui_formula_field_hint"><xsl:value-of select="@hint"/></p></xsl:if>
		</td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:field">
	<tr><td>
		<xsl:if test="@label"><label><xsl:value-of select="@label"/></label></xsl:if>
		<div class="hui_formula_item"><xsl:apply-templates/></div>
		<xsl:if test="@hint"><p class="hui_formula_field_hint"><xsl:value-of select="@hint"/></p></xsl:if>
	</td></tr>
</xsl:template>

<!-- Text -->

<xsl:template match="gui:group/gui:text">
	<tr>
		<th>
			<xsl:if test="not(@lines>1) and not(@multiline='true')"><xsl:attribute name="class">hui_formula_middle</xsl:attribute></xsl:if>
			<label><xsl:value-of select="@label"/></label></th>
		<td class="hui_formula_group"><div class="hui_formula_item"><xsl:call-template name="gui:text"/></div></td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:text">
	<tr><td>
		<label><xsl:value-of select="@label"/></label>
		<div class="hui_formula_item"><xsl:call-template name="gui:text"/></div>
	</td></tr>
</xsl:template>

<xsl:template name="gui:text" match="gui:textfield">
	<xsl:choose>
		<xsl:when test="@lines>1 or @multiline='true'">
			<div class="hui_field hui_longfield" id="{generate-id()}">
			
			<span class="hui_field_top"><span><span><xsl:comment/></span></span></span>
			<span class="hui_field_middle"><span class="hui_field_middle"><span class="hui_field_content">
				<span class="hui_formula_text_multiline">
				<textarea class="hui_formula_text" rows="{@lines}"><xsl:value-of select="@value"/><xsl:text></xsl:text></textarea>
				</span>
			</span></span></span>
			<span class="hui_field_bottom"><span><span><xsl:comment/></span></span></span>
			</div>
		</xsl:when>
		<xsl:otherwise>
			<div class="hui_field" id="{generate-id()}">
			<span class="hui_field_top"><span><span><xsl:comment/></span></span></span>
			<span class="hui_field_middle"><span class="hui_field_middle"><span class="hui_field_content">
				<span class="hui_field_singleline">
				<input class="hui_formula_text" value="{@value}"><xsl:if test="@secret='true'"><xsl:attribute name="type">password</xsl:attribute></xsl:if></input>
				</span>
			</span></span></span>
			<span class="hui_field_bottom"><span><span><xsl:comment/></span></span></span>
			</div>
		</xsl:otherwise>
	</xsl:choose>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.TextField({
			element : '<xsl:value-of select="generate-id()"/>',
			name : '<xsl:value-of select="@name"/>',
			key : '<xsl:value-of select="@key"/>'
			<xsl:if test="@animate-value-change='false'">
			,animateValueChange : <xsl:value-of select="@animate-value-change"/>
			</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!-- Date time -->

<xsl:template match="gui:group/gui:datetime">
	<tr>
		<th class="hui_formula_middle"><label><xsl:value-of select="@label"/></label></th>
		<td><div class="hui_formula_item"><xsl:call-template name="gui:datetime"/></div></td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:datetime">
	<tr><td>
		<label><xsl:value-of select="@label"/></label>
		<div class="hui_formula_item"><xsl:call-template name="gui:datetime"/></div>
	</td></tr>
</xsl:template>

<xsl:template name="gui:datetime">
	<div class="hui_field" id="{generate-id()}">
		<span class="hui_field_top"><span><span><xsl:comment/></span></span></span>
		<span class="hui_field_middle"><span class="hui_field_middle"><span class="hui_field_content">
			<span class="hui_field_singleline">
				<input type="text" class="hui_formula_text"/>
			</span>
		</span></span></span>
		<span class="hui_field_bottom"><span><span><xsl:comment/></span></span></span>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.DateTimeField({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>',
			key:'<xsl:value-of select="@key"/>',
			returnType:'<xsl:value-of select="@return-type"/>'
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!-- Number -->

<xsl:template match="gui:group/gui:number">
	<tr>
		<th class="hui_formula_middle"><label><xsl:value-of select="@label"/></label></th>
		<td class="hui_formula_group"><div class="hui_formula_item"><xsl:call-template name="gui:number"/></div></td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:number">
	<tr><td>
		<label><xsl:value-of select="@label"/></label>
		<div class="hui_formula_item"><xsl:call-template name="gui:number"/></div>
	</td></tr>
</xsl:template>

<xsl:template name="gui:number">
	<span id="{generate-id()}">
		<xsl:attribute name="class">
			<xsl:text>hui_numberfield</xsl:text>
			<xsl:if test="@adaptive='true'"><xsl:text> hui_numberfield_adaptive</xsl:text></xsl:if>
		</xsl:attribute>
		<span><span><input type="text" value="{@value}"/><em class="hui_numberfield_units"><xsl:comment/></em><a class="hui_numberfield_up"><xsl:comment/></a><a class="hui_numberfield_down"><xsl:comment/></a></span></span>
	</span>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.NumberField({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>',
			key:'<xsl:value-of select="@key"/>'
			<xsl:if test="@name">,name:'<xsl:value-of select="@name"/>'</xsl:if>
			<xsl:if test="@key">,key:'<xsl:value-of select="@key"/>'</xsl:if>
			<xsl:if test="@min">,min:<xsl:value-of select="@min"/></xsl:if>
			<xsl:if test="@max">,max:<xsl:value-of select="@max"/></xsl:if>
			<xsl:if test="@decimals">,decimals:<xsl:value-of select="@decimals"/></xsl:if>
			<xsl:if test="@allow-null">,allowNull:true</xsl:if>
			<xsl:if test="@value">,value:parseInt(<xsl:value-of select="@value"/>)</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!-- Style length -->

<xsl:template match="gui:group/gui:style-length">
	<tr>
		<th><label><xsl:value-of select="@label"/></label></th>
		<td class="hui_formula_group"><div class="hui_formula_item"><xsl:call-template name="gui:style-length"/></div></td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:style-length">
	<tr><td>
		<label><xsl:value-of select="@label"/></label>
		<div class="hui_formula_item"><xsl:call-template name="gui:style-length"/></div>
	</td></tr>
</xsl:template>

<xsl:template name="gui:style-length">
	<span class="hui_style_length hui_numberfield" id="{generate-id()}">
		<span><span><input type="text" value="{@value}"/><a class="hui_numberfield_up"><xsl:comment/></a><a class="hui_numberfield_down"><xsl:comment/></a></span></span>
	</span>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.StyleLength({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>',
			key:'<xsl:value-of select="@key"/>'
			<xsl:if test="@value">,value:'<xsl:value-of select="@value"/>'</xsl:if>
			<xsl:if test="@name">,name:'<xsl:value-of select="@name"/>'</xsl:if>
			<xsl:if test="@key">,key:'<xsl:value-of select="@key"/>'</xsl:if>
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!-- dropdown -->

<xsl:template match="gui:group/gui:dropdown">
	<tr>
		<th class="hui_formula_middle"><label><xsl:value-of select="@label"/></label></th>
		<td class="hui_formula_group"><div class="hui_formula_item"><xsl:call-template name="gui:dropdown"/></div></td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:dropdown">
	<tr><td>
		<label><xsl:value-of select="@label"/></label>
		<div class="hui_formula_item"><xsl:call-template name="gui:dropdown"/></div>
	</td></tr>
</xsl:template>

<xsl:template name="gui:dropdown" match="gui:dropdown">
	<a id="{generate-id()}" href="javascript://">
		<xsl:if test="@width">
			<xsl:attribute name="style">width:<xsl:value-of select="@width"/>px;</xsl:attribute>
		</xsl:if>
	<xsl:attribute name="class">
		<xsl:choose>
			<xsl:when test="@adaptive='true'">hui_dropdown hui_dropdown_adaptive</xsl:when>
			<xsl:otherwise>hui_dropdown</xsl:otherwise>
		</xsl:choose>
	</xsl:attribute>	
	<span><span><strong><xsl:comment/></strong></span></span>
	</a>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.DropDown(
			{element:'<xsl:value-of select="generate-id()"/>'
			,name:'<xsl:value-of select="@name"/>'
			,key:'<xsl:value-of select="@key"/>'
			,value:'<xsl:value-of select="@value"/>'
			<xsl:if test="@source">,source:<xsl:value-of select="@source"/></xsl:if>
			<xsl:if test="@url">,url:'<xsl:value-of select="@url"/>'</xsl:if>
			<xsl:if test="@placeholder">,placeholder:'<xsl:value-of select="@placeholder"/>'</xsl:if>
		});
		with(<xsl:value-of select="generate-id()"/>_obj) {
			<xsl:for-each select="gui:item">
				
				addItem({
					title:'<xsl:value-of select="@title"/><xsl:value-of select="@label"/>',
					value:hui.intOrString('<xsl:call-template name="gui:escapeScript"><xsl:with-param name="text" select="@value"/></xsl:call-template>')
				});
			</xsl:for-each>
		}
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!-- Radio buttons -->



<xsl:template match="gui:group/gui:radiobuttons">
	<tr>
		<th class="hui_formula_middle"><label><xsl:value-of select="@label"/></label></th>
		<td><div class="hui_formula_item"><xsl:call-template name="gui:radiobuttons"/></div></td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:radiobuttons">
	<tr><td>
		<label><xsl:value-of select="@label"/></label>
		<div class="hui_formula_item"><xsl:call-template name="gui:radiobuttons"/></div>
	</td></tr>
</xsl:template>

<xsl:template name="gui:radiobuttons">
	<div class="hui_radiobuttons" id="{generate-id()}">
		<xsl:apply-templates/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Radiobuttons({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>','value':'<xsl:value-of select="@value"/>','key':'<xsl:value-of select="@key"/>'});
		with (<xsl:value-of select="generate-id()"/>_obj) {
			<xsl:for-each select="gui:radiobutton | gui:item">
				registerRadiobutton({id:'<xsl:value-of select="generate-id()"/>','value':'<xsl:value-of select="@value"/>'});
			</xsl:for-each>
		}
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:radiobuttons/gui:radiobutton | gui:radiobuttons/gui:item">
	<a id="{generate-id()}">
		<xsl:attribute name="class">hui_radiobutton <xsl:if test="@value=../@value">hui_radiobutton_selected</xsl:if></xsl:attribute>
		<span><xsl:comment/></span><xsl:value-of select="@label"/><xsl:value-of select="@text"/>
	</a>
</xsl:template>

<!-- Checkbox -->

<xsl:template match="gui:group/gui:checkbox">
	<tr>
		<th class="hui_formula_middle"><label><xsl:value-of select="@label"/></label></th>
		<td><div class="hui_formula_item"><xsl:call-template name="gui:checkbox"/></div></td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:checkbox">
	<tr><td>
		<label style="float: left; line-height: 21px; padding-right: 5px; height: 24px;"><xsl:value-of select="@label"/></label>
		<xsl:call-template name="gui:checkbox"/>
	</td></tr>
</xsl:template>

<xsl:template name="gui:checkbox"  match="gui:checkbox">
	<a id="{generate-id()}" href="javascript://">
		<xsl:attribute name="class">
			<xsl:text>hui_checkbox</xsl:text>
			<xsl:if test="@value='true'"> hui_checkbox_selected</xsl:if>
		</xsl:attribute>
		<span><span><xsl:comment/></span></span>
		<xsl:value-of select="@title"/>
	</a>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Checkbox({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>',
			'key':'<xsl:value-of select="@key"/>',
			'value':'<xsl:value-of select="@value"/>'
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!-- Checkboxes -->

<xsl:template match="gui:group/gui:checkboxes">
	<tr>
		<th><label><xsl:value-of select="@label"/></label></th>
		<td><div class="hui_formula_item"><xsl:call-template name="gui:checkboxes"/></div></td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:checkboxes">
	<tr><td>
		<label><xsl:value-of select="@label"/></label>
		<div class="hui_formula_item"><xsl:call-template name="gui:checkboxes"/></div>
	</td></tr>
</xsl:template>

<xsl:template name="gui:checkboxes">
	<div class="hui_checkboxes" id="{generate-id()}">
		<xsl:if test="@max-height"><xsl:attribute name="style">max-height:<xsl:value-of select="@max-height"/>px; overflow: auto;</xsl:attribute></xsl:if>
		<xsl:apply-templates/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Checkboxes({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>',
			key:'<xsl:value-of select="@key"/>'
		});
		with (<xsl:value-of select="generate-id()"/>_obj) {
			<xsl:for-each select="gui:items">
				registerItems(<xsl:value-of select="generate-id()"/>_obj);
			</xsl:for-each>
			<xsl:for-each select="gui:item">
				registerItem({title:'<xsl:value-of select="@title"/>',value:hui.intOrString('<xsl:value-of select="@value"/>')});
			</xsl:for-each>
		}
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:checkboxes/gui:items">
	<div id="{generate-id()}">
		<xsl:comment/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Checkboxes.Items({element:'<xsl:value-of select="generate-id()"/>',name:'<xsl:value-of select="@name"/>',source:<xsl:value-of select="@source"/>});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:checkboxes/gui:item">
	<a class="hui_checkbox" href="javascript:void(0);">
		<span><span></span></span><xsl:value-of select="@title"/><xsl:value-of select="@text"/>
	</a>
</xsl:template>

<!-- Buttons -->

<xsl:template match="gui:formula/gui:buttons">
	<xsl:call-template name="gui:buttons"/>
</xsl:template>

<xsl:template match="gui:group/gui:buttons">
	<tr>
		<td colspan="2" style="border-spacing: 0px;">
			<xsl:call-template name="gui:buttons"/>
		</td>
	</tr>
</xsl:template>

<xsl:template match="gui:buttons" name="gui:buttons">
	<div>
		<xsl:attribute name="class">
			<xsl:text>hui_buttons</xsl:text>
			<xsl:if test="@align='right'">
				<xsl:text> hui_buttons_right</xsl:text>
			</xsl:if>
			<xsl:if test="@align='center'">
				<xsl:text> hui_buttons_center</xsl:text>
			</xsl:if>
		</xsl:attribute>
		<xsl:attribute name="style">
			<xsl:if test="@padding">padding:<xsl:value-of select="@padding"/>px;</xsl:if>
			<xsl:if test="@top">padding-top:<xsl:value-of select="@top"/>px;</xsl:if>
			<xsl:if test="@left">padding-left:<xsl:value-of select="@left"/>px;</xsl:if>
			<xsl:if test="@bottom">padding-bottom:<xsl:value-of select="@bottom"/>px;</xsl:if>
			<xsl:if test="@right">padding-right:<xsl:value-of select="@right"/>px;</xsl:if>
		</xsl:attribute>
		<div class="hui_buttons_body">
			<xsl:apply-templates/>
		</div>
	</div>
</xsl:template>

<xsl:template match="gui:button" name="gui:button">
	<a id="{generate-id()}" href="javascript://">
		<xsl:attribute name="class">
			hui_button
			<xsl:if test="@variant">
				<xsl:text>hui_button_</xsl:text><xsl:value-of select="@variant"/>
			</xsl:if>
			<xsl:if test="@disabled='true'"> hui_button_disabled</xsl:if>
			<xsl:choose>
				<xsl:when test="@variant and @small='true'">
					<xsl:text> hui_button_small_</xsl:text><xsl:value-of select="@variant"/>
				</xsl:when>
				<xsl:when test="@small='true' and @highlighted='true'">
					<xsl:text> hui_button_small hui_button_small_highlighted</xsl:text>
				</xsl:when>
				<xsl:when test="@small='true'">
					<xsl:text> hui_button_small</xsl:text>
				</xsl:when>
				<xsl:when test="@highlighted='true'">
					<xsl:text> hui_button_highlighted</xsl:text>
				</xsl:when>
			</xsl:choose>
		</xsl:attribute>
		<span><span>
			<xsl:if test="@icon"><em style="background-image: url('{$context}/hui/icons/{@icon}16.png')">
				<xsl:attribute name="class">
					<xsl:text>hui_button_icon</xsl:text>
					<xsl:if test="(not(@title) or @title='') and (not(@text) or @text='')"><xsl:text> hui_button_icon_notext</xsl:text></xsl:if>
				</xsl:attribute>
				<xsl:comment/>
			</em></xsl:if>
		<xsl:value-of select="@title"/><xsl:value-of select="@text"/>
	</span></span></a>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.Button({
			element:'<xsl:value-of select="generate-id()"/>'
			<xsl:if test="@name">,name:'<xsl:value-of select="@name"/>'</xsl:if>
			<xsl:if test="@submit='true'">,submit:true</xsl:if>
			<xsl:if test="gui:confirm">
				,confirm:{text:'<xsl:value-of select="gui:confirm/@text"/>',okText:'<xsl:value-of select="gui:confirm/@ok"/>',cancelText:'<xsl:value-of select="gui:confirm/@cancel"/>'}
			</xsl:if>
		});
		<xsl:if test="@click">
			<xsl:value-of select="generate-id()"/>_obj.listen({$click:function() {<xsl:value-of select="@click"/>}});
		</xsl:if>
		<xsl:if test="@url">
			<xsl:value-of select="generate-id()"/>_obj.listen({$click:function() {document.location='<xsl:value-of select="@url"/>'}});
		</xsl:if>
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<xsl:template match="gui:group/gui:button">
	<tr>
		<td colspan="2">
			<xsl:call-template name="gui:button"/>
		</td>
	</tr>
</xsl:template>


<!--                Image picker                -->

<xsl:template match="gui:group/gui:imagepicker">
	<tr>
		<th><label><xsl:value-of select="@label"/></label></th>
		<td>
			<xsl:call-template name="gui:imagepicker"/>
		</td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:imagepicker">
	<tr>
		<td>
			<label><xsl:value-of select="@label"/></label>
			<xsl:call-template name="gui:imagepicker"/>
		</td>
	</tr>
</xsl:template>

<xsl:template name="gui:imagepicker">
	<div class="hui_imagepicker" id="{generate-id()}" tabindex="0"><xsl:comment/></div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.ImagePicker({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>',
			source:'<xsl:value-of select="@source"/>'
		});
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>

<!--             Tokens            -->

<xsl:template match="gui:group/gui:tokens">
	<tr>
		<th><label><xsl:value-of select="@label"/></label></th>
		<td>
			<xsl:call-template name="gui:tokens"/>
		</td>
	</tr>
</xsl:template>

<xsl:template match="gui:group[@labels='above']/gui:tokens">
	<tr>
		<td>
			<label><xsl:value-of select="@label"/></label>
			<xsl:call-template name="gui:tokens"/>
		</td>
	</tr>
</xsl:template>

<xsl:template name="gui:tokens">
	<div class="hui_tokenfield" id="{generate-id()}">
		<xsl:comment/>
	</div>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.TokenField({
			element:'<xsl:value-of select="generate-id()"/>',
			name:'<xsl:value-of select="@name"/>',
			'key':'<xsl:value-of select="@key"/>'}
			);
		<xsl:call-template name="gui:createobject"/>
	</script>
</xsl:template>



<!--             Object List             -->




<xsl:template match="gui:group/gui:objectlist">
	<tr><td colspan="2">
		<xsl:call-template name="gui:objectlist"/>
	</td></tr>
</xsl:template>

<xsl:template match="gui:objectlist" name="gui:objectlist">
	<table cellspacing="0" cellpadding="0" id="{generate-id()}" class="hui_objectlist">
		<xsl:if test="gui:text/@label">
			<thead>
				<tr>
					<xsl:for-each select="gui:text | gui:select">
						<th class="hui_objectlist hui_objectlist{position()}" style="width: {100 div count(../*)}%;"><xsl:value-of select="@label"/></th>
					</xsl:for-each>
				</tr>
			</thead>
		</xsl:if>
		<tbody>
			<xsl:comment/>
		</tbody>
	</table>
	<script type="text/javascript">
		var <xsl:value-of select="generate-id()"/>_obj = new hui.ui.ObjectList({element:'<xsl:value-of select="generate-id()"/>'
			<xsl:if test="@name">,name:'<xsl:value-of select="@name"/>'</xsl:if>
			,key:'<xsl:value-of select="@key"/>'});
		<xsl:call-template name="gui:createobject"/>
		with (<xsl:value-of select="generate-id()"/>_obj) {
			<xsl:apply-templates select="gui:text | gui:select"/>
			ignite();
		}
	</script>
</xsl:template>

<xsl:template match="gui:objectlist/gui:text">
	registerTemplateItem(new hui.ui.ObjectList.Text('<xsl:value-of select="@key"/>'));
</xsl:template>

<xsl:template match="gui:objectlist/gui:select">
	<xsl:variable name="id" select="generate-id()"/>
	var <xsl:value-of select="$id"/> = new hui.ui.ObjectList.Select('<xsl:value-of select="@key"/>');
	<xsl:for-each select="gui:option">
		<xsl:value-of select="$id"/>.addOption('<xsl:value-of select="@value"/>','<xsl:value-of select="@label"/>');
	</xsl:for-each>
	registerTemplateItem(<xsl:value-of select="generate-id()"/>);
</xsl:template>

</xsl:stylesheet>