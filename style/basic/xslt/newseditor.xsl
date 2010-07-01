<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:ne="http://uri.in2isoft.com/onlinepublisher/publishing/newseditor/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 exclude-result-prefixes="ne"
 >

<xsl:template match="ne:newseditor">
<script type="text/javascript" src="http://yui.yahooapis.com/2.2.0/build/yahoo/yahoo-min.js"></script>
<script type="text/javascript">
	//alert(YAHOO);
</script>
<div class="NewsEditor">
<xsl:apply-templates/>
</div>
</xsl:template>


<xsl:template match="ne:message">
<div class="message">
<xsl:choose>
	<xsl:when test="@type='no-title'">Der er ikke angivet en titel.</xsl:when>
	<xsl:when test="@type='bad-startdate'">Startdatoen er ikke angivet korrekt.</xsl:when>
	<xsl:when test="@type='enddate-smaller-than-startdate'">Slutdatoen er mindre end startdatoen</xsl:when>
	<xsl:otherwise>Der skete en uventet fejl.</xsl:otherwise>
</xsl:choose>
</div>
</xsl:template>



<!--        List        -->

<xsl:template match="ne:list">
<h1 class="common">Liste over nyheder</h1>
	<a href="?id={//p:page/@id}&amp;action=new" class="common">Opret ny</a>
<table class="list">
	<thead>
		<tr>
			<th>Titel</th><th>Beskrivelse</th><th>Startdato</th><th>Slutdato</th>
		</tr>
	</thead>
	<tbody>
		<xsl:apply-templates/>
	</tbody>
</table>
</xsl:template>

<xsl:template match="ne:list/o:object[@type='news']">
<tr>
	<td><xsl:value-of select="substring(o:title,0,50)"/></td>
	<td><xsl:apply-templates select="o:note"/></td>
	<td>
		<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
	</td>
	<td><xsl:apply-templates select="o:sub/n:news/n:enddate"/></td>
</tr>
</xsl:template>


<xsl:template match="ne:list//n:startdate | ne:list//n:enddate">
x
</xsl:template>

<xsl:template match="ne:list/o:object[@type='news']/o:note/o:break">
<br/>
</xsl:template>



<!--        New        -->

<xsl:template match="ne:new">
<h1 class="common">Ny nyhed</h1>
<form accept-charset="UTF-8" method="post">
		<xsl:apply-templates select="ne:message"/>
<div>
<label>Titel:</label>
<input type="text" name="title" class="textfield" value="{ne:property[@name='title']}"/>
</div>
<div>
<label>Beskrivelse:</label>
<textarea name="note" rows="8" class="textfield"><xsl:comment></xsl:comment><xsl:value-of select="ne:property[@name='note']"/></textarea>
</div>
<div>
<label>Startdato:</label>
<input type="text" name="startdate" class="textfield" value="{ne:property[@name='startdate']}"/>
<div class="hint">Eksempel: 23/4/2004 eller 23-4-2004</div>
</div>
<div>
<label>Slutdato:</label>
<input type="text" name="enddate" class="textfield" value="{ne:property[@name='enddate']}"/>
<div class="hint">Eksempel: 25/4/2004 eller 25-4-2004</div>
</div>
<fieldset class="groups">
<legend>Grupper:</legend>
<xsl:apply-templates select="o:object[@type='newsgroup']"/>
</fieldset>
<div class="buttons">
<input type="button" value="Annuller" class="button" onclick="document.location='?id={//p:page/@id}'"/>
<input type="submit" value="Opret" class="button"/>
</div>
</form>
</xsl:template>

<xsl:template match="ne:new/o:object[@type='newsgroup']">
<span><input type="checkbox" class="checkbox" name="group[]" value="{@id}" id="{generate-id()}">
<xsl:if test="../ne:property[@name='groups']/ne:value/.=@id">
	<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input><label for="{generate-id()}"><xsl:value-of select="o:title"/></label></span>
</xsl:template>

</xsl:stylesheet>