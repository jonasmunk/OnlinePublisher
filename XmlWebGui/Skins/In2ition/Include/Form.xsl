<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Form"
    version="1.0"
    exclude-result-prefixes="xwg"
    >

<xsl:template match="xwg:form">
<xsl:variable name="name">
<xsl:if test="@name"><xsl:value-of select="@name"/></xsl:if>
<xsl:if test="not(@name)"><xsl:value-of select="generate-id()"/></xsl:if>
</xsl:variable>
<script src="{$root}Scripts/Form.js" type="text/javascript" language="JavaScript"></script>
<form style="padding: 0; margin: 0;" class="form" name="{$name}" method="{@method}" target="{@target}" action="{@action}" enctype="{@enctype}" id="{generate-id()}">
<xsl:choose>
<xsl:when test="@onsubmit">
<xsl:attribute name="onsubmit"><xsl:value-of select="@onsubmit"/></xsl:attribute>
</xsl:when>
<xsl:when test="xwg:validation">
<xsl:attribute name="onsubmit">return validate<xsl:value-of select="generate-id()"/>();</xsl:attribute>
</xsl:when>
</xsl:choose>
<xsl:apply-templates/>
<xsl:if test="@submit='true'"><div style="overflow: hidden; height: 0px; padding: 0px; margin: 0px;">
<input type="submit" style="overflow: hidden; height: 0px; padding: 0px; margin: 0px; border: 0px; background-color: transparent;" value=""/></div>
</xsl:if></form>
<xsl:if test="@focus">
<script language="JavaScript">
if (document.<xsl:value-of select="$name"/>.<xsl:value-of select="@focus"/>) {
	document.<xsl:value-of select="$name"/>.<xsl:value-of select="@focus"/>.focus();
	document.<xsl:value-of select="$name"/>.<xsl:value-of select="@focus"/>.select();
}
</script>
</xsl:if>
<script type="text/javascript" language="JavaScript">
function validate<xsl:value-of select="generate-id()"/>() {
	<xsl:for-each select="descendant::xwg:richtext">
	<xsl:value-of select="generate-id()"/>.save();
	</xsl:for-each>
	<xsl:value-of select="xwg:validation/."/>
	<xsl:if test="not(xwg:validation)">return true;</xsl:if>
}
</script>
<xsl:if test="@object">
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new In2iGui.Form('<xsl:value-of select="generate-id()"/>');
</script>
</xsl:if>
</xsl:template>

<xsl:template match="xwg:validation"></xsl:template>


<xsl:template match="xwg:group">
<table border="0" width="100%">
<xsl:apply-templates/>
</table>
</xsl:template>


<xsl:template match="xwg:hidden">
<input type="hidden" name="{@name}" value="{.}"/>
</xsl:template>

<xsl:template match="xwg:script">
<script src="{$root}Scripts/Form.js" type="text/javascript" language="JavaScript"></script>
</xsl:template>


<xsl:template match="xwg:disclosure">
<xsl:variable name="image">
<xsl:if test="not(@expanded='true')">Collapsed.png</xsl:if>
<xsl:if test="@expanded='true'">Expanded.png</xsl:if>
</xsl:variable>
<tr><td colspan="2" id="{generate-id()}_toggler" style="padding-left: 10px; cursor: pointer;">
<img src="{$graphics}{$image}" style="width: 11px; height: 11px; position: relative; top: 1px;"/>
<span class="FormDisclosure"><xsl:value-of select="@label"/></span>
</td></tr>
<tbody id="{generate-id()}_body">
<xsl:if test="not(@expanded='true')">
<xsl:attribute name="style">display: none;</xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</tbody>
<script language="JavaScript" src="{$root}Scripts/FormDisclosure.js"></script>
<script language="JavaScript">
var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.FormDisclosure('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="$graphics"/>');
<xsl:if test="@object">var <xsl:value-of select="@object"/>=<xsl:value-of select="generate-id()"/>_obj;</xsl:if>
</script>
</xsl:template>



<!-- INPUT ELEMENTS -->


<xsl:template match="xwg:textfield">
<xsl:variable name="value"><xsl:value-of select="."/></xsl:variable>
<xsl:variable name="badgeplacement" select="ancestor-or-self::xwg:group/@badgeplacement"/>
<tr>
<xsl:call-template name="badge-left"/>
<td height="1%">
<xsl:call-template name="badge-above"/>
<xsl:if test="not(@lines) or @lines=1">
<input class="FormInput{ancestor-or-self::xwg:group/@size}" name="{@name}" value="{$value}" id="{generate-id()}">
<xsl:if test="@align"><xsl:attribute name="style">text-align: <xsl:value-of select="@align"/>;</xsl:attribute></xsl:if>
<xsl:if test="@maxlength"><xsl:attribute name="maxlength"><xsl:value-of select="@maxlength"/></xsl:attribute></xsl:if>
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
<xsl:if test="@empty='false'">
<xsl:attribute name="onchange">if (this.value=='') this.value='<xsl:value-of select="$value"/>';</xsl:attribute>
</xsl:if>
<xsl:call-template name="events"/>
</input>
</xsl:if>
<xsl:if test="@lines>1">
<textarea cols="" wrap="virtual" class="FormInput{ancestor-or-self::xwg:group/@size}" name="{@name}" rows="{@lines}" id="{generate-id()}">
<xsl:if test="@align"><xsl:attribute name="style">text-align: <xsl:value-of select="@align"/>;</xsl:attribute></xsl:if>
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
<xsl:if test="@empty='false'">
<xsl:attribute name="onchange">if (this.value=='') this.value='<xsl:value-of select="$value"/>';</xsl:attribute>
</xsl:if>
<xsl:call-template name="events"/>
<xsl:value-of select="$value"/>
</textarea>
</xsl:if>
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
<xsl:if test="@object">
<script src="{$root}Scripts/FormTextfield.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new XWGFormTextfield('<xsl:value-of select="generate-id()"/>');
</script>
</xsl:if>
</td>
</tr>
</xsl:template>



<xsl:template match="xwg:richtext">
<xsl:variable name="value"><xsl:value-of select="."/></xsl:variable>
<xsl:variable name="badgeplacement" select="ancestor-or-self::xwg:group/@badgeplacement"/>
<tr>
<xsl:call-template name="badge-left"/>
<td>
<xsl:call-template name="badge-above"/>
<div id="{generate-id()}-BUTTONS" style="font-size: 9px;">
<img src="{$graphics}RichText/Bold.gif" onclick="{generate-id()}.command('bold')" style="margin-right: 2px;"/>
<img src="{$graphics}RichText/Italic.gif" onclick="{generate-id()}.command('italic')" style="margin-right: 2px;"/>
<img src="{$graphics}RichText/Underline.gif" onclick="{generate-id()}.command('underline')"/>
<xsl:text> </xsl:text>
<span>
<img src="{$graphics}RichText/JustifyLeft.gif" onclick="{generate-id()}.command('justifyleft')"/>
<img src="{$graphics}RichText/JustifyCenter.gif" onclick="{generate-id()}.command('justifycenter')"/>
<img src="{$graphics}RichText/JustifyRight.gif" onclick="{generate-id()}.command('justifyright')"/>
<img src="{$graphics}RichText/JustifyFull.gif" onclick="{generate-id()}.command('justifyfull')"/>
</span>
<xsl:text> </xsl:text>
<span style="white-space: nowrap;">
<img src="{$graphics}RichText/Outdent.gif" onclick="{generate-id()}.command('outdent')"/>
<img src="{$graphics}RichText/Indent.gif" onclick="{generate-id()}.command('indent')"/>
</span>
<xsl:text> </xsl:text>
<span style="white-space: nowrap;">
<img src="{$graphics}RichText/Ordered.gif" onclick="{generate-id()}.command('insertorderedlist')"/>
<img src="{$graphics}RichText/Unordered.gif" onclick="{generate-id()}.command('insertunorderedlist')"/>
</span>
<xsl:text> </xsl:text>
<span style="white-space: nowrap;">
<img src="{$graphics}RichText/Heading1.gif" onclick="{generate-id()}.format('h1')"/>
<img src="{$graphics}RichText/Heading2.gif" onclick="{generate-id()}.format('h2')"/>
<img src="{$graphics}RichText/Heading3.gif" onclick="{generate-id()}.format('h3')"/>
<img src="{$graphics}RichText/Heading4.gif" onclick="{generate-id()}.format('h4')"/>
<img src="{$graphics}RichText/Heading5.gif" onclick="{generate-id()}.format('h5')"/>
<img src="{$graphics}RichText/Heading6.gif" onclick="{generate-id()}.format('h6')"/>
</span>
</div>
<iframe id="{generate-id()}-IFRAME" width="100%" height="200" src="{$root}Scripts/Empty.html" style="border: solid 1px #aaa; margin-top: 3px;" frameborder="0"></iframe>
<textarea id="{generate-id()}-VALUE" name="{@name}" style="display: none;" class="FormInput{ancestor-or-self::xwg:group/@size}" rows="8"><xsl:value-of select="$value"/></textarea>
<script language="JavaScript" src="{$root}Scripts/FormRichText.js"></script>
<script language="JavaScript">
var <xsl:value-of select="generate-id()"/> = new XWGFormRichText('<xsl:value-of select="generate-id()"/>');
<xsl:if test="@object">var <xsl:value-of select="@object"/>=<xsl:value-of select="generate-id()"/>;</xsl:if>
</script>
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
</td>
</tr>
</xsl:template>



<xsl:template match="xwg:password">
<tr>
<xsl:call-template name="badge-left"/>
<td>
<xsl:call-template name="badge-above"/>
<input type="password" class="FormInput{ancestor-or-self::xwg:group/@size}" name="{@name}" value="{.}" id="{generate-id()}">
<xsl:if test="@maxlength"><xsl:attribute name="maxlength"><xsl:value-of select="@maxlength"/></xsl:attribute></xsl:if>
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
<xsl:call-template name="events"/>
</input>
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
<xsl:if test="@object">
<script src="{$root}Scripts/FormPassword.js" type="text/javascript"></script>
<script type="text/javascript">
var <xsl:value-of select="@object"/> = new XWGFormPassword('<xsl:value-of select="generate-id()"/>');
</script>
</xsl:if>
</td>
</tr>
</xsl:template>



<xsl:template match="xwg:file">
<tr>
<xsl:call-template name="badge-left"/>
<td>
<xsl:call-template name="badge-above"/>
<input type="file" class="FormInput{ancestor-or-self::xwg:group/@size}" name="{@name}">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
</input>
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
</td>
</tr>
</xsl:template>


<xsl:template match="xwg:object">
<xsl:variable name="trans-choose">
<xsl:choose>
<xsl:when test="xwg:translation/@choose"><xsl:value-of select="xwg:translation/@choose"/></xsl:when>
<xsl:otherwise>Choose</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:variable name="trans-remove">
<xsl:choose>
<xsl:when test="xwg:translation/@remove"><xsl:value-of select="xwg:translation/@remove"/></xsl:when>
<xsl:otherwise>Remove</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:variable name="trans-none">
<xsl:choose>
<xsl:when test="xwg:translation/@none"><xsl:value-of select="xwg:translation/@none"/></xsl:when>
<xsl:otherwise>None</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<tr>
<xsl:call-template name="badge-left"/>
<td>
<xsl:call-template name="badge-above"/>
<div class="object" id="{generate-id()}-base">
<input type="hidden" name="{@name}" value="{xwg:entity/@value}"/>
<img src="{$iconset}{xwg:entity/@icon}Standard2.gif" id="{generate-id()}-icon">
<xsl:if test="not(xwg:entity/@icon)">
<xsl:attribute name="style">display:none;</xsl:attribute>
</xsl:if>
</img>
<div style="background-image: url('{xwg:entity/@image}')" class="image" id="{generate-id()}-image">
<xsl:if test="not(xwg:entity/@image)">
<xsl:attribute name="style">display:none;</xsl:attribute>
</xsl:if>
</div>
<div style="float: left; margin-right: 10px;">
	<div class="title"><xsl:value-of select="xwg:entity/@title"/><xsl:if test="not(xwg:entity)"><xsl:value-of select="$trans-none"/></xsl:if></div>
	<div class="description"><xsl:value-of select="xwg:entity/@description"/></div>
</div>
<a href="#" id="{generate-id()}-change" class="ButtonSmallStandard ButtonSmall"><xsl:value-of select="$trans-choose"/></a>
<div style="float: right;">
<div id="{generate-id()}-list" class="objectList"></div>
</div>
<xsl:if test="@empty!='false'">
<a href="#" id="{generate-id()}-remove" class="ButtonSmallStandard ButtonSmall">
<xsl:if test="not(xwg:entity)"><xsl:attribute name="style">display: none;</xsl:attribute></xsl:if>
<xsl:value-of select="$trans-remove"/>
</a>
</xsl:if>
<div class="clear"></div>
</div>
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
<script src="{$root}Scripts/In2iScripts/In2iWindow.js" type="text/javascript"></script>
<script src="{$root}Scripts/FormObject.js" type="text/javascript"></script>
<script type="text/javascript">
var <xsl:value-of select="generate-id()"/>_object = new In2iGui.Form.Object('<xsl:value-of select="generate-id()"/>');
<xsl:value-of select="generate-id()"/>_object.source.list = '<xsl:value-of select="xwg:source/@list"/>';
<xsl:value-of select="generate-id()"/>_object.translation.none = '<xsl:value-of select="$trans-none"/>';
<xsl:value-of select="generate-id()"/>_object.translation.choose = '<xsl:value-of select="$trans-choose"/>';
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="generate-id()"/>_object;
</xsl:if>
</script>
</td>
</tr>
</xsl:template>


<xsl:template match="xwg:checkbox">
<tr>
<xsl:call-template name="badge-left"/>
<td>
<xsl:call-template name="badge-above"/>
<input type="checkbox" name="{@name}" style="float: left;" id="{generate-id()}">
<xsl:call-template name="events"/>
<xsl:if test="@selected='true'"><xsl:attribute name="checked"></xsl:attribute></xsl:if>
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
<xsl:if test="@value"><xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute></xsl:if>
</input>
<div style="margin: 4px 0 0 0;">
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
<xsl:if test="@object">
<script src="{$root}Scripts/FormCheckbox.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new XWGFormCheckbox('<xsl:value-of select="generate-id()"/>');
</script>
</xsl:if>
</div>
</td>
</tr>
</xsl:template>



<xsl:template match="xwg:radiobutton">
<tr>
<xsl:call-template name="badge-left"/>
<td>
<xsl:call-template name="badge-above"/>
<input type="radio" name="{@name}" style="float: left;" id="{generate-id()}">
<xsl:call-template name="events"/>
<xsl:if test="@selected='true'"><xsl:attribute name="checked"></xsl:attribute></xsl:if>
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
<xsl:if test="@value"><xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute></xsl:if>
</input>
<div style="margin: 4px 0 0 0;">
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
<xsl:if test="@object">
<script src="{$root}Scripts/FormRadiobutton.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new XWGFormRadiobutton('<xsl:value-of select="generate-id()"/>');
</script>
</xsl:if>
</div>
</td>
</tr>
</xsl:template>



<xsl:template match="xwg:select">
<tr>
<xsl:call-template name="badge-left"/>
<td>
<xsl:call-template name="badge-above"/>
<select class="FormInput{ancestor-or-self::xwg:group/@size}" name="{@name}" id="{generate-id()}">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
<xsl:if test="@lines>1">
<xsl:if test="@multiple='true'"><xsl:attribute name="multiple">true</xsl:attribute></xsl:if>
<xsl:attribute name="size"><xsl:value-of select="@lines"/></xsl:attribute>
</xsl:if>
<xsl:call-template name="events"/>
<xsl:apply-templates select="xwg:option"/>
</select>
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
<xsl:if test="@object">
<script src="{$root}Scripts/FormSelect.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new XWGFormSelect('<xsl:value-of select="generate-id()"/>');
</script>
</xsl:if>
</td>
</tr>
</xsl:template>

<xsl:template match="xwg:select/xwg:option">
<option value="{@value}">
<xsl:if test="@selected='true' or (../@selected=@value)"><xsl:attribute name="selected">true</xsl:attribute></xsl:if>
<xsl:value-of select="@title"/>
</option>
</xsl:template>


<!-- combo -->





<xsl:template match="xwg:combo">
<tr>
<xsl:call-template name="badge-left"/>
<td>
<xsl:call-template name="badge-above"/>
<table cellspacing="0" cellpadding="0"><tr><td width="1%">
<select class="FormInput{ancestor-or-self::xwg:group/@size}" id="{generate-id()}_select" name="{@name}" style="width: auto;">
<xsl:apply-templates select="xwg:option"/>
</select>
</td>
<td width="99%" style="padding-left: 5px;">
<xsl:apply-templates select="xwg:option/*"/>
</td>
</tr></table>
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
<script src="{$root}Scripts/FormCombo.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Form.Combo('<xsl:value-of select="generate-id()"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="generate-id()"/>_obj;
</xsl:if>
</script>
</td>
</tr>
</xsl:template>

<xsl:template match="xwg:combo/xwg:option">
<option value="{@value}">
<xsl:if test="@selected='true' or (../@selected=@value)"><xsl:attribute name="selected">true</xsl:attribute></xsl:if>
<xsl:value-of select="@title"/>
</option>
</xsl:template>

<xsl:template match="xwg:combo/xwg:option/xwg:textfield">
<xsl:variable name="number"><xsl:value-of select="generate-id(../..)"/>_<xsl:value-of select="count(parent::*/preceding-sibling::*)"/></xsl:variable>
<div id="{$number}" style="display: none;">
<input class="FormInput{ancestor-or-self::xwg:group/@size}" name="{@name}" value="{.}"/>
</div>
</xsl:template>

<xsl:template match="xwg:combo/xwg:option/xwg:select">
<xsl:variable name="number"><xsl:value-of select="generate-id(../..)"/>_<xsl:value-of select="count(parent::*/preceding-sibling::*)"/></xsl:variable>
<div id="{$number}" style="display: none;">
<select class="FormInput{ancestor-or-self::xwg:group/@size}" name="{@name}">
<xsl:apply-templates/>
</select>
</div>
</xsl:template>



<xsl:template match="xwg:radiogroup">
<tr>
<xsl:call-template name="badge-left"/>
<td>
<xsl:call-template name="badge-above"/>
<table border="0" cellpadding="0" cellspacing="0">
<xsl:if test="@direction='horizontal'">
  <tr><xsl:apply-templates/></tr>
</xsl:if>
<xsl:if test="@direction='vertical'">
  <xsl:apply-templates/>
</xsl:if>
</table>
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
</td></tr>
</xsl:template>


<xsl:template match="xwg:radiogroup/xwg:radiobutton">
<xsl:if test="../@direction='horizontal'">
<td><table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<input type="radio" name="{../@name}" value="{@value}">
<xsl:if test="@selected='true'"><xsl:attribute name="checked">true</xsl:attribute></xsl:if>
<xsl:if test="../@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
<xsl:call-template name="events"/>
</input>
</td>
<td>
<xsl:call-template name="badge"/>
</td>
</tr>
</table></td>
</xsl:if>
<xsl:if test="../@direction='vertical'">
<tr><td>
<input type="radio" name="{../@name}" value="{@value}">
<xsl:if test="@selected='true'"><xsl:attribute name="checked">true</xsl:attribute></xsl:if>
<xsl:if test="../@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
<xsl:call-template name="events"/>
</input>
<xsl:call-template name="badge"/>
</td></tr>
</xsl:if>
</xsl:template>

<xsl:template match="xwg:text">
<tr>
<xsl:call-template name="badge-left"/>
<td>
<xsl:call-template name="badge-above"/>
<span class="FormText{ancestor-or-self::xwg:group/@size}"><xsl:value-of select="@text"/></span>
</td>
</tr>
</xsl:template>






<!-- SPECIAL INPUT -->


<xsl:template match="xwg:date">
<xsl:variable name="id">
<xsl:value-of select="generate-id()"/>
</xsl:variable>
<xsl:variable name="min">
<xsl:if test="@min"><xsl:value-of select="@min"/></xsl:if>
<xsl:if test="not(@min)">19000101</xsl:if>
</xsl:variable>
<xsl:variable name="max">
<xsl:if test="@max"><xsl:value-of select="@max"/></xsl:if>
<xsl:if test="not(@max)">21001231</xsl:if>
</xsl:variable>
<xsl:variable name="value">
<xsl:if test="@value!=''"><xsl:value-of select="@value"/></xsl:if>
<xsl:if test="@value='' or not(@value)"><xsl:value-of select="$min"/></xsl:if>
</xsl:variable>
<tr>
<xsl:call-template name="badge-left"/>
<td nowrap="nowrap">
<xsl:call-template name="badge-above"/>
<xsl:if test="not(@display='dmy')">
<select class="FormInput{ancestor-or-self::xwg:group/@size}" id="month{$id}" style="width: 60px;" onchange="{$id}_obj.validate();{@onchange}">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onblur"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
<xsl:call-template name="xwg:optionmaker">
<xsl:with-param name="start" select="1"/>
<xsl:with-param name="stop" select="12"/>
<xsl:with-param name="selected" select="number(substring(@value,5,2))"/>
</xsl:call-template>
</select>
<xsl:text> / </xsl:text>
</xsl:if>
<select class="FormInput{ancestor-or-self::xwg:group/@size}" id="day{$id}" style="width: 60px;" onchange="{$id}_obj.validate();{@onchange}">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onblur"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
<xsl:call-template name="xwg:optionmaker">
<xsl:with-param name="start" select="1"/>
<xsl:with-param name="stop" select="31"/>
<xsl:with-param name="selected" select="number(substring(@value,7,2))"/>
</xsl:call-template>
</select>
<xsl:if test="@display='dmy'">
<xsl:text> / </xsl:text>
<select class="FormInput{ancestor-or-self::xwg:group/@size}" id="month{$id}" style="width: 60px;" onchange="{$id}_obj.validate();{@onchange}">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onblur"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
<xsl:call-template name="xwg:optionmaker">
<xsl:with-param name="start" select="1"/>
<xsl:with-param name="stop" select="12"/>
<xsl:with-param name="selected" select="number(substring(@value,5,2))"/>
</xsl:call-template>
</select>
</xsl:if>
<xsl:text> / </xsl:text>
<select class="FormInput{ancestor-or-self::xwg:group/@size}" id="year{$id}" style="width: 60px;" onchange="{$id}_obj.validate();{@onchange}">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onblur"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
<xsl:call-template name="xwg:optionmaker">
<xsl:with-param name="start" select="substring($min,1,4)"/>
<xsl:with-param name="stop" select="substring($max,1,4)"/>
<xsl:with-param name="selected" select="number(substring(@value,1,4))"/>
</xsl:call-template>
</select>
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
<script language="JavaScript" src="{$root}Scripts/FormDate.js"></script>
<script language="JavaScript">
var <xsl:value-of select="$id"/>_obj = new XWGFormDate('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="$min"/>','<xsl:value-of select="$max"/>');
<xsl:if test="@object">var <xsl:value-of select="@object"/>=<xsl:value-of select="$id"/>_obj;</xsl:if>
</script>
<input type="hidden" name="{@name}" id="value{$id}" value="{$value}"/>
</td>
</tr>
</xsl:template>



<xsl:template name="xwg:optionmaker">
<xsl:param name="start"/>
<xsl:param name="stop"/>
<xsl:param name="selected"/>
<xsl:if test="$start &lt; $stop+1">
<option value="{$start}">
<xsl:if test="$start=$selected">
<xsl:attribute name="selected">selected</xsl:attribute>
</xsl:if>
<xsl:value-of select="$start"/>
</option>
<xsl:call-template name="xwg:optionmaker">
<xsl:with-param name="start" select="$start + 1"/>
<xsl:with-param name="stop" select="$stop"/>
<xsl:with-param name="selected" select="$selected"/>
</xsl:call-template>
</xsl:if>
</xsl:template>




<xsl:template match="xwg:datetime">
<xsl:variable name="id">
<xsl:value-of select="generate-id()"/>
</xsl:variable>
<xsl:variable name="min">
<xsl:if test="@min"><xsl:value-of select="@min"/></xsl:if>
<xsl:if test="not(@min)">19000101000000</xsl:if>
</xsl:variable>
<xsl:variable name="max">
<xsl:if test="@max"><xsl:value-of select="@max"/></xsl:if>
<xsl:if test="not(@max)">21001231000000</xsl:if>
</xsl:variable>
<xsl:variable name="value">
<xsl:if test="@value!=''"><xsl:value-of select="@value"/></xsl:if>
<xsl:if test="@value='' or not(@value)"><xsl:value-of select="$min"/></xsl:if>
</xsl:variable>
<tr>
<xsl:call-template name="badge-left"/>
<td nowrap="nowrap">
<xsl:call-template name="badge-above"/>
<span style="font-size: 10pt; color: #555;">
<xsl:if test="not(@display='dmy')">
<input class="FormInput{ancestor-or-self::xwg:group/@size}" id="month{$id}" style="width: 24px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,5,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
<xsl:text> / </xsl:text>
</xsl:if>
<input class="FormInput{ancestor-or-self::xwg:group/@size}" id="day{$id}" style="width: 24px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,7,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
<xsl:if test="@display='dmy'">
<xsl:text> / </xsl:text>
<input class="FormInput{ancestor-or-self::xwg:group/@size}" id="month{$id}" style="width: 24px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,5,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
</xsl:if>
<xsl:text> / </xsl:text>
<input class="FormInput{ancestor-or-self::xwg:group/@size}" id="year{$id}" style="width: 40px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,1,4)}" maxlength="4">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
<xsl:text> &#160; </xsl:text>
<input class="FormInput{ancestor-or-self::xwg:group/@size}" id="hour{$id}" style="width: 24px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,9,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
<xsl:text> : </xsl:text>
<input class="FormInput{ancestor-or-self::xwg:group/@size}" id="minute{$id}" style="width: 24px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,11,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
<xsl:text> : </xsl:text>
<input class="FormInput{ancestor-or-self::xwg:group/@size}" id="second{$id}" style="width: 24px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,13,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
</span>
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
<script language="JavaScript" src="{$root}Scripts/FormDateTime.js"></script>
<script language="JavaScript">
var <xsl:value-of select="$id"/>_obj = new XWGFormDateTime('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="$min"/>','<xsl:value-of select="$max"/>');
<xsl:if test="@object">var <xsl:value-of select="@object"/>=<xsl:value-of select="$id"/>_obj;</xsl:if>
</script>
<input type="hidden" name="{@name}" id="value{$id}" value="{$value}"/>
</td>
</tr>
</xsl:template>


<xsl:template match="xwg:number">
<xsl:variable name="id">
<xsl:value-of select="generate-id()"/>
</xsl:variable>
<xsl:variable name="delimiter">
<xsl:choose>
<xsl:when test="@delimiter"><xsl:value-of select="@delimiter"/></xsl:when>
<xsl:otherwise>.</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:variable name="value">
<xsl:choose>
<xsl:when test="@value"><xsl:value-of select="@value"/></xsl:when>
<xsl:when test="not(@empty='true') and @min&gt;0"><xsl:value-of select="@min"/></xsl:when>
<xsl:when test="not(@empty='true') and @max&lt;0"><xsl:value-of select="@max"/></xsl:when>
<xsl:when test="not(@empty='true')">0</xsl:when>
</xsl:choose>
</xsl:variable>
<xsl:variable name="badgeplacement" select="ancestor-or-self::xwg:group/@badgeplacement"/>
<tr>
<xsl:call-template name="badge-left"/>
<td>
<xsl:call-template name="badge-above"/>
<input class="FormInput{ancestor-or-self::xwg:group/@size}" id="{$id}" value="{translate($value,'.',$delimiter)}" onblur="{$id}_obj.validate()">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:if test="@align"><xsl:attribute name="style">text-align: <xsl:value-of select="@align"/>;</xsl:attribute></xsl:if>
</input>
<input type="hidden" id="{$id}-HIDDEN" name="{@name}" value="{$value}"/>
<script language="JavaScript" src="{$root}Scripts/FormNumber.js"></script>
<script language="JavaScript">
var <xsl:value-of select="$id"/>_obj = new XWGFormNumber('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@delimiter"/>','<xsl:value-of select="@decimals"/>','<xsl:value-of select="@min"/>','<xsl:value-of select="@max"/>','<xsl:value-of select="@empty"/>');
<xsl:if test="@object">var <xsl:value-of select="@object"/>=<xsl:value-of select="$id"/>_obj;</xsl:if>
</script>
<xsl:call-template name="hint"/>
<xsl:call-template name="error"/>
</td>
</tr>
</xsl:template>




<!-- LAYOUT -->

<xsl:template match="xwg:divider">
<tr><td height="5" colspan="2"><hr class="FormDivider"/></td></tr>
</xsl:template>

<xsl:template match="xwg:space">
<tr><td height="5"></td></tr>
</xsl:template>

<xsl:template match="xwg:indent">
<tr>
<td nowrap="on" valign="top">
<xsl:apply-templates select="xwg:radio"/>
<xsl:apply-templates select="xwg:check"/>
<xsl:call-template name="badge"/>
</td>
<td style="padding-top: 3px;">
<table border="0" width="100%">
<xsl:apply-templates select="child::node()[not(name()='check') and not(name()='radio')]"/>
</table>
</td>
</tr>
</xsl:template>

<xsl:template match="xwg:box">
<tr><td colspan="2" style="padding-left: 3px; padding-right: 3px; padding-bottom: 4px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td width="1"><img width="5" height="2" src="{$graphics}Transparent.gif"/></td>
<td rowspan="2" width="1%" nowrap="true" class="FormBoxBadge{ancestor-or-self::xwg:group/@size}"><xsl:value-of select="@title"/></td>
<td width="100%"><img width="1" height="2" src="{$graphics}Transparent.gif"/></td></tr>
<tr><td width="1" style="border-style: solid; border-width: 0px; border-left-width: 1px; border-top-width: 1px; border-color: #999999;"><img width="5" height="1" src="{$graphics}Transparent.gif"/></td>
<td width="100%" style="border-style: solid; border-width: 0px; border-right-width: 1px; border-top-width: 1px; border-color: #999999;"><img width="1" height="1" src="{$graphics}Transparent.gif"/></td>
</tr>
<tr>
<td colspan="3" style="padding: 5px; padding-top: 0px; border-style: solid; border-width: 0px; border-left-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-color: #999999;">
<table width="100%" border="0">
<xsl:apply-templates/>
</table>
</td></tr></table></td></tr>
</xsl:template>






<!-- BUTTONS -->


<xsl:template match="xwg:buttongroup">
<xsl:variable name="badgeplacement" select="ancestor-or-self::xwg:group/@badgeplacement"/>
<tr><td>
<xsl:if test="$badgeplacement!='above' or not($badgeplacement)">
<xsl:attribute name="colspan">2</xsl:attribute>
</xsl:if>
<xsl:attribute name="align">
<xsl:if test="@align"><xsl:value-of select="@align"/></xsl:if>
<xsl:if test="not(@align)">right</xsl:if>
</xsl:attribute>
<table border="0" cellpadding="0" cellspacing="0"><tr><xsl:apply-templates/></tr></table>
</td></tr>
</xsl:template>

<xsl:template match="xwg:button[not(../@size) or ../@size='Large']">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td nowrap="nowrap">
<a class="ButtonLarge{$style} ButtonLarge" id="{generate-id()}">
<xsl:if test="not(@submit) or @submit!='true'">
<xsl:call-template name="link"/>
</xsl:if>
<xsl:if test="@submit='true'">
<xsl:variable name="formid">
<xsl:value-of select="generate-id(ancestor::*[name()='form'])"/>
</xsl:variable>
<xsl:variable name="formname">
<xsl:if test="ancestor::*[name()='form']/@name">
<xsl:value-of select="ancestor::*[name()='form']/@name"/>
</xsl:if>
<xsl:if test="not(ancestor::*[name()='form']/@name)">
<xsl:value-of select="generate-id(ancestor::*[name()='form'])"/>
</xsl:if>
</xsl:variable>
<xsl:attribute name="href">javascript: if (validate<xsl:value-of select="$formid"/>()) {document.forms.<xsl:value-of select="$formname"/>.submit();}</xsl:attribute>
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute>
</xsl:if>
<xsl:value-of select="@title"/>
</a>
</td>
<xsl:if test="not(position()=last())"><td style="padding-left: 4px;"></td></xsl:if>
<!--<xsl:if test="@submit='true'">
<script>
var glower = new Glower('<xsl:value-of select="generate-id()"/>');
glower.start();
</script>
</xsl:if>-->
</xsl:template>

<xsl:template match="xwg:button[../@size='Small']">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td nowrap="nowrap" background="{$graphics}ButtonSmall{$style}BG.gif">
<a class="ButtonSmall{$style} ButtonSmall">
<xsl:if test="not(@submit) or @submit!='true'">
<xsl:call-template name="link"/>
</xsl:if>
<xsl:if test="@submit='true'">
<xsl:variable name="formid">
<xsl:value-of select="generate-id(ancestor::*[name()='form'])"/>
</xsl:variable>
<xsl:variable name="formname">
<xsl:if test="ancestor::*[name()='form']/@name">
<xsl:value-of select="ancestor::*[name()='form']/@name"/>
</xsl:if>
<xsl:if test="not(ancestor::*[name()='form']/@name)">
<xsl:value-of select="generate-id(ancestor::*[name()='form'])"/>
</xsl:if>
</xsl:variable>
<xsl:attribute name="href">javascript: if (validate<xsl:value-of select="$formid"/>()) {document.forms.<xsl:value-of select="$formname"/>.submit();}</xsl:attribute>
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute>
</xsl:if>
<xsl:value-of select="@title"/>
</a>
</td>
<xsl:if test="not(position()=last())"><td width="2"></td></xsl:if>
</xsl:template>





<!-- SUPPORT TEMPLATES -->


<xsl:template name="badge-left">
<xsl:variable name="badgeplacement" select="ancestor-or-self::xwg:group/@badgeplacement"/>
<xsl:if test="$badgeplacement!='above' or not($badgeplacement)">
<td valign="top" nowrap="on" align="right" style="padding-right: 10px; padding-top: 3px;">
<xsl:call-template name="badge-width"/>
<xsl:apply-templates select="xwg:radio"/>
<xsl:apply-templates select="xwg:check"/>
<xsl:call-template name="badge"/>
</td>
</xsl:if>
</xsl:template>

<xsl:template name="badge-above">
<xsl:variable name="badgeplacement" select="ancestor-or-self::xwg:group/@badgeplacement"/>
<xsl:if test="$badgeplacement='above'"><xsl:call-template name="badge"/></xsl:if>
</xsl:template>

<xsl:template name="badge-width">
<xsl:attribute name="width"><xsl:choose>
<xsl:when test="ancestor::xwg:group/@badgewidth and not(name(parent::*)='indent')"><xsl:value-of select="ancestor::xwg:group/@badgewidth"/></xsl:when>
<xsl:otherwise>10%</xsl:otherwise>
</xsl:choose></xsl:attribute>
</xsl:template>


<xsl:template name="badge">
<a class="FormBadge{ancestor-or-self::xwg:group/@size}">
<xsl:if test="@help">
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute>
<xsl:attribute name="style">cursor: help;</xsl:attribute>
</xsl:if>
<xsl:value-of select="@badge"/>
</a><br/>
</xsl:template>

<xsl:template match="xwg:radio">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<input type="radio" name="{@name}" id="{$id}" style="margin: 0px 3px 0px 0px; position: relative; bottom: -2px;">
<xsl:if test="@object">
<xsl:attribute name="onclick"><xsl:value-of select="@object"/>.changed();</xsl:attribute>
</xsl:if>
<xsl:if test="@value"><xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute></xsl:if>
<xsl:if test="@selected='true'"><xsl:attribute name="checked"></xsl:attribute></xsl:if>
</input>
<xsl:if test="@object">
<script src="{$root}Scripts/FormRadio.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var others = '<xsl:for-each select="/descendant::*[name()='radio' and @object and generate-id()!=$id]">
<xsl:if test="position()&gt;1">,</xsl:if>
<xsl:value-of select="@object"/>
</xsl:for-each>';
var <xsl:value-of select="@object"/> = new XWGFormRadio('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@autoenable"/>',others);
</script>
</xsl:if>
</xsl:template>

<xsl:template match="xwg:check">
<input type="checkbox" name="{@name}" id="{generate-id()}">
<xsl:if test="@object">
<xsl:attribute name="onclick"><xsl:value-of select="@object"/>.changed();</xsl:attribute>
</xsl:if>
<xsl:if test="@value"><xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute></xsl:if>
<xsl:if test="@selected='true'"><xsl:attribute name="checked"></xsl:attribute></xsl:if>
</input>
<xsl:if test="@object">
<script src="{$root}Scripts/FormCheck.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new XWGFormCheck('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@autoenable"/>');
</script>
</xsl:if>
</xsl:template>



<xsl:template name="hint">
<div class="FormHint" id="{generate-id()}-HINT"><xsl:value-of select="@hint"/></div>
</xsl:template>

<xsl:template name="error">
<div class="FormError" id="{generate-id()}-ERROR"><xsl:value-of select="@error"/></div>
</xsl:template>

</xsl:stylesheet>