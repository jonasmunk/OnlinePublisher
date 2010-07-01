<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:pp="http://uri.in2isoft.com/onlinepublisher/part/person/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/class/person/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 exclude-result-prefixes="pp p o i"
 >

<xsl:template match="pp:person">
	<xsl:apply-templates select="o:object/o:sub/p:person"/>
</xsl:template>

<xsl:template match="pp:person//p:person">
<table width="100%" border="0" style="width: auto;">
	<xsl:if test="../../../pp:style/@align">
		<xsl:attribute name="align"><xsl:value-of select="../../../pp:style/@align"/></xsl:attribute>
	</xsl:if>
	<tr>
<xsl:if test="../../../pp:display/@image='true'">
<xsl:apply-templates select="p:image"/>
</xsl:if>
<td valign="top">
<div class="PartPerson PartPersonName">
	<xsl:if test="../../../pp:display/@firstname='true'">
		<xsl:value-of select="p:firstname"/>
	</xsl:if>
	<xsl:text> </xsl:text>
	<xsl:if test="../../../pp:display/@middlename='true'">
		<xsl:value-of select="p:middlename"/>
	</xsl:if>
	<xsl:text> </xsl:text>
	<xsl:if test="../../../pp:display/@surname='true'">
		<xsl:value-of select="p:surname"/>
	</xsl:if>
</div>
<xsl:if test="(../../../pp:display/@initials='true' and p:initials!='') or (../../../pp:display/@nickname='true' and p:nickname!='')">
<div class="PartPerson"><xsl:value-of select="p:initials"/><xsl:if test="(../../../pp:display/@initials='true' and p:initials!='') and (../../../pp:display/@nickname='true' and p:nickname!='')">/</xsl:if><xsl:value-of select="p:nickname"/></div>
</xsl:if>
<xsl:if test="../../../pp:display/@jobtitle='true' and p:jobtitle!=''"><div class="PartPerson"><xsl:value-of select="p:jobtitle"/></div></xsl:if>
<xsl:if test="../../../pp:display/@sex='true'">
<div class="PartPerson"><span class="PartPersonLabel">K&#248;n: </span>
<xsl:choose>
	<xsl:when test="p:sex='male'">Mand</xsl:when>
	<xsl:otherwise>Kvinde</xsl:otherwise>
</xsl:choose>
</div>
</xsl:if>
<xsl:if test="(../../../pp:display/@streetname='true' and p:streetname!='') or (../../../pp:display/@zipcode='true' and p:zipcode!='') or (../../../pp:display/@city='true' and p:city!='') or (../../../pp:display/@country='true' and p:country!='')">
<div class="PartPerson PartPersonAddress">
<xsl:if test="../../../pp:display/@streetname='true' and p:streetname!=''"><xsl:value-of select="p:streetname"/><br/></xsl:if>
<xsl:if test="../../../pp:display/@zipcode='true' or ../../../pp:display/@city='true' or ../../../pp:display/@country='true'">
<xsl:value-of select="p:zipcode"/><xsl:text> </xsl:text><xsl:value-of select="p:city"/><xsl:text> </xsl:text><xsl:value-of select="p:country"/>
</xsl:if>
</div>
</xsl:if>
<xsl:if test="../../../pp:display/@phone_private='true' and p:phone[@context='private']!=''">
<div class="PartPerson"><span class="PartPersonLabel">Tlf.: </span>
<xsl:value-of select="p:phone[@context='private']"/>
<span class="PartPersonExtra"> (privat)</span>
</div>
</xsl:if>
<xsl:if test="../../../pp:display/@phone_job='true' and p:phone[@context='job']!=''">
<div class="PartPerson">
<span class="PartPersonLabel">Tlf.: </span>
<xsl:value-of select="p:phone[@context='job']"/>
<span class="PartPersonExtra"> (arbejde)</span>
</div>
</xsl:if>
<xsl:if test="../../../pp:display/@email_private='true' and p:email[@context='private']!=''">
<div class="PartPerson">
<span class="PartPersonLabel">E-mail: </span>
<a class="common" href="mailto:{p:email[@context='private']}"><xsl:value-of select="p:email[@context='private']"/></a>
<span class="PartPersonExtra"> (privat)</span>
</div>
</xsl:if>
<xsl:if test="../../../pp:display/@email_job='true' and p:email[@context='job']!=''">
<div class="PartPerson">
<span class="PartPersonLabel">E-mail: </span>
<a class="common" href="mailto:{p:email[@context='job']}"><xsl:value-of select="p:email[@context='job']"/></a>
<span class="PartPersonExtra"> (arbejde)</span>
</div>
</xsl:if>
<xsl:if test="../../../pp:display/@webaddress='true' and p:webaddress!=''">
<div class="PartPerson">
<span class="PartPersonLabel">Web: </span>
<a class="common" href="{p:webaddress}"><xsl:value-of select="p:webaddress"/></a>
</div>
</xsl:if>
</td></tr></table>
</xsl:template>

<xsl:template match="p:image">
<td style="vertical-align: top;"><a href="{$path}util/images/?id={o:object/@id}"><img src="{$path}util/images/?id={o:object/@id}&amp;width=60" alt="" id="{generate-id(o:object)}" class="PartPerson"/></a><script type="text/javascript">
try {
	new N2i.InlineImage('<xsl:value-of select="generate-id(o:object)"/>','<xsl:value-of select="$path"/>',<xsl:value-of select="o:object/@id"/>,<xsl:value-of select="o:object/o:sub/i:image/i:width"/>,<xsl:value-of select="o:object/o:sub/i:image/i:height"/>);
} catch (ignore) {}
</script></td>
</xsl:template>

</xsl:stylesheet>