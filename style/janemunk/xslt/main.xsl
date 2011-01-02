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
			<title><xsl:value-of select="@title"/> » <xsl:value-of select="f:frame/@title"/></title>
			<xsl:call-template name="util:metatags"/>
			<xsl:call-template name="oo-script"/>
			<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/main.css"/>
			<xsl:choose>
				<xsl:when test="$template='document'">
					<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/{$template}.php"/>
				</xsl:when>
				<xsl:otherwise>
					<link rel="stylesheet" type="text/css" href="{$path}style/basic/css/{$template}.css"/>
				</xsl:otherwise>
			</xsl:choose>
			<xsl:comment><![CDATA[[if lt IE 7]>
			<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie6.css"> </link>
			<![endif]]]></xsl:comment>
		</head>
		<body>
			<div class="case">
				<xsl:choose>
				<xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id]">
					<div class="case_front">
						<div id="paintings">
							<div class="painting1"><xsl:comment/></div>
							<div class="painting2"><xsl:comment/></div>
							<div class="painting3"><xsl:comment/></div>
							<div class="painting4"><xsl:comment/></div>
							<div class="painting5"><xsl:comment/></div>
							<div class="painting6"><xsl:comment/></div>
							<div class="painting7"><xsl:comment/></div>
							<div class="painting8"><xsl:comment/></div>
							<div class="painting9"><xsl:comment/></div>
						</div>
						<div class="case_front_content">
							<xsl:apply-templates select="p:content"/>
						</div>
					</div>
					<script type="text/javascript">
						new op.Dissolver({elements:$$('div#paintings div'),wait:3000,transition:1000,delay:0});
					</script>
				</xsl:when>
				<xsl:otherwise>
					<div class="case_head">
						<h1>Jane Munk</h1>
						<ul class="case_navigation"><xsl:apply-templates select="f:frame/h:hierarchy/h:item"/></ul>
					</div>
					<xsl:call-template name="secondlevel"/><xsl:comment/>
					<div class="case_body">
						<div class="case_sidebar">
							<div class="case_contact">
								<h2>Jane Brinkmann Munk</h2>
								<p>Lindevej 73, 9370 Hals</p>
								<p><em>Tlf:</em> 98 25 18 83</p>
								<p><em>E-post: </em> <a href="mailto:janemunk@stofanet.dk"><span>janemunk@stofanet.dk</span></a></p>
						
								<blockquote>
									"Kontakt mig venligst hvis du er interesseret i nogle af malerierne. Bemærk at nogle malerier allerede kan være solgt."
								</blockquote>
							</div>
							<xsl:call-template name="thirdlevel"/>
							<xsl:comment/>
						</div>
						<div class="case_sidebar_content">
						<xsl:apply-templates select="p:content"/>
						</div>
					</div>
					<div class="case_footer"><xsl:comment/></div>
				</xsl:otherwise>
				</xsl:choose>
			</div>
			<xsl:call-template name="util:googleanalytics"><xsl:with-param name="code" select="'UA-420000-8'"/></xsl:call-template>
		</body>
	</html>
</xsl:template>


<xsl:template match="p:content">
	<div class="case_content">
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
</xsl:template>




<xsl:template match="h:hierarchy/h:item">
	<xsl:if test="not(@hidden='true')">
		<xsl:variable name="style">
			<xsl:choose>
				<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
				<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
				<xsl:otherwise><xsl:text>normal</xsl:text></xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<li class="{$style}">
			<a>
			<xsl:call-template name="link"/>
			<span><xsl:value-of select="@title"/></span>
			</a>
		</li>
	</xsl:if>
</xsl:template>

<xsl:template name="secondlevel">
	<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
		<ul class="case_sub_navigation">
			<xsl:apply-templates select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
		</ul>
	</xsl:if>
</xsl:template>

<xsl:template name="thirdlevel">
	<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
		<ul class="case_side_navigation">
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
			<xsl:otherwise><xsl:text>standard</xsl:text></xsl:otherwise>
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

</xsl:stylesheet>