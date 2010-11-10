<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:n="http://uri.in2isoft.com/onlinepublisher/class/news/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:pn="http://uri.in2isoft.com/onlinepublisher/part/news/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 exclude-result-prefixes="p f h n o pn util"
 >
<xsl:output encoding="UTF-8" method="xml" omit-xml-declaration="yes" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"/>

<xsl:include href="../../basic/xslt/util.xsl"/>

<xsl:template match="p:page">
<html>
	<xsl:attribute name="xmlns">http://www.w3.org/1999/xhtml</xsl:attribute>
<head>
	<title><xsl:value-of select="@title"/> : <xsl:value-of select="f:frame/@title"/></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
	<meta name="robots" content="index,follow"></meta>
	<xsl:call-template name="oo-script"/>
	<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/main.css"/>
	<xsl:if test="//p:page/p:context/p:home[@page=//p:page/@id]">
		<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/front.css"/>
	</xsl:if>
	<xsl:choose>
		<xsl:when test="$template='document'">
			<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/document.php"/>
		</xsl:when>
		<xsl:otherwise>
			<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/{$template}.css"/>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:comment><![CDATA[[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><![CDATA[style/atira/css/msie6.css"> </link>
	<![endif]]]></xsl:comment>
	<script src="{$path}style/{$design}/js/AtiraWebsite.js?1" type="text/javascript"><xsl:comment/></script>
</head>
<body>
	<xsl:if test="//p:design/p:parameter[@key='variant']/.='news'">
		<xsl:attribute name="class">news</xsl:attribute>
	</xsl:if>
	<div class="container head">
		<xsl:call-template name="languages"/>
	</div>
	<div class="container">
		<div class="menu">
			<a class="logo">
				<xsl:attribute name="href">
					<xsl:if test="//p:page/p:meta/p:language='da'"><xsl:value-of select="$path"/></xsl:if>
					<xsl:if test="//p:page/p:meta/p:language='en'"><xsl:value-of select="$path"/>en/</xsl:if>
				</xsl:attribute>
				<img src="{$path}style/{$design}/gfx/menu_logo.png" alt="Atira logo"/>
			</a>
			<div class="bar">
				<div class="bar">
					<div class="bar">
						<ul class="navigation"><xsl:apply-templates select="f:frame/h:hierarchy/h:item"/></ul>
						<xsl:call-template name="search"/>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="middle">
		<div class="container">
			<xsl:choose>
				<xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id] and //p:page/p:meta/p:language='da'">
					<xsl:call-template name="front-da"/>
				</xsl:when>
				<xsl:when test="//p:page/p:context/p:home[@page=//p:page/@id] and //p:page/p:meta/p:language='en'">
					<xsl:call-template name="front-en"/>
				</xsl:when>
				<xsl:otherwise>
					<xsl:apply-templates select="p:content"/>
				</xsl:otherwise>
			</xsl:choose>
		</div>
	</div>
	<xsl:call-template name="extra"/>
	<div class="footer">
		<xsl:apply-templates select="f:frame/f:text/f:bottom"/>
		<xsl:apply-templates select="f:frame/f:links/f:bottom"/>
	</div>
	<xsl:call-template name="util:googleanalytics">
		<xsl:with-param name="code" select="'UA-183873-1'"/>
	</xsl:call-template>
</body>
</html>
</xsl:template>

<xsl:template name="languages">
	<span class="translation">
		<xsl:for-each select="//p:page/p:context/p:home[@language and @language!=//p:page/p:meta/p:language and not(@language=//p:page/p:context/p:translation/@language)]">
			<xsl:call-template name="language"/>
		</xsl:for-each>
		<xsl:for-each select="//p:page/p:context/p:translation">
			<xsl:call-template name="language"/>
		</xsl:for-each>
	</span>
</xsl:template>

<xsl:template name="language">
	<xsl:if test="@language!='da'">
		<a class="{@language}">
			<xsl:call-template name="link"/>
			<xsl:choose>
				<xsl:when test="@language='da'">Dansk version</xsl:when>
				<xsl:when test="@language='en'">English version</xsl:when>
				<xsl:otherwise><xsl:value-of select="@language"/></xsl:otherwise>
			</xsl:choose>
		</a><xsl:text> </xsl:text>
	</xsl:if>
</xsl:template>

<xsl:template match="p:content">
	<div class="box">
		<div class="box_top"><div><div><xsl:comment/></div></div></div>
		
		<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
		<div class="box_head">
			<xsl:call-template name="secondlevel"/>
			<xsl:comment/>
		</div>
		</xsl:if>
		<div class="box_body">
			<div>
				<xsl:choose>
					<xsl:when test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or //f:newsblock">
					<xsl:attribute name="class">content content_sidebar</xsl:attribute>
					</xsl:when>
					<xsl:otherwise>
					<xsl:attribute name="class">content content_sidebar</xsl:attribute>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item or //f:newsblock">
					<div class="sidebar">
					<xsl:call-template name="thirdlevel"/>
					<xsl:apply-templates select="//f:newsblock"/>
					<xsl:comment/>
					</div>
				</xsl:if>
				<div class="inner_content">
					<xsl:if test="//p:design/p:parameter[@key='variant']/.='news'">
						<a href="javascript: history.back()" class="common back"><span>
							<xsl:if test="//p:page/p:meta/p:language='da'">Tilbage</xsl:if>
							<xsl:if test="//p:page/p:meta/p:language='en'">Go back</xsl:if>
						</span></a>
					</xsl:if>
					<xsl:comment/>
					<xsl:apply-templates/>
					<xsl:if test="//p:design/p:parameter[@key='variant']/.='news'">
						<a href="javascript: history.back()" class="common back"><span>
							<xsl:if test="//p:page/p:meta/p:language='da'">Tilbage</xsl:if>
							<xsl:if test="//p:page/p:meta/p:language='en'">Go back</xsl:if>
						</span></a>
					</xsl:if>
				</div>
			</div>
		</div>
		<div class="box_foot">
			<xsl:comment/>
		</div>
		<div class="box_bottom"><div><div><xsl:comment/></div></div></div>
	</div>
</xsl:template>

<xsl:template name="extra">
	<xsl:variable name="language" select="//p:page/p:meta/p:language"/>
	<div class="extra">
		<div class="container">
			<div class="left">
				<xsl:choose>
					<xsl:when test="$language='en'">
						<h2>Site map</h2>
					</xsl:when>
					<xsl:otherwise>
						<h2>Oversigt</h2>
					</xsl:otherwise>
				</xsl:choose>
				<div>
					<xsl:for-each select="//h:hierarchy/h:item[position()>1 and not(@hidden='true')]">
					<div class="tile">
						<p>
							<strong><a><xsl:call-template name="link"/><span><xsl:value-of select="@title"/></span></a></strong>
							<xsl:for-each select="h:item[not(@hidden='true') and not(@page=../@page-reference)]">
								<br/><a><xsl:call-template name="link"/><span><xsl:value-of select="@title"/></span></a>
							</xsl:for-each>
						</p>
					</div>
					</xsl:for-each>
				</div>
			</div>
			<div class="center part part_html">
				<xsl:choose>
					<xsl:when test="$language='en'">
						<h2>Contact</h2>
						<p class="address">
							Atira A/S
							<br/>Niels Jernes Vej 10
							<br/>9220 Aalborg Oest
							<br/>Denmark
							<br/>Phone: (+45) 96 35 61 00
							<br/>VAT no. 26835526
						</p>
						<p>
							<strong>General info: </strong><a href="mailto:info@atira.dk"><span>info@atira.dk</span></a>
							<br/><strong>PURE support: </strong><a href="mailto:support@pure.atira.dk"><span>support@pure.atira.dk</span></a>
							<br/><strong>Other support: </strong><a href="mailto:support@atira.dk"><span>support@atira.dk</span></a>
						</p>
					</xsl:when>
					<xsl:otherwise>
						<h2>Kontakt</h2>
						<p class="address">
							Atira A/S
							<br/>Niels Jernes Vej 10
							<br/>9220 Aalborg Oest
							<br/>Danmark
							<br/>Telefon: (+45) 96 35 61 00
							<br/>CVR nr.: 26835526
						</p>
						<p>
							<strong>Generel info: </strong>
							<a href="mailto:info@atira.dk"><span>info@atira.dk</span></a><br/>
							<strong>PURE support: </strong>
							<a href="mailto:support@pure.atira.dk"><span>support@pure.atira.dk</span></a>
							<br/><strong>Anden support: </strong>
							<a href="mailto:support@atira.dk"><span>support@atira.dk</span></a>
						</p>
					</xsl:otherwise>
				</xsl:choose>
			</div>
			<div class="right">
				<xsl:choose>
				<xsl:when test="$language='da'">
					<h2>Om Atira</h2>
					<p>Vi udvikler kunde- og branche-specifikke løsninger i videnstunge forretningsmiljøer. Vores fagområde er 
						server-applikationer og systemintegration i service-orienterede arkitekturer.</p>
						<p>Vores primære projektmetode 
						er SCRUM. Foruden Danmark arbejder vi p.t. i Finland, Belgien, Tyskland, Sverige og Holland.</p>
				</xsl:when>
				<xsl:otherwise>
					<h2>About Atira</h2>
					<p>We specialize in customer- and domain-specific solutions for knowledge intensive sectors. Our area 
						is server-side applications and integration in service oriented architectures.</p>
						<p>Our development and 
						project management method is SCRUM. We work in a number of European countries.</p>
				</xsl:otherwise>
				</xsl:choose>
			</div>
		</div>
	</div>
</xsl:template>

<xsl:template name="poster-news">
	<xsl:for-each select=".//o:object[@type='news']">
		<div class="news">
			<xsl:if test=".//n:startdate">
			<span class="date"><xsl:value-of select="number(.//n:startdate/@day)"/>/<xsl:value-of select="number(.//n:startdate/@month)"/>/<xsl:value-of select="substring(.//n:startdate/@year,3,2)"/></span>
			</xsl:if>
			<strong><xsl:value-of select="o:title"/></strong>
			<p><xsl:value-of select="o:note"/></p>
			<xsl:for-each select=".//o:link">
				<a title="{@alternative}" class="common">
					<xsl:call-template name="link"/>
					<span><xsl:value-of select="@title"/></span>
				</a><xsl:text> </xsl:text>
			</xsl:for-each>
		</div>
	</xsl:for-each>
</xsl:template>

<xsl:template name="front-placard-da">
	<div class="placard">
		<div class="inner_placard" style="width: 1796px;">
			<div class="poster poster_pure">
				<div onclick="document.location='/da/pure/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=2">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/da/pure/';" class="right"><xsl:comment/></div>
			</div>
			<div class="poster poster_2">
				<div onclick="document.location='/da/loesninger/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=3">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/da/loesninger/';" class="right"><xsl:comment/></div>
			</div>
			<!--
			<div class="poster poster_3">
				<div onclick="document.location='/da/atira/jobs.html';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=4">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/da/atira/jobs.html';" class="right"><xsl:comment/></div>
			</div>
			-->
		</div>
	</div>
</xsl:template>

<xsl:template name="front-placard-en">
	<div class="placard">
		<div class="inner_placard" style="width: 8082px;">
			<div class="poster poster_pure poster_pure_1">
				<div onclick="document.location='/en/pure/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=2">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/en/pure/';" class="right"><xsl:comment/></div>
			</div>
			<div class="poster poster_pure poster_pure_2">
				<div onclick="document.location='/en/pure/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=2">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/en/pure/';" class="right"><xsl:comment/></div>
			</div>
			<div class="poster poster_pure poster_pure_3">
				<div onclick="document.location='/en/pure/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=2">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/en/pure/';" class="right"><xsl:comment/></div>
			</div>
			<div class="poster poster_pure poster_pure_4">
				<div onclick="document.location='/en/pure/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=2">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/en/pure/';" class="right"><xsl:comment/></div>
			</div>
			<div class="poster poster_pure poster_pure_5">
				<div onclick="document.location='/en/pure/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=2">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/en/pure/';" class="right"><xsl:comment/></div>
			</div>
			<div class="poster poster_pure poster_pure_6">
				<div onclick="document.location='/en/pure/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=2">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/en/pure/';" class="right"><xsl:comment/></div>
			</div>
			<div class="poster poster_pure poster_pure_7">
				<div onclick="document.location='/en/pure/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=2">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/en/pure/';" class="right"><xsl:comment/></div>
			</div>
			<div class="poster poster_pure poster_pure_8">
				<div onclick="document.location='/en/pure/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=2">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/en/pure/';" class="right"><xsl:comment/></div>
			</div>
			<!--
			<div class="poster poster_pure poster_pure_9">
				<div onclick="document.location='/en/pure/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=2">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/en/pure/';" class="right"><xsl:comment/></div>
			</div>
			<div class="poster poster_2">
				<div onclick="document.location='/en/solutions/';" class="left"><xsl:comment/></div>
				<div class="center">
					<xsl:for-each select="//pn:news">
						<xsl:if test="position()=3">
							<xsl:call-template name="poster-news"/>
						</xsl:if>
					</xsl:for-each>
					<xsl:comment/>
				</div>
				<div onclick="document.location='/en/solutions/';" class="right"><xsl:comment/></div>
			</div>
			-->
		</div>
	</div>
</xsl:template>

<xsl:template name="front-ticker">
	<xsl:variable name="language" select="//p:page/p:meta/p:language"/>
	<div class="ticker">
		<div class="left">
			<div>
				<xsl:if test="$language='en'"><strong>Latest news:</strong></xsl:if>
				<xsl:if test="$language!='en'"><strong>Seneste nyheder:</strong></xsl:if>
				<img src="{$path}style/{$design}/gfx/front/ticker_previous.png" onmouseover="this.src='{$path}style/{$design}/gfx/front/ticker_previous_hover.png'" onmouseout="this.src='{$path}style/{$design}/gfx/front/ticker_previous.png'" class="arrow previous" alt=""/>
				<img src="{$path}style/{$design}/gfx/front/ticker_next.png" onmouseover="this.src='{$path}style/{$design}/gfx/front/ticker_next_hover.png'" onmouseout="this.src='{$path}style/{$design}/gfx/front/ticker_next.png'" class="arrow next" alt=""/>
			</div>
		</div>
		<div class="news"><a class="item"><xsl:comment/></a></div>
		<div class="right">
			<xsl:if test="$language='en'"><a href="{$path}en/latest/">More news »</a></xsl:if>
			<xsl:if test="$language!='en'"><a href="{$path}da/aktuelt/">Flere nyheder »</a></xsl:if>
			<xsl:comment/>
		</div>
	</div>
	<script charset="utf-8" type="text/javascript">
		var ticker = new Atira.Website.Ticker();
		<xsl:for-each select="//pn:news">
			<xsl:if test="position()=1">
				<xsl:for-each select=".//o:object[@type='news']">
					{
						var title = "<xsl:value-of select="o:title"/>";
						var link = null;
						<xsl:for-each select=".//o:link[1]">
							link="<xsl:call-template name="link-href"/>";
						</xsl:for-each>
						ticker.addItem(title,link,null);
					}
				</xsl:for-each>
			</xsl:if>
		</xsl:for-each>
		ticker.start();
	</script>
</xsl:template>

<xsl:template name="front-posters">
	<xsl:variable name="language" select="//p:page/p:meta/p:language"/>
	<div class="posters">
		<div class="left">
			<div class="box software">
				<div class="box_top"><div><div><xsl:comment/></div></div></div>
				<div class="box_body">
					<div class="small_poster">
						<xsl:choose>
							<xsl:when test="$language='en'">
								<h2><strong>software</strong> solutions</h2>
								<p>We are a software development and consultancy company.</p>
								<div class="links">
									<a href="{$path}en/solutions/"><span>Solutions</span></a>
									<xsl:text> · </xsl:text>
									<a href="{$path}en/method/"><span>Method</span></a>
								</div>
								<a href="{$path}en/solutions/" class="button"><span>Mere</span></a>
							</xsl:when>
							<xsl:otherwise>
								<h2><strong>software</strong> løsninger</h2>
								<p>Vi er et software udviklingsfirma, der leverer kunde-specifikke løsninger.</p>
								<div class="links">
									<a href="{$path}da/loesninger/"><span>Løsninger</span></a>
									<xsl:text> · </xsl:text>
									<a href="{$path}da/metode/"><span>Metode</span></a>
								</div>
								<a href="{$path}da/loesninger/" class="button"><span>Mere</span></a>
							</xsl:otherwise>
						</xsl:choose>
					</div>
				</div>
				<div class="box_bottom"><div><div><xsl:comment/></div></div></div>
			</div>
		</div>
		<div class="center">
			<div class="box pure">
				<div class="box_top"><div><div><xsl:comment/></div></div></div>
				<div class="box_body">
					<div class="small_poster">
						<xsl:choose>
							<xsl:when test="$language='en'">
								<h2><strong>pure</strong> version 4</h2>
								<p>Enterprise repository platform for registering, archiving, e-publishing and managing research.</p>
								<div class="links">
									<a href="{$path}en/pure/references.html"><span>References</span></a>
									<xsl:text> · </xsl:text>
									<a href="{$path}en/latest/"><span>News</span></a>
								</div>
								<a href="{$navigation-path}en/pure/" class="button"><span>Visit</span></a>
							</xsl:when>
							<xsl:otherwise>
								<h2><strong>pure</strong> version 4</h2>
								<p>Forskningsbase til registrering, arkivering, e-publisering og rapportering af forskning.</p>
								<div class="links">
									<a href="{$path}da/pure/referencer.html"><span>Referencer</span></a>
									<xsl:text> · </xsl:text>
									<a href="{$path}da/aktuelt/"><span>Aktuelt</span></a>
								</div>
								<a href="{$path}da/pure/" class="button"><span>Besøg</span></a>
							</xsl:otherwise>
						</xsl:choose>
					</div>
				</div>
				<div class="box_bottom"><div><div><xsl:comment/></div></div></div>
			</div>
		</div>
		<div class="right">
			<div class="box technology">
				<div class="box_top"><div><div><xsl:comment/></div></div></div>
				<div class="box_body">
					<div class="small_poster">
						<xsl:choose>
							<xsl:when test="$language='en'">
								<h2><strong>about</strong> atira</h2>
								<p>Company profile, Legal and financial profile, Technical profile, Organisation</p>
								<a href="{$path}en/about/" class="button"><span>Visit</span></a>
							</xsl:when>
							<xsl:otherwise>
								<h2><strong>om</strong> atira</h2>
								<p>Interne projekter, blogposts, baggrunds-artikler, netværk, downloads.</p>
								<a href="{$path}da/om/" class="button"><span>Besøg</span></a>
							</xsl:otherwise>
						</xsl:choose>
					</div>
				</div>
				<div class="box_bottom"><div><div><xsl:comment/></div></div></div>
			</div>
		</div>
	</div>
</xsl:template>

<xsl:template name="front-da">
	<div class="container front lang_da">
		<xsl:call-template name="front-placard-da"/>
		<xsl:call-template name="front-ticker"/>
		<xsl:call-template name="front-posters"/>
	</div>
	<script type="text/javascript">
		In2iGui.onDomReady(function() {
			new Atira.Website.Poster({random:true});
		})
	</script>
</xsl:template>

<xsl:template name="front-en">
	<div class="container front lang_en">
		<xsl:call-template name="front-placard-en"/>
		<xsl:call-template name="front-ticker"/>
		<xsl:call-template name="front-posters"/>
	</div>
	<script type="text/javascript">
		In2iGui.onDomReady(function() {
			new Atira.Website.Poster();
		})
	</script>
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
<xsl:if test="not(@hidden='true') and position()>1">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
<xsl:otherwise>normal</xsl:otherwise>
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
<ul class="tabbar">
<xsl:apply-templates select="../f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
</ul>
</xsl:if>
</xsl:template>

<xsl:template name="thirdlevel">
<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
<ul>
<xsl:apply-templates select="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item"/>
</ul>
</xsl:if>
</xsl:template>

<xsl:template match="h:hierarchy/h:item/h:item">
<xsl:variable name="style">
<xsl:choose>
<xsl:when test="//p:page/@id=@page"><xsl:text>selected</xsl:text></xsl:when>
<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:text>highlighted</xsl:text></xsl:when>
<xsl:otherwise>standard</xsl:otherwise>
</xsl:choose>
</xsl:variable>
<xsl:if test="not(@hidden='true')">
<li><a class="{$style}">
<xsl:call-template name="link"/>
<span><xsl:value-of select="@title"/></span>
</a></li>
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
<li><a class="{$style}">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a></li>
<xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
<ul style="padding-left: 12px;">
<xsl:apply-templates/>
</ul>
</xsl:if>
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
<span class="links">
<xsl:apply-templates/>
<xsl:if test="f:link"><span>&#160;&#183;&#160;</span></xsl:if>
<a title="XHTML 1.1" class="common" href="http://validator.w3.org/check?uri=referer">XHTML 1.1</a>
</span>
</xsl:template>

<xsl:template match="f:links/f:bottom/f:link">
<xsl:if test="position()>1"><span>&#160;&#183;&#160;</span></xsl:if>
<a title="{@alternative}" class="common">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>

<xsl:template match="f:links/f:top/f:link">
<span>&#160;&#183;&#160;</span>
<a title="{@alternative}" class="common">
<xsl:call-template name="link"/>
<xsl:value-of select="@title"/>
</a>
</xsl:template>



<!--            Text              -->





<xsl:template match="f:text/f:bottom">
<span class="text">
<xsl:apply-templates/>
</span>
</xsl:template>

<xsl:template match="f:text/f:bottom/f:break">
	<br/>
</xsl:template>


<xsl:template match="f:text/f:bottom/f:link">
<a title="{@alternative}" class="common">
<xsl:call-template name="link"/>
<xsl:apply-templates/>
</a>
</xsl:template>




<!--            News              -->





<xsl:template match="f:newsblock">
<div class="news">
<xsl:if test="@title!=''">
<strong class="header"><xsl:value-of select="@title"/></strong>
</xsl:if>
<xsl:apply-templates/>
</div>
</xsl:template>

<xsl:template match="f:newsblock//o:object">
<div class="news_item">
<div class="title">
<xsl:value-of select="o:title"/>
</div>
<div class="note"><xsl:comment/>
<xsl:apply-templates select="o:note"/>
</div>
<xsl:apply-templates select="o:sub/n:news/n:startdate"/>
<xsl:if test="o:links">
<div class="links">
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
<div class="date"><xsl:value-of select="@day"/>/<xsl:value-of select="@month"/>/<xsl:value-of select="substring(@year,3,2)"/></div>
</xsl:template>

<xsl:template match="f:newsblock//o:link">
<xsl:if test="position()>1"><xsl:text> &#160; </xsl:text></xsl:if>
<a title="{@alternative}" class="common link">
<xsl:call-template name="link"/>
<span><xsl:value-of select="@title"/></span>
</a>
</xsl:template>



<!--                  Search                     -->


<xsl:template name="search">
<xsl:if test="f:frame/f:search">
<form action="{$navigation-path}" method="get" class="search" accept-charset="UTF-8">
<div>
<div>
<input type="hidden" name="id" value="{f:frame/f:search/@page}"/>
<xsl:for-each select="f:frame/f:search/f:types/f:type">
<input type="hidden" name="{@unique}" value="on"/>
</xsl:for-each>
<input type="text" class="text" name="query" id="searchfield"></input>
<input type="submit" class="submit" value="Søg"/>
</div>
</div>
</form>
<script type="text/javascript"><xsl:comment>
new op.SearchField({element:'searchfield'});
</xsl:comment>
</script>
</xsl:if>
</xsl:template>


<!--                 Support templates                  -->



</xsl:stylesheet>