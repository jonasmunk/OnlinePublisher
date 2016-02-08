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
 xmlns:html="http://uri.in2isoft.com/onlinepublisher/publishing/html/1.0/"
 exclude-result-prefixes="p f h n o html util"
 >
  <xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes"/>

  <xsl:include href="../../basic/xslt/util.xsl"/>

  <xsl:template match="p:page">
    <xsl:call-template name="util:doctype"/>
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
      	<xsl:call-template name="util:metatags"/>
      	<xsl:if test="//p:design/p:parameter[@key='theme']">
      		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,600,300,200' rel='stylesheet' type='text/css'/>
      		<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200' rel='stylesheet' type='text/css'/>
      	</xsl:if>
      	<xsl:call-template name="util:style"/>
      	<xsl:call-template name="util:scripts"/>
      </head>
      <body>
      	<xsl:if test="//p:design/p:parameter[@key='theme']">
      		<xsl:attribute name="class">
      			<xsl:text>theme_</xsl:text><xsl:value-of select="//p:design/p:parameter[@key='theme']"/>
      		</xsl:attribute>
      	</xsl:if>
      	<xsl:choose>
      		<xsl:when test="//html:html">
      			<xsl:apply-templates select="p:content"/>
      		</xsl:when>
      		<xsl:otherwise>
      			<div class="layout">
      				<div class="layout_navigation">
      					<xsl:call-template name="util:navigation-first-level"/>
      				</div>
      				<div class="layout_top">
      					<xsl:comment/>
      				</div>
      				<div class="layout_content">
      					<xsl:call-template name="util:navigation-second-level"/>
      					<xsl:call-template name="util:navigation-third-level"/>
      					<xsl:apply-templates select="p:content"/>
      					<xsl:comment/>
      				</div>
      				<div class="layout_bottom">
      					<xsl:comment/>
      				</div>
      				<div class="layout_footer">
      					<a href="http://www.humanise.dk/" class="layout_designed">Designet og udviklet af Humanise</a>
      				</div>
      			</div>
      		</xsl:otherwise>
      	</xsl:choose>
      	<xsl:call-template name="util:googleanalytics"/>
      </body>
    </html>
  </xsl:template>





</xsl:stylesheet>