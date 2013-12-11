<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
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
					<div class="chrome_top_header">
						<xsl:comment/>
					</div>

		            <div class="chrome_body">
						<xsl:apply-templates select="f:frame/h:hierarchy"/>
						<xsl:apply-templates select="p:content"/>
					</div>
					<div class="chrome_footer">
						<xsl:comment/>
						<!--div class="chrome_insurance"></div-->

						<!--a href="http://www.in2isoft.dk/" class="chrome_insurance"><span>Overenskomst med sygesikringen</span></a-->
						<a href="http://www.in2isoft.dk/" class="chrome_powered"><span>Designet og udviklet af In2iSoft</span></a>
					</div>
				</div>
				<div class="chrome_frame_bottom"><xsl:comment/></div>
			</div>
			<div class="chrome_contact">
				<h2>Kontakt</h2>
				<p>Hedensted</p>
				<p>Horsens</p>
				<p>Struer</p>
				<p>Aarhus</p>
				<p>Tlf.: 61 26 50 49</p>
				<hr/>
				<h2>Mail</h2>
				<p><a href="rie_karnoe@yahoo.dk">rie_karnoe@yahoo.dk</a></p>
				<hr/>
			</div>
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



</xsl:stylesheet>