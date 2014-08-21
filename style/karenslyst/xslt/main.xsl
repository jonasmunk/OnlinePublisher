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
 xmlns:hr="http://uri.in2isoft.com/onlinepublisher/part/horizontalrule/1.0/"
 exclude-result-prefixes="p f h n o util hr"
 >
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>

<xsl:include href="../../basic/xslt/util.xsl"/>


<xsl:template match="p:page">
	<xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html&gt;
</xsl:text>
<html>
	<xsl:call-template name="util:html-attributes"/>
    <head>
    	<title>
    		<xsl:if test="not(//p:page/@id=//p:context/p:home/@page)"> 
    			<xsl:value-of select="@title"/>
    			<xsl:text> - </xsl:text>
    		</xsl:if>
    		<xsl:value-of select="f:frame/@title"/>
    	</title>
		<meta name="viewport" content="user-scalable=yes, initial-scale = 1, maximum-scale = 10, minimum-scale = 0.2"/>
    	<xsl:call-template name="util:metatags"/>
		<link href='http://fonts.googleapis.com/css?family=Playfair+Display:400,400italic|Annie+Use+Your+Telescope' rel='stylesheet' type='text/css'/>
    	<xsl:call-template name="util:style"/>
    	<xsl:call-template name="util:style-ie7"/>
		<!--
    	<xsl:call-template name="util:style-ie6"/>
    	<xsl:call-template name="util:style-ie8"/>
			-->
    	<xsl:call-template name="util:scripts"/>
	
    </head>
    <body>
    	<div class="layout">
			<xsl:if test="//p:page/@id=//p:context/p:home/@page">
			<header class="layout_top">
				<h1 class="title">Karenslyst <span class="title_more"> ~ et landsted til leje</span></h1>
				<div class="layout_top_body"><div><xsl:comment/></div></div>
			</header>
			</xsl:if>

			<nav class="menu">
				<ul class="menu_items">
					<xsl:for-each select="//f:frame/h:hierarchy/h:item[not(@hidden='true')]">
						<li>
							<xsl:attribute name="class">
								<xsl:text>menu_item</xsl:text>
								<xsl:choose>
									<xsl:when test="//p:page/@id=@page"> menu_item_selected</xsl:when>
									<xsl:when test="descendant-or-self::*/@page=//p:page/@id"> menu_item_highlighted</xsl:when>
								</xsl:choose>
							</xsl:attribute>
							<a class="menu_link">
								<xsl:call-template name="util:link"/>
								<span><xsl:value-of select="@title"/></span>
							</a>
						</li>
					</xsl:for-each>
				</ul>
			</nav>
			
			<main class="layout_middle">
    			<xsl:apply-templates select="p:content"/>
			</main>
    		<footer class="layout_bottom">
    			<p><a href="http://www.humanise.dk/" class="layout_humanise" title="Humanise">Designet og udviklet af Humanise</a></p>
    		</footer>
    	</div>
    	<xsl:call-template name="util:googleanalytics"/>
    </body>
</html>
</xsl:template>

<xsl:template match="p:content">
	<div class="layout_content">
		<xsl:apply-templates/>
		<xsl:comment/>
	</div>
</xsl:template>

</xsl:stylesheet>