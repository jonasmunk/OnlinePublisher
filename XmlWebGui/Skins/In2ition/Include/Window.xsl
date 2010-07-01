<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:w="uri:Window"
    xmlns:t="uri:Toolbar"
    version="1.0"
    exclude-result-prefixes="w t"
    >

<xsl:template match="w:window">
<xsl:variable name="topmargin">
<xsl:if test="not(@margin) and not(@top)"><xsl:value-of select="0"/></xsl:if>
<xsl:if test="@margin and not(@top)"><xsl:value-of select="@margin"/></xsl:if>
<xsl:if test="@top"><xsl:value-of select="@top"/></xsl:if>
</xsl:variable>
<xsl:variable name="height">
<xsl:if test="w:titlebar and not(w:tabgroup)"><xsl:value-of select="22+$topmargin+(count(w:parent)*19)"/></xsl:if>
<xsl:if test="w:titlebar and (w:tabgroup/@size='Small' or (w:tabgroup and not(w:tabgroup/@size)))"><xsl:value-of select="42+$topmargin+(count(w:parent)*19)"/></xsl:if>
<xsl:if test="w:titlebar and (w:tabgroup/@size='Large')"><xsl:value-of select="46+$topmargin+(count(w:parent)*19)"/></xsl:if>
<xsl:if test="not(w:titlebar)"><xsl:value-of select="0+$topmargin+(count(w:parent)*19)"/></xsl:if>
</xsl:variable>
<table border="0" cellpadding="0" cellspacing="0">
<xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute>
<xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute>
<xsl:attribute name="height"><xsl:value-of select="@height"/></xsl:attribute>
<tr><td>
<xsl:if test="@margin or @top or @left or @right or @bottom">
<xsl:attribute name="style">
<xsl:if test="@margin">padding:<xsl:value-of select="@margin"/>px;</xsl:if>
<xsl:if test="@top">padding-top:<xsl:value-of select="@top"/>px;</xsl:if>
<xsl:if test="@bottom">padding-bottom:<xsl:value-of select="@bottom"/>px;</xsl:if>
<xsl:if test="@left">padding-left:<xsl:value-of select="@left"/>px;</xsl:if>
<xsl:if test="@right">padding-right:<xsl:value-of select="@right"/>px;</xsl:if>
</xsl:attribute></xsl:if>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" id="{generate-id()}">
<xsl:apply-templates select="w:parent"/>
<xsl:apply-templates select="w:titlebar"/>
<xsl:apply-templates select="w:tabgroup"/>
<xsl:apply-templates select="t:toolbar"><xsl:with-param name="height"><xsl:value-of select="$height"/></xsl:with-param></xsl:apply-templates>
<xsl:apply-templates select="w:pathbar"/>
<xsl:apply-templates select="w:statusbar"/>
<xsl:apply-templates select="w:content"/>
</table>
<xsl:apply-templates select="w:sheet">
	<xsl:with-param name="window-id"><xsl:value-of select="generate-id(w:titlebar)"/></xsl:with-param>
</xsl:apply-templates>
</td></tr></table>
</xsl:template>

<xsl:template match="w:parent">
<tr><td height="1" style="padding-left: {(count(../w:parent)-position()+1)*20}px; padding-right: {(count(../w:parent)-position()+1)*20}px;">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<td width="5"><img src="{$graphics}ParentLeft.gif" width="5" height="19" border="0"/></td>
<td background="{$graphics}ParentBG.gif" valign="middle">
<a class="WindowParent" title="{@help}">
<xsl:if test="@link">
<xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:if>
<xsl:value-of select="@title"/>
</a>
</td>
<td width="5"><img src="{$graphics}ParentRight.gif" width="5" height="19" border="0"/></td>
</tr></table></td></tr>
</xsl:template>

<xsl:template match="w:titlebar">
<tr><td height="1" id="{generate-id()}">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
<td height="22" width="5"><img src="{$graphics}TitlebarLeft.gif" width="5" height="22" border="0"/></td>
<td align="center" background="{$graphics}TitlebarBG.gif">
<xsl:if test="w:close or w:minimize or w:maximize">
<xsl:attribute name="style">
<xsl:choose>
<xsl:when test="w:close and w:minimize and w:maximize">padding-left: 54px;</xsl:when>
<xsl:when test="w:close and not(w:minimize or w:maximize)">padding-left: 20px;</xsl:when>
<xsl:when test="w:minimze and not(w:close or w:maximize)">padding-left: 20px;</xsl:when>
<xsl:when test="w:maximize and not(w:close or w:minimize)">padding-left: 14px;</xsl:when>
<xsl:when test="w:close and w:minimize and not(w:maximize)">padding-left: 40px;</xsl:when>
<xsl:when test="w:maximize and (w:minimize or w:close) and not(w:minimize or w:close)">padding-right: 34px;</xsl:when>
</xsl:choose>
</xsl:attribute>
</xsl:if>
<table border="0" cellspacing="0" cellpadding="0"><tr>
<xsl:if test="@icon">
<td style="padding-right: 2px;"><img border="0" width="16" height="16" src="{$iconset}{@icon}Standard1.gif"/></td>
</xsl:if>
<td nowrap="true"><div class="WindowTitle"><xsl:value-of select="@title"/></div></td>
</tr></table>
</td>
<xsl:apply-templates select="w:maximize"/>
<xsl:apply-templates select="w:minimize"/>
<xsl:apply-templates select="w:close"/>
<td height="22" width="5"><img src="{$graphics}TitlebarRight.gif" width="5" height="22" border="0"/></td>
</tr></table>
</td>
</tr>
</xsl:template>

<xsl:template match="w:close">
<td width="15" align="left" background="{$graphics}TitlebarBG.gif" valign="top">
<a>
<xsl:call-template name="link"/>
<img src="{$graphics}TitlebarClose.gif" width="15" height="15" border="0" onmouseover="this.src='{$graphics}TitlebarCloseHover.gif';" onmouseout="this.src='{$graphics}TitlebarClose.gif';" style="margin-top: 4px;"/>
</a>
</td>
</xsl:template>

<xsl:template match="w:minimize">
<td width="18" align="left" background="{$graphics}TitlebarBG.gif">
<a>
<xsl:call-template name="link"/>
<img src="{$graphics}TitlebarMinimize.gif" width="15" height="22" border="0" onmouseover="this.src='{$graphics}TitlebarMinimizeHover.gif';" onmouseout="this.src='{$graphics}TitlebarMinimize.gif';"/>
</a>
</td>
</xsl:template>

<xsl:template match="w:maximize">
<td width="18" align="left" background="{$graphics}TitlebarBG.gif">
<a>
<xsl:call-template name="link"/>
<img src="{$graphics}TitlebarMaximize.gif" width="15" height="22" border="0" onmouseover="this.src='{$graphics}TitlebarMaximizeHover.gif';" onmouseout="this.src='{$graphics}TitlebarMaximize.gif';"/>
</a>
</td>
</xsl:template>

<xsl:template match="w:tabgroup[@size='Large']">
<xsl:variable name="align">
<xsl:if test="@align"><xsl:value-of select="@align"/></xsl:if>
<xsl:if test="not(@align)">center</xsl:if>
</xsl:variable>
<xsl:variable name="background"><xsl:choose>
<xsl:when test="../t:toolbar">Toolbar</xsl:when>
<xsl:when test="../w:statusbar">Statusbar</xsl:when>
<xsl:when test="../w:content/@background='true'">Window</xsl:when>
</xsl:choose></xsl:variable>
<tr><td height="24" background="{$graphics}WindowTabbarLargeBG.gif" class="WindowTabgroup{$background}" valign="bottom">
<table border="0" cellspacing="0" cellpadding="0" width="100%"><tr>
<td align="{$align}">
<table border="0" cellspacing="0" cellpadding="0"><tr>
<xsl:apply-templates/>
</tr></table></td></tr></table></td></tr>
</xsl:template>

<xsl:template match="w:tabgroup[@size='Small' or not(@size)]">
<xsl:variable name="align">
<xsl:if test="@align"><xsl:value-of select="@align"/></xsl:if>
<xsl:if test="not(@align)">center</xsl:if>
</xsl:variable>
<xsl:variable name="background"><xsl:choose>
<xsl:when test="../t:toolbar">Toolbar</xsl:when>
<xsl:when test="../w:statusbar">Statusbar</xsl:when>
<xsl:when test="../w:content/@background='true'">Window</xsl:when>
</xsl:choose></xsl:variable>
<tr><td height="20" background="{$graphics}WindowTabbarSmallBG.gif" class="WindowTabgroup{$background}" valign="bottom" align="{$align}">
<table border="0" cellspacing="0" cellpadding="0"><tr>
<xsl:apply-templates/>
</tr></table></td></tr>
</xsl:template>

<xsl:template match="w:tab[../@size='Large']">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td nowrap="true">
<xsl:attribute name="class"><xsl:text>TabLarge TabLarge</xsl:text><xsl:value-of select="$style"/><xsl:if test="position()=last() or not(name(following-sibling::*)='tab')"> TabLargeLast</xsl:if></xsl:attribute>
<xsl:if test="position()=1 or not(name(preceding-sibling::*[1])='tab')"></xsl:if>
<a class="TabLarge TabLarge{$style}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/></a>
</td>
</xsl:template>

<xsl:template match="w:tab[../@size='Small' or not(../@size)]">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td nowrap="true">
<xsl:attribute name="class"><xsl:text>TabSmall TabSmall</xsl:text><xsl:value-of select="$style"/><xsl:if test="position()=last() or not(name(following-sibling::*)='tab')"> TabSmallLast</xsl:if></xsl:attribute>
<xsl:if test="position()=1 or not(name(preceding-sibling::*[1])='tab')"></xsl:if>
<a class="TabSmall TabSmall{$style}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/></a>
</td>
</xsl:template>

<xsl:template match="w:space">
<td width="5"></td>
</xsl:template>

<xsl:template match="w:sheet">
<xsl:param name = "window-id" /> 
<xsl:variable name="winWidth">
<xsl:choose>
<xsl:when test="../@width and not(../@width='100%')"><xsl:value-of select="number(../@width)"/></xsl:when>
<xsl:otherwise>500</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:variable name="left">
<xsl:choose>
<xsl:when test="../@margin"><xsl:value-of select="((number($winWidth)-number(@width)) div 2)-number(../@margin)"/>px</xsl:when>
<xsl:when test="../@left"><xsl:value-of select="((number($winWidth)-number(@width)) div 2)-number(../@left)"/>px</xsl:when>
<xsl:otherwise><xsl:value-of select="(number($winWidth)-number(@width)) div 2"/>px</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:variable name="visible">
<xsl:choose>
<xsl:when test="@visible='true'">true</xsl:when>
<xsl:otherwise>false</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:variable name="top">
<xsl:value-of select="count(../w:parent)*19+22"/>
</xsl:variable>
<div align="center" id="{generate-id()}" class="WindowSheet">
<xsl:attribute name="style">
width: <xsl:value-of select="@width"/>px;<!-- margin-top: <xsl:value-of select="$top"/>px;--> margin-left: <xsl:value-of select="$left"/>;
<xsl:if test="$visible='false'">clip: rect(0px 0px 0px 0px);</xsl:if>
</xsl:attribute>
<xsl:apply-templates/>
</div>
<xsl:if test="@object">
<script src="{$root}Scripts/WindowSheet.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new In2iGui.WindowSheet('<xsl:value-of select="generate-id()"/>',<xsl:value-of select="$visible"/>,<xsl:value-of select="@width"/>,'<xsl:value-of select="$window-id"/>');

<xsl:value-of select="@object"/>.position();
</script>
</xsl:if>
</xsl:template>

<xsl:template match="w:statusbar">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<tr id="{generate-id()}TR">
<xsl:if test="@visible='false'"><xsl:attribute name="style">display: none;</xsl:attribute></xsl:if>
<td height="20" align="center" id="{generate-id()}TD">
<xsl:attribute name="class">
<xsl:text>WindowStatusbar</xsl:text><xsl:value-of select="$style"/>
<xsl:if test="../t:toolbar"> WindowStatusbarToolbar</xsl:if>
</xsl:attribute>
<img border="0" width="12" height="12" class="WindowStatusbarStatus" id="{generate-id()}STATUS">
<xsl:if test="@status">
<xsl:attribute name="src"><xsl:value-of select="$graphics"/>Status<xsl:value-of select="@status"/>.gif</xsl:attribute>
</xsl:if>
<xsl:if test="not(@status)">
<xsl:attribute name="style">display: none;</xsl:attribute>
</xsl:if>
</img>
<span id="{generate-id()}TEXT"><xsl:value-of select="@text"/></span>
<xsl:if test="@object">
<script src="{$root}Scripts/WindowStatusbar.js" type="text/javascript" language="JavaScript"></script>
<script type="text/javascript" language="JavaScript">
var <xsl:value-of select="@object"/> = new In2iGuiWindowStatusbar('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="$style"/>','<xsl:value-of select="@text"/>','<xsl:value-of select="@status"/>','<xsl:value-of select="$graphics"/>');
</script>
</xsl:if>
</td></tr>
</xsl:template>

<xsl:template match="w:pathbar">
<tr>
<td height="20" align="center" id="{generate-id()}TD">
<xsl:attribute name="class">
<xsl:text>WindowPathbar</xsl:text>
</xsl:attribute>
<xsl:apply-templates/>
</td></tr>
</xsl:template>

<xsl:template match="w:pathbar/w:item">
	<xsl:if test="position()>1">/</xsl:if>
	<a href="{@link}"><xsl:value-of select="@title"/></a>
</xsl:template>

<xsl:template match="w:content">
<tr><td bgcolor="#ffffff" width="{@width}" height="{@height}" align="{@align}" valign="{@valign}" id="{generate-id()}">
<xsl:attribute name="class">WindowContent<xsl:if test="@background='true'"> WindowContentBackground<xsl:if test="../t:toolbar">Toolbar</xsl:if></xsl:if></xsl:attribute>
<xsl:attribute name="style">
<xsl:if test="@padding">padding:<xsl:value-of select="@padding"/>px;</xsl:if>
<xsl:if test="(../t:toolbar and not(@background='true')) or (not(../t:toolbar) and not(../w:statusbar) and not(../w:tabgroup) and not(@background='true'))">border-top-width: 1px;</xsl:if>
</xsl:attribute>
<xsl:apply-templates/>
<xsl:if test="not(node())">&#160;</xsl:if>
</td></tr>
</xsl:template>

</xsl:stylesheet>