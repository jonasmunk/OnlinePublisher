<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xwg="uri:List"
    xmlns:menu="uri:Menu"
    version="1.0"
    exclude-result-prefixes="xwg menu"
    >

<xsl:template match="xwg:list">
<div id="list_outer_{generate-id()}" class="ListContainer">
<xsl:if test="@margin or @top or @left or @right or @bottom">
<xsl:attribute name="style">
<xsl:if test="@margin">padding:<xsl:value-of select="@margin"/>px;</xsl:if>
<xsl:if test="@top">padding-top:<xsl:value-of select="@top"/>px;</xsl:if>
<xsl:if test="@bottom">padding-bottom:<xsl:value-of select="@bottom"/>px;</xsl:if>
<xsl:if test="@left">padding-left:<xsl:value-of select="@left"/>px;</xsl:if>
<xsl:if test="@right">padding-right:<xsl:value-of select="@right"/>px;</xsl:if>
</xsl:attribute></xsl:if>
<xsl:apply-templates select="xwg:tabgroup"/>
<xsl:apply-templates select="xwg:title"/>
<xsl:apply-templates select="xwg:content"/>
</div>
<xsl:apply-templates select="//menu:menu[name(.//..)!='item']"/>
<script src="{$root}Scripts/List.js" type="text/javascript"></script>
<script type="text/javascript">
var list_<xsl:value-of select="generate-id()"/> = new In2iGui.List('<xsl:value-of select="generate-id()"/>','<xsl:value-of select="@sort"/>','<xsl:value-of select="@selectable"/>');
<xsl:if test="@object">
var <xsl:value-of select="@object"/> = list_<xsl:value-of select="generate-id()"/>;
</xsl:if>
</script>
</xsl:template>

<xsl:template match="xwg:content">
<table border="0" cellpadding="0" cellspacing="0" width="{../@width}" id="list_{generate-id(../.)}_content">
<xsl:attribute name="class">
<xsl:text>List</xsl:text>
<xsl:if test="../@variant"><xsl:value-of select="../@variant"/></xsl:if>
</xsl:attribute>
<xsl:apply-templates select="xwg:headergroup"/>
<tbody id="body">
<xsl:apply-templates select="xwg:row"/>
</tbody>
</table>
</xsl:template>

<xsl:template match="xwg:title">
<table border="0" cellpadding="0" cellspacing="0" width="{../@width}">
<tr><td class="ListTitle" style="background-image:url({$graphics}ListHeaderStandardBG.gif)"><xsl:value-of select="@title"/></td></tr>
</table>
</xsl:template>


<!-- Tabs -->


<xsl:template match="xwg:tabgroup">
<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td>
<table border="0" cellpadding="0" cellspacing="0"><tr><td width="3"></td>
<xsl:apply-templates/>
</tr></table>
</td><td class="ListTabgroupText"><xsl:value-of select="@text"/></td></tr></table>
</xsl:template>

<xsl:template match="xwg:tab">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td nowrap="nowrap">
<a class="ListTab ListTab{$style}" title="{@help}" style="white-space: nowrap;">
<xsl:if test="@link">
<xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:if>
<xsl:if test="not(@link)"><xsl:attribute name="style">cursor: default;</xsl:attribute></xsl:if>
<xsl:value-of select="@title"/>
</a>
</td>
<td width="2"></td>
</xsl:template>




<!-- Headers -->


<xsl:template match="xwg:headergroup">
<tr>
<xsl:if test="../../@selectable!=''">
<td class="ListHeaderStandard" width="1%"></td>
</xsl:if>
<xsl:apply-templates/>
</tr>
</xsl:template>

<xsl:template match="xwg:header">
<xsl:variable name="object_name">list_<xsl:value-of select="generate-id(../../../.)"/></xsl:variable>
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td class="ListHeader{$style}">
<xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>
<xsl:if test="@width"><xsl:attribute name="width"><xsl:value-of select="@width"/></xsl:attribute></xsl:if>
<a class="ListHeader ListHeader{$style}" title="{@help}" onfocus="this.blur();">
<xsl:if test="@link">
<xsl:attribute name="href"><xsl:value-of select="@link"/></xsl:attribute>
<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
</xsl:if>
<xsl:if test="not(@link)">
<xsl:if test="not(ancestor::xwg:list[@sort='true'])">
<xsl:attribute name="style">cursor: default;</xsl:attribute>
</xsl:if>
<xsl:if test="ancestor::xwg:list[@sort='true']">
<xsl:attribute name="href">javascript: <xsl:value-of select="$object_name"/>.sortColumn(<xsl:value-of select="position()-1"/>,'<xsl:value-of select="@type"/>');</xsl:attribute>
</xsl:if>
</xsl:if>
<xsl:if test="@checker='true'"><span class="ListHeaderCheck" onclick="list_{generate-id(../../../.)}.toggleCheckboxes();">[x]</span></xsl:if>
<xsl:value-of select="@title"/>
</a>
<xsl:if test="@direction='Down'">
<img border="0" class="ListHeaderDirection" width="7" height="9" src="{$graphics}ListHeaderDown{$style}.gif"/>
</xsl:if>
<xsl:if test="@direction='Up'">
<img border="0" class="ListHeaderDirection" width="7" height="9" src="{$graphics}ListHeaderUp{$style}.gif"/>
</xsl:if>
</td>
</xsl:template>




<!-- Rows & Cells -->


<xsl:template match="xwg:row">
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<xsl:variable name="flavor">
<xsl:if test="position() mod 2 = 0">1</xsl:if>
<xsl:if test="not(position() mod 2 = 0)">2</xsl:if>
</xsl:variable>
<tr class="{$style}{$flavor}">
<xsl:if test="../../@selectable!=''">
<td class="Cell Standard">
	<input type="checkbox" value="{@uid}" name="{../../@selectable}">
		<xsl:if test="@selected='true'">
			<xsl:attribute name="checked">checked</xsl:attribute>
		</xsl:if>
	</input>
</td>
</xsl:if>
<xsl:apply-templates>
<xsl:with-param name="flavor" select="$flavor"/>
<xsl:with-param name="rowstyle" select="$style"/>
</xsl:apply-templates>
</tr>
</xsl:template>

<xsl:template match="xwg:cell">
<xsl:param name="flavor"/>
<xsl:param name="rowstyle"/>
<xsl:variable name="columnstyle"><xsl:call-template name="xwg:columnstyle"/></xsl:variable>
<xsl:variable name="columnwidth"><xsl:call-template name="xwg:columnwidth"/></xsl:variable>
<xsl:variable name="nowrap"><xsl:call-template name="xwg:nowrap"/></xsl:variable>
<xsl:variable name="align">
<xsl:if test="@align"><xsl:value-of select="@align"/></xsl:if>
<xsl:if test="not(@align)"><xsl:call-template name="xwg:align"/></xsl:if>
</xsl:variable>
<td width="{$columnwidth}" align="{$align}">
<xsl:if test="ancestor::xwg:list[@sort='true']">
<xsl:attribute name="xwgindex">
<xsl:if test="@index"><xsl:value-of select="@index"/></xsl:if>
<xsl:if test="not(@index)"><xsl:value-of select="substring(normalize-space(.),0,20)"/></xsl:if>
</xsl:attribute>
</xsl:if>
<xsl:attribute name="class">
<xsl:if test="position()=last()"><xsl:text>Last </xsl:text></xsl:if>
<xsl:text>Cell </xsl:text>
<xsl:value-of select="$columnstyle"/>
</xsl:attribute>
<xsl:if test="node() and not(child::text())">
  <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;" height="100%">
  <tr>
    <xsl:apply-templates>
      <xsl:with-param name="rowstyle" select="$rowstyle"/>
      <xsl:with-param name="columnstyle" select="$columnstyle"/>
      <xsl:with-param name="align" select="$align"/>
      <xsl:with-param name="nowrap" select="$nowrap"/>
    </xsl:apply-templates>
    <xsl:if test="not(xwg:text)"><td></td></xsl:if>
  </tr>
  </table>
</xsl:if>
<xsl:if test="child::text()">
  <xsl:if test="$nowrap='true'"><xsl:attribute name="nowrap">nowrap</xsl:attribute></xsl:if>
  <a class="ListText">
    <xsl:call-template name="xwg:listlink"/>
    <xsl:apply-templates/>
  </a>
</xsl:if>
<xsl:if test="not(node())">&#160;</xsl:if>
</td>
</xsl:template>

<xsl:template name="xwg:columnwidth">
<xsl:variable name="position" select="position()"/>
<xsl:value-of select="../../xwg:headergroup/xwg:header[position()=$position]/@width"/>
</xsl:template>

<xsl:template name="xwg:columnstyle">
<xsl:variable name="position" select="position()"/>
<xsl:if test="../../xwg:headergroup/xwg:header[position()=$position]/@style">
<xsl:value-of select="../../xwg:headergroup/xwg:header[position()=$position]/@style"/>
</xsl:if>
<xsl:if test="not(../../xwg:headergroup/xwg:header[position()=$position]/@style)">
<xsl:value-of select="'Standard'"/>
</xsl:if>
</xsl:template>

<xsl:template name="xwg:align">
<xsl:variable name="position" select="position()"/>
<xsl:value-of select="../../xwg:headergroup/xwg:header[position()=$position]/@align"/>
</xsl:template>

<xsl:template name="xwg:nowrap">
<xsl:variable name="position" select="position()"/>
<xsl:if test="@nowrap"><xsl:text>true</xsl:text></xsl:if>
<xsl:if test="not(@nowrap)">
<xsl:value-of select="../../xwg:headergroup/xwg:header[position()=$position]/@nowrap"/>
</xsl:if>
</xsl:template>




<!-- Text -->


<xsl:template match="xwg:text">
<xsl:param name="rowstyle"/>
<xsl:param name="columnstyle"/>
<xsl:param name="align"/>
<xsl:param name="nowrap"/>
<td valign="top" style="padding: 2px;">
<xsl:if test="$nowrap='true' or @nowrap='true'"><xsl:attribute name="nowrap">nowrap</xsl:attribute></xsl:if>
<xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>
<xsl:if test="not(@align) and not($align='')"><xsl:attribute name="align"><xsl:value-of select="$align"/></xsl:attribute></xsl:if>
<a class="ListText">
<xsl:call-template name="xwg:listlink"/>
<xsl:apply-templates>
<xsl:with-param name="rowstyle" select="$rowstyle"/>
<xsl:with-param name="columnstyle" select="$columnstyle"/>
</xsl:apply-templates>
</a>
</td>
</xsl:template>

<xsl:template match="xwg:line">
<xsl:param name="columnstyle"/>
<xsl:param name="rowstyle"/>
<xsl:if test="preceding-sibling::*">
<br/>
</xsl:if>
<a class="ListText">
<xsl:call-template name="xwg:listlink"/>
<xsl:value-of select="."/>
</a>
</xsl:template>

<xsl:template match="xwg:break"><br/></xsl:template>

<xsl:template match="xwg:strong"><b><xsl:apply-templates/></b></xsl:template>




<!-- Graphics -->


<xsl:template match="xwg:progress">
<xsl:param name="align"/>
<td valign="top" style="padding: 2px; width: 100%">
<xsl:call-template name="xwg:subcell"><xsl:with-param name="align" select="$align"/></xsl:call-template>
<a>
<xsl:call-template name="xwg:listlink"/>
<div class="ListProgressBG">
<div class="ListProgress" style="width: {@value}%;">
</div>
</div>
</a>
<xsl:if test="@minwidth"><div width="{@minwidth}"></div></xsl:if>
</td>
</xsl:template>

<xsl:template match="xwg:icon">
<xsl:param name="rowstyle"/>
<xsl:param name="align"/>
<xsl:variable name="style">
<xsl:if test="@style"><xsl:call-template name="style"/></xsl:if>
<xsl:if test="not(@style)"><xsl:value-of select="$rowstyle"/></xsl:if>
</xsl:variable>
<xsl:variable name="size"><xsl:choose>
<xsl:when test="@size"><xsl:value-of select="@size"/></xsl:when>
<xsl:otherwise>1</xsl:otherwise>
</xsl:choose></xsl:variable>
<xsl:variable name="link"><xsl:call-template name="xwg:linkvalue"/></xsl:variable>

<td valign="top" style="padding: 0px 1px 1px 1px;">
<xsl:call-template name="xwg:subcell"><xsl:with-param name="align" select="$align"/></xsl:call-template>

<xsl:if test="not(@overlay)">
<a>
<xsl:call-template name="xwg:listlink"/>
<img class="ListIcon" border="0" width="{$size*16}" height="{$size*16}" src="{$iconset}{@icon}{$style}{$size}.gif">
<xsl:if test="$style='Standard' and ($link!='' or @menu or menu:menu)">
<xsl:attribute name="onmouseout">unHilite(this);</xsl:attribute>
<xsl:attribute name="onmouseover">hilite(this);</xsl:attribute>
</xsl:if>
</img>
</a>
</xsl:if>

<xsl:if test="@overlay">
<table class="ListIcon" border="0" cellpadding="0" cellspacing="0" width="{$size*16}" height="{$size*16}"><tr><td>
<xsl:attribute name="id"><xsl:value-of select="generate-id()"/></xsl:attribute>
<xsl:attribute name="background"><xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/><xsl:value-of select="$size"/>.gif</xsl:attribute>
<a>
<xsl:attribute name="title"><xsl:value-of select="@help"/></xsl:attribute>
<xsl:call-template name="xwg:listlink"/>
<img border="0" width="{$size*16}" height="{$size*16}" src="{$iconset}Overlay/{@overlay}{$style}{$size}.gif">
<xsl:if test="$style='Standard' and $link!=''">
<xsl:attribute name="onmouseout">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/><xsl:value-of select="$style"/><xsl:value-of select="$size"/>.gif\')';</xsl:attribute>
<xsl:attribute name="onmouseover">document.getElementById('<xsl:value-of select="generate-id()"/>').style.backgroundImage='url(\'<xsl:value-of select="$iconset"/><xsl:value-of select="@icon"/>Hilited<xsl:value-of select="$size"/>.gif\')';</xsl:attribute>
</xsl:if>
</img>
</a>
</td></tr></table>
</xsl:if>
</td>
</xsl:template>

<xsl:template match="xwg:statusgroup">
<xsl:param name="align"/>
<td valign="top">
<xsl:call-template name="xwg:subcell"><xsl:with-param name="align" select="$align"/></xsl:call-template>
<xsl:apply-templates/>
</td>
</xsl:template>

<xsl:template match="xwg:status">
<xsl:param name="align"/>
<td valign="top" class="ListStatus">
<xsl:call-template name="xwg:subcell"><xsl:with-param name="align" select="$align"/></xsl:call-template>
<a><xsl:call-template name="xwg:listlink"/>
<img border="0" class="ListStatus" width="12" height="12" src="{$graphics}Status{@type}.gif"/>
</a>
</td>
</xsl:template>

<xsl:template match="xwg:statusgroup/xwg:status">
<a><xsl:call-template name="xwg:listlink"/>
<img border="0" class="ListStatus" width="12" height="12" src="{$graphics}Status{@type}.gif"/>
</a>
</xsl:template>




<!-- Form elements -->


<xsl:template match="xwg:select">
<xsl:param name="align"/>
<td style="width: 100%">
<xsl:call-template name="xwg:subcell"><xsl:with-param name="align" select="$align"/></xsl:call-template>
<xsl:if test="not(@lines) or @lines=1">
<select class="ListForm" name="{@name}">
<xsl:call-template name="events"/>
<xsl:apply-templates/>
</select>
</xsl:if>
<xsl:if test="@lines>1">
<select class="ListForm" name="{@name}" size="{@lines}">
<xsl:call-template name="events"/>
<xsl:if test="@multiple='on'"><xsl:attribute name="multiple">on</xsl:attribute></xsl:if>
<xsl:apply-templates/>
</select>
</xsl:if>
</td>
</xsl:template>

<xsl:template match="xwg:option">
<option value="{@value}">
<xsl:if test="@selected='true' or @value=../@selected"><xsl:attribute name="selected">true</xsl:attribute></xsl:if>
<xsl:value-of select="@title"/>
</option>
</xsl:template>

<xsl:template match="xwg:textfield">
<xsl:param name="align"/>
<td style="width: 100%">
<xsl:call-template name="xwg:subcell"><xsl:with-param name="align" select="$align"/></xsl:call-template>
<xsl:if test="not(@lines) or @lines=1">
<input class="ListForm" name="{@name}" value="{.}">
<xsl:call-template name="events"/>
<xsl:if test="@maxlength">
<xsl:attribute name="maxlength"><xsl:value-of select="@maxlength"/></xsl:attribute></xsl:if>
<xsl:if test="@size"><xsl:attribute name="size"><xsl:value-of select="@size"/></xsl:attribute></xsl:if>
</input>
</xsl:if>
<xsl:if test="@lines>1">
<textarea class="ListForm" style="overflow: auto;" wrap="virtual" name="{@name}" rows="{@lines}">
<xsl:call-template name="events"/>
<xsl:value-of select="."/>
</textarea>
</xsl:if>
</td>
</xsl:template>

<xsl:template match="xwg:checkbox">
<xsl:param name="align"/>
<td valign="top">
<xsl:call-template name="xwg:subcell"><xsl:with-param name="align" select="$align"/></xsl:call-template>
<input type="checkbox" name="{@name}" class="Checkbox">
<xsl:call-template name="events"/>
<xsl:if test="@selected='true'"><xsl:attribute name="checked"></xsl:attribute></xsl:if>
<xsl:if test="@value">
<xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute>
</xsl:if>
</input></td>
</xsl:template>

<xsl:template match="xwg:radio">
<xsl:param name="align"/>
<td valign="top">
<xsl:call-template name="xwg:subcell"><xsl:with-param name="align" select="$align"/></xsl:call-template>
<input type="radio" name="{@name}">
<xsl:if test="@value"><xsl:attribute name="value"><xsl:value-of select="@value"/></xsl:attribute></xsl:if>
<xsl:call-template name="events"/>
<xsl:if test="@selected='true'"><xsl:attribute name="checked"></xsl:attribute></xsl:if>
</input></td>
</xsl:template>




<!-- Buttons -->


<xsl:template match="xwg:button">
<xsl:param name="align"/>
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td nowrap="nowrap" style="padding-left: 1px;">
<xsl:call-template name="xwg:subcell"><xsl:with-param name="align" select="$align"/></xsl:call-template>
<table border="0" cellpadding="0" cellspacing="0"><tr>
<td nowrap="nowrap">
<a class="ButtonSmall{$style} ButtonSmall">
<xsl:call-template name="xwg:listlink"/>
<xsl:value-of select="@title"/>
</a></td>
</tr></table>
</td>
</xsl:template>

<xsl:template match="xwg:direction">
<xsl:param name="align"/>
<xsl:variable name="style"><xsl:call-template name="style"/></xsl:variable>
<td style="padding: 1px;">
<xsl:call-template name="xwg:subcell"><xsl:with-param name="align" select="$align"/></xsl:call-template>
<a>
<xsl:call-template name="xwg:listlink"/>
<img src="{$graphics}ButtonSmall{@direction}{$style}.gif" width="20" height="18" border="0">
<xsl:if test="$style='Standard' and @link">
<xsl:attribute name="onmouseover">this.src='<xsl:value-of select="$graphics"/>ButtonSmall<xsl:value-of select="@direction"/>Hilited.gif';</xsl:attribute>
<xsl:attribute name="onmouseout">this.src='<xsl:value-of select="$graphics"/>ButtonSmall<xsl:value-of select="@direction"/>Standard.gif';</xsl:attribute>
</xsl:if>
</img>
</a></td>
</xsl:template>




<!-- support -->

<xsl:template name="xwg:subcell">
<xsl:param name="align"/>
<xsl:if test="@align"><xsl:attribute name="align"><xsl:value-of select="@align"/></xsl:attribute></xsl:if>
<xsl:if test="not(@align) and not($align='')"><xsl:attribute name="align"><xsl:value-of select="$align"/></xsl:attribute></xsl:if>
<xsl:if test="following-sibling::node() or preceding-sibling::node()">
<xsl:attribute name="width">1</xsl:attribute>
</xsl:if>
</xsl:template>

<xsl:template name="xwg:listlink">
<xsl:variable name="link"><xsl:call-template name="xwg:linkvalue"/></xsl:variable>
<xsl:variable name="target"><xsl:call-template name="xwg:targetvalue"/></xsl:variable>
<xsl:variable name="help"><xsl:call-template name="xwg:helpvalue"/></xsl:variable>
<xsl:if test="not(menu:menu or @menu) and $link!=''">
  <xsl:attribute name="href"><xsl:value-of select="$link"/></xsl:attribute>
  <xsl:if test="$target!=''">
    <xsl:attribute name="target"><xsl:value-of select="$target"/></xsl:attribute>
  </xsl:if>
</xsl:if>
<xsl:if test="menu:menu or @menu">
<xsl:call-template name="xwg:listmenu"/>
</xsl:if>
<xsl:if test="$link='' and not(menu:menu or @menu)">
<xsl:attribute name="style">cursor: default;</xsl:attribute>
</xsl:if>
<xsl:if test="$help!=''">
<xsl:attribute name="title"><xsl:value-of select="$help"/></xsl:attribute>
</xsl:if>
</xsl:template>

<xsl:template name="xwg:linkvalue">
<xsl:if test="@link">
<xsl:value-of select="@link"/>
</xsl:if>
<xsl:if test="ancestor::xwg:cell[@link] and not(@link)">
<xsl:value-of select="ancestor::xwg:cell/@link"/>
</xsl:if>
<xsl:if test="ancestor::xwg:row[@link] and not(ancestor::xwg:cell[@link]) and not(@link)">
<xsl:value-of select="ancestor::xwg:row/@link"/>
</xsl:if>
</xsl:template>

<xsl:template name="xwg:targetvalue">
<xsl:if test="@target">
<xsl:value-of select="@target"/>
</xsl:if>
<xsl:if test="ancestor::xwg:cell[@target] and not(@target)">
<xsl:value-of select="ancestor::xwg:cell/@target"/>
</xsl:if>
<xsl:if test="ancestor::xwg:row[@target] and not(ancestor::xwg:cell[@target]) and not(@target)">
<xsl:value-of select="ancestor::xwg:row/@target"/>
</xsl:if>
</xsl:template>

<xsl:template name="xwg:helpvalue">
<xsl:if test="@help">
<xsl:value-of select="@help"/>
</xsl:if>
<xsl:if test="ancestor::xwg:cell[@help] and not(@help)">
<xsl:value-of select="ancestor::xwg:cell/@help"/>
</xsl:if>
<xsl:if test="ancestor::xwg:row[@help] and not(ancestor::xwg:cell[@help]) and not(@help)">
<xsl:value-of select="ancestor::xwg:row/@help"/>
</xsl:if>
</xsl:template>

<xsl:template name="xwg:listmenu">
<xsl:if test="@menu">
<xsl:attribute name="href">#</xsl:attribute>
<xsl:attribute name="onfocus">MenuHandler.showMenu(<xsl:value-of select="@menu"/>, this)</xsl:attribute>
<xsl:attribute name="onblur">MenuHandler.hideMenu(<xsl:value-of select="@menu"/>)</xsl:attribute>
<xsl:attribute name="onclick">return false</xsl:attribute>
</xsl:if>
<xsl:if test="menu:menu">
<xsl:attribute name="href">#</xsl:attribute>
<xsl:attribute name="onfocus">MenuHandler.showMenu(<xsl:value-of select="generate-id(menu:menu)"/>, this)</xsl:attribute>
<xsl:attribute name="onblur">MenuHandler.hideMenu(<xsl:value-of select="generate-id(menu:menu)"/>)</xsl:attribute>
<xsl:attribute name="onclick">return false</xsl:attribute>
</xsl:if>
</xsl:template>

</xsl:stylesheet>