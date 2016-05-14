<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:s="http://uri.in2isoft.com/onlinepublisher/publishing/search/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/class/file/1.0/"
 xmlns:ps="http://uri.in2isoft.com/onlinepublisher/class/person/1.0/"
 xmlns:pr="http://uri.in2isoft.com/onlinepublisher/class/product/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="s p o i f ps pr util"
 >

<xsl:template match="s:search">
    <div class="search">
        <h1 class="common"><xsl:value-of select="s:title"/></h1>
        <xsl:if test="s:text!=''">
        <p class="common"><xsl:apply-templates select="s:text"/></p>
        </xsl:if>
        <form method="get" action="." accept-charset="UTF-8">
        <input type="hidden" name="id" value="{/p:page/@id}"/>
        <input name="query" value="{s:parameters/s:query/.}" class="text"/>
        <input type="submit" value="{s:buttontitle/.}" class="submit"/>
        <xsl:call-template name="method"/>
        <xsl:apply-templates select="s:types"/>
        </form>
        <xsl:apply-templates select="s:results"/>
    </div>
    <script type="text/javascript" src="{$path}style/basic/js/templates/Search.js"><xsl:comment/></script>
</xsl:template>

<xsl:template match="s:break">
<br/>
</xsl:template>

<xsl:template name="method">
<div class="search_method">
<input type="radio" name="method" value="all" checked="checked" id="all">
<xsl:if test="not(s:parameters) or s:parameters/@method='all'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input> <label for="all">Alle ord</label>
<input type="radio" name="method" value="some" id="some">
<xsl:if test="s:parameters/@method='some'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input> <label for="some">Nogle ord</label>
<input type="radio" name="method" value="sentence" id="sentence">
<xsl:if test="s:parameters/@method='sentence'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input> <label for="sentence">Sætning</label>
</div>
</xsl:template>

<xsl:template match="s:types[s:type/@hidden='false']">
<div class="search_types">
<xsl:apply-templates select="s:type"/>
</div>
</xsl:template>

<xsl:template match="s:types[not(s:type/@hidden='false')]">
<xsl:apply-templates select="s:type"/>
</xsl:template>

<xsl:template match="s:type">
<xsl:variable name="unique">
<xsl:value-of select="@unique"/>
</xsl:variable>
<xsl:variable name="selected">
<xsl:choose>
<xsl:when test="../../s:parameters">
<xsl:value-of select="../../s:parameters/s:types/s:type[@unique=$unique]/@selected"/>
</xsl:when>
<xsl:otherwise><xsl:value-of select="@default"/></xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="@hidden!='true'">
<input type="checkbox" name="{@unique}" id="{@unique}">
<xsl:if test="$selected='true'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input>
<label for="{@unique}"><xsl:value-of select="@label"/></label>
</xsl:if>
<xsl:if test="@hidden='true'">
<input type="hidden" name="{@unique}" value="on"/>
</xsl:if>
</xsl:template>

<xsl:template match="s:results">
<xsl:apply-templates select="s:group[@count>0]"/>
<xsl:apply-templates select="s:group[@count=0]"/>
</xsl:template>

<xsl:template match="s:results/s:group">
	<xsl:variable name="type" select="@type"/>
	<div class="search_result_group">
		<xsl:if test="//s:type[@unique=$type]/@label!=''">
			<h2><xsl:value-of select="//s:type[@unique=$type]/@label"/></h2>
		</xsl:if>
		<xsl:call-template name="s:counter"/>
		<ol>
		<xsl:apply-templates/>
		</ol>
	</div>
</xsl:template>

<xsl:template match="s:page">
<li class="search_result_item">
<h3>
<a href="?id={@id}" class="common"><span><xsl:value-of select="s:title"/></span></a>
</h3>
<xsl:if test="not(s:description='')">
<p class="search_result_description"><xsl:text> </xsl:text>
<xsl:apply-templates select="s:description"/>
</p>
</xsl:if>
<xsl:if test="not(s:summary='')">
<xsl:apply-templates select="s:summary"/>
</xsl:if>
</li>
</xsl:template>

<xsl:template match="s:summary">
<p class="search_result_summary"><xsl:text> </xsl:text>
<xsl:apply-templates/>
</p>
</xsl:template>

<xsl:template match="s:summary/s:highlight">
<em class="search_result_highlight"><xsl:apply-templates/></em>
</xsl:template>


<xsl:template match="o:note">
<xsl:if test=".!=''">
<p class="search_result_note"><xsl:text> </xsl:text>
<xsl:apply-templates/>
</p>
</xsl:if>
</xsl:template>

<xsl:template match="o:break">
<br/>
</xsl:template>

<xsl:template name="s:counter">
<p class="search_result_count">

<xsl:choose>
<xsl:when test="@count=1">
1 emne fundet
</xsl:when>
<xsl:when test="@count>0">
<xsl:value-of select="@count"/> emner fundet
</xsl:when>
<xsl:otherwise>
Intet fundet
</xsl:otherwise>
</xsl:choose>
</p>
</xsl:template>

<xsl:template match="s:result//o:links">
<div class="search_result_links">
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="s:result//o:link">
<a class="common">
<xsl:call-template name="util:link"/>
<span><xsl:value-of select="@title"/></span>
</a>
</xsl:template>

<!--                   images                  -->

<xsl:template match="s:group[@type='image']/s:result">
<li class="search_result_item">
<image src="{$path}services/images/?id={o:object/@id}&amp;width=128&amp;height=128" border="0"/>
<h3>
<a href="{$path}images/{o:object/o:sub/i:image/i:filename}" class="common"><span><xsl:value-of select="substring(o:object/o:title,1,30)"/></span></a></h3>
<p class="search_result_metadata"><xsl:value-of select="o:object/o:sub/i:image/i:width"/>x<xsl:value-of select="o:object/o:sub/i:image/i:height"/>, <xsl:value-of select="o:object/o:sub/i:image/i:size"/> bytes</p>
<xsl:apply-templates select="o:object/o:note"/>
<xsl:apply-templates select="s:summary"/>
</li>
</xsl:template>


<!--                   files                    -->

<xsl:template match="s:group[@type='file']/s:result">
<li class="search_result_item">
<h3>
<a href="{$path}?file={o:object/@id}" class="common"><span><xsl:value-of select="o:object/o:title"/></span></a>
</h3>
<p><xsl:value-of select="o:object/o:sub/f:file/f:size"/> bytes</p>
<xsl:apply-templates select="o:object/o:note"/>
<xsl:apply-templates select="s:summary"/>
</li>
</xsl:template>



<!--                   persons                    -->

<xsl:template match="s:group[@type='person']/s:result">
<li class="search_result_item">
<xsl:if test="o:object/o:sub/ps:person/ps:image">
<img src="{$path}services/images/?id={o:object/o:sub/ps:person/ps:image/o:object/@id}&amp;width=128&amp;height=128"/>
</xsl:if>
<h3><xsl:value-of select="o:object/o:title"/></h3>
<xsl:if test="o:object/o:sub/ps:person/ps:initials!='' or o:object/o:sub/ps:person/ps:nickname!=''">
	<p class="search_result_property"><xsl:value-of select="o:object/o:sub/ps:person/ps:initials"/><xsl:if test="o:object/o:sub/ps:person/ps:initials!='' and o:object/o:sub/ps:person/ps:nickname!=''">/</xsl:if><xsl:value-of select="o:object/o:sub/ps:person/ps:nickname"/></p>
</xsl:if>
<xsl:apply-templates select="o:object/o:sub/ps:person"/>
<xsl:apply-templates select="o:object/o:note"/>
<xsl:apply-templates select="s:summary"/>
</li>
</xsl:template>


<xsl:template match="s:result//ps:person">
<xsl:if test="ps:jobtitle!=''"><p class="SearchResultProperty"><xsl:value-of select="ps:jobtitle"/></p></xsl:if>
<xsl:if test="ps:streetname!='' or ps:zipcode!='' or ps:city!='' or ps:country!=''">
<p class="search_result_property">
<xsl:if test="ps:streetname!=''"><xsl:value-of select="ps:streetname"/><br/></xsl:if>
<xsl:value-of select="ps:zipcode"/><xsl:text> </xsl:text><xsl:value-of select="ps:city"/><xsl:text> </xsl:text><xsl:value-of select="ps:country"/>
</p>
</xsl:if>
<xsl:if test="ps:phone[@context='private']!=''">
<p class="search_result_property"><span class="search_result_label">Tlf.: </span>
<xsl:value-of select="ps:phone[@context='private']"/>
<span class="search_result_context"> (privat)</span>
</p>
</xsl:if>
<xsl:if test="ps:phone[@context='job']!=''">
<p class="search_result_property">
<span class="search_result_label">Tlf.: </span>
<xsl:value-of select="ps:phone[@context='job']"/>
<span class="search_result_context"> (arbejde)</span>
</p>
</xsl:if>
<xsl:if test="ps:email[@context='private']!=''">
<p class="search_result_property">
<span class="search_result_label">E-mail: </span>
<a href="mailto:{ps:email[@context='private']}" class="common"><span><xsl:value-of select="ps:email[@context='private']"/></span></a>
<span class="search_result_context"> (privat)</span>
</p>
</xsl:if>
<xsl:if test="ps:email[@context='job']!=''">
<p class="search_result_property">
<span class="search_result_label">E-mail: </span>
<a href="mailto:{ps:email[@context='job']}" class="common"><span><xsl:value-of select="ps:email[@context='job']"/></span></a>
<span class="search_result_context"> (arbejde)</span>
</p>
</xsl:if>
<xsl:if test="ps:webaddress!=''">
<p class="search_result_property">
<span class="search_result_label">Web: </span>
<a href="{ps:webaddress}" class="common"><span><xsl:value-of select="ps:webaddress"/></span></a>
</p>
</xsl:if>
</xsl:template>

<!--                   news                    -->

<xsl:template match="s:group[@type='news']/s:result">
<li class="search_result_item">
<h3><xsl:value-of select="o:object/o:title"/></h3>
<xsl:apply-templates select="o:object/o:note"/>
<xsl:apply-templates select="s:summary"/>
<xsl:apply-templates select="o:object/o:links"/>
</li>
</xsl:template>

<!--                   products                    -->

<xsl:template match="s:group[@type='product']/s:result">
<li class="search_result_item">
<h3>
<xsl:value-of select="o:object/o:title"/>
 #<xsl:value-of select="o:object/o:sub/pr:product/pr:number"/>
</h3>
<xsl:apply-templates select="o:object/o:note"/>
<xsl:apply-templates select="s:summary"/>
</li>
</xsl:template>

</xsl:stylesheet>