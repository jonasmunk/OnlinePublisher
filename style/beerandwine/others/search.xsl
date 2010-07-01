<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:s="http://uri.in2isoft.com/onlinepublisher/publishing/search/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 exclude-result-prefixes="s"
 >

<xsl:template match="s:content">
<h1 class="Search"><xsl:value-of select="s:title"/></h1>
<p class="Search"><xsl:apply-templates select="s:text"/></p>
<form method="get" action="." class="Search">
<input type="hidden" name="id" value="{/p:page/@id}"/>
<table id="SearchBox"><tr>
<td>
<input name="query" value="{s:parameters/s:query/.}" id="searchQuery" class="SearchField"/>
<input type="submit" value="{s:buttontitle/.}" class="SearchButton"/>
<xsl:call-template name="method"/>
</td><td>
<xsl:apply-templates select="s:types"/>
</td>
</tr></table>
</form>
<script>
document.getElementById('searchQuery').focus();
document.getElementById('searchQuery').select();
</script>
<xsl:apply-templates select="s:result"/>
</xsl:template>

<xsl:template match="s:types[s:type/@hidden='false']">
<fieldset class="SearchTypes">
<legend class="SearchTypes">Typer:</legend>
<xsl:apply-templates select="s:type"/>
</fieldset>
</xsl:template>

<xsl:template name="method">
<div class="SearchMethod">
<input type="radio" name="method" value="all" checked="checked">
<xsl:if test="not(s:parameters) or s:parameters/@method='all'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input> Alle ord
<input type="radio" name="method" value="some">
<xsl:if test="s:parameters/@method='some'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input> Nogle ord
<input type="radio" name="method" value="sentence">
<xsl:if test="s:parameters/@method='sentence'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input> Sætning
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
<input type="checkbox" name="{@unique}">
<xsl:if test="$selected='true'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input>
<span class="SearchTypes"><xsl:value-of select="@label"/></span>
</xsl:if>
<xsl:if test="@hidden='true'">
<input type="hidden" name="{@unique}" value="on"/>
</xsl:if>
</xsl:template>

<xsl:template match="s:result">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="s:result/s:pages">
<div class="SearchResultBox">
<div class="SearchResultHeader">
<xsl:value-of select="@count"/> side(r) fundet
</div>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="s:pages/s:page">
<div class="SearchResult">
<div class="SearchResultTitle">
<a href="?id={@id}" class="SearchResultTitle"><xsl:value-of select="s:title"/></a>
</div>
<div class="SearchResultDescription">
<xsl:apply-templates select="s:description"/>
</div>
<div class="SearchResultSummary">
<xsl:apply-templates select="s:summary"/>
</div>
</div>
</xsl:template>

<xsl:template match="s:page/s:summary">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="s:summary/s:highlight">
<strong class="SearchHighlight"><xsl:apply-templates/></strong>
</xsl:template>

<xsl:template match="s:result/s:images">
<div class="SearchResultBox">
<div class="SearchResultHeader">
<xsl:value-of select="@count"/> billede(r) fundet
</div>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="s:images/s:image">
<div class="SearchResult">
<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr>
<td width="128" align="center" valign="top" class="SearchResultPreview">
<image src="images/128/{@filename}.png"/>
</td>
<td valign="top">
<div class="SearchResultTitle">
<a href="images/{@filename}" class="SearchResultTitle"><xsl:value-of select="s:title"/></a>
<span class="SearchResultProps"><xsl:value-of select="@width"/>x<xsl:value-of select="@height"/>, <xsl:value-of select="@size"/></span>
</div>
<div class="SearchResultDescription">
<xsl:apply-templates select="s:description"/>
</div>
</td>
</tr>
</table>
</div>
</xsl:template>

<xsl:template match="s:image/s:description">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="s:break">
<br/>
</xsl:template>

</xsl:stylesheet>