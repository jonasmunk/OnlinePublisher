<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:BarForm"
    version="1.0"
    exclude-result-prefixes="xwg"
>

<xsl:template match="xwg:form">
<xsl:variable name="name">
<xsl:if test="@name"><xsl:value-of select="@name"/></xsl:if>
<xsl:if test="not(@name)"><xsl:value-of select="generate-id()"/></xsl:if>
</xsl:variable>
<script src="{$root}Scripts/Form.js" type="text/javascript" language="JavaScript"></script>
<form style="padding: 0; margin: 0;" name="{$name}" method="{@method}" target="{@target}" action="{@action}" enctype="{@enctype}">
<xsl:choose>
<xsl:when test="@onsubmit">
<xsl:attribute name="onsubmit"><xsl:value-of select="@onsubmit"/></xsl:attribute>
</xsl:when>
<xsl:when test="xwg:validation">
<xsl:attribute name="onsubmit">return validate<xsl:value-of select="generate-id()"/>();</xsl:attribute>
</xsl:when>
</xsl:choose>
<xsl:apply-templates/>
<xsl:if test="@submit='true'"><div style="overflow: hidden; height: 1px; padding: 0px; margin: 0px;">
<input type="submit" style="overflow: hidden; height: 0px; padding: 0px; margin: 0px; border: 0px; background-color: transparent;" value=""/></div>
</xsl:if></form>
<xsl:if test="@focus">
<script language="JavaScript">document.<xsl:value-of select="$name"/>.<xsl:value-of select="@focus"/>.focus();document.<xsl:value-of select="$name"/>.<xsl:value-of select="@focus"/>.select();</script>
</xsl:if>
<script type="text/javascript" language="JavaScript">
function validate<xsl:value-of select="generate-id()"/>() {
	<xsl:value-of select="xwg:validation/."/>
	<xsl:if test="not(xwg:validation)">return true;</xsl:if>
}
</script>
</xsl:template>

<xsl:template match="xwg:validation"></xsl:template>

<xsl:template match="xwg:group">
<td>
<table border="0" cellpadding="0" cellspacing="0" height="44">
<tr style="height: 30px;"><xsl:apply-templates select="xwg:top/*"/></tr>
<tr><xsl:apply-templates select="xwg:bottom/*"/></tr>
</table>
</td>
</xsl:template>

<xsl:template match="xwg:pool">
<td>
<table border="0" cellpadding="0" cellspacing="0">
<tr><xsl:apply-templates/></tr>
</table>
</td>
</xsl:template>

<xsl:template match="xwg:space">
<td>
</td>
</xsl:template>

<xsl:template match="xwg:hidden">
<input type="hidden" name="{@name}" value="{.}"/>
</xsl:template>

<xsl:template match="xwg:badge">
<td align="right" class="BarForm" nowrap="nowrap">
<a class="BarFormBadge">
<xsl:if test="@help">
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute>
<xsl:attribute name="style">cursor: help;</xsl:attribute>
</xsl:if>
<xsl:value-of select="."/>
</a>
</td>
</xsl:template>

<xsl:template match="xwg:textfield">
<xsl:variable name="width">
<xsl:choose>
<xsl:when test="@width"><xsl:value-of select="@width"/></xsl:when>
<xsl:otherwise>100</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:variable name="value"><xsl:value-of select="."/></xsl:variable>
<td nowrap="nowrap" align="left" class="BarForm">
<xsl:if test="not(@multiline='true')">
<input class="BarForm" name="{@name}" value="{$value}" id="{generate-id()}" style="width: {$width}px;">
<xsl:if test="@align"><xsl:attribute name="style">text-align: <xsl:value-of select="@align"/>;</xsl:attribute></xsl:if>
<xsl:if test="@maxlength"><xsl:attribute name="maxlength"><xsl:value-of select="@maxlength"/></xsl:attribute></xsl:if>
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
<xsl:if test="@empty='false'">
<xsl:attribute name="onchange">if (this.value=='') this.value='<xsl:value-of select="$value"/>';</xsl:attribute>
</xsl:if>
<xsl:call-template name="events"/>
</input>
</xsl:if>
<xsl:if test="@multiline='true'">
<xsl:attribute name="rowspan">2</xsl:attribute>
<textarea cols="" wrap="virtual" class="BarForm" name="{@name}" id="{generate-id()}" style="width: {$width}px;">
<xsl:if test="@align"><xsl:attribute name="style">text-align: <xsl:value-of select="@align"/>;</xsl:attribute></xsl:if>
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
<xsl:if test="@empty='false'">
<xsl:attribute name="onchange">if (this.value=='') this.value='<xsl:value-of select="$value"/>';</xsl:attribute>
</xsl:if>
<xsl:call-template name="events"/>
<xsl:value-of select="$value"/>
</textarea>
</xsl:if>
<xsl:if test="@object">
<script src="{$root}Scripts/BarFormTextfield.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new In2iGui.BarFormTextfield('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@object"/>');
</script>
</xsl:if>
</td>
</xsl:template>



<xsl:template match="xwg:checkbox">
<td class="BarForm">
<input type="checkbox" name="{@name}" id="{generate-id()}">
<xsl:call-template name="events"/>
<xsl:if test="@selected='true'"><xsl:attribute name="checked">checked</xsl:attribute></xsl:if>
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:if test="@value"><xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute></xsl:if>
</input>
<xsl:if test="@object">
<script src="{$root}Scripts/BarFormCheckbox.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new In2iGui.BarFormCheckbox('<xsl:value-of select="generate-id()"/>');
</script>
</xsl:if>
</td>
</xsl:template>


<xsl:template match="xwg:radiobutton">
<td class="BarForm" style="padding: 0px;">
<input type="radio" name="{@name}" style="float: left;" id="{generate-id()}">
<xsl:call-template name="events"/>
<xsl:if test="@selected='true'"><xsl:attribute name="checked">checked</xsl:attribute></xsl:if>
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:if test="@value"><xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute></xsl:if>
</input>
<xsl:if test="@object">
<script src="{$root}Scripts/BarFormRadiobutton.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new In2iGui.BarFormRadiobutton('<xsl:value-of select="generate-id()"/>');
</script>
</xsl:if>
</td>
</xsl:template>

<xsl:template match="xwg:select">
<td class="BarForm">
<xsl:if test="@multiline='true'">
<xsl:attribute name="rowspan">2</xsl:attribute>
</xsl:if>
<select class="BarForm" name="{@name}" id="{generate-id()}">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">true</xsl:attribute></xsl:if>
<xsl:if test="@multiline='true'">
<xsl:attribute name="style">height: 52px; position: relative; top: 3px; font-size: 9px;</xsl:attribute>
<xsl:if test="@multiple='true'"><xsl:attribute name="multiple">true</xsl:attribute></xsl:if>
<xsl:attribute name="size">3</xsl:attribute>
</xsl:if>
<xsl:call-template name="events"/>
<xsl:apply-templates select="xwg:option"/>
</select>
<xsl:if test="@object">
<script src="{$root}Scripts/BarFormSelect.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new In2iGui.BarFormSelect('<xsl:value-of select="generate-id()"/>');
</script>
</xsl:if>
</td>
</xsl:template>

<xsl:template match="xwg:option">
<option value="{@value}">
<xsl:if test="@selected='true' or (../@selected=@value)"><xsl:attribute name="selected">true</xsl:attribute></xsl:if>
<xsl:value-of select="@title"/>
</option>
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
<td nowrap="nowrap" class="BarForm">
<span style="font-size: 10pt; color: #555;">
<xsl:if test="not(@display='dmy')">
<input class="BarForm" id="month{$id}" style="width: 20px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,5,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
<xsl:text>/</xsl:text>
</xsl:if>
<input class="BarForm" id="day{$id}" style="width: 20px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,7,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
<xsl:if test="@display='dmy'">
<xsl:text>/</xsl:text>
<input class="BarForm" id="month{$id}" style="width: 20px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring(@value,5,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
</xsl:if>
<xsl:text>/</xsl:text>
<input class="BarForm" id="year{$id}" style="width: 32px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,1,4)}" maxlength="4">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
<xsl:text>&#160;</xsl:text>
<input class="BarForm" id="hour{$id}" style="width: 20px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,9,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
<xsl:text>:</xsl:text>
<input class="BarForm" id="minute{$id}" style="width: 20px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,11,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
<xsl:text>:</xsl:text>
<input class="BarForm" id="second{$id}" style="width: 20px; padding-left: 3px;" onblur="{$id}_obj.validate();{@onblur}" value="{substring($value,13,2)}" maxlength="2">
<xsl:if test="@disabled='true'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
<xsl:call-template name="onfocus"/>
<xsl:call-template name="onchange"/>
<xsl:call-template name="onmouseover"/>
<xsl:call-template name="onmouseout"/>
</input>
</span>
<script language="JavaScript" src="{$root}Scripts/BarFormDateTime.js"></script>
<script language="JavaScript">
var <xsl:value-of select="$id"/>_obj = new In2iGui.BarFormDateTime('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="$min"/>','<xsl:value-of select="$max"/>');
<xsl:if test="@object">var <xsl:value-of select="@object"/>=<xsl:value-of select="$id"/>_obj;</xsl:if>
</script>
<input type="hidden" name="{@name}" id="value{$id}" value="{$value}"/>
</td>
</xsl:template>





<xsl:template match="xwg:combo">
<td class="BarForm">
<table cellspacing="0" cellpadding="0"><tr><td width="1%">
<select class="BarForm" id="{generate-id()}_select" name="{@name}" style="width: auto;">
<xsl:apply-templates select="xwg:option"/>
</select>
</td>
<td width="99%" style="padding-left: 5px;">
<xsl:apply-templates select="xwg:option/*"/>
</td>
</tr></table>
<script src="{$root}Scripts/FormCombo.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="generate-id()"/>_obj = new In2iGui.Form.Combo('<xsl:value-of select="generate-id()"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="generate-id()"/>_obj;
</xsl:if>
</script>
</td>
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
<input class="BarForm" style="width: 160px;" name="{@name}" value="{.}"/>
</div>
</xsl:template>

<xsl:template match="xwg:combo/xwg:option/xwg:select">
<xsl:variable name="number"><xsl:value-of select="generate-id(../..)"/>_<xsl:value-of select="count(parent::*/preceding-sibling::*)"/></xsl:variable>
<div id="{$number}" style="display: none;">
<select class="BarForm" style="width: 160px;" name="{@name}">
<xsl:apply-templates/>
</select>
</div>
</xsl:template>







<xsl:template match="xwg:button">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td class="BarForm">
<table border="0" cellpadding="0" cellspacing="0"><tr>
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
</a></td>
</tr></table></td>
</xsl:template>

</xsl:stylesheet>