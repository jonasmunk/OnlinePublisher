<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:Dock"
    xmlns:scp="uri:Script"
    version="1.0"
    exclude-result-prefixes="xwg scp"
    >

<xsl:template match="xwg:dock">
<xsl:variable name="height">
<xsl:if test="@orientation='Bottom'">
<xsl:if test="xwg:tabgroup">16</xsl:if>
<xsl:if test="not(xwg:tabgroup)">0</xsl:if>
</xsl:if>
<xsl:if test="@orientation='Top'">0</xsl:if>
</xsl:variable>
<head>
<link rel="stylesheet" type="text/css" href="{$skin}Stylesheet.css"/>
<xsl:if test="@refresh"><meta http-equiv="Refresh" content="{@refresh}"/></xsl:if>
<script>
var In2iGui = {};
In2iGui.paths = {iconset:'<xsl:value-of select="$iconset"/>',graphics:'<xsl:value-of select="$graphics"/>'};
	
var orientation='<xsl:value-of select="@orientation"/>';
var isOpened=true;
if (orientation=='Bottom') {
    collapsed='*,16';
    opened='*,70';
}
else if (orientation=='Top') {
    collapsed='16,*';
    opened='70,*';
}

function toggleView() {
    if (document.all || document.getElementById) {
        if (isOpened) {
            var theRows=collapsed;
            var scroll=56;
			isOpened=false;
        }
        else {
            var theRows=opened;
            var scroll=0;
			isOpened=true;
        }
        parent.document.getElementById(orientation.toLowerCase()).rows=theRows;
        if (orientation=='Top') {
            window.scrollTo(0,scroll);
            window.setInterval('window.scrollTo(0,56)',500);
        }
    }
}

function collapse() {
	if (isOpened) {
		if (document.all || document.getElementById) {
			var theRows=collapsed;
			var scroll=56;
			parent.document.getElementById(orientation.toLowerCase()).rows=theRows;
			if (orientation=='Top') {
				window.scrollTo(0,scroll);
				window.setInterval('window.scrollTo(0,56)',500);
			}
			isOpened=false;
		}
	}
}

function expand() {
	if (!isOpened) {
		if (document.all || document.getElementById) {
			var theRows=opened;
			var scroll=0;
			parent.document.getElementById(orientation.toLowerCase()).rows=theRows;
			if (orientation=='Top') {
				window.scrollTo(0,scroll);
				window.setInterval('window.scrollTo(0,56)',500);
			}
			isOpened=true;
		}
	}
}
</script>
<script type="text/javascript" src="{$root}Scripts/In2iScripts.js"> </script>
</head>

<xsl:if test="@orientation='Bottom'">
<body class="BackgroundDesktop" style="margin: 0px;overflow-x:hidden; overflow-y:hidden;">
<xsl:attribute name="onload"><xsl:value-of select="@onload"/></xsl:attribute>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<xsl:apply-templates select="xwg:tabgroup"/>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<xsl:apply-templates select="xwg:content">
<xsl:with-param name="height"><xsl:value-of select="$height"/></xsl:with-param>
</xsl:apply-templates>
</tr>
</table>
<xsl:apply-templates select="scp:*"/>
</body>
</xsl:if>

<xsl:if test="@orientation='Top'">
<body class="BackgroundDesktop" style="margin: 0px;overflow-x:hidden; overflow-y:hidden;">
<xsl:attribute name="onload"><xsl:value-of select="@onload"/></xsl:attribute>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<xsl:apply-templates select="xwg:content">
<xsl:with-param name="height"><xsl:value-of select="$height"/></xsl:with-param>
</xsl:apply-templates>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<xsl:apply-templates select="xwg:tabgroup"/>
</table>
<xsl:apply-templates select="scp:*"/>
</body>
</xsl:if>
</xsl:template>




<!-- Tabs -->


<xsl:template match="xwg:tabgroup">
<tr>
<td background="{$graphics}DockTabgroup{../@orientation}BG.gif">
<xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute>
<table border="0" cellspacing="0" cellpadding="0"><tr>
<td style="padding-left: 3px;"><table/></td>
<xsl:apply-templates/>
<td style="padding-left: 3px;"><table/></td>
</tr></table></td>
</tr>
</xsl:template>

<xsl:template match="xwg:tab">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td nowrap="true">
<xsl:attribute name="class"><xsl:text>TabSmall TabSmall</xsl:text><xsl:value-of select="$style"/><xsl:if test="position()=last() or not(name(following-sibling::*)='tab')"> TabSmallLast</xsl:if></xsl:attribute>
<xsl:if test="position()=1 or not(name(preceding-sibling::*[1])='tab')"></xsl:if>
<a class="TabSmall TabSmall{$style}">
<xsl:if test="not(@link)"><xsl:attribute name="href">javascript: toggleView();</xsl:attribute></xsl:if>
<xsl:if test="@link"><xsl:call-template name="link"/></xsl:if>
<xsl:value-of select="@title"/></a>
</td>
</xsl:template>



<!-- Other -->


<xsl:template match="xwg:content">
<xsl:param name="height"/>
<td valign="top" height="54" style="padding-right: 6px; padding-left: 4px;">
<xsl:if test="not(../xwg:tabgroup)">
<xsl:attribute name="class">DockContent DockContent<xsl:value-of select="../@orientation"/></xsl:attribute>
</xsl:if>
<xsl:if test="../xwg:tabgroup">
<xsl:attribute name="class">DockContent</xsl:attribute>
</xsl:if>
<table border="0" cellspacing="0" cellpadding="0" height="54"><tr>
<xsl:apply-templates>
<xsl:with-param name="height"><xsl:value-of select="$height"/></xsl:with-param>
</xsl:apply-templates>
</tr></table>
</td>
</xsl:template>

<xsl:template match="xwg:flexible">
<xsl:if test="name(parent::*)='tabgroup'">
<td width="99%" class="DockTabSpace{../../@orientation}"><table/></td>
</xsl:if>
<xsl:if test="name(parent::*)='content'">
<td width="99%"></td>
</xsl:if>
</xsl:template>

<xsl:template match="xwg:tool">
<xsl:param name="height"/>
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td valign="bottom" style="padding-bottom: 4px; padding-left: 4px; padding-right: 4px;" width="1">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td align="center" height="32">
<xsl:if test="@badge">
<xsl:variable name="badgestyle">
<xsl:if test="@badgestyle"><xsl:value-of select="@badgestyle"/></xsl:if>
<xsl:if test="not(@badgestyle)"><xsl:value-of select="$style"/></xsl:if>
</xsl:variable>
<a>
<xsl:attribute name="style">top: <xsl:value-of select="$height+27"/>px;
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
<xsl:call-template name="link"/>
<img border="0" width="32" height="32" src="{$iconset}{@icon}{$style}2.gif" id="{generate-id()}-icon">
<xsl:if test="$style='Standard' and @link">
<xsl:attribute name="onmouseout">this.src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>2.gif';</xsl:attribute>
<xsl:attribute name="onmouseover">this.src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited2.gif';</xsl:attribute>
</xsl:if>
</img>
</a>
</xsl:if>
<xsl:if test="@overlay">
<table border="0" cellpadding="0" cellspacing="0" width="32" height="32" style="display: inline;"><tr><td>
<xsl:attribute name="id"><xsl:value-of select="generate-id()"/></xsl:attribute>
<xsl:attribute name="background"><xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>2.gif</xsl:attribute>
<a>
<xsl:call-template name="link"/>
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute>
<img border="0" width="32" height="32" src="{$iconset}Overlay/{@overlay}{$style}2.gif">
<xsl:if test="$style='Standard' and @link">
<xsl:attribute name="onmouseout">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>2.gif\')';</xsl:attribute>
<xsl:attribute name="onmouseover">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited2.gif\')';</xsl:attribute>
</xsl:if>
</img>
</a>
</td></tr></table>
</xsl:if>
</td>
</tr>
<tr><td align="center" nowrap="true">
<a class="Icon{$style}">
<xsl:if test="$style='Standard' and @link">
<xsl:if test="@overlay">
<xsl:attribute name="onmouseout">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>2.gif\')';</xsl:attribute>
<xsl:attribute name="onmouseover">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited2.gif\')';</xsl:attribute>
</xsl:if>
<xsl:if test="not(@overlay)">
<xsl:attribute name="onmouseout">document.getElementById('<xsl:value-of select="generate-id()"/>-icon').src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/>2.gif';</xsl:attribute>
<xsl:attribute name="onmouseover">document.getElementById('<xsl:value-of select="generate-id()"/>-icon').src='<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited2.gif';</xsl:attribute>
</xsl:if>
</xsl:if>
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/></a></td></tr></table></td>
</xsl:template>

<xsl:template match="xwg:divider">
<td valign="top" style="padding: 2px 6px 2px 6px;" width="1">
<div class="Divider" style="height: 50px;"/>
</td>
</xsl:template>

<xsl:template match="xwg:searchfield">
<td valign="bottom" style="padding-left: 5px; padding-right: 5px;"><table cellpadding="0" cellspacing="0" border="0">
<tr><td align="center" style="padding-bottom: 2px;"><table cellspacing="0" cellpadding="0" border="0">
<xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute>
<form name="{generate-id()}" method="{@method}" target="{@target}" action="{@action}">
<tr>
<td width="12"><img border="0" width="12" height="24" src="{$graphics}SearchfieldLeft.gif"/></td>
<td style="padding-top: 5px; padding-right: 3px;" background="{$graphics}SearchfieldBG.gif">
<input type="text" class="Searchfield" name="{@name}" value="{@value}"></input>
</td>
<td width="20"><input type="image" border="0" width="20" height="24" title="{@help}" src="{$graphics}SearchfieldRight.gif"/></td>
</tr></form>
</table>
</td></tr>
<tr><td height="1" valign="top" nowrap="on" align="center" style="padding: 2px 0 4px 0;">
<a style="cursor: default;" class="IconStandard"><xsl:value-of select="@title"/></a>
</td></tr></table>
<xsl:if test="@focus and @name">
<script language="JavaScript">document.<xsl:value-of select="generate-id()"/>.<xsl:value-of select="@name"/>.focus();document.<xsl:value-of select="generate-id()"/>.<xsl:value-of select="@name"/>.select();</script>
</xsl:if>
</td>
</xsl:template>

</xsl:stylesheet>