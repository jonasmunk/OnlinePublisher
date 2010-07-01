<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:DockForm"
    version="1.0"
    exclude-result-prefixes="xwg"
    >


<xsl:template match="xwg:form">
<td valign="top" width="1%">
<table border="0" cellspacing="0" cellpadding="0" height="54" width="1"><form>
<xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute>
<xsl:attribute name="method"><xsl:value-of select="@method"/></xsl:attribute>
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
<xsl:attribute name="action"><xsl:value-of select="@action"/></xsl:attribute>
<xsl:attribute name="enctype"><xsl:value-of select="@enctype"/></xsl:attribute>
<xsl:apply-templates/>
</form>
</table>
</td>
<xsl:if test="@focus">
<script language="JavaScript">
document.forms[0].<xsl:value-of select="@focus"/>.focus();
</script>
</xsl:if>
</xsl:template>

<xsl:template match="xwg:row">
<tr><xsl:apply-templates/></tr>
</xsl:template>

<xsl:template match="xwg:cell">
<td><xsl:attribute name="rowspan"><xsl:value-of select="@rowspan"/></xsl:attribute><xsl:attribute name="colspan"><xsl:value-of select="@colspan"/></xsl:attribute><xsl:apply-templates/></td>
</xsl:template>

<xsl:template match="xwg:divider">
<td valign="top" rowspan="2" style="padding-left: 3px; padding-right: 3px; padding-top: 1px;">
<img width="1" height="49"><xsl:attribute name="src"><xsl:value-of select="//configuration/@path"/>MacOSX/Graphics/DockDivider.gif</xsl:attribute></img>
</td>
</xsl:template>

<xsl:template match="xwg:hidden">
<input type="hidden"><xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute><xsl:attribute name="value"><xsl:value-of select="."/></xsl:attribute></input>
</xsl:template>

<xsl:template match="xwg:checkbox">
<table border="0" cellspacing="2" cellpadding="0" width="1%"><tr>
<td>
<input type="checkbox" name="{@name}">
<xsl:call-template name="xwg:formevents"/>
<xsl:if test="@id"><xsl:attribute name="id"><xsl:value-of select="@id"/></xsl:attribute></xsl:if>
<xsl:if test="@selected='true'"><xsl:attribute name="checked"></xsl:attribute></xsl:if>
</input>
</td>
<td width="1%" nowrap="on"><span class="DockFormBadge"><xsl:value-of select="@badge"/></span>
</td>
</tr></table>
</xsl:template>

<xsl:template match="xwg:color">
<xsl:variable name="color">
<xsl:if test="@value!=''"><xsl:value-of select="@value"/></xsl:if>
<xsl:if test="@value=''"><xsl:text>transparent</xsl:text></xsl:if>
</xsl:variable>
<table border="0" cellpadding="0" cellspacing="0" style="margin: 0 2px 0 2px;">
<tr>
<td width="1%" nowrap="on" style="padding-right: 3px;">
<span class="DockFormBadge"><xsl:value-of select="@badge"/></span></td>
<td style="border: 1px solid #666; background-color: {$color};">
<xsl:attribute name="id">ColorBox<xsl:value-of select="@name"/></xsl:attribute>
<img border="0" width="24" height="16" style="cursor: pointer; cursor: hand;" src="{$graphics}Transparent.gif" onmousedown="ShiftColor{@name}();">
</img>
</td>
<td><input type="hidden" id="{@name}" name="{@name}" value="{@value}"/></td>
</tr>
</table>
<script language="JavaScript">
function ShiftColor<xsl:value-of select="@name"/>(e) {
	var width=300;
	var height=330;
	if (navigator.userAgent.indexOf('Gecko')!=-1) {
		width+=6;
		height+=6;
	}
    winColor = window.open('<xsl:value-of select="$skin"/>Color.htm?id=<xsl:value-of select="@name"/>','winColor<xsl:value-of select="@name"/>','status=no,left='+100+',top='+100+',toolbar=no,location=no,scrollbars=no,width='+width+',height='+height);
    winColor.focus;
    window.blur;
}
function ReturnColor<xsl:value-of select="@name"/>(color) {
    <xsl:value-of select="@onchange"/>
}
function HoverColor<xsl:value-of select="@name"/>(color) {
    <xsl:value-of select="@onhover"/>
}
</script>
</xsl:template>

<xsl:template match="xwg:select">
<table border="0" cellspacing="2" cellpadding="0"><tr>
<xsl:if test="@lines=1 or not(@lines)">
<td width="1%" nowrap="on">
<xsl:apply-templates select="xwg:radio"/>
<span class="DockFormBadge"><xsl:value-of select="@badge"/></span>
</td>
<td>
<select class="dockForm" name="{@name}">
<xsl:if test="@id"><xsl:attribute name="id"><xsl:value-of select="@id"/></xsl:attribute></xsl:if>
<xsl:call-template name="xwg:formevents"/>
<xsl:if test="@width"><xsl:attribute name="style">width: <xsl:value-of select="@width"/>px;</xsl:attribute></xsl:if>
<xsl:apply-templates select="xwg:option"/>
</select>
</td>
</xsl:if>
<xsl:if test="@lines>1">
<td valign="top" width="1%" nowrap="on">
<xsl:apply-templates select="xwg:radio"/>
<div class="DockFormBadge"><xsl:value-of select="@badge"/></div>
</td>
<td>
<select class="dockForm" name="{@name}" size="{@lines}">
<xsl:if test="@id"><xsl:attribute name="id"><xsl:value-of select="@id"/></xsl:attribute></xsl:if>
<xsl:call-template name="xwg:formevents"/>
<xsl:if test="@width"><xsl:attribute name="style">width: <xsl:value-of select="@width"/>px;</xsl:attribute></xsl:if>
<xsl:if test="@multiple='true'"><xsl:attribute name="multiple">on</xsl:attribute></xsl:if>
<xsl:apply-templates select="xwg:option"/>
</select></td>
</xsl:if>
</tr></table>
</xsl:template>

<xsl:template match="xwg:option">
<option value="{@value}">
<xsl:if test="@selected='true' or (../@selected=@value)"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
<xsl:value-of select="@title"/>
</option>
</xsl:template>

<xsl:template match="xwg:textfield">
<table border="0" cellspacing="2" cellpadding="0"><tr>
<xsl:if test="not(../@rowspan='2')">
<td width="1%" nowrap="on">
<xsl:apply-templates select="xwg:radio"/>
<span class="DockFormBadge"><xsl:value-of select="@badge"/></span>
</td>
<td>
<input class="dockForm" name="{@name}" value="{.}">
<xsl:call-template name="xwg:formevents"/>
<xsl:if test="@id"><xsl:attribute name="id"><xsl:value-of select="@id"/></xsl:attribute></xsl:if>
<xsl:if test="@width"><xsl:attribute name="style">width: <xsl:value-of select="@width"/>px;</xsl:attribute></xsl:if>
<xsl:if test="@max"><xsl:attribute name="maxlength"><xsl:value-of select="@max"/></xsl:attribute></xsl:if>
</input>
</td>
</xsl:if>
<xsl:if test="../@rowspan='2'">
<td valign="top" width="1%" nowrap="on">
<xsl:apply-templates select="xwg:radio"/>
<span class="DockFormBadge"><xsl:value-of select="@badge"/></span></td><td>
<textarea class="dockForm" name="{@name}">
<xsl:call-template name="xwg:formevents"/>
<xsl:if test="@id"><xsl:attribute name="id"><xsl:value-of select="@id"/></xsl:attribute></xsl:if>
<xsl:attribute name="style">height: 45px; <xsl:if test="@width">width: <xsl:value-of select="@width"/>px;</xsl:if></xsl:attribute>
<xsl:value-of select="."/>
</textarea></td>
</xsl:if>
</tr></table>
</xsl:template>

<xsl:template match="xwg:radio">
<input type="radio" name="{@name}" value="{@value}">
<xsl:if test="@id"><xsl:attribute name="id"><xsl:value-of select="@id"/></xsl:attribute></xsl:if>
<xsl:call-template name="xwg:formevents"/>
<xsl:if test="@selected='true'"><xsl:attribute name="checked">checked</xsl:attribute></xsl:if>
</input>
</xsl:template>



<xsl:template match="xwg:buttongroup">
<table border="0" cellpadding="0" cellspacing="0"><tr><xsl:apply-templates/></tr></table>
</xsl:template>

<xsl:template match="xwg:buttongroup/xwg:button">
<td>
<xsl:call-template name="xwg:button"/>
</td>
<xsl:if test="not(position()=last())"><td style="padding-left: 2px;"></td></xsl:if>
</xsl:template>

<xsl:template match="xwg:button" name="xwg:button">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<table border="0" cellpadding="0" cellspacing="0"><tr>
<td><img src="{$graphics}ButtonSmall{$style}Left.gif" width="5" height="18"/></td>
<td nowrap="nowrap" background="{$graphics}ButtonSmall{$style}BG.gif">
<a class="Button{$style} ButtonSmall">
<xsl:if test="not(@submit) or @submit!='true'">
<xsl:call-template name="link"/>
</xsl:if>
<xsl:if test="@submit='true'">
<xsl:attribute name="href">javascript: document.forms.<xsl:value-of select="ancestor::*[name()='form']/@name"/>.submit();</xsl:attribute>
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute>
</xsl:if>
<xsl:value-of select="@title"/>
</a></td>
<td><img src="{$graphics}ButtonSmall{$style}Right.gif" width="5" height="18"/></td>
</tr></table>
</xsl:template>

<xsl:template name="xwg:formevents">
<xsl:if test="@onchange"><xsl:attribute name="onchange"><xsl:value-of select="@onchange"/></xsl:attribute></xsl:if>
<xsl:if test="@onclick"><xsl:attribute name="onclick"><xsl:value-of select="@onclick"/></xsl:attribute></xsl:if>
<xsl:if test="@onfocus"><xsl:attribute name="onfocus"><xsl:value-of select="@onfocus"/></xsl:attribute></xsl:if>
</xsl:template>

</xsl:stylesheet>