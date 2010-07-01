<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:ig="http://uri.in2isoft.com/onlinepublisher/publishing/imagegallery/1.0/"
 exclude-result-prefixes="ig"
 >

<xsl:template match="ig:content">
<xsl:apply-templates/>

<script language="JavaScript" type="text/javascript">
function showImage(url,title,width,height)
{
	var isSafari = (navigator.userAgent.indexOf('Safari')!=-1);
	if (isSafari) {
		width-=2;
		height+=2;
	}
        var popup=window.open('',title,'height='+height+',width='+width);
        if (window.focus) {popup.focus()}
	 var tmp = popup.document;
        tmp.write('<html><head><title>'+title+'</title>');
        tmp.write('</head><body style="margin: 0px;">');
        tmp.write('<img src="'+url+'" onclick="window.close()"/>');
        tmp.write('</body></html>');
        tmp.close();
        return true;
}
</script>
</xsl:template>

<xsl:template match="ig:title">
<h1><xsl:apply-templates/></h1>
</xsl:template>


<xsl:template match="ig:images">
<div><xsl:apply-templates/></div>
</xsl:template>

<xsl:template match="ig:image">
<xsl:variable name="width">
<xsl:if test="@width>=@height">
<xsl:text>128</xsl:text>
</xsl:if>
<xsl:if test="@height>@width">
<xsl:value-of select="round(number(@width) div number(@height)*128)"/>
</xsl:if>
</xsl:variable>
<xsl:variable name="height">
<xsl:if test="@height>=@width">
<xsl:text>128</xsl:text>
</xsl:if>
<xsl:if test="@width>@height">
<xsl:value-of select="round(number(@height) div number(@width)*128)"/>
</xsl:if>
</xsl:variable>
<table border="0" style="display: inline; float: left; background-color: #efefef; margin: 5px;">
<tr><td><div style="overflow: hidden; width: 140px; text-align: center; font-family: Tahoma; color: grey; font-size: 12px;"><xsl:value-of select="@title"/></div></td></tr>
<tr>
<td align="center" valign="middle" style="width: 140px; height: 140px;">
<a href="#" onclick="javascript: showImage('images/{@file}','{@title}',{@width},{@height});">
<img src="images/128/{@file}.png" width="{$width}" height="{$height}" border="0" style="border: solid 1px white;"/>
</a>
</td>
</tr>
</table>
</xsl:template>

</xsl:stylesheet>