<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:p="http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/"
 xmlns:f="http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/"
 xmlns:h="http://uri.in2isoft.com/onlinepublisher/publishing/hierarchy/1.0/"
 xmlns:header="http://uri.in2isoft.com/onlinepublisher/part/header/1.0/"
 xmlns:text="http://uri.in2isoft.com/onlinepublisher/part/text/1.0/"
 xmlns:movie="http://uri.in2isoft.com/onlinepublisher/part/movie/1.0/"
 xmlns:util="http://uri.in2isoft.com/onlinepublisher/util/"
 xmlns:part="http://uri.in2isoft.com/onlinepublisher/part/1.0/"
 exclude-result-prefixes="p f h header text util part"
 >

<xsl:variable name="only-inline" select="'!true'" />

<xsl:variable name="timestamp-query">
	<xsl:if test="$urlrewrite!='true'">
		<xsl:text>?version=</xsl:text><xsl:value-of select="$timestamp"/>
	</xsl:if>
</xsl:variable>

<xsl:variable name="timestamp-url">
	<xsl:if test="$urlrewrite='true'">
		<xsl:text>version</xsl:text><xsl:value-of select="$timestamp"/><xsl:text>/</xsl:text>
	</xsl:if>
</xsl:variable>


<!-- Links -->

<xsl:template name="util:link">
	<xsl:attribute name="title"><xsl:value-of select="@alternative"/></xsl:attribute>
	<xsl:choose>
		<xsl:when test="$editor='true'">
			<xsl:attribute name="href">javascript://</xsl:attribute>
			<xsl:choose>
				<xsl:when test="@id">
					<xsl:attribute name="onclick">
					linkController.linkWasClicked({
						id : <xsl:value-of select="@id"/>
						, event : event
						, node : this
						<xsl:if test="ancestor::part:part/@id">,part : <xsl:value-of select="ancestor::part:part/@id"/></xsl:if>
					}); return false;
					</xsl:attribute>
					<xsl:attribute name="oncontextmenu">
						linkController.linkMenu({
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
					<xsl:attribute name="href">
						<xsl:value-of select="$navigation-path"/>
						<xsl:choose>
							<xsl:when test="starts-with(@path,'/')">
								<xsl:value-of select="substring(@path,2)"/>
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="@path"/>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:attribute>
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
					<xsl:attribute name="onclick">try {window.open(this.getAttribute('href')); return false;} catch (ignore) {}</xsl:attribute>
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




<!-- Uncategorized -->

<xsl:template name="util:googleanalytics">
	<xsl:param name="code" select="//p:meta/p:analytics/@key"/>
	<xsl:if test="not($preview='true') and $code!='' and $statistics='true'">
		<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', '<xsl:value-of select="$code"/>', {siteSpeedSampleRate : 20});ga('send', 'pageview');</script>
	</xsl:if>
</xsl:template>

<!--
<xsl:template name="util:googleanalytics_old">
	<xsl:param name="code" select="//p:meta/p:analytics/@key"/>
	<xsl:if test="not($preview='true') and $code!='' and $statistics='true'">
		<script>
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
-->

<xsl:template name="util:doctype">
    <xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html&gt;
</xsl:text>
</xsl:template>

<xsl:template name="util:title">
	<title>
        <xsl:if test="not(//p:page/@id=//p:context/p:home/@page)">
            <xsl:value-of select="//p:page/@title"/>
            <xsl:text> - </xsl:text>
        </xsl:if>
        <xsl:value-of select="//f:frame/@title"/>
    </title>
</xsl:template>

<xsl:template name="util:google-font">
    <xsl:param name="family" />
    <link href='http://fonts.googleapis.com/css?family={$family}' rel='stylesheet' type='text/css'/>
</xsl:template>

<xsl:template name="util:metatags">
	<meta http-equiv="content-type" content="text/html; charset=utf-8"></meta>
    <!-- Set on server 
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"></meta>
    -->
	<meta name="robots" content="index,follow"></meta>
	<meta property="og:title" content="{//p:page/@title}"/>
	<meta property="og:site_name" content="{//f:frame/@title}"/>
	<meta property="og:url" content="{$absolute-page-path}" />
	<xsl:if test="p:meta/p:description">
		<meta property="og:description" content="{p:meta/p:description}" />
		<meta name="Description" content="{p:meta/p:description}"></meta>
	</xsl:if>
	<xsl:for-each select="//p:page/p:context/p:translation[@language and @language!=$language and not(@language=//p:page/@language)]">
		<link rel="alternate" hreflang="{@language}">
      <xsl:attribute name="href">
        <xsl:choose>
    		<xsl:when test="@path">
					<xsl:choose>
						<xsl:when test="starts-with(@path,'/')">
							<xsl:value-of select="@path"/>
						</xsl:when>
						<xsl:otherwise>
    			    <xsl:value-of select="$navigation-path"/><xsl:value-of select="@path"/>
						</xsl:otherwise>
					</xsl:choose>
    		</xsl:when>
    		<xsl:when test="@page">
    			<xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="@page"/>
    		</xsl:when>
        </xsl:choose>
      </xsl:attribute>
    </link>
	</xsl:for-each>
</xsl:template>

<xsl:template name="util:feedback">
	<xsl:param name="text">Feedback</xsl:param>
	<p class="layout_feedback">
		<a class="common" href="javascript://" onclick="op.feedback(this)"><span><xsl:value-of select="$text"/></span></a>
	</p>
</xsl:template>

<xsl:template name="util:watermark">
	<xsl:comment>
    _    _                             _          
   | |  | |                           (_)         
   | |__| |_   _ _ __ ___   __ _ _ __  _ ___  ___ 
   |  __  | | | | '_ ` _ \ / _` | '_ \| / __|/ _ \
   | |  | | |_| | | | | | | (_| | | | | \__ \  __/
   |_|  |_|\__,_|_| |_| |_|\__,_|_| |_|_|___/\___|
   
   SOFTWARE FOR HUMANS     http://www.humanise.dk/
    </xsl:comment>
</xsl:template>

<xsl:template name="util:userstatus">
	<xsl:if test="//f:userstatus">
		<div class="layout_userstatus">
			<xsl:choose>
				<xsl:when test="$userid>0">
					<strong><xsl:text>Bruger: </xsl:text></strong><xsl:value-of select="$usertitle"/>
					<xsl:text> </xsl:text>
					<a href="{$path}?id={//f:userstatus/@page}&amp;logout=true" class="common common_link"><span><xsl:text>log ud</xsl:text></span></a>
				</xsl:when>
				<xsl:otherwise>
    				<span>Ikke logget ind</span>
    				<xsl:text> </xsl:text>
    				<a href="{$path}?id={//f:userstatus/@page}" class="common common_link"><span>log ind</span></a>
				</xsl:otherwise>
			</xsl:choose>
		</div>
	</xsl:if>
</xsl:template>

<xsl:template name="util:parameter">
    <xsl:param name="name" />
    <xsl:param name="default" />
    <div>
        <xsl:attribute name="data-editable">{"name":"<xsl:value-of select="$name"/>"}</xsl:attribute>
        <xsl:choose>
          <xsl:when test="//p:parameter[@name=$name]">
              <xsl:value-of select="//p:parameter[@name=$name]" disable-output-escaping="yes"/>
          </xsl:when>
          <xsl:otherwise>
              <xsl:copy-of select="$default"/>
          </xsl:otherwise>
        </xsl:choose>
    </div>
</xsl:template>


<!-- Scripts -->

<xsl:template name="util:scripts-build">
    <xsl:call-template name="util:_scripts-base"/>
    <xsl:call-template name="util:_scripts-msie"/>
	<xsl:choose>
		<xsl:when test="$preview='true'">
            <xsl:call-template name="util:_scripts-config"/>
			<script src="{$path}{$timestamp-url}hui/bin/minimized.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/Editor.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/Pages.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}style/{$design}/js/script.private.js{$timestamp-query}"><xsl:comment/></script>
		</xsl:when>
		<xsl:when test="$development='true'">
            <xsl:call-template name="util:_scripts-config"/>
			<script src="{$path}{$timestamp-url}hui/bin/joined.site.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}style/basic/js/OnlinePublisher.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}style/{$design}/js/script.dev.js{$timestamp-query}"><xsl:comment/></script>
        </xsl:when>
		<xsl:otherwise>
            <xsl:call-template name="util:_scripts-config"/>
			<script src="{$path}{$timestamp-url}style/{$design}/js/script.js{$timestamp-query}" async="async" defer="defer"><xsl:comment/></script>
			<!--
            <script>_editor.loadScript('<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/>/js/script.js<xsl:value-of select="$timestamp-query"/>')</script>
				-->
		</xsl:otherwise>
	</xsl:choose>
    <xsl:call-template name="util:_scripts-preview"/>
</xsl:template>

<xsl:template name="util:scripts">
    <xsl:call-template name="util:_scripts-base"/>
    <xsl:call-template name="util:_scripts-msie"/>
	<xsl:choose>
		<xsl:when test="$preview='true'">
			<xsl:choose>
				<xsl:when test="$development='true'">
					<script src="{$path}{$timestamp-url}hui/bin/joined.js{$timestamp-query}"><xsl:comment/></script>
				</xsl:when>
				<xsl:otherwise>
					<script src="{$path}{$timestamp-url}hui/bin/minimized.js{$timestamp-query}"><xsl:comment/></script>
				</xsl:otherwise>
			</xsl:choose>
			<script src="{$path}{$timestamp-url}hui/js/Editor.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/Pages.js{$timestamp-query}"><xsl:comment/></script>
		</xsl:when>
		<xsl:when test="$development='true'">
			<script src="{$path}{$timestamp-url}hui/js/hui.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/hui_animation.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/hui_parallax.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/hui_color.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/hui_require.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/ui.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/ImageViewer.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/Box.js{$timestamp-query}"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/SearchField.js{$timestamp-query}"><xsl:comment/></script>
		</xsl:when>
		<xsl:otherwise>
			<script src="{$path}{$timestamp-url}hui/bin/minimized.site.js{$timestamp-query}"><xsl:comment/></script>
		</xsl:otherwise>
	</xsl:choose>
	<script src="{$path}{$timestamp-url}style/basic/js/OnlinePublisher.js{$timestamp-query}"><xsl:comment/></script>
  <xsl:call-template name="util:_scripts-config"/>
  <xsl:call-template name="util:_scripts-preview"/>
</xsl:template>

<xsl:template name="util:_scripts-preview">
    
	<xsl:if test="$preview='true' and $mini!='true'">
		<script src="editor.js?version={$timestamp}"><xsl:comment/></script>
		<script src="{$path}{$timestamp-url}Editor/Template/{$template}/js/editor.php{$timestamp-query}"><xsl:comment/></script>
	</xsl:if>
</xsl:template>

<xsl:template name="util:_scripts-msie">
<!-- html5 -->
<xsl:comment><![CDATA[[if lt IE 9]>
<script src="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>hui/bin/compatibility.min.js<xsl:value-of select="$timestamp-query"/><![CDATA[" data-movable="false"></script>
<![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:_scripts-config">
<script>
<xsl:comment>
require(['hui.ui'],function() {hui.ui.context='<xsl:value-of select="$path"/>';hui.ui.language='<xsl:value-of select="$language"/>';});require(['op'],function() {op.context='<xsl:value-of select="$path"/>';op.page.id=<xsl:value-of select="@id"/>;op.page.template='<xsl:value-of select="$template"/>';op.page.path='<xsl:value-of select="$path"/>';op.page.pagePath='<xsl:value-of select="$page-path"/>';op.user={username:'<xsl:value-of select="$username"/>',id:<xsl:value-of select="$userid"/>,internal:<xsl:value-of select="$internal-logged-in"/>};op.preview=<xsl:value-of select="$preview"/>;op.ignite();})
</xsl:comment>
</script>
</xsl:template>

<xsl:template name="util:_scripts-base">
    <xsl:call-template name="util:script-inline">
        <xsl:with-param name="file" select="'style/basic/js/boot.js'"/>
        <xsl:with-param name="compiled"><![CDATA[!function(){function e(e,t){t in a?e(t,a[t]):i[t]?i[t].push(e):i[t]=[e]}function t(e,t){a[e]=t
var n=i[e]
n&&(n.forEach(function(n){n(e,t)}),i[e]=0)}function n(t,n){var i=t.length
if(i){var a=[],r=0
t.forEach(e.bind(0,function(e,o){a[t.indexOf(e)]=o,++r>=i&&n.apply(0,a)}))}else n()}var i={},a={}
require=n,define=function(e,i,a){a?n(i,function(){t(e,a.apply(0,arguments))}):t(e,i)}}(),function(e,t){e._editor={ready:function(n){"complete"==t.readyState?n():e.addEventListener?e.addEventListener("DOMContentLoaded",n,!1):t.addEventListener?t.addEventListener("load",n,!1):"undefined"!=typeof e.attachEvent&&e.attachEvent("onload",n)},viewReady:function(t){var n=e.requestAnimationFrame||e.mozRequestAnimationFrame||e.webkitRequestAnimationFrame||e.msRequestAnimationFrame
return n?n(t):void this.ready(t)},loadPart:function(e){require(["hui","hui.ui","op"],function(){_editor.loadScript(_editor.context+"style/basic/js/parts/"+e.name+".js")}),require(["op.part."+e.name],e.$ready)},loadCSS:function(e){this.viewReady(function(){_editor.inject(_editor._build("link",{rel:"stylesheet",type:"text/css",href:e}))})},_loaded:{},loadScript:function(e){this._loaded[e]||(this._loaded[e]=1,_editor.inject(this._build("script",{async:"async",src:e})))},_build:function(e,n){var i=t.createElement(e)
for(variable in n)i.setAttribute(variable,n[variable])
return i},inject:function(e){var n=t.getElementsByTagName("head")[0]
n?n.appendChild(e):this.ready(function(){_editor.inject(e)})},processNoscript:function(){this.ready(function(){for(var e=t.getElementsByTagName("noscript"),n=0;n<e.length;n++){var i=e[n]
if("js-async"==i.className&&i.firstChild){var a=t.createElement("div")
a.innerHTML=i.firstChild.nodeValue
for(var r=a.childNodes;r.length;){var o=a.removeChild(r[0])
i.parentNode.insertBefore(o,i)}}}})}},_editor.processNoscript()}(window,document)
]]></xsl:with-param>
    </xsl:call-template>
    <script>_editor.context = '<xsl:value-of select="$path"/>';</script>
</xsl:template>

<xsl:template name="util:scripts-adaptive">
	<script src="{$path}{$timestamp-url}hui/lib/ios-orientationchange-fix.js{$timestamp-query}"><xsl:comment/></script>
</xsl:template>

<xsl:template name="util:script-inline">
    <xsl:param name="compiled" />
    <xsl:param name="file"/>
    <xsl:choose>
      <xsl:when test="$development='true'">
  		<script src="{$path}{$timestamp-url}{$file}{$timestamp-query}"><xsl:comment/></script>
      </xsl:when>
      <xsl:otherwise>
      	<script>
            <xsl:text disable-output-escaping="yes">//&lt;![CDATA[
</xsl:text>
      		<xsl:value-of select="$compiled" disable-output-escaping="yes"/>
            <xsl:text disable-output-escaping="yes">
//]]&gt;</xsl:text>
      	</script>
      </xsl:otherwise>
    </xsl:choose>    
</xsl:template>

<xsl:template name="util:html-attributes">
	<xsl:if test="//p:page/p:meta/p:language">
		<xsl:if test="//p:page/p:meta/p:language/text()">
			<xsl:attribute name="lang"><xsl:value-of select="//p:page/p:meta/p:language"/></xsl:attribute>
			<xsl:attribute name="xml:lang"><xsl:value-of select="//p:page/p:meta/p:language"/></xsl:attribute>
		</xsl:if>
	</xsl:if>
</xsl:template>





<!-- Style -->

<xsl:template name="util:style-ie6">
	<xsl:comment><![CDATA[[if lt IE 7]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie6.css"> </link><![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style-ie7">
	<xsl:comment><![CDATA[[if IE 7]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie7.css"> </link><![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style-ie8">
	<xsl:comment><![CDATA[[if IE 8]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie8.css"> </link><![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style-lt-ie9">
	<xsl:comment><![CDATA[[if lt IE 9]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie_lt9.css"> </link><![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style-lt-ie8">
	<xsl:comment><![CDATA[[if lt IE 9]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie_lt8.css"> </link><![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style">
	<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}style/basic/css/{$template}.css"/>
	<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}style/{$design}/css/style.php"/>
	<xsl:choose>
		<xsl:when test="$preview='true'">
			<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}hui/bin/minimized.css{$timestamp-query}"/>
			<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}hui/css/pages.css{$timestamp-query}"/>
			<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}hui/css/editor.css{$timestamp-query}"/>
		</xsl:when>
		<xsl:otherwise>
			<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}hui/bin/minimized.site.css{$timestamp-query}"/>
		</xsl:otherwise>
	</xsl:choose>
    <xsl:call-template name="util:_style-dynamic"/>
    <xsl:call-template name="util:_style-hui-msie"/>
</xsl:template>

<xsl:template name="util:style-build">
    <xsl:param name="plain" select="'false'"/>
    <xsl:param name="async" select="'true'"/>
    <xsl:if test="$template!='document'">
        <link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}style/basic/css/{$template}.css"/>
    </xsl:if>
	<xsl:choose>
		<xsl:when test="$preview='true' and $development='true'">
			<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}hui/bin/minimized.css{$timestamp-query}"/>
			<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}hui/css/pages.css{$timestamp-query}"/>
			<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}hui/css/editor.css{$timestamp-query}"/>
	        <link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}style/{$design}/css/style.dev.css"/>
		</xsl:when>
		<xsl:when test="$preview='true'">
			<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}hui/bin/minimized.css{$timestamp-query}"/>
			<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}hui/css/pages.css{$timestamp-query}"/>
			<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}hui/css/editor.css{$timestamp-query}"/>
	        <link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}style/{$design}/css/style.private.css"/>
		</xsl:when>
		<xsl:when test="$development='true'">
			<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}hui/bin/minimized.site.css{$timestamp-query}"/>
            <xsl:if test="$plain='false' and $async='true'">
                <xsl:call-template name="util:lazy-style">
                    <xsl:with-param name="href">
                        <xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><xsl:text>/css/style.dev.css</xsl:text>
                    </xsl:with-param>
                </xsl:call-template>
            </xsl:if>
            <xsl:if test="$plain='false' and $async='false'">
                <link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}style/{$design}/css/style.dev.css{$timestamp-query}"/>
            </xsl:if>
        </xsl:when>
    	<xsl:when test="$plain='false' and $async='true'">
            <xsl:call-template name="util:lazy-style">
                <xsl:with-param name="href">
                    <xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><xsl:text>/css/style.css</xsl:text>
                </xsl:with-param>
            </xsl:call-template>
		</xsl:when>
    	<xsl:when test="$plain='false' and $async='false'">
            <link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}style/{$design}/css/style.css{$timestamp-query}"/>
        </xsl:when>
	</xsl:choose>
    <xsl:call-template name="util:_style-dynamic"/>
    <xsl:call-template name="util:_style-hui-msie"/>
</xsl:template>

<xsl:template name="util:css">
    <xsl:param name="path"/>
    <link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}{$path}{$timestamp-query}"/>
</xsl:template>

<xsl:template name="util:lazy-style">
    <xsl:param name="href"/>
    <!--
    <script>_editor.loadCSS('<xsl:value-of select="$href"/>');</script>-->
    <noscript class="js-async">
    <link rel="stylesheet" type="text/css" href="{$href}" media="all"/>
    </noscript>
	<xsl:comment><![CDATA[[if lt IE 9]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$href"/><![CDATA[" media="all"/><![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:load-font">
    <xsl:param name="href"/>
    <xsl:param name="family"/>
    <xsl:param name="weights" select="'400'"/>
    <xsl:param name="class" select="'font'"/>
    <xsl:call-template name="util:script-inline">
        <xsl:with-param name="file" select="'style/basic/js/boot_fonts.js'"/>
        <xsl:with-param name="compiled"><![CDATA[!function(e,t,l){l.loadFont=function(o){for(var n=o.weights||["normal"],i={},a=n.length,s=function(l){var n=t.createElement("div")
n.style.position="absolute",n.style.whiteSpace="nowrap",n.style.top="-9999px",n.style.left="-9999px",n.style.font="999px fantasy",n.style.fontWeight=l,n.innerHTML="Am-i#w^o",t.body.appendChild(n)
var s=n.clientWidth
n.style.fontFamily="'"+o.family+"',fantasy"
var r,c=.01;(r=function(){c*=1.5
var f=n.clientWidth
0==s||s!=f&&!i[f]?(a--,console.log("found: "+l+","+s+"/"+n.clientWidth),0==a&&(console.log("finished: "+o.family),t.body.className+=" "+o.cls,e.localStorage&&localStorage.setItem(o.href,"1")),i[f]=1,n.parentNode.removeChild(n)):e.setTimeout(r,c)})()},r=0;r<n.length;r++)s(n[r])
l.inject(l._build("link",{rel:"stylesheet",type:"text/css",href:o.href}))}}(window,document,_editor)
]]></xsl:with-param>
    </xsl:call-template>
    <script>_editor.loadFont({href:'<xsl:value-of select="$href"/>',family:'<xsl:value-of select="$family"/>',cls:'<xsl:value-of select="$class"/>'<xsl:if test="$weights!=''">,weights:'<xsl:value-of select="$weights"/>'.split(',')</xsl:if>});</script>
</xsl:template>

<xsl:template name="util:lazy-fonts">
    <xsl:param name="google"/>
<script><xsl:text disable-output-escaping="yes">//&lt;![CDATA[</xsl:text>
WebFontConfig={google:{families:['<xsl:value-of select="$google"/>']}};
<!--/* Web Font Loader v1.5.10 - (c) Adobe Systems, Google. License: Apache 2.0 */-->
<xsl:text disable-output-escaping="yes"><![CDATA[;(function(window,document,undefined){var k=this;function l(a,b){var c=a.split("."),d=k;c[0]in d||!d.execScript||d.execScript("var "+c[0]);for(var e;c.length&&(e=c.shift());)c.length||void 0===b?d=d[e]?d[e]:d[e]={}:d[e]=b}function aa(a,b,c){return a.call.apply(a.bind,arguments)}
function ba(a,b,c){if(!a)throw Error();if(2<arguments.length){var d=Array.prototype.slice.call(arguments,2);return function(){var c=Array.prototype.slice.call(arguments);Array.prototype.unshift.apply(c,d);return a.apply(b,c)}}return function(){return a.apply(b,arguments)}}function n(a,b,c){n=Function.prototype.bind&&-1!=Function.prototype.bind.toString().indexOf("native code")?aa:ba;return n.apply(null,arguments)}var q=Date.now||function(){return+new Date};function s(a,b){this.K=a;this.w=b||a;this.D=this.w.document}s.prototype.createElement=function(a,b,c){a=this.D.createElement(a);if(b)for(var d in b)b.hasOwnProperty(d)&&("style"==d?a.style.cssText=b[d]:a.setAttribute(d,b[d]));c&&a.appendChild(this.D.createTextNode(c));return a};function t(a,b,c){a=a.D.getElementsByTagName(b)[0];a||(a=document.documentElement);a&&a.lastChild&&a.insertBefore(c,a.lastChild)}function ca(a,b){function c(){a.D.body?b():setTimeout(c,0)}c()}
function u(a,b,c){b=b||[];c=c||[];for(var d=a.className.split(/\s+/),e=0;e<b.length;e+=1){for(var f=!1,g=0;g<d.length;g+=1)if(b[e]===d[g]){f=!0;break}f||d.push(b[e])}b=[];for(e=0;e<d.length;e+=1){f=!1;for(g=0;g<c.length;g+=1)if(d[e]===c[g]){f=!0;break}f||b.push(d[e])}a.className=b.join(" ").replace(/\s+/g," ").replace(/^\s+|\s+$/,"")}function v(a,b){for(var c=a.className.split(/\s+/),d=0,e=c.length;d<e;d++)if(c[d]==b)return!0;return!1}
function w(a){var b=a.w.location.protocol;"about:"==b&&(b=a.K.location.protocol);return"https:"==b?"https:":"http:"}function x(a,b){var c=a.createElement("link",{rel:"stylesheet",href:b}),d=!1;c.onload=function(){d||(d=!0)};c.onerror=function(){d||(d=!0)};t(a,"head",c)}
function y(a,b,c,d){var e=a.D.getElementsByTagName("head")[0];if(e){var f=a.createElement("script",{src:b}),g=!1;f.onload=f.onreadystatechange=function(){g||this.readyState&&"loaded"!=this.readyState&&"complete"!=this.readyState||(g=!0,c&&c(null),f.onload=f.onreadystatechange=null,"HEAD"==f.parentNode.tagName&&e.removeChild(f))};e.appendChild(f);window.setTimeout(function(){g||(g=!0,c&&c(Error("Script load timeout")))},d||5E3);return f}return null};function z(a,b,c,d){this.R=a;this.Z=b;this.Ba=c;this.ra=d}l("webfont.BrowserInfo",z);z.prototype.sa=function(){return this.R};z.prototype.hasWebFontSupport=z.prototype.sa;z.prototype.ta=function(){return this.Z};z.prototype.hasWebKitFallbackBug=z.prototype.ta;z.prototype.ua=function(){return this.Ba};z.prototype.hasWebKitMetricsBug=z.prototype.ua;z.prototype.qa=function(){return this.ra};z.prototype.hasNativeFontLoading=z.prototype.qa;function A(a,b,c,d){this.c=null!=a?a:null;this.g=null!=b?b:null;this.B=null!=c?c:null;this.e=null!=d?d:null}var da=/^([0-9]+)(?:[\._-]([0-9]+))?(?:[\._-]([0-9]+))?(?:[\._+-]?(.*))?$/;A.prototype.compare=function(a){return this.c>a.c||this.c===a.c&&this.g>a.g||this.c===a.c&&this.g===a.g&&this.B>a.B?1:this.c<a.c||this.c===a.c&&this.g<a.g||this.c===a.c&&this.g===a.g&&this.B<a.B?-1:0};A.prototype.toString=function(){return[this.c,this.g||"",this.B||"",this.e||""].join("")};
function B(a){a=da.exec(a);var b=null,c=null,d=null,e=null;a&&(null!==a[1]&&a[1]&&(b=parseInt(a[1],10)),null!==a[2]&&a[2]&&(c=parseInt(a[2],10)),null!==a[3]&&a[3]&&(d=parseInt(a[3],10)),null!==a[4]&&a[4]&&(e=/^[0-9]+$/.test(a[4])?parseInt(a[4],10):a[4]));return new A(b,c,d,e)};function C(a,b,c,d,e,f,g,h){this.P=a;this.ja=c;this.ya=e;this.ia=g;this.m=h}l("webfont.UserAgent",C);C.prototype.getName=function(){return this.P};C.prototype.getName=C.prototype.getName;C.prototype.oa=function(){return this.ja};C.prototype.getEngine=C.prototype.oa;C.prototype.pa=function(){return this.ya};C.prototype.getPlatform=C.prototype.pa;C.prototype.na=function(){return this.ia};C.prototype.getDocumentMode=C.prototype.na;C.prototype.ma=function(){return this.m};C.prototype.getBrowserInfo=C.prototype.ma;function D(a,b){this.a=a;this.k=b}var ea=new C("Unknown",0,"Unknown",0,"Unknown",0,void 0,new z(!1,!1,!1,!1));
D.prototype.parse=function(){var a;if(-1!=this.a.indexOf("MSIE")||-1!=this.a.indexOf("Trident/")){a=E(this);var b=B(F(this)),c=null,d=null,e=G(this.a,/Trident\/([\d\w\.]+)/,1),f=H(this.k),c=-1!=this.a.indexOf("MSIE")?B(G(this.a,/MSIE ([\d\w\.]+)/,1)):B(G(this.a,/rv:([\d\w\.]+)/,1));""!=e?(d="Trident",B(e)):d="Unknown";a=new C("MSIE",0,d,0,a,0,f,new z("Windows"==a&&6<=c.c||"Windows Phone"==a&&8<=b.c,!1,!1,!!this.k.fonts))}else if(-1!=this.a.indexOf("Opera"))a:if(a="Unknown",c=B(G(this.a,/Presto\/([\d\w\.]+)/,
1)),B(F(this)),b=H(this.k),null!==c.c?a="Presto":(-1!=this.a.indexOf("Gecko")&&(a="Gecko"),B(G(this.a,/rv:([^\)]+)/,1))),-1!=this.a.indexOf("Opera Mini/"))c=B(G(this.a,/Opera Mini\/([\d\.]+)/,1)),a=new C("OperaMini",0,a,0,E(this),0,b,new z(!1,!1,!1,!!this.k.fonts));else{if(-1!=this.a.indexOf("Version/")&&(c=B(G(this.a,/Version\/([\d\.]+)/,1)),null!==c.c)){a=new C("Opera",0,a,0,E(this),0,b,new z(10<=c.c,!1,!1,!!this.k.fonts));break a}c=B(G(this.a,/Opera[\/ ]([\d\.]+)/,1));a=null!==c.c?new C("Opera",
0,a,0,E(this),0,b,new z(10<=c.c,!1,!1,!!this.k.fonts)):new C("Opera",0,a,0,E(this),0,b,new z(!1,!1,!1,!!this.k.fonts))}else/OPR\/[\d.]+/.test(this.a)?a=I(this):/AppleWeb(K|k)it/.test(this.a)?a=I(this):-1!=this.a.indexOf("Gecko")?(a="Unknown",b=new A,B(F(this)),b=!1,-1!=this.a.indexOf("Firefox")?(a="Firefox",b=B(G(this.a,/Firefox\/([\d\w\.]+)/,1)),b=3<=b.c&&5<=b.g):-1!=this.a.indexOf("Mozilla")&&(a="Mozilla"),c=B(G(this.a,/rv:([^\)]+)/,1)),b||(b=1<c.c||1==c.c&&9<c.g||1==c.c&&9==c.g&&2<=c.B),a=new C(a,
0,"Gecko",0,E(this),0,H(this.k),new z(b,!1,!1,!!this.k.fonts))):a=ea;return a};function E(a){var b=G(a.a,/(iPod|iPad|iPhone|Android|Windows Phone|BB\d{2}|BlackBerry)/,1);if(""!=b)return/BB\d{2}/.test(b)&&(b="BlackBerry"),b;a=G(a.a,/(Linux|Mac_PowerPC|Macintosh|Windows|CrOS|PlayStation|CrKey)/,1);return""!=a?("Mac_PowerPC"==a?a="Macintosh":"PlayStation"==a&&(a="Linux"),a):"Unknown"}
function F(a){var b=G(a.a,/(OS X|Windows NT|Android) ([^;)]+)/,2);if(b||(b=G(a.a,/Windows Phone( OS)? ([^;)]+)/,2))||(b=G(a.a,/(iPhone )?OS ([\d_]+)/,2)))return b;if(b=G(a.a,/(?:Linux|CrOS|CrKey) ([^;)]+)/,1))for(var b=b.split(/\s/),c=0;c<b.length;c+=1)if(/^[\d\._]+$/.test(b[c]))return b[c];return(a=G(a.a,/(BB\d{2}|BlackBerry).*?Version\/([^\s]*)/,2))?a:"Unknown"}
function I(a){var b=E(a),c=B(F(a)),d=B(G(a.a,/AppleWeb(?:K|k)it\/([\d\.\+]+)/,1)),e="Unknown",f=new A,f="Unknown",g=!1;/OPR\/[\d.]+/.test(a.a)?e="Opera":-1!=a.a.indexOf("Chrome")||-1!=a.a.indexOf("CrMo")||-1!=a.a.indexOf("CriOS")?e="Chrome":/Silk\/\d/.test(a.a)?e="Silk":"BlackBerry"==b||"Android"==b?e="BuiltinBrowser":-1!=a.a.indexOf("PhantomJS")?e="PhantomJS":-1!=a.a.indexOf("Safari")?e="Safari":-1!=a.a.indexOf("AdobeAIR")?e="AdobeAIR":-1!=a.a.indexOf("PlayStation")&&(e="BuiltinBrowser");"BuiltinBrowser"==
e?f="Unknown":"Silk"==e?f=G(a.a,/Silk\/([\d\._]+)/,1):"Chrome"==e?f=G(a.a,/(Chrome|CrMo|CriOS)\/([\d\.]+)/,2):-1!=a.a.indexOf("Version/")?f=G(a.a,/Version\/([\d\.\w]+)/,1):"AdobeAIR"==e?f=G(a.a,/AdobeAIR\/([\d\.]+)/,1):"Opera"==e?f=G(a.a,/OPR\/([\d.]+)/,1):"PhantomJS"==e&&(f=G(a.a,/PhantomJS\/([\d.]+)/,1));f=B(f);g="AdobeAIR"==e?2<f.c||2==f.c&&5<=f.g:"BlackBerry"==b?10<=c.c:"Android"==b?2<c.c||2==c.c&&1<c.g:526<=d.c||525<=d.c&&13<=d.g;return new C(e,0,"AppleWebKit",0,b,0,H(a.k),new z(g,536>d.c||536==
d.c&&11>d.g,"iPhone"==b||"iPad"==b||"iPod"==b||"Macintosh"==b,!!a.k.fonts))}function G(a,b,c){return(a=a.match(b))&&a[c]?a[c]:""}function H(a){if(a.documentMode)return a.documentMode};function J(a){this.xa=a||"-"}J.prototype.e=function(a){for(var b=[],c=0;c<arguments.length;c++)b.push(arguments[c].replace(/[\W_]+/g,"").toLowerCase());return b.join(this.xa)};function K(a,b){this.P=a;this.$=4;this.Q="n";var c=(b||"n4").match(/^([nio])([1-9])$/i);c&&(this.Q=c[1],this.$=parseInt(c[2],10))}K.prototype.getName=function(){return this.P};function L(a){return a.Q+a.$}function fa(a){var b=4,c="n",d=null;a&&((d=a.match(/(normal|oblique|italic)/i))&&d[1]&&(c=d[1].substr(0,1).toLowerCase()),(d=a.match(/([1-9]00|normal|bold)/i))&&d[1]&&(/bold/i.test(d[1])?b=7:/[1-9]00/.test(d[1])&&(b=parseInt(d[1].substr(0,1),10))));return c+b};function ga(a,b,c,d,e){this.d=a;this.q=b;this.T=c;this.j="wf";this.h=new J("-");this.ha=!1!==d;this.C=!1!==e}function M(a){if(a.C){var b=v(a.q,a.h.e(a.j,"active")),c=[],d=[a.h.e(a.j,"loading")];b||c.push(a.h.e(a.j,"inactive"));u(a.q,c,d)}N(a,"inactive")}function N(a,b,c){if(a.ha&&a.T[b])if(c)a.T[b](c.getName(),L(c));else a.T[b]()};function ha(){this.A={}};function O(a,b){this.d=a;this.H=b;this.o=this.d.createElement("span",{"aria-hidden":"true"},this.H)}function P(a){t(a.d,"body",a.o)}
function Q(a){var b;b=[];for(var c=a.P.split(/,\s*/),d=0;d<c.length;d++){var e=c[d].replace(/['"]/g,"");-1==e.indexOf(" ")?b.push(e):b.push("'"+e+"'")}b=b.join(",");c="normal";"o"===a.Q?c="oblique":"i"===a.Q&&(c="italic");return"display:block;position:absolute;top:0px;left:0px;visibility:hidden;font-size:300px;width:auto;height:auto;line-height:normal;margin:0;padding:0;font-variant:normal;white-space:nowrap;font-family:"+b+";"+("font-style:"+c+";font-weight:"+(a.$+"00")+";")}
O.prototype.remove=function(){var a=this.o;a.parentNode&&a.parentNode.removeChild(a)};function ja(a,b,c,d,e,f,g,h){this.aa=a;this.va=b;this.d=c;this.t=d;this.H=h||"BESbswy";this.m=e;this.J={};this.Y=f||3E3;this.da=g||null;this.G=this.F=null;a=new O(this.d,this.H);P(a);for(var p in R)R.hasOwnProperty(p)&&(b=new K(R[p],L(this.t)),b=Q(b),a.o.style.cssText=b,this.J[R[p]]=a.o.offsetWidth);a.remove()}var R={Ea:"serif",Da:"sans-serif",Ca:"monospace"};
ja.prototype.start=function(){this.F=new O(this.d,this.H);P(this.F);this.G=new O(this.d,this.H);P(this.G);this.za=q();var a=new K(this.t.getName()+",serif",L(this.t)),a=Q(a);this.F.o.style.cssText=a;a=new K(this.t.getName()+",sans-serif",L(this.t));a=Q(a);this.G.o.style.cssText=a;ka(this)};function la(a,b,c){for(var d in R)if(R.hasOwnProperty(d)&&b===a.J[R[d]]&&c===a.J[R[d]])return!0;return!1}
function ka(a){var b=a.F.o.offsetWidth,c=a.G.o.offsetWidth;b===a.J.serif&&c===a.J["sans-serif"]||a.m.Z&&la(a,b,c)?q()-a.za>=a.Y?a.m.Z&&la(a,b,c)&&(null===a.da||a.da.hasOwnProperty(a.t.getName()))?S(a,a.aa):S(a,a.va):ma(a):S(a,a.aa)}function ma(a){setTimeout(n(function(){ka(this)},a),25)}function S(a,b){a.F.remove();a.G.remove();b(a.t)};function T(a,b,c,d){this.d=b;this.u=c;this.U=0;this.fa=this.ca=!1;this.Y=d;this.m=a.m}function na(a,b,c,d,e){c=c||{};if(0===b.length&&e)M(a.u);else for(a.U+=b.length,e&&(a.ca=e),e=0;e<b.length;e++){var f=b[e],g=c[f.getName()],h=a.u,p=f;h.C&&u(h.q,[h.h.e(h.j,p.getName(),L(p).toString(),"loading")]);N(h,"fontloading",p);h=null;h=new ja(n(a.ka,a),n(a.la,a),a.d,f,a.m,a.Y,d,g);h.start()}}
T.prototype.ka=function(a){var b=this.u;b.C&&u(b.q,[b.h.e(b.j,a.getName(),L(a).toString(),"active")],[b.h.e(b.j,a.getName(),L(a).toString(),"loading"),b.h.e(b.j,a.getName(),L(a).toString(),"inactive")]);N(b,"fontactive",a);this.fa=!0;oa(this)};
T.prototype.la=function(a){var b=this.u;if(b.C){var c=v(b.q,b.h.e(b.j,a.getName(),L(a).toString(),"active")),d=[],e=[b.h.e(b.j,a.getName(),L(a).toString(),"loading")];c||d.push(b.h.e(b.j,a.getName(),L(a).toString(),"inactive"));u(b.q,d,e)}N(b,"fontinactive",a);oa(this)};function oa(a){0==--a.U&&a.ca&&(a.fa?(a=a.u,a.C&&u(a.q,[a.h.e(a.j,"active")],[a.h.e(a.j,"loading"),a.h.e(a.j,"inactive")]),N(a,"active")):M(a.u))};function U(a){this.K=a;this.v=new ha;this.Aa=new D(a.navigator.userAgent,a.document);this.a=this.Aa.parse();this.V=this.W=0;this.M=this.N=!0}
U.prototype.load=function(a){var b=a.context||this.K;this.d=new s(this.K,b);this.N=!1!==a.events;this.M=!1!==a.classes;var b=new ga(this.d,b.document.documentElement,a,this.N,this.M),c=[],d=a.timeout;b.C&&u(b.q,[b.h.e(b.j,"loading")]);N(b,"loading");var c=this.v,e=this.d,f=[],g;for(g in a)if(a.hasOwnProperty(g)){var h=c.A[g];h&&f.push(h(a[g],e))}c=f;this.V=this.W=c.length;a=new T(this.a,this.d,b,d);g=0;for(d=c.length;g<d;g++)e=c[g],e.L(this.a,n(this.wa,this,e,b,a))};
U.prototype.wa=function(a,b,c,d){var e=this;d?a.load(function(a,b,d){pa(e,c,a,b,d)}):(a=0==--this.W,this.V--,a&&0==this.V?M(b):(this.M||this.N)&&na(c,[],{},null,a))};function pa(a,b,c,d,e){var f=0==--a.W;(a.M||a.N)&&setTimeout(function(){na(b,c,d||null,e||null,f)},0)};function qa(a,b,c){this.S=a?a:b+ra;this.s=[];this.X=[];this.ga=c||""}var ra="//fonts.googleapis.com/css";qa.prototype.e=function(){if(0==this.s.length)throw Error("No fonts to load!");if(-1!=this.S.indexOf("kit="))return this.S;for(var a=this.s.length,b=[],c=0;c<a;c++)b.push(this.s[c].replace(/ /g,"+"));a=this.S+"?family="+b.join("%7C");0<this.X.length&&(a+="&subset="+this.X.join(","));0<this.ga.length&&(a+="&text="+encodeURIComponent(this.ga));return a};function sa(a){this.s=a;this.ea=[];this.O={}}
var ta={latin:"BESbswy",cyrillic:"&#1081;&#1103;&#1046;",greek:"&#945;&#946;&#931;",khmer:"&#x1780;&#x1781;&#x1782;",Hanuman:"&#x1780;&#x1781;&#x1782;"},ua={thin:"1",extralight:"2","extra-light":"2",ultralight:"2","ultra-light":"2",light:"3",regular:"4",book:"4",medium:"5","semi-bold":"6",semibold:"6","demi-bold":"6",demibold:"6",bold:"7","extra-bold":"8",extrabold:"8","ultra-bold":"8",ultrabold:"8",black:"9",heavy:"9",l:"3",r:"4",b:"7"},va={i:"i",italic:"i",n:"n",normal:"n"},wa=/^(thin|(?:(?:extra|ultra)-?)?light|regular|book|medium|(?:(?:semi|demi|extra|ultra)-?)?bold|black|heavy|l|r|b|[1-9]00)?(n|i|normal|italic)?$/;
sa.prototype.parse=function(){for(var a=this.s.length,b=0;b<a;b++){var c=this.s[b].split(":"),d=c[0].replace(/\+/g," "),e=["n4"];if(2<=c.length){var f;var g=c[1];f=[];if(g)for(var g=g.split(","),h=g.length,p=0;p<h;p++){var m;m=g[p];if(m.match(/^[\w-]+$/)){m=wa.exec(m.toLowerCase());var r=void 0;if(null==m)r="";else{r=void 0;r=m[1];if(null==r||""==r)r="4";else var ia=ua[r],r=ia?ia:isNaN(r)?"4":r.substr(0,1);m=m[2];r=[null==m||""==m?"n":va[m],r].join("")}m=r}else m="";m&&f.push(m)}0<f.length&&(e=f);
3==c.length&&(c=c[2],f=[],c=c?c.split(","):f,0<c.length&&(c=ta[c[0]])&&(this.O[d]=c))}this.O[d]||(c=ta[d])&&(this.O[d]=c);for(c=0;c<e.length;c+=1)this.ea.push(new K(d,e[c]))}};function V(a,b){this.a=(new D(navigator.userAgent,document)).parse();this.d=a;this.f=b}var xa={Arimo:!0,Cousine:!0,Tinos:!0};V.prototype.L=function(a,b){b(a.m.R)};V.prototype.load=function(a){var b=this.d;"MSIE"==this.a.getName()&&1!=this.f.blocking?ca(b,n(this.ba,this,a)):this.ba(a)};
V.prototype.ba=function(a){for(var b=this.d,c=new qa(this.f.api,w(b),this.f.text),d=this.f.families,e=d.length,f=0;f<e;f++){var g=d[f].split(":");3==g.length&&c.X.push(g.pop());var h="";2==g.length&&""!=g[1]&&(h=":");c.s.push(g.join(h))}d=new sa(d);d.parse();x(b,c.e());a(d.ea,d.O,xa)};function W(a,b){this.d=a;this.f=b;this.p=[]}W.prototype.I=function(a){var b=this.d;return w(this.d)+(this.f.api||"//f.fontdeck.com/s/css/js/")+(b.w.location.hostname||b.K.location.hostname)+"/"+a+".js"};
W.prototype.L=function(a,b){var c=this.f.id,d=this.d.w,e=this;c?(d.__webfontfontdeckmodule__||(d.__webfontfontdeckmodule__={}),d.__webfontfontdeckmodule__[c]=function(a,c){for(var d=0,p=c.fonts.length;d<p;++d){var m=c.fonts[d];e.p.push(new K(m.name,fa("font-weight:"+m.weight+";font-style:"+m.style)))}b(a)},y(this.d,this.I(c),function(a){a&&b(!1)})):b(!1)};W.prototype.load=function(a){a(this.p)};function X(a,b){this.d=a;this.f=b;this.p=[]}X.prototype.I=function(a){var b=w(this.d);return(this.f.api||b+"//use.typekit.net")+"/"+a+".js"};X.prototype.L=function(a,b){var c=this.f.id,d=this.d.w,e=this;c?y(this.d,this.I(c),function(a){if(a)b(!1);else{if(d.Typekit&&d.Typekit.config&&d.Typekit.config.fn){a=d.Typekit.config.fn;for(var c=0;c<a.length;c+=2)for(var h=a[c],p=a[c+1],m=0;m<p.length;m++)e.p.push(new K(h,p[m]));try{d.Typekit.load({events:!1,classes:!1})}catch(r){}}b(!0)}},2E3):b(!1)};
X.prototype.load=function(a){a(this.p)};function Y(a,b){this.d=a;this.f=b;this.p=[]}Y.prototype.L=function(a,b){var c=this,d=c.f.projectId,e=c.f.version;if(d){var f=c.d.w;y(this.d,c.I(d,e),function(e){if(e)b(!1);else{if(f["__mti_fntLst"+d]&&(e=f["__mti_fntLst"+d]()))for(var h=0;h<e.length;h++)c.p.push(new K(e[h].fontfamily));b(a.m.R)}}).id="__MonotypeAPIScript__"+d}else b(!1)};Y.prototype.I=function(a,b){var c=w(this.d),d=(this.f.api||"fast.fonts.net/jsapi").replace(/^.*http(s?):(\/\/)?/,"");return c+"//"+d+"/"+a+".js"+(b?"?v="+b:"")};
Y.prototype.load=function(a){a(this.p)};function Z(a,b){this.d=a;this.f=b}Z.prototype.load=function(a){var b,c,d=this.f.urls||[],e=this.f.families||[],f=this.f.testStrings||{};b=0;for(c=d.length;b<c;b++)x(this.d,d[b]);d=[];b=0;for(c=e.length;b<c;b++){var g=e[b].split(":");if(g[1])for(var h=g[1].split(","),p=0;p<h.length;p+=1)d.push(new K(g[0],h[p]));else d.push(new K(g[0]))}a(d,f)};Z.prototype.L=function(a,b){return b(a.m.R)};var $=new U(k);$.v.A.custom=function(a,b){return new Z(b,a)};$.v.A.fontdeck=function(a,b){return new W(b,a)};$.v.A.monotype=function(a,b){return new Y(b,a)};$.v.A.typekit=function(a,b){return new X(b,a)};$.v.A.google=function(a,b){return new V(b,a)};k.WebFont||(k.WebFont={},k.WebFont.load=n($.load,$),k.WebFontConfig&&$.load(k.WebFontConfig));})(this,document);]]>
</xsl:text><xsl:text disable-output-escaping="yes">//]]&gt;</xsl:text>	</script>
</xsl:template>

<xsl:template name="util:_style-hui-msie">
	<xsl:comment><![CDATA[[if lt IE 7]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>hui/css/msie6.css<xsl:value-of select="$timestamp-query"/><![CDATA["></link><![endif]]]></xsl:comment>
	<xsl:comment><![CDATA[[if IE 7]><link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>hui/css/msie7.css<xsl:value-of select="$timestamp-query"/><![CDATA["></link><![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:_style-dynamic">
    <!--
	<xsl:if test="//movie:movie">
		<link href="http://vjs.zencdn.net/4.1/video-js.css" rel="stylesheet"/>
	</xsl:if>
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

<xsl:template name="util:style-inline">
    <xsl:param name="compiled" />
    <xsl:param name="file" select="'inline.css'"/>
    <xsl:choose>
      <xsl:when test="$development='true'">
  		<link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}style/{$design}/css/{$file}{$timestamp-query}"/>
      </xsl:when>
      <xsl:otherwise>
      	<style type="text/css">
            
      		<xsl:value-of select="$compiled" disable-output-escaping="yes"/>
            <xsl:text> </xsl:text>
      	</style>
      </xsl:otherwise>
    </xsl:choose>    
</xsl:template>




<!-- Dates -->

<xsl:template name="util:weekday">
	<xsl:param name="node"/>
	<xsl:choose>
		<xsl:when test="$language='en'">
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
		<xsl:when test="$language='en'">
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
		<xsl:when test="$language='en'">
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







<!-- Languages -->

<xsl:template name="util:languages">
	<xsl:param name="tag" select="'span'"/>
	<xsl:element name="{$tag}">
		<xsl:attribute name="class">layout_languages</xsl:attribute>
		<xsl:for-each select="//p:page/p:context/p:home[@language and @language!=$language and not(@language=//p:page/p:context/p:translation/@language)]">
			<xsl:call-template name="util:language"/>
		</xsl:for-each>
		<xsl:for-each select="//p:page/p:context/p:translation">
			<xsl:call-template name="util:language"/>
		</xsl:for-each>
		<xsl:comment/>
	</xsl:element>
</xsl:template>

<xsl:template name="util:language">
	<a class="layout_language layout_language_{@language}">
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





<!-- Navigation -->

<xsl:template name="util:menu-top-level">
	<xsl:if test="//f:frame/h:hierarchy/h:item[not(@hidden='true')]">
		<ul class="layout_menu_top">
			<xsl:for-each select="//f:frame/h:hierarchy/h:item">
				<xsl:if test="not(@hidden='true')">
					<li>
                        <xsl:attribute name="class">
                            <xsl:text>layout_menu_top_item</xsl:text>
    						<xsl:choose>
    							<xsl:when test="//p:page/@id=@page"> layout_menu_top_item_selected</xsl:when>
    							<xsl:when test="descendant-or-self::*/@page=//p:page/@id"> layout_menu_top_item_highlighted</xsl:when>
    						</xsl:choose>
                        </xsl:attribute>
						<a>
                            <xsl:attribute name="class">
                                <xsl:text>layout_menu_top_link</xsl:text>
        						<xsl:choose>
        							<xsl:when test="//p:page/@id=@page"> layout_menu_top_link_selected</xsl:when>
        							<xsl:when test="descendant-or-self::*/@page=//p:page/@id"> layout_menu_top_link_highlighted</xsl:when>
        						</xsl:choose>
                            </xsl:attribute>
							<xsl:call-template name="util:link"/>
							<span><xsl:value-of select="@title"/></span>
						</a>
					</li>
				</xsl:if>
			</xsl:for-each>
		</ul>
	</xsl:if>
</xsl:template>

<xsl:template name="util:navigation-first-level">
	<xsl:if test="//f:frame/h:hierarchy/h:item[not(@hidden='true')]">
		<ul class="layout_navigation_first">
			<xsl:for-each select="//f:frame/h:hierarchy/h:item">
				<xsl:if test="not(@hidden='true')">
					<li>
						<xsl:choose>
							<xsl:when test="//p:page/@id=@page"><xsl:attribute name="class">layout_selected</xsl:attribute></xsl:when>
							<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">layout_highlighted</xsl:attribute></xsl:when>
						</xsl:choose>
						<a>
							<xsl:call-template name="util:link"/>
							<span><xsl:value-of select="@title"/></span>
						</a>
					</li>
				</xsl:if>
			</xsl:for-each>
		</ul>
	</xsl:if>
</xsl:template>

<xsl:template name="util:navigation-second-level">
	<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[not(@hidden='true')]">
		<ul class="layout_navigation_second">
			<xsl:for-each select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
				<xsl:if test="not(@hidden='true')">
					<li>
						<xsl:choose>
							<xsl:when test="//p:page/@id=@page"><xsl:attribute name="class">layout_selected</xsl:attribute></xsl:when>
							<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">layout_highlighted</xsl:attribute></xsl:when>
						</xsl:choose>
						<a>
							<xsl:call-template name="util:link"/>
							<span><xsl:value-of select="@title"/></span>
						</a>
					</li>
				</xsl:if>
			</xsl:for-each>
		</ul>
	</xsl:if>
</xsl:template>

<xsl:template name="util:navigation-third-level">
	<xsl:if test="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item[not(@hidden='true')]">
		<ul class="layout_navigation_third">
			<xsl:for-each select="//f:frame/h:hierarchy/h:item/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
				<xsl:if test="not(@hidden='true')">
					<li>
						<xsl:choose>
							<xsl:when test="//p:page/@id=@page"><xsl:attribute name="class">layout_selected</xsl:attribute></xsl:when>
							<xsl:when test="descendant-or-self::*/@page=//p:page/@id"><xsl:attribute name="class">layout_highlighted</xsl:attribute></xsl:when>
						</xsl:choose>
						<a>
							<xsl:call-template name="util:link"/>
							<span><xsl:value-of select="@title"/></span>
						</a>
					</li>
				</xsl:if>
			</xsl:for-each>
		</ul>
	</xsl:if>
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




<!-- Shared -->



<xsl:template name="util:wrap-in-frame">
    <xsl:param name="variant"/>
    <xsl:param name="content"/>
    
	<xsl:choose>
		<xsl:when test="$variant!=''">
			<span class="shared_frame shared_frame_{$variant}">
				<span class="shared_frame_{$variant}_top"><span class="shared_frame_{$variant}_top_inner"><span class="shared_frame_{$variant}_top_innermost"><xsl:comment/></span></span></span>
				<span class="shared_frame_{$variant}_middle">
					<span class="shared_frame_{$variant}_middle_inner">
						<span class="shared_frame_{$variant}_content">
							<xsl:copy-of select="$content"/>
						</span>
					</span>
				</span>
				<span class="shared_frame_{$variant}_bottom"><span class="shared_frame_{$variant}_bottom_inner"><span class="shared_frame_{$variant}_bottom_innermost"><xsl:comment/></span></span></span>
			</span>
		</xsl:when>
		<xsl:otherwise>
			<xsl:copy-of select="$content"/>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>
                                               


<!-- deprecated -->
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

<!-- Deprecated -->
<xsl:template name="util:hierarchy-second-level">
	<xsl:if test="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
		<ul class="case_sub_navigation">
			<xsl:for-each select="//f:frame/h:hierarchy/h:item[descendant-or-self::*/@page=//p:page/@id]/h:item">
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
	</xsl:if>
</xsl:template>


<!--
	<xsl:template name="util:share">
		<script src="http://apis.google.com/js/plusone.js"></script>
		<g:plusone size="small"></g:plusone>"
		<div id="fb-root"></div>
		<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
		<fb:like href="" send="false" layout="button_count" width="450" show_faces="false" font="lucida grande"></fb:like>
	</xsl:template>
-->
</xsl:stylesheet>