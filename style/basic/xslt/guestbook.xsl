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
	<p id="newGuestbookLink">
		<a href="#" class="common" onclick="document.getElementById('newGuestbookItem').style.display='block';this.parentNode.style.display='none'; return false;">
			<span><xsl:value-of select="//i8n:text[@key='action-new']"/></span>
		</a>
	</p>
	<form accept-charset="UTF-8" action="{$page-path}" method="post" id="newGuestbookItem">
		<div><input type="hidden" name="userinteraction"/></div>
		<div>
			<label><xsl:value-of select="//i8n:text[@key='newitem-label-name']"/></label>
			<input type="text" class="text" name="name"/>
		</div>
		<div>
			<label><xsl:value-of select="//i8n:text[@key='newitem-label-text']"/></label>
			<textarea name="text" rows="6"><xsl:comment/></textarea>
		</div>
		<div>
			<input type="submit" value="{//i8n:text[@key='action-create']}" class="submit" onclick="this.form.userinteraction.value='true'"/>
			<xsl:text> </xsl:text>
			<input type="button" value="{//i8n:text[@key='action-cancel']}" class="submit" onclick="this.form.style.display='none'; document.getElementById('newGuestbookLink').style.display='block'"/>
		</div>
	</form>
	<xsl:if test="gb:item">
		<div class="guestbook_list">
			<xsl:apply-templates/>
		</div>
	</xsl:if>
</xsl:template>

<!--xsl:template match="gb:item">
<tr>
<td class="time"><xsl:value-of select="gb:time/@day"/>-<xsl:value-of select="gb:time/@month"/>-<xsl:value-of select="gb:time/@year"/><xsl:text> </xsl:text><xsl:value-of select="gb:time/@hour"/>:<xsl:value-of select="gb:time/@minute"/>:<xsl:value-of select="gb:time/@second"/></td>
<td class="name"><xsl:value-of select="gb:name"/></td>
<td class="text"><xsl:apply-templates select="gb:text"/></td>
</tr>
</xsl:template-->

<xsl:template match="gb:item">
	<div class="guestbook_item">
		<p>
			<strong><xsl:value-of select="gb:name"/></strong>
			<xsl:text> </xsl:text>
			<span class="guestbook_time">
				<xsl:value-of select="gb:time/@day"/>-<xsl:value-of select="gb:time/@month"/>-<xsl:value-of select="gb:time/@year"/><xsl:text> </xsl:text><xsl:value-of select="gb:time/@hour"/>:<xsl:value-of select="gb:time/@minute"/>:<xsl:value-of select="gb:time/@second"/>
			</span>
		</p>
		<p class="guestbook_text"><xsl:apply-templates select="gb:text"/></p>
	</div>
</xsl:template>

<xsl:template match="gb:newitem">

</xsl:template>


</xsl:stylesheet>