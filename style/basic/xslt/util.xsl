<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:header="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"
 xmlns:text="http://uri.in2isoft.com/onlinepublisher/part/text/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 xmlns:part="http://uri.in2isoft.com/onlinepublisher/part/1.0/"
 exclude-result-prefixes="p f h header text util part"
 >

<xsl:variable name="timestamp-query">
	<xsl:if test="$urlrewrite!='true'">
		<xsl:text>?version=</xsl:text><xsl:value-of select="$timestamp"/>
	</xsl:if>
</xsl:variable> 

<xsl:variable name="timestamp-url">
	<xsl:if test="$urlrewrite='true'">
		<xsl:text>/version</xsl:text><xsl:value-of select="$timestamp"/>
	</xsl:if>
</xsl:variable> 

<xsl:template name="util:link">
	<xsl:attribute name="title"><xsl:value-of select="@alternative"/></xsl:attribute>
	<xsl:choose>
		<xsl:when test="$editor='true'">
			<xsl:attribute name="href">javascript://</xsl:attribute>
			<xsl:choose>
				<xsl:when test="@id">
					<xsl:attribute name="onclick">
					controller.linkWasClicked({
						id : <xsl:value-of select="@id"/>
						, event : event
						, node : this
						<xsl:if test="ancestor::part:part/@id">,part : <xsl:value-of select="ancestor::part:part/@id"/></xsl:if>
					}); return false;
					</xsl:attribute>
					<xsl:attribute name="oncontextmenu">
						controller.linkMenu({
							id : <xsl:value-of select="@id"/>
							, event : event
							, node : this
							<xsl:if test="ancestor::part:part/@id">,part : <xsl:value-of select="ancestor::part:part/@id"/></xsl:if>
						});
					</xsl:attribute>
				</xsl:when>
				<xsl:otherwise>
					<xsl:attribute name="onclick">return false;</xsl:attribute>
				</xsl:otherwise>
			</xsl:choose>
			<xsl:if test="@part-id">
				<span class="editor_link_bound"><xsl:comment/></span>
			</xsl:if>
		</xsl:when>
		<xsl:otherwise>
			<xsl:choose>
				<xsl:when test="@path and $preview='false'">
					<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/><xsl:value-of select="@path"/></xsl:attribute>
				</xsl:when>
				<xsl:when test="@page">
					<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page"/></xsl:attribute>
				</xsl:when>
				<xsl:when test="@page-reference">
					<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page-reference"/></xsl:attribute>
				</xsl:when>
				<xsl:when test="@url">
					<xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
				</xsl:when>
				<xsl:when test="@file">
					<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?file=<xsl:value-of select="@file"/><xsl:if test="@target='_download'">&amp;download=true</xsl:if></xsl:attribute>
				</xsl:when>
				<xsl:when test="@email">
					<xsl:attribute name="href">mailto:<xsl:value-of select="@email"/></xsl:attribute>
				</xsl:when>
			</xsl:choose>
			<xsl:choose>
				<xsl:when test="@target='_blank'">
					<xsl:attribute name="onclick">try {window.open(this.getAttribute('href')); return false;} catch (igonre) {}</xsl:attribute>
				</xsl:when>
				<xsl:when test="@target and @target!='_self' and @target!='_download'">
					<xsl:attribute name="target"><xsl:value-of select="@target"/></xsl:attribute>
				</xsl:when>
			</xsl:choose>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="util:link-href">
	<xsl:choose>
		<xsl:when test="@path and $preview='false'">
			<xsl:value-of select="$navigation-path"/><xsl:value-of select="@path"/>
		</xsl:when>
		<xsl:when test="@page">
			<xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page"/>
		</xsl:when>
		<xsl:when test="@page-reference">
			<xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page-reference"/>
		</xsl:when>
		<xsl:when test="@url">
			<xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
		</xsl:when>
		<xsl:when test="@file">
			<xsl:value-of select="$navigation-path"/>?file=<xsl:value-of select="@file"/><xsl:if test="@target='_download'">&amp;download=true</xsl:if>
		</xsl:when>
		<xsl:when test="@email">
			mailto:<xsl:value-of select="@email"/>
		</xsl:when>
	</xsl:choose>
</xsl:template>


<xsl:template name="util:scripts">
	<xsl:choose>
		<xsl:when test="$preview='true'">
			<link rel="stylesheet" type="text/css" href="{$path}hui{$timestamp-url}/bin/minimized.css{$timestamp-query}"/>
		</xsl:when>
		<xsl:otherwise>
			<link rel="stylesheet" type="text/css" href="{$path}hui{$timestamp-url}/bin/minimized.site.css{$timestamp-query}"/>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:comment><![CDATA[[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>hui<xsl:value-of select="$timestamp-url"/>/css/msie6.css<xsl:value-of select="$timestamp-query"/><![CDATA["></link>
	<![endif]]]></xsl:comment>
	<xsl:comment><![CDATA[[if IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>hui<xsl:value-of select="$timestamp-url"/>/css/msie7.css<xsl:value-of select="$timestamp-query"/><![CDATA["></link>
	<![endif]]]></xsl:comment>
	<xsl:choose>
		<xsl:when test="$preview='true'">
			<xsl:choose>
				<xsl:when test="$development='true'">
					<script src="{$path}hui{$timestamp-url}/bin/combined.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
					<script src="{$path}hui{$timestamp-url}/js/Editor.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
				</xsl:when>
				<xsl:otherwise>
					<script src="{$path}hui{$timestamp-url}/bin/minimized.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
				</xsl:otherwise>
			</xsl:choose>
		</xsl:when>
		<xsl:when test="$development='true'">
			<script src="{$path}hui{$timestamp-url}/js/hui.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}hui{$timestamp-url}/js/hui_animation.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}hui{$timestamp-url}/js/hui_color.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}hui{$timestamp-url}/js/hui_require.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}hui{$timestamp-url}/js/ui.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}hui{$timestamp-url}/js/ImageViewer.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}hui{$timestamp-url}/js/Box.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}hui{$timestamp-url}/js/SearchField.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
		</xsl:when>
		<xsl:otherwise>
			<script src="{$path}hui{$timestamp-url}/bin/minimized.site.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
		</xsl:otherwise>
	</xsl:choose>
	<script src="{$path}style{$timestamp-url}/basic/js/OnlinePublisher.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
	<script type="text/javascript"><xsl:comment>
		hui.ui.context = '<xsl:value-of select="$path"/>';
		op.context = '<xsl:value-of select="$path"/>';
		op.page.id=<xsl:value-of select="@id"/>;
		op.page.template='<xsl:value-of select="$template"/>';
		op.page.path='<xsl:value-of select="$path"/>';
		op.page.pagePath='<xsl:value-of select="$page-path"/>';
		op.user = {
			username : '<xsl:value-of select="$username"/>',
			id : <xsl:value-of select="$userid"/>,
			internal : <xsl:value-of select="$internal-logged-in"/>
		};
		op.preview=<xsl:value-of select="$preview"/>;
		op.ignite();
	</xsl:comment></script>
	<xsl:if test="$preview='true'">
		<script src="editor.js?version={$timestamp}" type="text/javascript"><xsl:comment/></script>
		<script src="{$path}Editor/Template/{$template}/js/editor.php?version={$timestamp}" type="text/javascript"><xsl:comment/></script>
	</xsl:if>
</xsl:template>

<xsl:template name="util:scripts-adaptive">
	<script src="{$path}hui{$timestamp-url}/lib/ios-orientationchange-fix.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
</xsl:template>

<xsl:template name="util:html-attributes">
	<xsl:attribute name="xmlns">http://www.w3.org/1999/xhtml</xsl:attribute>
	<xsl:if test="//p:page/p:meta/p:language">
		<xsl:attribute name="lang"><xsl:value-of select="//p:page/p:meta/p:language"/></xsl:attribute>
		<xsl:attribute name="xml:lang"><xsl:value-of select="//p:page/p:meta/p:language"/></xsl:attribute>
	</xsl:if>
</xsl:template>

<xsl:template name="util:style-ie6">
	<xsl:comment><![CDATA[[if lt IE 7]>
		<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie6.css"> </link>
	<![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style-ie7">
	<xsl:comment><![CDATA[[if IE 7]>
		<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie7.css"> </link>
	<![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style-ie8">
	<xsl:comment><![CDATA[[if IE 8]>
		<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie8.css"> </link>
	<![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style-lt-ie9">
	<xsl:comment><![CDATA[[if lt IE 9]>
		<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie_lt9.css"> </link>
	<![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style">
	<link rel="stylesheet" type="text/css" href="{$path}style/basic/css/{$template}.css"/>
	<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/style.php"/>
	<!--
	<xsl:choose>
		<xsl:when test="$template='document'">
			<link rel="stylesheet" type="text/css" href="{$path}style/{$design}/css/{$template}.php"/>
		</xsl:when>
		<xsl:otherwise>
		</xsl:otherwise>
	</xsl:choose>
	-->
	<xsl:if test="//header:style[contains(@font-family,'Cabin Sketch')] or //text:style[contains(@font-family,'Cabin Sketch')]">
		<link href='http://fonts.googleapis.com/css?family=Cabin+Sketch:bold' rel='stylesheet' type='text/css'/>
	</xsl:if>
	<xsl:if test="//header:style[contains(@font-family,'Droid Sans')] or //text:style[contains(@font-family,'Droid Sans')]">
		<link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css' />
	</xsl:if>
	<xsl:if test="//header:style[contains(@font-family,'Just Me Again Down Here')] or //text:style[contains(@font-family,'Just Me Again Down Here')]">
		<link href='http://fonts.googleapis.com/css?family=Just+Me+Again+Down+Here' rel='stylesheet' type='text/css'/>
	</xsl:if>
	<xsl:if test="//header:style[contains(@font-family,'Crimson Text')] or //text:style[contains(@font-family,'Crimson Text')]">
		<link href='http://fonts.googleapis.com/css?family=Crimson+Text:regular,bold' rel='stylesheet' type='text/css' />
	</xsl:if>
	<xsl:if test="//header:style[contains(@font-family,'Luckiest Guy')] or //text:style[contains(@font-family,'Luckiest Guy')]">
		<link href='http://fonts.googleapis.com/css?family=Luckiest+Guy' rel='stylesheet' type='text/css' />
	</xsl:if>
	<xsl:if test="//header:style[contains(@font-family,'Dancing Script')] or //text:style[contains(@font-family,'Dancing Script')]">
		<link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css' />
	</xsl:if>
	
</xsl:template>

<xsl:template name="util:weekday">
	<xsl:param name="node"/>
	<xsl:choose>
		<xsl:when test="//p:page/p:meta/p:language='en'">
			<xsl:choose>
				<xsl:when test="$node/@weekday=0">Sunday</xsl:when>
				<xsl:when test="$node/@weekday=1">Monday</xsl:when>
				<xsl:when test="$node/@weekday=2">Tuesday</xsl:when>
				<xsl:when test="$node/@weekday=3">Wednesday</xsl:when>
				<xsl:when test="$node/@weekday=4">Thursday</xsl:when>
				<xsl:when test="$node/@weekday=5">Friday</xsl:when>
				<xsl:when test="$node/@weekday=6">Saturday</xsl:when>
			</xsl:choose>
		</xsl:when>
		<xsl:otherwise>
			<xsl:choose>
				<xsl:when test="$node/@weekday=0">Søndag</xsl:when>
				<xsl:when test="$node/@weekday=1">Mandag</xsl:when>
				<xsl:when test="$node/@weekday=2">Tirsdag</xsl:when>
				<xsl:when test="$node/@weekday=3">Onsdag</xsl:when>
				<xsl:when test="$node/@weekday=4">Torsdag</xsl:when>
				<xsl:when test="$node/@weekday=5">Fredag</xsl:when>
				<xsl:when test="$node/@weekday=6">Lørdag</xsl:when>
			</xsl:choose>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="util:month">
	<xsl:param name="node"/>
	<xsl:choose>
		<xsl:when test="//p:page/p:meta/p:language='en'">
			<xsl:choose>
				<xsl:when test="number($node/@month)=1">January</xsl:when>
				<xsl:when test="number($node/@month)=2">February</xsl:when>
				<xsl:when test="number($node/@month)=3">March</xsl:when>
				<xsl:when test="number($node/@month)=4">April</xsl:when>
				<xsl:when test="number($node/@month)=5">May</xsl:when>
				<xsl:when test="number($node/@month)=6">June</xsl:when>
				<xsl:when test="number($node/@month)=7">July</xsl:when>
				<xsl:when test="number($node/@month)=8">August</xsl:when>
				<xsl:when test="number($node/@month)=9">September</xsl:when>
				<xsl:when test="number($node/@month)=10">October</xsl:when>
				<xsl:when test="number($node/@month)=11">November</xsl:when>
				<xsl:when test="number($node/@month)=12">December</xsl:when>
			</xsl:choose>
		</xsl:when>
		<xsl:otherwise>
			<xsl:choose>
				<xsl:when test="number($node/@month)=1">januar</xsl:when>
				<xsl:when test="number($node/@month)=2">februar</xsl:when>
				<xsl:when test="number($node/@month)=3">marts</xsl:when>
				<xsl:when test="number($node/@month)=4">april</xsl:when>
				<xsl:when test="number($node/@month)=5">maj</xsl:when>
				<xsl:when test="number($node/@month)=6">juni</xsl:when>
				<xsl:when test="number($node/@month)=7">juli</xsl:when>
				<xsl:when test="number($node/@month)=8">august</xsl:when>
				<xsl:when test="number($node/@month)=9">september</xsl:when>
				<xsl:when test="number($node/@month)=10">oktober</xsl:when>
				<xsl:when test="number($node/@month)=11">november</xsl:when>
				<xsl:when test="number($node/@month)=12">december</xsl:when>
			</xsl:choose>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="util:long-date-time">
	<xsl:param name="node"/>
	<xsl:choose>
		<xsl:when test="//p:page/p:meta/p:language='en'">
			<xsl:call-template name="util:weekday"><xsl:with-param name="node" select="$node"/></xsl:call-template>
			<xsl:text>, </xsl:text>
			<xsl:value-of select="number($node/@day)"/><xsl:text> </xsl:text>
			<xsl:call-template name="util:month"><xsl:with-param name="node" select="$node"/></xsl:call-template>
			<xsl:if test="number($node/@hour)>0 or number($node/@minute)>0">
				<xsl:text> at </xsl:text><xsl:value-of select="$node/@hour"/>:<xsl:value-of select="$node/@minute"/>
			</xsl:if>
		</xsl:when>
		<xsl:otherwise>
			<xsl:call-template name="util:weekday"><xsl:with-param name="node" select="$node"/></xsl:call-template>
			<xsl:text> d. </xsl:text>
			<xsl:value-of select="number($node/@day)"/><xsl:text>. </xsl:text>
			<xsl:call-template name="util:month"><xsl:with-param name="node" select="$node"/></xsl:call-template>
			<xsl:if test="number($node/@hour)>0 or number($node/@minute)>0">
				<xsl:text> kl. </xsl:text><xsl:value-of select="$node/@hour"/>:<xsl:value-of select="$node/@minute"/>
			</xsl:if>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="util:googleanalytics">
	<xsl:param name="code" select="//p:meta/p:analytics/@key"/>
	<xsl:if test="not($preview='true') and $code!=''">
		<script type="text/javascript">
		try {
			if (document.location.hostname!=="localhost") {
				//,'_trackPageLoadTime'
				var _gaq=[['_setAccount','<xsl:value-of select="$code"/>'],['_trackPageview']];
				 (function() {
    				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(ga, s);
				})();
			}
		} catch(ex) {}
		</script>
	</xsl:if>
</xsl:template>

<xsl:template name="util:metatags">
	<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
	<xsl:if test="p:meta/p:description">
		<meta name="Description" content="{p:meta/p:description}"></meta>
	</xsl:if>
	<meta name="robots" content="index,follow"></meta>
</xsl:template>

<xsl:template name="util:languages">
	<span class="layout_languages">
		<xsl:for-each select="//p:page/p:context/p:home[@language and @language!=//p:page/p:meta/p:language and not(@language=//p:page/p:context/p:translation/@language)]">
			<xsl:call-template name="util:language"/>
		</xsl:for-each>
		<xsl:for-each select="//p:page/p:context/p:translation">
			<xsl:call-template name="util:language"/>
		</xsl:for-each>
		<xsl:comment/>
	</span>
</xsl:template>

<xsl:template name="util:language">
	<a class="layout_language_{@language}">
		<xsl:call-template name="util:link"/>
		<span>
		<xsl:choose>
			<xsl:when test="@language='da'">Dansk version</xsl:when>
			<xsl:when test="@language='en'">English version</xsl:when>
			<xsl:otherwise><xsl:value-of select="@language"/></xsl:otherwise>
		</xsl:choose>
		</span>
	</a><xsl:text> </xsl:text>
</xsl:template>

<xsl:template name="util:userstatus">
	<xsl:if test="//f:userstatus">
		<div class="layout_userstatus">
			<xsl:choose>
				<xsl:when test="$userid>0">
					<strong><xsl:text>Bruger: </xsl:text></strong><xsl:value-of select="$usertitle"/>
					<xsl:text> </xsl:text>
					<a href="{$path}?id={//f:userstatus/@page}&amp;logout=true"><xsl:text>log ud</xsl:text></a>
				</xsl:when>
				<xsl:otherwise>
				<span>Ikke logget ind</span>
				<xsl:text> </xsl:text>
				<a href="{$path}?id={//f:userstatus/@page}">log ind</a>
				</xsl:otherwise>
			</xsl:choose>
		</div>
	</xsl:if>
</xsl:template>

<xsl:template name="util:hierarchy-first-level">
	<ul>
		<xsl:for-each select="//f:frame/h:hierarchy/h:item">
			<xsl:if test="not(@hidden='true')">
				<li>
				<xsl:choose>
					<xsl:when test="//p:page/@id=@page"><xsl:attribute name="class">selected</xsl:attribute></xsl:when>
					<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">highlighted</xsl:attribute></xsl:when>
				</xsl:choose>
				<a>
					<xsl:call-template name="util:link"/>
					<span><xsl:value-of select="@title"/></span>
				</a>
				</li>
			</xsl:if>
		</xsl:for-each>
	</ul>
</xsl:template>

<xsl:template name="util:hierarchy-after-first-level">
	<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
		<ul>
			<xsl:for-each select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
				<xsl:call-template name="util:hierarchy-item-iterator"/>
			</xsl:for-each>
		</ul>
	</xsl:if>
</xsl:template>

<xsl:template name="util:hierarchy-all-levels">
	<ul>
		<xsl:for-each select="//f:frame/h:hierarchy/h:item">
			<xsl:call-template name="util:hierarchy-item-iterator"/>
		</xsl:for-each>
	</ul>
</xsl:template>

<xsl:template name="util:hierarchy-item-iterator">
	<xsl:if test="not(@hidden='true')">
		<li>
			<a>
				<xsl:choose>
					<xsl:when test="//p:page/@id=@page"><xsl:attribute name="class">selected</xsl:attribute></xsl:when>
					<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">highlighted</xsl:attribute></xsl:when>
				</xsl:choose>
				<xsl:call-template name="util:link"/>
				<span><xsl:value-of select="@title"/></span>
			</a>
			<xsl:if test="descendant-or-self::*/@page=//p:page/@id and h:item">
				<ul>
					<xsl:for-each select="h:item">
						<xsl:call-template name="util:hierarchy-item-iterator"/>
					</xsl:for-each>
				</ul>
			</xsl:if>
		</li>
	</xsl:if>
</xsl:template>
<!--
	<xsl:template name="util:share">
		<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
		<g:plusone size="small"></g:plusone>"
		<div id="fb-root"></div>
		<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
		<fb:like href="" send="false" layout="button_count" width="450" show_faces="false" font="lucida grande"></fb:like>
	</xsl:template>
-->
</xsl:stylesheet>