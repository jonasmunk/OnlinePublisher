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
				<title><xsl:if test="not(//p:page/@id=//p:context/p:home/@page)"><xsl:value-of select="@title"/> - </xsl:if><xsl:value-of select="f:frame/@title"/></title>
				<xsl:call-template name="util:metatags"/>
				<xsl:call-template name="util:style"/>
				<xsl:call-template name="util:scripts"/>
			</head>
			<body>
				<div class="layout">
					<div class="layout_header">
						<xsl:comment/>
					</div>
					<div class="layout_sidebar">
						<div class="layout_menu">
						<xsl:call-template name="util:hierarchy-all-levels"/>
						</div>
						<xsl:comment/>
						<div class="layout_box">
							<h3>Kontakt</h3>
							<p><strong>Green Benefit</strong></p>
							<div class="layout_address">
							<p>Vossvej 29 A</p>
							<p>9000 Aalborg</p>
							<p>Denmark</p>
							</div>
							<p>Tlf: 23 62 33 59</p>
						</div>
						<div class="layout_box">
							<h3>Nyhedsbrev</h3>
							<p>Tilmeld dig Psoriasisbadet Nyt og følg med i den helbredende udvikling.</p>
							<p><a href="/#nyhedsbrev">Tilmeld nyhedsbrev »</a></p>
						</div>
					</div>
					<div class="layout_content">
						<xsl:apply-templates select="p:content"/>
						<xsl:comment/>
					</div>
					<div class="layout_footer">
						<p class="layout_designed">
							<a href="http://www.in2isoft.dk/"><span>Designet og udviklet af In2iSoft</span></a>
						</p>
					</div>
				</div>
				<xsl:call-template name="util:googleanalytics">
					<xsl:with-param name="code" select="'UA-420000-14'"/>
				</xsl:call-template>
			</body>
		</html>
	</xsl:template>
	
</xsl:stylesheet>