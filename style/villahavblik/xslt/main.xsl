<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p f h n o"
 >
<xsl:output encoding="UTF-8" method="xml" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:include href="../../basic/xslt/util.xsl"/>

<xsl:template match="p:page">
<html xmlns="http://www.w3.org/1999/xhtml">
	<xsl:call-template name="util:html-attributes"/>
<head> 
	<title><xsl:if test="not(//p:page/@id=//p:context/p:home/@page)"><xsl:value-of select="@title"/> » </xsl:if><xsl:value-of select="f:frame/@title"/></title>
	<meta name="google-site-verification" content="vagGQtrnVxxm4omlbXckjUkFqucyeVPmo-CE_LxQQ10" />
	<xsl:call-template name="util:metatags"/>
	<xsl:call-template name="util:style"/>
	<xsl:call-template name="util:style-ie6"/>
	<xsl:call-template name="util:scripts"/>
</head>
<body>
	<div class="chrome">
		<div class="chrome_frame">
			<div class="chrome_header">
				<xsl:comment/>
			</div>
			<div class="chrome_body">
				<xsl:apply-templates select="f:frame/h:hierarchy"/>
				<xsl:apply-templates select="p:content"/>
			</div>
			<div class="chrome_footer">
				<xsl:comment/>
				<a href="http://www.in2isoft.dk/" class="chrome_powered"><span>Designet og udviklet af In2iSoft</span></a>
			</div>
		</div>
		<div class="chrome_frame_bottom"><xsl:comment/></div>
	</div>
	<div class="chrome_photos">
		<div id="chrome_photos1">
			<div class="chrome_photo1"><xsl:comment/></div>
			<div class="chrome_photo2"><xsl:comment/></div>
			<div class="chrome_photo3"><xsl:comment/></div>
			<div class="chrome_photo4"><xsl:comment/></div>
		</div>
		<div id="chrome_photos2">
			<div class="chrome_photo1"><xsl:comment/></div>
			<div class="chrome_photo2"><xsl:comment/></div>
			<div class="chrome_photo3"><xsl:comment/></div>
			<div class="chrome_photo4"><xsl:comment/></div>
		</div>
	</div>
	<div class="chrome_contact">
		<h2>Adresse</h2>
		<p>Nordlysvej 14</p>
		<p>9840 Løkken</p>
		<p>Danmark</p>
		<hr/>
		<h2>Kontakt</h2>
		<p>Buster Munk</p>
		<p>Tlf: 53 74 01 02</p>
		<p><a href="mailto:bustermunk@gmail.com">bustermunk@gmail.com</a></p>
	</div>
	<script type="text/javascript">
		var one = hui.get('chrome_photos2');
		var two = hui.get('chrome_photos1');
		new op.Dissolver({elements:one.getElementsByTagName('div'),wait:5000,transition:3000,delay:4500});
		new op.Dissolver({elements:two.getElementsByTagName('div'),wait:5000,transition:3000});
	</script>
	<xsl:call-template name="util:googleanalytics"><xsl:with-param name="code" select="'UA-420000-4'"/></xsl:call-template>
</body>
</html>
</xsl:template>


<xsl:template match="p:content">
	<xsl:call-template name="secondlevel"/>
	<div class="chrome_content">
		<xsl:choose>
			<xsl:when test="../f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
				<xsl:attribute name="class">chrome_content chrome_content_sidebar</xsl:attribute>
			</xsl:when>
			<xsl:otherwise>
				<xsl:attribute name="class">chrome_content</xsl:attribute>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
</xsl:template>



<!--            User status                 -->



<xsl:template match="f:userstatus">
<div>
	<xsl:choose>
	<xsl:when test="$userid>0">
	<strong>Bruger: </strong><xsl:value-of select="$usertitle"/>
	<xsl:text> </xsl:text>
	<a href="./?id={@page}&amp;logout=true">log ud</a>
	</xsl:when>
	<xsl:otherwise>
	<span>Ikke logget ind</span>
	<xsl:text> </xsl:text>
	<a href="./?id={@page}">log ind</a>
	</xsl:otherwise>
	</xsl:choose>
</div>
</xsl:template>



<xsl:template match="h:hierarchy">
<ul class="navigation">
	<xsl:apply-templates select="h:item"/>
</ul>
</xsl:template>

<xsl:template match="h:hierarchy/h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>Selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>Hilited</xsl:text></xsl:when>
<xsl:otherwise>Standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<li class="{$style}">
<a>
<xsl:call-template name="util:link"/>
<xsl:value-of select="@title"/>
</a>
</li>
</xsl:if>
</xsl:template>

<xsl:template name="secondlevel">
<xsl:if test="../f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
<div class="chrome_sidebar">
<ul>
<xsl:apply-templates select="../f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
</ul>
</div>
</xsl:if>
</xsl:template>

<xsl:template match="h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlited</xsl:text></xsl:when>
<xsl:otherwise>standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<li>
<a class="navigation navigation_{$style}">
<xsl:call-template name="util:link"/>
<span><xsl:value-of select="@title"/></span>
</a>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
<ul><xsl:apply-templates select="h:item"/></ul>
</xsl:if>
</li>
</xsl:if>
</xsl:template>

<!--            Links              -->


<xsl:template match="f:links/f:top">
<span>
<a title="Udskriv siden" href="{$page-path}print=true">Udskriv</a>
<xsl:apply-templates/>
</span>
</xsl:template>

<xsl:template match="f:links/f:bottom">
<span>
<xsl:apply-templates/>
<xsl:if test="f:link"><span>&#160;|&#160;</span></xsl:if>
<a title="XHTML 1.1" href="http://validator.w3.org/check?uri=referer">XHTML 1.1</a>
</span>
</xsl:template>

<xsl:template match="f:links/f:bottom/f:link">
<xsl:if test="position()>1"><span>&#160;|&#160;</span></xsl:if>
<a title="{@alternative}">
<xsl:call-template name="util:link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="f:links/f:top/f:link">
<span>&#160;|&#160;</span>
<a title="{@alternative}">
<xsl:call-template name="util:link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>



<!--            Text              -->





<xsl:template match="f:text/f:bottom">
<xsl:apply-templates/>
<xsl:text> </xsl:text>
</xsl:template>


<xsl:template match="f:text/f:bottom/f:link">
<a title="{@alternative}">
<xsl:call-template name="util:link"/>
<xsl:apply-templates/>
</a>
</xsl:template>




<!--            News              -->





<xsl:template match="f:newsblock">
<div>
<h2>
<xsl:value-of select="@title"/>
</h2>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="f:newsblock//o:object">
<div>
<h3>
<xsl:value-of select="o:title"/>
</h3>
<p>
<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
<xsl:apply-templates select="o:note"/>
</p>
<xsl:if test="o:links">
<div>
<xsl:apply-templates select="o:links/o:link"/>
</div>
</xsl:if>
</div>
</xsl:template>

<xsl:template match="f:newsblock//o:note">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="f:newsblock//o:break">
<br/>
</xsl:template>

<xsl:template match="f:newsblock//n:startdate">
<em><xsl:value-of select="@day"/>/<xsl:value-of select="@month"/></em><xsl:text>: </xsl:text>
</xsl:template>

<xsl:template match="f:newsblock//o:link">
<xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
<a title="{@alternative}" class="common FrameNewsLink">
<xsl:call-template name="util:link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>



<!--                  Search                     -->


<xsl:template name="search">
<xsl:if test="f:frame/f:search">
<form action="." method="get" accept-charset="UTF-8">
<div>
<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
<xsl:for-each select="f:frame/f:search/f:types/f:type">
<input type="hidden" name="{@unique}" value="on"/>
</xsl:for-each>
</div>
<div>
<input name="query"></input>
<input type="submit" value="S&#248;g"/>
</div>
</form>
</xsl:if>
</xsl:template>

</xsl:stylesheet>