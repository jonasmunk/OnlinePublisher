<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"

 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:gb="http://uri.in2isoft.com/onlinepublisher/publishing/guestbook/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:i8n="http://uri.in2isoft.com/onlinepublisher/publishing/internationalization/"
 exclude-result-prefixes="gb p i8n"
 >

<xsl:template match="i8n:text">
</xsl:template>

<xsl:template match="gb:guestbook">
<div class="guestbook">
<xsl:apply-templates/>
</div>
</xsl:template>


<xsl:template match="gb:title[.!='']">
<h1 class="common"><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="gb:guestbook/gb:text[.!='']">
<p class="common"><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="gb:break">
<br/>
</xsl:template>

<xsl:template match="gb:list">
<a href="{$page-path}newitem=true" class="common"><xsl:value-of select="//i8n:text[@key='action-new']"/></a>
<table width="100%" class="list">
<tr>
<th><xsl:value-of select="//i8n:text[@key='list-header-time']"/></th>
<th><xsl:value-of select="//i8n:text[@key='list-header-name']"/></th>
<th><xsl:value-of select="//i8n:text[@key='list-header-text']"/></th>
</tr>
<xsl:apply-templates/>
</table>
</xsl:template>

<xsl:template match="gb:item">
<tr>
<td class="time"><xsl:value-of select="gb:time/@day"/>-<xsl:value-of select="gb:time/@month"/>-<xsl:value-of select="gb:time/@year"/><xsl:text> </xsl:text><xsl:value-of select="gb:time/@hour"/>:<xsl:value-of select="gb:time/@minute"/>:<xsl:value-of select="gb:time/@second"/></td>
<td class="name"><xsl:value-of select="gb:name"/></td>
<td class="text"><xsl:apply-templates select="gb:text"/></td>
</tr>
</xsl:template>

<xsl:template match="gb:newitem">
<form accept-charset="UTF-8" action="{$page-path}" method="post" class="new_item">
<div><input type="hidden" name="userinteraction"/></div>
<table border="0">
<tr>
<th><xsl:value-of select="//i8n:text[@key='newitem-label-name']"/></th><td><input type="text" class="text" name="name"/></td>
</tr>
<tr>
<th><xsl:value-of select="//i8n:text[@key='newitem-label-text']"/></th><td><textarea type="text" name="text" rows="6"><xsl:comment/></textarea></td>
</tr>
<tr><td/><td class="buttons"><input type="button" value="{//i8n:text[@key='action-cancel']}" onclick="window.location='{$page-path}'" class="button"/><input type="submit" value="{//i8n:text[@key='action-create']}" class="submit" onclick="this.form.userinteraction.value='true'"/></td></tr>
</table>
</form>
</xsl:template>


</xsl:stylesheet>