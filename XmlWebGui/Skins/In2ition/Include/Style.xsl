<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:s="uri:Style"
    xmlns:scp="uri:Script"
    version="1.0"
    exclude-result-prefixes="s scp"
    >

<xsl:template match="s:text-align">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<img border="0" width="17" height="15" src="{$graphics}StyleTextAlignLeft.gif" id="{$id}-left" onclick="{$id}obj.switchValue('left')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleTextAlignCenter.gif" id="{$id}-center" onclick="{$id}obj.switchValue('center')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleTextAlignRight.gif" id="{$id}-right" onclick="{$id}obj.switchValue('right')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleTextAlignJustify.gif" id="{$id}-justify" onclick="{$id}obj.switchValue('justify')"/>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleTextAlign.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleTextAlign('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="$graphics"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="s:align">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<img border="0" width="17" height="15" src="{$graphics}StyleAlignLeft.gif" id="{$id}-left" onclick="{$id}obj.switchValue('left')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleAlignCenter.gif" id="{$id}-center" onclick="{$id}obj.switchValue('center')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleAlignRight.gif" id="{$id}-right" onclick="{$id}obj.switchValue('right')"/>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleAlign.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleAlign('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="$graphics"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="s:size">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<table cellspacing="0" cellpadding="0" border="0"><tr>
<td><img border="0" width="9" height="9" src="{$graphics}StyleRemove.gif" onclick="{$id}obj.clear()"/></td>
<td nowrap="nowrap" style="padding-left: 3px;">
<input id="{$id}_input" onblur="{$id}obj.valueChanged();" style="border-width:0px; padding: 0px; font: 10px Verdana; text-align: right; width: 30px; margin-right: 3px;"/>
</td><td>
<div style="cursor: pointer; width: 18px; font: 9px Verdana;" id="{$id}_unit" onclick="{$id}obj.switchUnit();"></div>
</td>
<td>
<img border="0" width="11" height="9" src="{$graphics}StyleArrowUp.gif" onclick="{$id}obj.up()" style="margin-bottom: 1px;"/><br/>
<img border="0" width="11" height="9" src="{$graphics}StyleArrowDown.gif" onclick="{$id}obj.down()"/>
</td>
</tr>
</table>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleSize.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleSize('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="$graphics"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="s:font-family">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<table cellspacing="0" cellpadding="0" border="0"><tr>
<td><img border="0" width="9" height="9" src="{$graphics}StyleRemove.gif" onclick="{$id}obj.clear()"/></td>
<td nowrap="nowrap" width="110" style="font: 10px Verdana; text-align: left; padding-left: 3px; padding-right: 2px;">
<span id="{$id}_display"></span>
</td>
<td>
<img border="0" width="11" height="9" src="{$graphics}StyleArrowUp.gif" onclick="{$id}obj.previous()" style="margin-bottom: 1px;"/><br/>
<img border="0" width="11" height="9" src="{$graphics}StyleArrowDown.gif" onclick="{$id}obj.next()"/>
</td>
</tr>
</table>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleFontFamily.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var fontValues = '<xsl:for-each select="s:font">
<xsl:if test="position()>1">;</xsl:if><xsl:value-of select="@value"/>
</xsl:for-each>';
var fontTitles = '<xsl:for-each select="s:font">
<xsl:if test="position()>1">;</xsl:if><xsl:value-of select="@title"/>
</xsl:for-each>';
var <xsl:value-of select="$id"/>obj = new XWGStyleFontFamily('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>',fontTitles,fontValues,'<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="s:color">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<table cellspacing="0" cellpadding="0" border="0"><tr>
<td><img border="0" width="9" height="9" src="{$graphics}StyleRemove.gif" onclick="{$id}obj.clear()"/></td>
<td nowrap="nowrap" width="30" style="padding-left: 3px;">
<div id="{$id}_display" onclick="{$id}obj.openPicker()" style="cursor: pointer;">
<img border="0" width="24" height="15" src="{$graphics}Transparent.gif"/>
</div>
</td>
</tr>
</table>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleColor.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleColor('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="$root"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="s:font-weight">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<img border="0" width="17" height="15" src="{$graphics}StyleWeightLighter.gif" id="{$id}-lighter" onclick="{$id}obj.switchValue('lighter')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleWeightNormal.gif" id="{$id}-normal" onclick="{$id}obj.switchValue('normal')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleWeightBold.gif" id="{$id}-bold" onclick="{$id}obj.switchValue('bold')"/>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleFontWeight.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleFontWeight('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="$graphics"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="s:font-style">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<img border="0" width="17" height="15" src="{$graphics}StyleFontStyleNormal.gif" id="{$id}-normal" onclick="{$id}obj.switchValue('normal')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleFontStyleOblique.gif" id="{$id}-oblique" onclick="{$id}obj.switchValue('oblique')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleFontStyleItalic.gif" id="{$id}-italic" onclick="{$id}obj.switchValue('italic')"/>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleFontStyle.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleFontStyle('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="$graphics"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="s:text-transform">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<img border="0" width="17" height="15" src="{$graphics}StyleTextTransformNone.gif" id="{$id}-none" onclick="{$id}obj.switchValue('none')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleTextTransformCapitalize.gif" id="{$id}-capitalize" onclick="{$id}obj.switchValue('capitalize')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleTextTransformUppercase.gif" id="{$id}-uppercase" onclick="{$id}obj.switchValue('uppercase')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleTextTransformLowercase.gif" id="{$id}-lowercase" onclick="{$id}obj.switchValue('lowercase')"/>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleTextTransform.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleTextTransform('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="$graphics"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="s:font-variant">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<img border="0" width="17" height="15" src="{$graphics}StyleFontVariantNormal.gif" id="{$id}-normal" onclick="{$id}obj.switchValue('normal')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleFontVariantSmallCaps.gif" id="{$id}-small-caps" onclick="{$id}obj.switchValue('small-caps')"/>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleFontVariant.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleFontVariant('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="$graphics"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="s:text-decoration">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<img border="0" width="17" height="15" src="{$graphics}StyleTextDecorationNone.gif" id="{$id}-none" onclick="{$id}obj.switchValue('none')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleTextDecorationUnderline.gif" id="{$id}-underline" onclick="{$id}obj.switchValue('underline')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleTextDecorationLineThrough.gif" id="{$id}-line-through" onclick="{$id}obj.switchValue('line-through')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleTextDecorationOverline.gif" id="{$id}-overline" onclick="{$id}obj.switchValue('overline')"/>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleTextDecoration.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleTextDecoration('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="$graphics"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>


<xsl:template match="s:number">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<table cellspacing="0" cellpadding="0" border="0"><tr>
<xsl:if test="@empty='true'">
<td><img border="0" width="9" height="9" src="{$graphics}StyleRemove.gif" onclick="{$id}obj.clear()"/></td>
</xsl:if>
<td nowrap="nowrap" style="padding-left: 3px;">
<input id="{$id}_input" onblur="{$id}obj.valueChanged();" style="border-width:0px; padding: 0px; font: 10px Verdana; text-align: right; width: 30px; margin-right: 3px;"/>
</td>
<td>
<img border="0" width="11" height="9" src="{$graphics}StyleArrowUp.gif" onclick="{$id}obj.up()" style="margin-bottom: 1px;"/><br/>
<img border="0" width="11" height="9" src="{$graphics}StyleArrowDown.gif" onclick="{$id}obj.down()"/>
</td>
</tr>
</table>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleNumber.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleNumber('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="@min"/>','<xsl:value-of select="@max"/>','<xsl:value-of select="@empty"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="s:list-type">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<img border="0" width="17" height="15" src="{$graphics}StyleListTypeDisc.gif" id="{$id}-disc" onclick="{$id}obj.switchValue('disc')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleListTypeSquare.gif" id="{$id}-square" onclick="{$id}obj.switchValue('square')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleListTypeCircle.gif" id="{$id}-circle" onclick="{$id}obj.switchValue('circle')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleListType1.gif" id="{$id}-1" onclick="{$id}obj.switchValue('1')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleListTypea.gif" id="{$id}-a" onclick="{$id}obj.switchValue('a')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleListTypeAA.gif" id="{$id}-AA" onclick="{$id}obj.switchValue('A')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleListTypei.gif" id="{$id}-i" onclick="{$id}obj.switchValue('i')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleListTypeII.gif" id="{$id}-II" onclick="{$id}obj.switchValue('I')"/>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleListType.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleListType('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="$graphics"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="s:float">
<xsl:variable name="id"><xsl:value-of select="generate-id()"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxLeft.gif"/>
</td>
<td background="{$graphics}StyleBoxBG.gif" nowrap="nowrap">
<img border="0" width="17" height="15" src="{$graphics}StyleFloatLeft.gif" id="{$id}-left" onclick="{$id}obj.switchValue('left')"/>
<img border="0" width="17" height="15" src="{$graphics}StyleFloatRight.gif" id="{$id}-right" onclick="{$id}obj.switchValue('right')"/>
</td>
<td>
<img border="0" width="5" height="23" src="{$graphics}StyleBoxRight.gif"/>
</td>
</tr>
</table>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
<script src="{$root}Scripts/StyleFloat.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="$id"/>obj = new XWGStyleFloat('<xsl:value-of select="$id"/>','<xsl:value-of select="@value"/>','<xsl:value-of select="$graphics"/>','<xsl:value-of select="@onchange"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = <xsl:value-of select="$id"/>obj;
</xsl:if>
</script>
</xsl:template>

</xsl:stylesheet>