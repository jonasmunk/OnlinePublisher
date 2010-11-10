<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:c="http://uri.in2isoft.com/onlinepublisher/publishing/calendar/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 exclude-result-prefixes="c p"
 >

<xsl:template match="c:calendar">
<div class="calendar">
<script type="text/javascript" src="{$path}style/basic/js/templates/Calendar.js"><xsl:text> </xsl:text></script>
<a name="calendar"><xsl:comment/></a>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template name="c:views">
<div class="views">
<a href="?id={//p:page/@id}&amp;date={//c:state/c:date/@year}{//c:state/c:date/@month}{//c:state/c:date/@day}&amp;view=list" class="common"><span>Liste</span></a> &#183; 
<a href="?id={//p:page/@id}&amp;date={//c:state/c:date/@year}{//c:state/c:date/@month}{//c:state/c:date/@day}&amp;view=week" class="common"><span>Uge</span></a> &#183; 
<a href="?id={//p:page/@id}&amp;date={//c:state/c:date/@year}{//c:state/c:date/@month}{//c:state/c:date/@day}&amp;view=month" class="common"><span>Måned</span></a> &#183; 
<a href="?id={//p:page/@id}&amp;date={//c:state/c:date/@year}{//c:state/c:date/@month}{//c:state/c:date/@day}&amp;view=agenda" class="common"><span>Agenda</span></a>
</div>
</xsl:template>

<xsl:template match="c:weekview">
	<div class="weekview">
	<div class="navigation">
	<xsl:call-template name="c:views"/>
	<div class="jumps">
	<a href="?id={//p:page/@id}&amp;date={../c:state/c:previous/@year}{../c:state/c:previous/@month}{../c:state/c:previous/@day}&amp;view=week" class="common"><span>Forrige uge</span></a>
	 &#183; <a href="?id={//p:page/@id}&amp;date={../c:state/c:today/@year}{../c:state/c:today/@month}{../c:state/c:today/@day}&amp;view=week" class="common"><span>Idag</span></a>
	 &#183; <a href="?id={//p:page/@id}&amp;date={../c:state/c:next/@year}{../c:state/c:next/@month}{../c:state/c:next/@day}&amp;view=week" class="common"><span>Naeste uge</span></a>
	</div>
	</div>
	<table cellspacing="0" cellpadding="0" class="calendar_weekview">
	<thead>
		<tr>
		<td></td>
		<xsl:for-each select="c:day">
		<th>
		<xsl:if test="@date=concat(../../c:state/c:date/@year,../../c:state/c:date/@month,../../c:state/c:date/@day)">
		<xsl:attribute name="class">selected</xsl:attribute>
		</xsl:if>
		<a href="?id={//p:page/@id}&amp;date={@date}"><span><xsl:value-of select="@title"/></span></a></th>
		</xsl:for-each>
		</tr>
	</thead>
	<tbody>
	<tr>
	<td class="time">
		<xsl:attribute name="style">height: <xsl:value-of select="(count(c:hour)-1)*45+1"/>px;</xsl:attribute>
		<xsl:for-each select="c:hour">
			<div>
			<xsl:attribute name="style">margin-top: <xsl:value-of select="(position()-1)*45-6"/>px;</xsl:attribute>
			<xsl:if test="number(@value)&lt;10">0</xsl:if>
			<xsl:value-of select="@value"/>:00</div>
		</xsl:for-each>
	</td>
		<xsl:apply-templates select="c:day">
			<xsl:with-param name="height"><xsl:value-of select="(count(c:hour)-1)*45+1"/></xsl:with-param>
		</xsl:apply-templates>
	</tr>
	</tbody>
	</table>
	</div>
</xsl:template>


<xsl:template match="c:weekview/c:day">
	<xsl:param name="height"/>
<td>
<xsl:attribute name="class">day <xsl:if test="@selected='true'">selected</xsl:if></xsl:attribute>
	<div style="height: {$height}px; position: relative;">
		<xsl:apply-templates select="c:event"/>
	</div>
</td>
</xsl:template>

<xsl:template match="c:weekview/c:day/c:event">
<div class="event event{@collision-number}-{@collision-count}">
<xsl:attribute name="style">margin-top: <xsl:value-of select="number(@top)*45*(count(../../c:hour)-1)"/>px; height: <xsl:value-of select="number(@height)*45*(count(../../c:hour)-1)-1"/>px;</xsl:attribute>
<div class="title"><xsl:value-of select="@time-from"/></div>
<div class="body"><xsl:value-of select="c:summary"/> <span class="calendar_title"><xsl:text> - </xsl:text><xsl:value-of select="c:calendar"/></span></div>
</div>
</xsl:template>

<!-- Month view -->

<xsl:template match="c:monthview">
<div class="monthview">
<div class="navigation">
<xsl:call-template name="c:views"/>
<div class="jumps">
<a href="?id={//p:page/@id}&amp;date={../c:state/c:previous/@year}{../c:state/c:previous/@month}{../c:state/c:previous/@day}&amp;view=month" class="common"><span>Forrige måned</span></a>
 &#183; <a href="?id={//p:page/@id}&amp;date={../c:state/c:today/@year}{../c:state/c:today/@month}{../c:state/c:today/@day}&amp;view=month#calendar" class="common"><span>Idag</span></a>
 &#183; <a href="?id={//p:page/@id}&amp;date={../c:state/c:next/@year}{../c:state/c:next/@month}{../c:state/c:next/@day}&amp;view=month" class="common"><span>Næste måned</span></a>
</div>
</div>
<table cellspacing="0" cellpadding="0">
<tr>
	<th>Mandag</th>
	<th>Tirsdag</th>
	<th>Onsdag</th>
	<th>Torsdag</th>
	<th>Fredag</th>
	<th>Lørdag</th>
	<th>Søndag</th>
</tr>
<xsl:apply-templates/>
</table>
</div>
</xsl:template>

<xsl:template name="c:getMonth">
	<xsl:param name="month"/>
	<xsl:choose>
		<xsl:when test="$month=1">Januar</xsl:when>
		<xsl:when test="$month=2">Februar</xsl:when>
		<xsl:when test="$month=3">Marts</xsl:when>
		<xsl:when test="$month=4">April</xsl:when>
		<xsl:when test="$month=5">Maj</xsl:when>
		<xsl:when test="$month=6">Juni</xsl:when>
		<xsl:when test="$month=7">Juli</xsl:when>
		<xsl:when test="$month=8">August</xsl:when>
		<xsl:when test="$month=9">September</xsl:when>
		<xsl:when test="$month=10">Oktober</xsl:when>
		<xsl:when test="$month=11">Novemver</xsl:when>
		<xsl:when test="$month=12">December</xsl:when>
		<xsl:otherwise><xsl:value-of select="$month"/></xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template match="c:monthview/c:week">
<tr>
<xsl:apply-templates/>
</tr>
</xsl:template>


<xsl:template match="c:monthview/c:week/c:day">
<td>
<xsl:attribute name="class">day <xsl:if test="@selected='true'">selected</xsl:if></xsl:attribute>
<a href="?id={//p:page/@id}&amp;date={@date}&amp;view=month" class="monthday"><span><xsl:value-of select="c:date/@day"/></span></a>
<xsl:apply-templates select="c:event"/>
</td>
</xsl:template>


<xsl:template match="c:monthview//c:event">
<div>
	<xsl:attribute name="class"><xsl:text>event</xsl:text><xsl:if test="number(@collision-count)>0"><xsl:text> collision</xsl:text></xsl:if></xsl:attribute>
	<span class="time"><xsl:value-of select="@time-from"/>: </span>
	<xsl:value-of select="c:summary"/>
	<span class="calendar_title"><xsl:text> - </xsl:text><xsl:value-of select="c:calendar"/></span>
</div>
</xsl:template>

<!--               List view                -->

<xsl:template match="c:listview">
<div class="listview">
<div class="navigation">
<xsl:call-template name="c:views"/>
<div class="jumps">
<a href="?id={//p:page/@id}&amp;date={../c:state/c:previous/@year}{../c:state/c:previous/@month}{../c:state/c:previous/@day}&amp;view=list" class="common"><span>Forrige måned</span></a>
 &#183; <a href="?id={//p:page/@id}&amp;date={../c:state/c:today/@year}{../c:state/c:today/@month}{../c:state/c:today/@day}&amp;view=list#selected" class="common"><span>Idag</span></a>
 &#183; <a href="?id={//p:page/@id}&amp;date={../c:state/c:next/@year}{../c:state/c:next/@month}{../c:state/c:next/@day}&amp;view=list" class="common"><span>Næste måned</span></a>
</div>
</div>
<table cellspacing="0" cellpadding="0">
<xsl:apply-templates/>
</table>
</div>
</xsl:template>

<xsl:template match="c:listview/c:day">
<tr>
<xsl:attribute name="class">
<xsl:if test="@selected='true'">selected</xsl:if>
<xsl:if test="@today='true'"> today</xsl:if>
</xsl:attribute>
	<th><a href="?id={//p:page/@id}&amp;date={@date}&amp;view=list#calendar">
		<xsl:if test="@selected='true'"><xsl:attribute name="name">selected</xsl:attribute></xsl:if>
		<xsl:value-of select="@title"/>
		</a>
	</th>
	<td><xsl:apply-templates/><div> </div></td>
</tr>
</xsl:template>

<xsl:template match="c:listview/c:day/c:event">
<div>
<xsl:attribute name="class"><xsl:text>event vevent</xsl:text><xsl:if test="number(@collision-count)>0"><xsl:text> collision</xsl:text></xsl:if></xsl:attribute>
<span class="time"><span class="from"><xsl:value-of select="@time-from"/></span> - <xsl:value-of select="@time-to"/>: </span>
<xsl:value-of select="c:summary"/>
	<xsl:if test="c:description"><span class="calendar_description"><xsl:text> - </xsl:text><xsl:value-of select="c:description"/></span></xsl:if>
<xsl:if test="c:location/.!=''">
<xsl:text> </xsl:text><span class="location">(<xsl:value-of select="c:location"/>)</span>
</xsl:if>
<span class="calendar_title"><xsl:text> - </xsl:text><xsl:value-of select="c:calendar"/></span>
</div>
</xsl:template>


<!--               Agenda view                -->

<xsl:template match="c:agendaview">
<div class="listview">
<div class="navigation">
<xsl:call-template name="c:views"/>
<div class="jumps">
<a href="?id={//p:page/@id}&amp;date={../c:state/c:previous/@year}{../c:state/c:previous/@month}{../c:state/c:previous/@day}&amp;view=agenda" class="common"><span>Forrige måned</span></a>
 &#183; <a href="?id={//p:page/@id}&amp;date={../c:state/c:today/@year}{../c:state/c:today/@month}{../c:state/c:today/@day}&amp;agenda=list#selected" class="common"><span>Idag</span></a>
 &#183; <a href="?id={//p:page/@id}&amp;date={../c:state/c:next/@year}{../c:state/c:next/@month}{../c:state/c:next/@day}&amp;view=agenda" class="common"><span>Næste måned</span></a>
</div>
</div>
<table cellspacing="0" cellpadding="0">
	<caption><xsl:call-template name="c:getMonth"><xsl:with-param name="month" select="number(../c:state/c:date/@month)"/></xsl:call-template></caption>
	<tbody>
		<xsl:apply-templates/>
	</tbody>
</table>
<xsl:if test="not(c:event)"><p class="calendar_nodata">Der er ingen data for denne måned</p></xsl:if>
</div>
</xsl:template>

<xsl:template match="c:agendaview/c:event">
<tr>
	<td>
		<div>
			<xsl:attribute name="class"><xsl:text>event vevent</xsl:text><xsl:if test="number(@collision-count)>0"><xsl:text> collision</xsl:text></xsl:if></xsl:attribute>
			<xsl:value-of select="c:summary"/>
	<xsl:if test="c:description"><span class="calendar_description"><xsl:text> - </xsl:text><xsl:value-of select="c:description"/></span></xsl:if>
			<xsl:if test="c:location/.!=''">
			<xsl:text> </xsl:text><span class="location">(<xsl:value-of select="c:location"/>)</span>
			</xsl:if>
			<!--<span class="calendar_title"><xsl:text> - </xsl:text><xsl:value-of select="c:calendar"/></span>-->
			<xsl:comment/>
		</div>
	</td>
</tr>
</xsl:template>

</xsl:stylesheet>