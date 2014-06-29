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
<xsl:output encoding="UTF-8" method="xml" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:include href="../../basic/xslt/util.xsl"/>


<xsl:template match="p:page">
<html xmlns="http://www.w3.org/1999/xhtml">
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
    	<xsl:call-template name="util:style"/>
    	<xsl:call-template name="util:style-ie6"/>
    	<xsl:call-template name="util:style-ie7"/>
    	<xsl:call-template name="util:style-ie8"/>
    	<xsl:call-template name="util:scripts"/>
	
    </head>
    <body>
    	<div class="layout_bg">
    	<div class="layout">
    		<div class="layout_top">
    			<div class="layout_top_fade"><xsl:comment/></div>
    			<p>Østerlide ferielejlighed</p>
    			<div class="layout_navigation">
					<xsl:call-template name="util:navigation-first-level"/>
    			</div>
    			<xsl:call-template name="util:languages"/>
    		</div>
    		<xsl:apply-templates select="p:content"/>
    		<div class="layout_bottom">
    			<div class="layout_bottom_effect"><xsl:comment/></div>
    			<p><a href="http://www.humanise.dk/" title="Humanise"><span>Designet og udviklet af Humanise</span></a></p>
    		</div>
    	</div>
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