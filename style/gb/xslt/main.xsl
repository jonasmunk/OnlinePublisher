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
				<title><xsl:if test="not(//p:page/@id=//p:context/p:home/@page)"><xsl:value-of select="@title"/> - </xsl:if><xsl:value-of select="f:frame/@title"/></title>
				<xsl:call-template name="util:metatags"/>
				<xsl:call-template name="util:style"/>
				<xsl:call-template name="util:style-ie7"/>
				<xsl:call-template name="util:scripts"/>
			</head>
			<body>
				<div class="layout">
					<div class="layout_header">
						<p>Green Benefit</p>
					</div>
					<div class="layout_middle">
					<div class="layout_sidebar">
						<div class="layout_menu">
						<div class="layout_menu_top"><xsl:comment/></div>
						<div class="layout_menu_middle">
							<xsl:call-template name="util:hierarchy-all-levels"/>
						</div>
						<div class="layout_menu_bottom"><xsl:comment/></div>
						</div>
						<xsl:comment/>
						<div class="layout_box">
							<div class="layout_box_top"><xsl:comment/></div>
							<div class="layout_box_middle">
							<h3>Kontakt</h3>
							<p><strong>Green Benefit</strong></p>
							<div class="layout_address">
							<p>Maren Hemmings Vej 13</p>
							<p>9000 Aalborg</p>
							<p>Denmark</p>
							</div>
							<p>E-mail: <a href="mailto:info@greenbenefit.dk">info@greenbenefit.dk</a></p>
							<p>Tlf: 70 20 60 77</p>
							</div>
							<div class="layout_box_bottom"><xsl:comment/></div>
						</div>
						<!--
						<div class="layout_box">
							<div class="layout_box_top"><xsl:comment/></div>
							<div class="layout_box_middle">
							<h3>Nyhedsbrev</h3>
							<p>Tilmeld dig Psoriasisbadet Nyt og følg med i den helbredende udvikling.</p>
							<p><a href="/#nyhedsbrev">Tilmeld nyhedsbrev »</a></p>
							</div>
							<div class="layout_box_bottom"><xsl:comment/></div>
						</div>
						-->
					</div>
					<div class="layout_content">
						<div class="layout_content_top"><xsl:comment/></div>
						<div class="layout_content_left"><xsl:comment/></div>
						<div class="layout_content_middle">
						<xsl:apply-templates select="p:content"/>
						<xsl:comment/>
						</div>
						<div class="layout_content_right"><xsl:comment/></div>
						<div class="layout_content_bottom"><xsl:comment/></div>
					</div>
					</div>
				</div>
					<div class="layout_footer">
						<div class="layout_footer_text">
							<p><strong>Green Benefit ApS</strong></p>
							<p>Maren Hemmings Vej 13, 9000 Aalborg, telefon 70 20 60 77</p>
							<p>cvr.nr. 33068271 – reg. 7452 konto 0001061692</p>
						</div>
						<p class="layout_designed">
							<a href="http://www.in2isoft.dk/"><span>Designet og udviklet af In2iSoft</span></a>
						</p>
					</div>
				<xsl:call-template name="util:googleanalytics"/>
			</body>
		</html>
	</xsl:template>
	
</xsl:stylesheet>