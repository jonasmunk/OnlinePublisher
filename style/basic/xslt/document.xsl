<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:doc="http://uri.in2isoft.com/onlinepublisher/publishing/document/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/class/person/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:part="http://uri.in2isoft.com/onlinepublisher/part/1.0/"
 xmlns:ph="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"
 exclude-result-prefixes="doc n p i o part ph"
 >

<xsl:include href="part_header.xsl"/>
<xsl:include href="part_text.xsl"/>
<xsl:include href="part_html.xsl"/>
<xsl:include href="part_horizontalrule.xsl"/>
<xsl:include href="part_image.xsl"/>
<xsl:include href="part_listing.xsl"/>
<xsl:include href="part_news.xsl"/>
<xsl:include href="part_person.xsl"/>
<xsl:include href="part_richtext.xsl"/>
<xsl:include href="part_imagegallery.xsl"/>
<xsl:include href="part_mailinglist.xsl"/>
<xsl:include href="part_file.xsl"/>
<xsl:include href="part_list.xsl"/>
<xsl:include href="part_formula.xsl"/>

<xsl:template match="doc:content">
<div class="document">
	<xsl:apply-templates/>
	<xsl:comment/>
</div>
</xsl:template>

<xsl:template match="doc:row">
<table width="100%">
<tr>
<xsl:apply-templates/>
</tr>
</table>
</xsl:template>

<xsl:template match="doc:row[count(doc:column)=1]">
<div class="document_row">
	<div class="document_column">
		<xsl:apply-templates select="doc:column/doc:section"/>
		<xsl:comment/>
	</div>
</div>
</xsl:template>

<xsl:template match="doc:column">
<td valign="top">
<xsl:attribute name="class">
<xsl:text>DocumentColumn document_column</xsl:text>
<xsl:if test="position()=1"> DocumentColumnFirst document_column_first</xsl:if>
</xsl:attribute>
<xsl:choose>
<xsl:when test="@width='min'">
<xsl:attribute name="style">width: 1%;</xsl:attribute>
</xsl:when>
<xsl:when test="@width='max'">
<xsl:attribute name="style">width: 100%;</xsl:attribute>
</xsl:when>
<xsl:when test="contains(@width,'%')">
<xsl:attribute name="style">width: <xsl:value-of select="@width"/>;</xsl:attribute>
</xsl:when>
<xsl:when test="@width">
<xsl:attribute name="style">width: <xsl:value-of select="@width"/>px;</xsl:attribute>
</xsl:when>
</xsl:choose>
<xsl:apply-templates/>
</td>
</xsl:template>

<xsl:template match="doc:section">
<xsl:variable name="style">
<xsl:if test="@left"> padding-left: <xsl:value-of select="@left"/>;</xsl:if>
<xsl:if test="@right"> padding-right: <xsl:value-of select="@right"/>;</xsl:if>
<xsl:if test="@top"> padding-top: <xsl:value-of select="@top"/>;</xsl:if>
<xsl:if test="@bottom"> padding-bottom: <xsl:value-of select="@bottom"/>;</xsl:if>
<xsl:if test="@float"> float: <xsl:value-of select="@float"/>;</xsl:if>
<xsl:if test="@width"> width: <xsl:value-of select="@width"/>;</xsl:if>
</xsl:variable>
<div style="{$style}">
<xsl:if test="$preview='true' and part:part">
<xsl:attribute name="id"><xsl:text>part-</xsl:text><xsl:value-of select="part:part/@id"/></xsl:attribute>
</xsl:if>
<xsl:attribute name="class">
<xsl:choose>
<xsl:when test="part:part">part_section part_section_<xsl:value-of select="part:part/@type"/>
<!-- Hack to make headers margins work -->
<xsl:if test="part:part/@type='header'"> part_section_header_<xsl:value-of select="part:part/part:sub/ph:header/@level"/></xsl:if>
</xsl:when>
</xsl:choose>
</xsl:attribute>
<xsl:apply-templates/>
<xsl:comment/>
</div>
</xsl:template>


<!--          Part            -->

<xsl:template match="part:part">
<xsl:apply-templates select="part:sub/*"/>
</xsl:template>

<xsl:template match="doc:text">
<xsl:attribute name="class">section-text</xsl:attribute>
<xsl:variable name="style">
<xsl:if test="@font-size">font-size: <xsl:value-of select="@font-size"/>;</xsl:if>
<xsl:if test="@font-family">font-family: <xsl:value-of select="@font-family"/>;</xsl:if>
<xsl:if test="@text-align">text-align: <xsl:value-of select="@text-align"/>;</xsl:if>
<xsl:if test="@line-height"> line-height: <xsl:value-of select="@line-height"/>;</xsl:if>
<xsl:if test="@font-weight"> font-weight: <xsl:value-of select="@font-weight"/>;</xsl:if>
<xsl:if test="@color"> color: <xsl:value-of select="@color"/>;</xsl:if>
<xsl:if test="@font-style"> font-style: <xsl:value-of select="@font-style"/>;</xsl:if>
<xsl:if test="@word-spacing"> word-spacing: <xsl:value-of select="@word-spacing"/>;</xsl:if>
<xsl:if test="@letter-spacing"> letter-spacing: <xsl:value-of select="@letter-spacing"/>;</xsl:if>
<xsl:if test="@text-decoration"> text-decoration: <xsl:value-of select="@text-decoration"/>;</xsl:if>
<xsl:if test="@text-indent"> text-indent: <xsl:value-of select="@text-indent"/>;</xsl:if>
<xsl:if test="@text-transform"> text-transform: <xsl:value-of select="@text-transform"/>;</xsl:if>
<xsl:if test="@font-variant"> font-variant: <xsl:value-of select="@font-variant"/>;</xsl:if>
</xsl:variable>
<div style="{$style}" class="DocumentText">
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="doc:text/doc:image">
<img src="{$path}images/{o:object/o:sub/i:image/i:filename}" width="{o:object/o:sub/i:image/i:width}" height="{o:object/o:sub/i:image/i:height}" style="border-width: 0px;float: {@float};" class="DocumentTextImage DocumentTextImage-{@float}" alt=""/>
</xsl:template>

<xsl:template match="doc:header">
<xsl:attribute name="class">section-header<xsl:value-of select="@level"/></xsl:attribute>
<xsl:variable name="style">
<xsl:if test="@font-size">font-size: <xsl:value-of select="@font-size"/>;</xsl:if>
<xsl:if test="@font-family">font-family: <xsl:value-of select="@font-family"/>;</xsl:if>
<xsl:if test="@text-align">text-align: <xsl:value-of select="@text-align"/>;</xsl:if>
<xsl:if test="@line-height"> line-height: <xsl:value-of select="@line-height"/>;</xsl:if>
<xsl:if test="@font-weight"> font-weight: <xsl:value-of select="@font-weight"/>;</xsl:if>
<xsl:if test="@color"> color: <xsl:value-of select="@color"/>;</xsl:if>
<xsl:if test="@font-style"> font-style: <xsl:value-of select="@font-style"/>;</xsl:if>
<xsl:if test="@word-spacing"> word-spacing: <xsl:value-of select="@word-spacing"/>;</xsl:if>
<xsl:if test="@letter-spacing"> letter-spacing: <xsl:value-of select="@letter-spacing"/>;</xsl:if>
<xsl:if test="@text-decoration"> text-decoration: <xsl:value-of select="@text-decoration"/>;</xsl:if>
<xsl:if test="@text-indent"> text-indent: <xsl:value-of select="@text-indent"/>;</xsl:if>
<xsl:if test="@text-transform"> text-transform: <xsl:value-of select="@text-transform"/>;</xsl:if>
<xsl:if test="@font-variant"> font-variant: <xsl:value-of select="@font-variant"/>;</xsl:if>
</xsl:variable>
<xsl:element name="h{@level}">
<xsl:attribute name="style"><xsl:value-of select="$style"/></xsl:attribute>
<xsl:attribute name="class">DocumentHeader</xsl:attribute>
<xsl:apply-templates/>
</xsl:element>
</xsl:template>

<xsl:template match="doc:list">
<xsl:attribute name="class">section-list</xsl:attribute>
<xsl:variable name="style">
<xsl:if test="@font-size">font-size: <xsl:value-of select="@font-size"/>;</xsl:if>
<xsl:if test="@font-family">font-family: <xsl:value-of select="@font-family"/>;</xsl:if>
<xsl:if test="@text-align">text-align: <xsl:value-of select="@text-align"/>;</xsl:if>
<xsl:if test="@line-height"> line-height: <xsl:value-of select="@line-height"/>;</xsl:if>
<xsl:if test="@font-weight"> font-weight: <xsl:value-of select="@font-weight"/>;</xsl:if>
<xsl:if test="@color"> color: <xsl:value-of select="@color"/>;</xsl:if>
<xsl:if test="@font-style"> font-style: <xsl:value-of select="@font-style"/>;</xsl:if>
<xsl:if test="@word-spacing"> word-spacing: <xsl:value-of select="@word-spacing"/>;</xsl:if>
<xsl:if test="@letter-spacing"> letter-spacing: <xsl:value-of select="@letter-spacing"/>;</xsl:if>
<xsl:if test="@text-decoration"> text-decoration: <xsl:value-of select="@text-decoration"/>;</xsl:if>
<xsl:if test="@text-indent"> text-indent: <xsl:value-of select="@text-indent"/>;</xsl:if>
<xsl:if test="@text-transform"> text-transform: <xsl:value-of select="@text-transform"/>;</xsl:if>
<xsl:if test="@font-variant"> font-variant: <xsl:value-of select="@font-variant"/>;</xsl:if>
</xsl:variable>
<xsl:choose>
<xsl:when test="@type='disc' or @type='square' or @type='circle'">
<ul style="{$style}" type="{@type}" class="DocumentList">
<xsl:apply-templates/>
</ul>
</xsl:when>
<xsl:otherwise>
<ol style="{$style}" type="{@type}" class="DocumentList">
<xsl:apply-templates/>
</ol>
</xsl:otherwise>
</xsl:choose>
</xsl:template>

<xsl:template match="doc:list/doc:item">
<li class="DocumentList"><xsl:apply-templates/></li>
</xsl:template>

<xsl:template match="doc:list/doc:item/doc:first">
<span class="DocumentList"><xsl:apply-templates/></span>
</xsl:template>

<xsl:template match="doc:break">
<br/>
</xsl:template>

<xsl:template match="doc:image">
<xsl:attribute name="class">section-image</xsl:attribute>
<img src="{$path}images/{o:object/o:sub/i:image/i:filename}" width="{o:object/o:sub/i:image/i:width}" height="{o:object/o:sub/i:image/i:height}" style="border-width: 0px;" alt=""/>
</xsl:template>

<xsl:template match="doc:image[@align]">
<xsl:attribute name="class">section-image</xsl:attribute>
<div style="text-align: {@align}">
<img src="{$path}images/{o:object/o:sub/i:image/i:filename}" width="{o:object/o:sub/i:image/i:width}" height="{o:object/o:sub/i:image/i:height}" style="border-width: 0px;" alt=""/>
</div>
</xsl:template>

<xsl:template match="doc:link">
<a class="common DocumentLink">
<xsl:call-template name="link"/>
<xsl:apply-templates/>
</a>
</xsl:template>

<xsl:template match="doc:strong">
<strong>
<xsl:apply-templates/>
</strong>
</xsl:template>

<xsl:template match="doc:em">
<em>
<xsl:apply-templates/>
</em>
</xsl:template>

<xsl:template match="doc:del">
<del>
<xsl:apply-templates/>
</del>
</xsl:template>

<xsl:template match="doc:newsblock">
<xsl:attribute name="class">section-news</xsl:attribute>
<div class="DocumentNewsBlock">
<xsl:if test="@title">
<div class="DocumentNewsBlockTitle">
<xsl:value-of select="@title"/>
</div>
</xsl:if>
<xsl:apply-templates/>
</div>
</xsl:template>


<xsl:template match="doc:newsblock/o:object">
<div class="DocumentNews DocumentNews{(position() mod 2)+1}">
<div class="DocumentNewsTitle">
<xsl:value-of select="o:title"/>
</div>
<div class="DocumentNewsDescription">
<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
<xsl:apply-templates select="o:note"/>
</div>
<xsl:if test="o:links">
<div class="DocumentNewsLinks">
<xsl:apply-templates select="o:links/o:link"/>
</div>
</xsl:if>
</div>
</xsl:template>

<xsl:template match="doc:newsblock//o:break">
<br/>
</xsl:template>

<xsl:template match="doc:newsblock//n:startdate">
<span class="DocumentNewsDate"><xsl:value-of select="number(@day)"/>/<xsl:value-of select="number(@month)"/>/<xsl:value-of select="substring(@year,3,2)"/><xsl:text>: </xsl:text></span>
</xsl:template>

<xsl:template match="doc:newsblock//o:link">
<xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
<a title="{@alternative}" class="common DocumentNewsLink">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="doc:personblock">
<xsl:if test="doc:display/@align">
<xsl:attribute name="align"><xsl:value-of select="doc:display/@align"/></xsl:attribute>
</xsl:if>
<xsl:apply-templates select="o:object/o:sub/p:person"/>
</xsl:template>

<xsl:template match="doc:personblock//p:person">
<table width="100%"><tr>
<xsl:if test="../../../doc:display/@image='true'">
<xsl:apply-templates select="p:image"/>
</xsl:if>
<td valign="top">
<div class="DocumentPerson DocumentPersonName">
	<xsl:if test="../../../doc:display/@firstname='true'">
		<xsl:value-of select="p:firstname"/>
	</xsl:if>
	<xsl:text> </xsl:text>
	<xsl:if test="../../../doc:display/@middlename='true'">
		<xsl:value-of select="p:middlename"/>
	</xsl:if>
	<xsl:text> </xsl:text>
	<xsl:if test="../../../doc:display/@surname='true'">
		<xsl:value-of select="p:surname"/>
	</xsl:if>
</div>
<xsl:if test="(../../../doc:display/@initials='true' and p:initials!='') or (../../../doc:display/@nickname='true' and p:nickname!='')">
<div class="DocumentPerson"><xsl:value-of select="p:initials"/><xsl:if test="(../../../doc:display/@initials='true' and p:initials!='') and (../../../doc:display/@nickname='true' and p:nickname!='')">/</xsl:if><xsl:value-of select="p:nickname"/></div>
</xsl:if>
<xsl:if test="../../../doc:display/@jobtitle='true' and p:jobtitle!=''"><div class="DocumentPerson"><xsl:value-of select="p:jobtitle"/></div></xsl:if>
<xsl:if test="../../../doc:display/@sex='true'">
<div class="DocumentPerson"><span class="DocumentPersonLabel">K&#248;n: </span>
<xsl:choose>
	<xsl:when test="p:sex='male'">Mand</xsl:when>
	<xsl:otherwise>Kvinde</xsl:otherwise>
</xsl:choose>
</div>
</xsl:if>
<xsl:if test="(../../../doc:display/@streetname='true' and p:streetname!='') or (../../../doc:display/@zipcode='true' and p:zipcode!='') or (../../../doc:display/@city='true' and p:city!='') or (../../../doc:display/@country='true' and p:country!='')">
<div class="DocumentPerson DocumentPersonAddress">
<xsl:if test="../../../doc:display/@streetname='true' and p:streetname!=''"><xsl:value-of select="p:streetname"/><br/></xsl:if>
<xsl:if test="../../../doc:display/@zipcode='true' or ../../../doc:display/@city='true' or ../../../doc:display/@country='true'">
<xsl:value-of select="p:zipcode"/><xsl:text> </xsl:text><xsl:value-of select="p:city"/><xsl:text> </xsl:text><xsl:value-of select="p:country"/>
</xsl:if>
</div>
</xsl:if>
<xsl:if test="../../../doc:display/@phone_private='true' and p:phone[@context='private']!=''">
<div class="DocumentPerson"><span class="DocumentPersonLabel">Tlf.: </span>
<xsl:value-of select="p:phone[@context='private']"/>
<span class="DocumentPersonExtra"> (privat)</span>
</div>
</xsl:if>
<xsl:if test="../../../doc:display/@phone_job='true' and p:phone[@context='job']!=''">
<div class="DocumentPerson">
<span class="DocumentPersonLabel">Tlf.: </span>
<xsl:value-of select="p:phone[@context='job']"/>
<span class="DocumentPersonExtra"> (arbejde)</span>
</div>
</xsl:if>
<xsl:if test="../../../doc:display/@email_private='true' and p:email[@context='private']!=''">
<div class="DocumentPerson">
<span class="DocumentPersonLabel">E-mail: </span>
<a class="common" href="mailto:{p:email[@context='private']}"><xsl:value-of select="p:email[@context='private']"/></a>
<span class="DocumentPersonExtra"> (privat)</span>
</div>
</xsl:if>
<xsl:if test="../../../doc:display/@email_job='true' and p:email[@context='job']!=''">
<div class="DocumentPerson">
<span class="DocumentPersonLabel">E-mail: </span>
<a class="common" href="mailto:{p:email[@context='job']}"><xsl:value-of select="p:email[@context='job']"/></a>
<span class="DocumentPersonExtra"> (arbejde)</span>
</div>
</xsl:if>
<xsl:if test="../../../doc:display/@webaddress='true' and p:webaddress!=''">
<div class="DocumentPerson">
<span class="DocumentPersonLabel">Web: </span>
<a class="common" href="{p:webaddress}"><xsl:value-of select="p:webaddress"/></a>
</div>
</xsl:if>
</td></tr></table>
</xsl:template>

<xsl:template match="doc:personblock//p:image">
<td valign="top"><img src="{$path}images/128/{o:object/o:sub/i:image/i:filename}.png" alt="" class="DocumentPerson"/></td>
</xsl:template>



<xsl:template match="doc:divider">
<xsl:attribute name="class">section-divider</xsl:attribute>
<hr/>
</xsl:template>


<xsl:template match="doc:richtext">
<xsl:attribute name="class">section-richtext</xsl:attribute>
<xsl:value-of select="." disable-output-escaping="yes"/>
</xsl:template>

</xsl:stylesheet>