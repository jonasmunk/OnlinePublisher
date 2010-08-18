<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p f h n o util"
 >
<xsl:output encoding="UTF-8" method="xml" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:include href="../../basic/xslt/util.xsl"/>


<xsl:template match="p:page">
<html>
	<xsl:attribute name="xmlns">http://www.w3.org/1999/xhtml</xsl:attribute>
<head>
	<title><xsl:choose><xsl:when test="//p:page/@id=//p:context/p:home/@page">In2iSoft : Intuitive Internet Software</xsl:when><xsl:otherwise><xsl:value-of select="@title"/> » <xsl:value-of select="f:frame/@title"/></xsl:otherwise></xsl:choose></title>
	<xsl:call-template name="util:metatags"/>
	<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/main.css"/>
	<xsl:choose>
		<xsl:when test="$template='document'">
			<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/{$template}.php"/>
		</xsl:when>
		<xsl:otherwise>
			<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/{$template}.css"/>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:comment><![CDATA[[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie6.css"> </link>
	<![endif]]]></xsl:comment>
	<xsl:call-template name="oo-script"/>
</head>
<body>
	<div class="chrome">
		<div class="chrome_head">
			<ul class="chrome_navigation"><xsl:apply-templates select="f:frame/h:hierarchy/h:item"/></ul>
			<xsl:call-template name="search"/>
		</div>
		<div class="chrome_base">
			<div class="chrome_box_top"><div><div><xsl:comment/></div></div></div>
			<div class="chrome_box_middle"><div class="chrome_box_middle">
				<div class="chrome_box_body">
					<div class="chrome_box_head">
						<xsl:call-template name="secondlevel"/><xsl:comment/>
					</div>
					<xsl:if test="//p:page/@id=//p:context/p:home/@page">
					<div id="poster">
						<div id="poster_loader">0%</div>
						<div class="left" id="poster_left"><div id="poster_inner_left"><xsl:comment/></div></div>
						<div class="right" id="poster_right"><div id="poster_inner_right"><xsl:comment/></div></div>
					</div>
					<script type="text/javascript" src="{$path}style/{$design}/js/Poster.js"><xsl:comment/></script>
					</xsl:if>
					<xsl:apply-templates select="p:content"/>
					<xsl:choose>
						<xsl:when test="//p:page/@id=//p:context/p:home/@page">
							<div class="chrome_placards">
								<a class="chrome_placard chrome_placard_left" href="{$path}produkter/onlinepublisher/"><xsl:comment/></a>
								<a class="chrome_placard chrome_placard_center" href="{$path}produkter/onlineobjects/"><xsl:comment/></a>
								<a class="chrome_placard chrome_placard_right" href="{$path}produkter/onlineme/"><xsl:comment/></a>
							</div>
						</xsl:when>
						<xsl:otherwise>
							<div class="chrome_box_foot">
								<xsl:comment/>
							</div>
						</xsl:otherwise>
					</xsl:choose>
				</div>
			</div></div>
			<div class="chrome_box_bottom"><div><div><xsl:comment/></div></div></div>
			<div class="chrome_info">
				<div class="chrome_box_top"><div><div><xsl:comment/></div></div></div>
				<div class="chrome_box_middle"><div class="chrome_box_middle">
					<div class="chrome_box_body">
						<div class="about">
							<h2>Om In2iSoft</h2>
							<p>Vores focus er på brugeroplevelse og design. Vi leder altid efter
								den mest enkle og essentielle løsning. Vi tror på at maskinen skal
								arbejde for mennesket. Vi mener at viden bør være frit
								og tilgængeligt for alle. Vi håber du er enig :-)
							</p>
							<p class="more"><a href="{$path}om/" class="common"><span>Mere om In2iSoft »</span></a></p>
						</div>
						<div class="contact">
							<h2>Kontakt</h2>
							<p class="name"><strong>Jonas Brinkmann Munk</strong></p>
							<p class="email"><a href="mailto:jonasmunk@me.com" class="common"><span>jonasmunk@me.com</span></a></p>
							<p class="phone">28 77 63 65</p>
							<p class="name"><strong>Kenni Graversen</strong></p>
							<p class="email"><a href="mailto:gr@versen.dk" class="common"><span>gr@versen.dk</span></a></p>
							<p class="phone">22 48 61 53</p>
						</div>
					</div>
				</div></div>
				<div class="chrome_box_bottom"><div><div><xsl:comment/></div></div></div>
			</div>
		</div>
	</div>
	<div class="chrome_footer">
		<a class="chrome_design">Designet og udviklet af In2iSoft</a>
			<xsl:apply-templates select="f:frame/f:text/f:bottom"/>
			<xsl:apply-templates select="f:frame/f:links/f:bottom"/>
	</div>
	<xsl:call-template name="util:googleanalytics"><xsl:with-param name="code" select="'UA-420000-2'"/></xsl:call-template>
</body>
</html>
</xsl:template>


<xsl:template match="p:content">
<div>
<xsl:choose>
<xsl:when test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
<xsl:attribute name="class">chrome_content chrome_content_sidebar</xsl:attribute>
</xsl:when>
<xsl:otherwise>
<xsl:attribute name="class">chrome_content</xsl:attribute>
</xsl:otherwise>
</xsl:choose>
<xsl:if test="../f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or ../f:frame/f:newsblock or ../f:frame/f:userstatus">
<div class="chrome_sidebar">
<xsl:call-template name="thirdlevel"/>
<xsl:apply-templates select="../f:frame/f:newsblock"/>
<xsl:comment/>
</div>
</xsl:if>
<div class="chrome_inner_content">
<xsl:apply-templates/>
<xsl:comment/>
</div>
</div>
</xsl:template>


<!--            User status                 -->



<xsl:template match="f:userstatus">
	<xsl:choose>
		<xsl:when test="$userid>0">
		<span class="userstatus">Bruger: <strong><xsl:value-of select="$usertitle"/></strong></span>
		<xsl:text> · </xsl:text>
		<a href="./?id={@page}&amp;logout=true" class="common">Log ud</a>
		</xsl:when>
		<xsl:otherwise>
		<a href="./?id={@page}" class="common">Log ind</a>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>



<xsl:template match="h:hierarchy/h:item">
<xsl:if test="not(@hidden='true')">
<li><xsl:choose>
<xsl:when test="position()>1 and //p:page/@id=@page"><xsl:attribute name="class">selected</xsl:attribute></xsl:when>
<xsl:when test="position()>1 anddescendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">highlighted</xsl:attribute></xsl:when>
<xsl:when test="position()>1"><xsl:attribute name="class">normal</xsl:attribute></xsl:when>
</xsl:choose>
<a>
<xsl:call-template name="link"/>
<xsl:choose>
<xsl:when test="position()=1">
<img src="{$path}style/{$design}/gfx/logo.png" alt="In2iSoft"/>
</xsl:when>
<xsl:otherwise>
<span><xsl:value-of select="@title"/></span>
</xsl:otherwise>
</xsl:choose>
</a>
</li>
</xsl:if>
</xsl:template>

<xsl:template name="secondlevel">
<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
	<ul class="chrome_sub_navigation">
		<xsl:apply-templates select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
	</ul>
</xsl:if>
</xsl:template>

<xsl:template name="thirdlevel">
<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
	<ul class="chrome_side_navigation">
		<xsl:apply-templates select="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
	</ul>
</xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<li>
<a class="{$style}">
<xsl:call-template name="link"/>
<span><xsl:value-of select="@title"/></span>
</a>
</li>
</xsl:if>
</xsl:template>

<xsl:template match="h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
<xsl:otherwise>standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<li>
<a class="{$style}">
<xsl:call-template name="link"/>
<span><xsl:value-of select="@title"/></span>
</a>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
<ul><xsl:apply-templates/></ul>
</xsl:if>
</li>
</xsl:if>
</xsl:template>





<!--            Links              -->


<xsl:template match="f:links/f:top">
<div class="links_top">
<div>
<xsl:apply-templates select="//f:frame/f:userstatus"/> · 
<a title="Udskriv siden" class="common" href="?id={//p:page/@id}&amp;print=true">Udskriv</a>
<xsl:apply-templates/>
</div>
</div>
</xsl:template>

<xsl:template match="f:links/f:bottom">
<div class="chrome_links">
<xsl:apply-templates/>
<xsl:if test="f:link"><span>&#160;&#183;&#160;</span></xsl:if>
<a title="XHTML 1.1" class="common" href="http://validator.w3.org/check?uri=referer"><span>XHTML 1.1</span></a>
</div>
</xsl:template>

<xsl:template match="f:links/f:bottom/f:link">
<xsl:if test="position()>1"><span>&#160;&#183;&#160;</span></xsl:if>
<a title="{@alternative}" class="common">
<xsl:call-template name="link"/>
<span><xsl:value-of select="@title"/></span>
</a>
</xsl:template>

<xsl:template match="f:links/f:top/f:link">
<span>&#160;&#183;&#160;</span>
<a title="{@alternative}" class="common">
<xsl:call-template name="link"/>
<span><xsl:value-of select="@title"/></span>
</a>
</xsl:template>



<!--            Text              -->





<xsl:template match="f:text/f:bottom">
<span class="text">
	<xsl:comment/>
<xsl:apply-templates/>
</span>
</xsl:template>

<xsl:template match="f:text/f:bottom/f:break">
	<br/>
</xsl:template>


<xsl:template match="f:text/f:bottom/f:link">
<a title="{@alternative}" class="common">
<xsl:call-template name="link"/>
<span><xsl:apply-templates/></span>
</a>
</xsl:template>




<!--            News              -->





<xsl:template match="f:newsblock">
<div class="chrome_news">
<h2><xsl:value-of select="@title"/></h2>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="f:newsblock//o:object">
<div class="chrome_news_item">
<h3>
<xsl:value-of select="o:title"/>
</h3>
<p class="chrome_news_text">
<xsl:apply-templates select="o:note"/>
</p>
<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
<xsl:apply-templates select="o:links"/>
</div>
</xsl:template>

<xsl:template match="f:newsblock//o:links">
<p class="chrome_news_links">
<xsl:apply-templates/>
</p>
</xsl:template>

<xsl:template match="f:newsblock//o:note">
<xsl:apply-templates/>
</xsl:template>

<xsl:template match="f:newsblock//o:break">
<br/>
</xsl:template>

<xsl:template match="f:newsblock//n:startdate">
<p class="chrome_news_date"> <xsl:value-of select="@day"/>/<xsl:value-of select="@month"/><!--/<xsl:value-of select="substring(@year,3,2)"/>--></p>
</xsl:template>

<xsl:template match="f:newsblock//o:link">
<xsl:if test="position()>1"><xsl:text> </xsl:text></xsl:if>
<a title="{@alternative}" class="common">
<xsl:call-template name="link"/>
<span>
<xsl:value-of select="@title"/>
</span>
</a>
</xsl:template>



<!--                  Search                     -->


<xsl:template name="search">
<xsl:if test="f:frame/f:search">
<form action="{$path}" method="get" class="search" accept-charset="UTF-8">
<div>
<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
<xsl:for-each select="f:frame/f:search/f:types/f:type">
<input type="hidden" name="{@unique}" value="on"/>
</xsl:for-each>
<input type="text" class="text" name="query" id="searchfield"/>
<input type="submit" class="submit" value="Søg"/>
</div>
</form>
<script type="text/javascript"><xsl:comment>
new op.SearchField({element:'searchfield',placeholder:'Søg her!'});
</xsl:comment>
</script>
</xsl:if>
</xsl:template>




</xsl:stylesheet>