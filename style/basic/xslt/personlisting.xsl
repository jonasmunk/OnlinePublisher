<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:pl="http://uri.in2isoft.com/onlinepublisher/publishing/personlisting/1.0/"
 xmlns:ps="http://uri.in2isoft.com/onlinepublisher/class/person/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 exclude-result-prefixes="o ps pl"
 >

<xsl:template match="pl:personlisting">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="pl:title">
<h1 class="common"><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="pl:text">
<p class="common"><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="pl:break | o:break | ps:break">
<br/>
</xsl:template>

<xsl:template match="pl:persons">
<table border="0" width="100%" class="Personlisting" cellspacing="0" cellpadding="0">
<xsl:apply-templates/>
</table>
</xsl:template>


<xsl:template match="o:object">
<tr>
<td width="140" align="center" valign="top" class="Personlisting">
<xsl:choose>
<xsl:when test="o:sub/ps:person/ps:image">
<image src="{$path}images/128/{o:sub/ps:person/ps:image/o:object/o:sub/i:image/i:filename}.png"/>
</xsl:when>
<xsl:otherwise>
<!--No image!-->
</xsl:otherwise>
</xsl:choose>
</td>
<td valign="top">
<xsl:if test="o:sub/ps:person/ps:image">
</xsl:if>
<div class="PersonlistingTitle"><xsl:value-of select="o:title"/></div>
<xsl:if test="o:sub/ps:person/ps:initials!='' or o:sub/ps:person/ps:nickname!=''">
<div class="PersonlistingProperty"><xsl:value-of select="o:sub/ps:person/ps:initials"/><xsl:if test="o:sub/ps:person/ps:initials!='' and o:sub/ps:person/ps:nickname!=''">/</xsl:if><xsl:value-of select="o:sub/ps:person/ps:nickname"/></div>
	</xsl:if>
<xsl:apply-templates select="o:sub/ps:person"/>
<xsl:apply-templates select="o:note"/>
</td>
</tr>
</xsl:template>


<xsl:template match="ps:person">
<xsl:if test="ps:jobtitle!=''"><div class="PersonlistingProperty"><xsl:value-of select="ps:jobtitle"/></div></xsl:if>
<xsl:if test="ps:streetname!='' or ps:zipcode!='' or ps:city!='' or ps:country!=''">
<div class="PersonlistingProperty">
<xsl:if test="ps:streetname!=''"><xsl:value-of select="ps:streetname"/><br/></xsl:if>
<xsl:value-of select="ps:zipcode"/><xsl:text> </xsl:text><xsl:value-of select="ps:city"/><xsl:text> </xsl:text><xsl:value-of select="ps:country"/>
</div>
</xsl:if>
<xsl:if test="ps:phone[@context='private']!=''">
<div class="PersonlistingProperty"><span class="PersonlistingPropertyLabel">Tlf.: </span>
<xsl:value-of select="ps:phone[@context='private']"/>
<span class="PersonlistingPropertyExtra"> (privat)</span>
</div>
</xsl:if>
<xsl:if test="ps:phone[@context='job']!=''">
<div class="PersonlistingProperty">
<span class="PersonlistingPropertyLabel">Tlf.: </span>
<xsl:value-of select="ps:phone[@context='job']"/>
<span class="PersonlistingPropertyExtra"> (arbejde)</span>
</div>
</xsl:if>
<xsl:if test="ps:email[@context='private']!=''">
<div class="PersonlistingProperty">
<span class="PersonlistingPropertyLabel">E-mail: </span>
<a href="mailto:{ps:email[@context='private']}" class="common"><xsl:value-of select="ps:email[@context='private']"/></a>
<span class="PersonlistingPropertyExtra"> (privat)</span>
</div>
</xsl:if>
<xsl:if test="ps:email[@context='job']!=''">
<div class="PersonlistingProperty">
<span class="PersonlistingPropertyLabel">E-mail: </span>
<a href="mailto:{ps:email[@context='job']}" class="common"><xsl:value-of select="ps:email[@context='job']"/></a>
<span class="PersonlistingPropertyExtra"> (arbejde)</span>
</div>
</xsl:if>
<xsl:if test="ps:webaddress!=''">
<div class="PersonlistingProperty">
<span class="PersonlistingPropertyLabel">Web: </span>
<a href="{ps:webaddress}" class="common"><xsl:value-of select="ps:webaddress"/></a>
</div>
</xsl:if>
</xsl:template>

</xsl:stylesheet>