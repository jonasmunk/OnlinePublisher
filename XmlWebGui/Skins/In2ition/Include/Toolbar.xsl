<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:t="uri:Toolbar"
    xmlns:menu="uri:Menu"
    xmlns:area="uri:Area"
    version="1.0"
    exclude-result-prefixes="t menu area"
    >

<xsl:template match="t:toolbar">
<xsl:param name="height"/>
<tr>
<td height="50" align="{@align}">
<xsl:attribute name="class">
<xsl:if test="name(parent::*)='window'">ToolbarWindow</xsl:if>
<xsl:if test="name(parent::*)='area'">ToolbarArea</xsl:if>
</xsl:attribute>
<table cellpadding="0" cellspacing="0" border="0" height="50">
<xsl:if test="t:flexible"><xsl:attribute name="width">100%</xsl:attribute></xsl:if>
<tr>
<xsl:apply-templates>
<xsl:with-param name="height"><xsl:value-of select="$height"/></xsl:with-param>
</xsl:apply-templates>
</tr></table>
</td></tr>
</xsl:template>


<xsl:template match="t:tool">
<xsl:param name="height"/>
<xsl:apply-templates/>
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td valign="bottom">
<xsl:attribute name="class">
<xsl:choose>
<xsl:when test="@selected='true'">ToolbarToolBGSelected</xsl:when>
<xsl:otherwise>ToolbarToolBG</xsl:otherwise>
</xsl:choose>
</xsl:attribute>
<table cellpadding="0" cellspacing="0" border="0" height="1" id="{@drop}">
<tr>
<td align="center" valign="bottom" id="{generate-id()}_menu_anchor">
<xsl:if test="@badge">
<xsl:variable name="badgestyle">
<xsl:if test="@badgestyle"><xsl:value-of select="@badgestyle"/></xsl:if>
<xsl:if test="not(@badgestyle)"><xsl:value-of select="$style"/></xsl:if>
</xsl:variable>
<a>
<xsl:attribute name="style">top: <xsl:value-of select="$height+23"/>px;
<xsl:if test="@badgehelp">cursor: help;</xsl:if>
<xsl:if test="not(@badgehelp) and not(@link)">cursor: default;</xsl:if>
</xsl:attribute>
<xsl:if test="@link">
	<xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
	<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:if>
<xsl:attribute name="class">IconBadge IconBadge<xsl:value-of select="$badgestyle"/></xsl:attribute>
<xsl:attribute name="title"><xsl:value-of select="@badgehelp"/></xsl:attribute>
<xsl:value-of select="@badge"/>
</a></xsl:if>

<xsl:if test="not(@overlay)">
<a>
<xsl:if test="@link">
<xsl:call-template name="link"/>
</xsl:if>
<xsl:if test="not(@link)">
<xsl:call-template name="t:menu"/>
</xsl:if>
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute>
<img border="0" width="32" height="32" id="{generate-id()}-icon">
<xsl:attribute name="src"><xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>2.gif</xsl:attribute>
<xsl:if test="$style='Standard' and (@link or @menu or menu:menu)">
<xsl:attribute name="onmouseout">this.src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>2.gif';</xsl:attribute>
<xsl:attribute name="onmouseover">this.src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited2.gif';</xsl:attribute>
</xsl:if>
</img>
</a>
</xsl:if>

<xsl:if test="@overlay">
<table border="0" cellpadding="0" cellspacing="0" width="32" height="32" style="display: inline;">
<tr><td id="{generate-id()}" background="{$iconset}{@icon}{$style}2.gif">
<a title="{@help}">
<xsl:if test="@link">
<xsl:call-template name="link"/>
</xsl:if>
<xsl:if test="not(@link)">
<xsl:call-template name="t:menu"/>
</xsl:if>
<img border="0" width="32" height="32" src="{$iconset}Overlay/{@overlay}{$style}2.gif">
<xsl:if test="$style='Standard' and (@link or @menu or menu:menu)">
<xsl:attribute name="onmouseout">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>2.gif\')';</xsl:attribute>
<xsl:attribute name="onmouseover">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited2.gif\')';</xsl:attribute>
</xsl:if>
</img>
</a>
</td></tr></table>
</xsl:if>

</td></tr><tr><td height="1" valign="top" nowrap="on">
<a class="Icon{$style} Toolbar">
<xsl:if test="$style='Standard' and (@link or @menu or menu:menu) and @overlay">
<xsl:attribute name="onmouseout">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>2.gif\')';</xsl:attribute>
<xsl:attribute name="onmouseover">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited2.gif\')';</xsl:attribute>
</xsl:if>
<xsl:if test="$style='Standard' and (@link or @menu or menu:menu) and not(@overlay)">
<xsl:attribute name="onmouseout">document.getElementById('<xsl:value-of select="generate-id()"/>-icon').src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>2.gif';</xsl:attribute>
<xsl:attribute name="onmouseover">document.getElementById('<xsl:value-of select="generate-id()"/>-icon').src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited2.gif';</xsl:attribute>
</xsl:if>
<xsl:if test="@link">
<xsl:call-template name="link"/>
</xsl:if>
<xsl:if test="not(@link)">
<xsl:call-template name="t:menu"/>
</xsl:if>
<xsl:value-of select="@title"/></a>
</td></tr></table></td>
<xsl:if test="@drop">
<script language="javascript">registerDropArea("<xsl:value-of select="@drop"/>");</script>
</xsl:if>
<xsl:if test="menu:menu">
<script language="javascript">
In2iMenuAttacher.attachAsClickMenu('<xsl:value-of select="generate-id()"/>_menu_anchor',<xsl:value-of select="generate-id(menu:menu)"/>);
</script>
</xsl:if>
</xsl:template>


<xsl:template match="t:direction">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td valign="bottom" style="padding-left: 5px; padding-right: 5px;">
<table cellpadding="0" cellspacing="0" border="0"><tr>
<td align="center" style="padding-bottom: 3px;">
<a>
<xsl:if test="@link">
<xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:if>
<img src="{$graphics}ButtonLarge{@direction}{$style}.gif" width="24" height="22" border="0">
<xsl:if test="$style='Standard' and @link">
<xsl:attribute name="onmouseover">this.src='<xsl:value-of select="$graphics"/>ButtonLarge<xsl:value-of select="@direction"/>Hilited.gif';</xsl:attribute>
<xsl:attribute name="onmouseout">this.src='<xsl:value-of select="$graphics"/>ButtonLarge<xsl:value-of select="@direction"/>Standard.gif';</xsl:attribute>
</xsl:if>
</img>
</a>
</td></tr>
<tr><td height="1" valign="top" nowrap="on" align="center">
<a class="Icon{$style} Toolbar">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/></a>
</td></tr></table></td>
</xsl:template>

<xsl:template match="t:select">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td valign="bottom" style="padding-left: 5px; padding-right: 5px;">
<table cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="padding-bottom: 5px;">
<select class="ToolbarSelect" name="{@name}">
<xsl:if test="@onchange">
<xsl:attribute name="onchange"><xsl:value-of select="@onchange"/></xsl:attribute>
</xsl:if>
<xsl:apply-templates/>
</select>
</td></tr>
<tr><td height="1" valign="top" nowrap="on" align="center">
<a class="Icon{$style} Toolbar">
<xsl:if test="not(@link)"><xsl:attribute name="style">cursor: default;</xsl:attribute></xsl:if>
<xsl:if test="@link">
<xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:if>
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute><xsl:value-of select="@title"/></a>
</td></tr></table></td>
</xsl:template>

<xsl:template match="t:option">
<option><xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute>
<xsl:if test="@selected='true' or ../@selected=@value"><xsl:attribute name="selected">true</xsl:attribute></xsl:if>
<xsl:value-of select="@title"/></option>
</xsl:template>

<xsl:template match="t:divider">
<td style="padding: 2px 5px 2px 5px;">
<div class="Divider"/>
</td>
</xsl:template>

<xsl:template match="t:flexible">
<td width="100%"></td>
</xsl:template>

<xsl:template match="t:space">
<td width="10"></td>
</xsl:template>

<xsl:template match="t:searchfield">
<td valign="bottom" style="padding-left: 5px; padding-right: 5px;"><table cellpadding="0" cellspacing="0" border="0">
<tr><td align="center" style="padding-bottom: 4px;"><table cellspacing="0" cellpadding="0" border="0">
<xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute>
<form name="{generate-id()}" method="{@method}" target="{@target}" action="{@action}">
<tr>
<td width="4" valign="top"><img border="0" width="4" height="24" src="{$graphics}SearchfieldLeft.gif"/></td>
<td style="padding-top: 5px; padding-right: 3px; padding-left: 1px; overflow: hidden; height: 24px;" background="{$graphics}SearchfieldBG.gif" valign="top">
<input type="text" class="Searchfield" name="{@name}" value="{@value}"></input>
</td>
<td width="20" valign="top"><input type="image" border="0" width="20" height="24" title="{@help}" src="{$graphics}SearchfieldRight.gif"/></td>
</tr></form>
</table>
</td></tr>
<tr><td height="1" valign="top" nowrap="on" align="center">
<a style="cursor: default;" class="IconStandard Toolbar"><xsl:value-of select="@title"/></a>
</td></tr></table>
<xsl:if test="@focus and @name">
<script language="JavaScript">
document.<xsl:value-of select="generate-id()"/>.<xsl:value-of select="@name"/>.focus();
document.<xsl:value-of select="generate-id()"/>.<xsl:value-of select="@name"/>.select();
</script>
</xsl:if>
</td>
</xsl:template>

<xsl:template match="t:viewgroup">
<td valign="bottom" style="padding-left: 5px; padding-right: 5px;">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td class="ViewGroup" nowrap="true">
<xsl:apply-templates/>
</td>
</tr>
<tr><td height="1" valign="top" nowrap="on" align="center">
<a style="cursor: default;" class="IconStandard Toolbar"><xsl:value-of select="@title"/></a>
</td></tr></table></td>
</xsl:template>

<xsl:template match="t:view">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="../@value=@type">Hilited</xsl:when>
<xsl:when test="../@value">Standard</xsl:when>
<xsl:otherwise>
<xsl:call-template name="style"/>
</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:variable name="class">
<xsl:choose>
<xsl:when test="../@value=@type">Hilited</xsl:when>
<xsl:when test="../@value">Standard</xsl:when>
<xsl:otherwise>
<xsl:call-template name="style"/>
</xsl:otherwise>
</xsl:choose>
<xsl:if test="position()=1"> First</xsl:if>
</xsl:variable>
<a>
<xsl:call-template name="link"/>
<img border="0" width="21" height="22" class="{$class}">
<xsl:attribute name="src"><xsl:value-of select="$graphics"/>View<xsl:value-of select="@type"/><xsl:value-of select="$style"/>.gif</xsl:attribute>
</img>
</a>
</xsl:template>

<xsl:template name="t:menu">
<xsl:if test="@menu">
<xsl:attribute name="href">#</xsl:attribute>
<xsl:attribute name="onfocus">MenuHandler.showMenu(<xsl:value-of select="@menu"/>, this)</xsl:attribute>
<xsl:attribute name="onblur">MenuHandler.hideMenu(<xsl:value-of select="@menu"/>)</xsl:attribute>
<xsl:attribute name="onclick">return false</xsl:attribute>
</xsl:if>
<xsl:if test="menu:menu">
<xsl:attribute name="style">cursor:pointer;</xsl:attribute>
<!--
<xsl:attribute name="onfocus">MenuHandler.showMenu(<xsl:value-of select="generate-id(menu:menu)"/>, this)</xsl:attribute>
<xsl:attribute name="onblur">MenuHandler.hideMenu(<xsl:value-of select="generate-id(menu:menu)"/>)</xsl:attribute>
-->
</xsl:if>
</xsl:template>

</xsl:stylesheet>