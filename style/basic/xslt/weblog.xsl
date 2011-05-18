<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:wl="http://uri.in2isoft.com/onlinepublisher/publishing/weblog/1.0/"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:e="http://uri.in2isoft.com/onlinepublisher/class/weblogentry/1.0/"
 exclude-result-prefixes="wl p o e"
 >
<xsl:include href="html.xsl"/>

<xsl:template match="wl:weblog">
<div class="weblog">
<xsl:apply-templates select="wl:list | wl:edit | wl:new"/>
</div>
<xsl:if test="$username!=''">
<script type="text/javascript" charset="utf-8" src="{$path}In2iGui/lib/date.js"><xsl:comment/></script>
<script type="text/javascript" charset="utf-8" src="{$path}In2iGui/js/Button.js"><xsl:comment/></script>
<script type="text/javascript" charset="utf-8" src="{$path}In2iGui/js/Formula.js"><xsl:comment/></script>
<script type="text/javascript" charset="utf-8" src="{$path}In2iGui/js/Overlay.js"><xsl:comment/></script>
<script type="text/javascript" charset="utf-8" src="{$path}style/basic/js/templates/Weblog.js"><xsl:comment/></script>
<script	type="text/javascript">
<xsl:for-each select="wl:group">
	op.WeblogTemplate.groups.push({value:'<xsl:value-of select="@id"/>',title:'<xsl:value-of select="@title"/>'});
</xsl:for-each>
</script>
</xsl:if>
</xsl:template>


<xsl:template match="wl:title">
<h1 class="common"><xsl:apply-templates/></h1>
</xsl:template>

<xsl:template match="wl:message">
<div class="message">
<xsl:choose>
	<xsl:when test="@type='no-title'">Der er ikke angivet en titel.</xsl:when>
	<xsl:otherwise>Der skete en uventet fejl.</xsl:otherwise>
</xsl:choose>
</div>
</xsl:template>

<xsl:template match="wl:list">
<xsl:if test="$username!=''">
<div class="actions">
<a class="hui_button hui_button_paper" id="weblog_new" href="#"><span><span>Opret ny</span></span></a>
</div>
</xsl:if>
<xsl:apply-templates select="../wl:title"/>
<div class="list">
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="wl:list/wl:entry">
<div class="entry">
<em class="date"><xsl:apply-templates select="o:object/o:sub/e:weblogentry/e:date"/></em>
<h2 class="common">
	<xsl:choose>
		<xsl:when test="@page-id">
			<a href="{$navigation-path}?id={@page-id}" class="common"><xsl:value-of select="o:object/o:title"/></a>
		</xsl:when>
		<xsl:when test="@page-path">
			<a href="{$navigation-path}{@page-path}"><xsl:value-of select="o:object/o:title"/></a>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="o:object/o:title"/>
		</xsl:otherwise>
	</xsl:choose>
</h2>
<div class="groups">
<xsl:for-each select="wl:group">
	<xsl:if test="position()>1">, </xsl:if>
	<xsl:value-of select="@title"/>
</xsl:for-each>
</div>
<xsl:choose>
	<xsl:when test="wl:page-content">
		<xsl:apply-templates select="wl:page-content"/>
	</xsl:when>
	<xsl:otherwise>
		<div class="text">
		<xsl:value-of select="o:object/o:sub/e:weblogentry/e:text" disable-output-escaping = "yes"/>
		<xsl:comment/>
		</div>
	</xsl:otherwise>
</xsl:choose>
<xsl:if test="$username!=''">
<div class="operations">
	<a href="#" onclick="op.WeblogTemplate.edit({o:object/@id}); return false;" class="hui_button hui_button_paper hui_button_small_paper" style="margin-right: 5px;"><span><span>Rediger</span></span></a>
	<a href="#" onclick="op.WeblogTemplate.deleteEntry({o:object/@id},this); return false;" class="hui_button hui_button_paper hui_button_small_paper"><span><span>Slet</span></span></a></div>
</xsl:if>
</div>
</xsl:template>


<xsl:template match="e:weblogentry/e:content">
<div>
<xsl:apply-templates/>
</div>
</xsl:template>


<xsl:template match="e:weblogentry/e:date">
<xsl:value-of select="concat(@day,'/',@month,' kl. ',@hour,':',@minute)"/>
</xsl:template>

</xsl:stylesheet>