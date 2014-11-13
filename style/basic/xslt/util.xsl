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
		<script type="text/javascript">
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', '<xsl:value-of select="$code"/>', 'auto');
			ga('send', 'pageview');
		</script>
	</xsl:if>
</xsl:template>

<!--
<xsl:template name="util:googleanalytics_old">
	<xsl:param name="code" select="//p:meta/p:analytics/@key"/>
	<xsl:if test="not($preview='true') and $code!='' and $statistics='true'">
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

<xsl:template name="util:parameter">
    <xsl:param name="name" />
    <xsl:param name="default" />
    <xsl:choose>
      <xsl:when test="//p:parameter[@name=$name]">
          <xsl:value-of select="//p:parameter[@name=$name]" disable-output-escaping="yes"/>
      </xsl:when>
      <xsl:otherwise>
          <xsl:copy-of select="$default"/>
      </xsl:otherwise>
    </xsl:choose>
</xsl:template>


<!-- Scripts -->

<xsl:template name="util:scripts-build">
    <xsl:call-template name="util:_scripts-base"/>
    <xsl:call-template name="util:_scripts-msie"/>
	<xsl:choose>
		<xsl:when test="$preview='true'">
            <xsl:call-template name="util:_scripts-config"/>
			<script src="{$path}{$timestamp-url}hui/bin/minimized.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/Editor.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/Pages.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}style/{$design}/js/script.private.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
            <script type="text/javascript">
                _editor.$scriptReady();
            </script>
		</xsl:when>
		<xsl:when test="$development='true'">
            <xsl:call-template name="util:_scripts-config"/>
			<script src="{$path}{$timestamp-url}hui/bin/joined.site.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}style/basic/js/OnlinePublisher.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}style/{$design}/js/script.dev.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
            <script type="text/javascript">
                _editor.$scriptReady();
            </script>
        </xsl:when>
		<xsl:otherwise>
            <xsl:call-template name="util:_scripts-config"/>
            <script type="text/javascript">
                _editor.ready(function() {
                    $script('<xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/>/js/script.js<xsl:value-of select="$timestamp-query"/>',function() {
                        _editor.$scriptReady();
                    })
                })
            </script>
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
					<script src="{$path}{$timestamp-url}hui/bin/joined.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
				</xsl:when>
				<xsl:otherwise>
					<script src="{$path}{$timestamp-url}hui/bin/minimized.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
				</xsl:otherwise>
			</xsl:choose>
			<script src="{$path}{$timestamp-url}hui/js/Editor.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/Pages.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
		</xsl:when>
		<xsl:when test="$development='true'">
			<script src="{$path}{$timestamp-url}hui/js/hui.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/hui_animation.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/hui_parallax.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/hui_color.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/hui_require.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/ui.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/ImageViewer.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/Box.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/SearchField.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
		</xsl:when>
		<xsl:otherwise>
			<script src="{$path}{$timestamp-url}hui/bin/minimized.site.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
		</xsl:otherwise>
	</xsl:choose>
	<script src="{$path}{$timestamp-url}style/basic/js/OnlinePublisher.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
    <xsl:call-template name="util:_scripts-config"/>
    <xsl:call-template name="util:_scripts-preview"/>
	<script>_editor.$scriptReady();</script>
</xsl:template>

<xsl:template name="util:_scripts-preview">
    
	<xsl:if test="$preview='true' and $mini!='true'">
		<script src="editor.js?version={$timestamp}" type="text/javascript"><xsl:comment/></script>
		<script src="{$path}{$timestamp-url}Editor/Template/{$template}/js/editor.php{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
	</xsl:if>
    
    <!--
	<xsl:if test="//movie:movie">
		<script src="http://vjs.zencdn.net/4.1/video.js"><xsl:comment/></script>
	</xsl:if>
    -->
    
</xsl:template>

<xsl:template name="util:_scripts-msie">
	<!-- html5 -->
	<xsl:comment><![CDATA[[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js" data-movable="false"></script>
	<![endif]]]></xsl:comment>
	<xsl:comment><![CDATA[[if lt IE 8]>
	<script type="text/javascript" src="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>hui/lib/json2.js<xsl:value-of select="$timestamp-query"/><![CDATA["></script>
	<![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:_scripts-config">
	<script type="text/javascript"><xsl:comment>
        _editor.defer(function() {
		hui.ui.context = '<xsl:value-of select="$path"/>';
		hui.ui.language = '<xsl:value-of select="$language"/>';
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
        });
	</xsl:comment></script>
</xsl:template>

<xsl:template name="util:_scripts-base">
	<script type="text/javascript">
        <xsl:text disable-output-escaping="yes">//&lt;![CDATA[</xsl:text>
        <xsl:text disable-output-escaping="yes">
        <![CDATA[
        (function(e,t){typeof module!="undefined"&&module.exports?module.exports=t():typeof define=="function"&&define.amd?define(t):this[e]=t()})("$script",function(){function h(e,t){for(var n=0,i=e.length;n<i;++n)if(!t(e[n]))return r;return 1}function p(e,t){h(e,function(e){return!t(e)})}function d(e,t,n){function g(e){return e.call?e():u[e]}function y(){if(!--m){u[o]=1,s&&s();for(var e in f)h(e.split("|"),g)&&!p(f[e],g)&&(f[e]=[])}}e=e[i]?e:[e];var r=t&&t.call,s=r?t:n,o=r?e.join(""):t,m=e.length;return setTimeout(function(){p(e,function(e){if(e===null)return y();if(l[e])return o&&(a[o]=1),l[e]==2&&y();l[e]=1,o&&(a[o]=1),v(!/^https?:\/\//.test(e)&&c?c+e+".js":e,y)})},0),d}function v(n,i){var u=e.createElement("script"),a=r;u.onload=u.onerror=u[o]=function(){if(u[s]&&!/^c|loade/.test(u[s])||a)return;u.onload=u[o]=null,a=1,l[n]=2,i()},u.async=1,u.src=n,t.insertBefore(u,t.lastChild)}var e=document,t=e.getElementsByTagName("head")[0],n="string",r=!1,i="push",s="readyState",o="onreadystatechange",u={},a={},f={},l={},c;return d.get=v,d.order=function(e,t,n){(function r(i){i=e.shift(),e.length?d(i,r):d(i,t,n)})()},d.path=function(e){c=e},d.ready=function(e,t,n){e=e[i]?e:[e];var r=[];return!p(e,function(e){u[e]||r[i](e)})&&h(e,function(e){return u[e]})?t():!function(e){f[e]=f[e]||[],f[e][i](t),n&&n(r)}(e.join("|")),d},d.done=function(e){d([null],e)},d})        
        window._editor = {
            deferred : [],
            scriptLoaded : false,
            ready : function(delegate) {
                if (document.readyState == 'complete' || document.readyState == 'interactive') {
                    delegate();
                    return;
                }
            	if (window.addEventListener) {
            		window.addEventListener('DOMContentLoaded',delegate,false);
            	}
                else if(document.addEventListener) {
            		document.addEventListener('load', delegate, false);
            	}
            	else if(typeof window.attachEvent != 'undefined') {
            		window.attachEvent('onload', delegate);
            	}
            	else {
            		if(typeof window.onload == 'function') {
            			var existing = window.onload;
            			window.onload = function() {
            				existing();
            				delegate();
            			};
            		} else {
            			window.onload = delegate;
            		}
            	}
    
            },
            defer : function(func) {
                if (this.scriptLoaded) {
                    func();
                } else {
                    this.deferred[this.deferred.length] = func;
                }
            },
            _parts : {},
            _loadPart : function(info) {
                var name = info.name;
                if (op.part[name]) {
                    info.$ready();
                } else {
                    if (!this._parts[name]) {
                        this._parts[name] = [info];
                        $script(_editor.context+'style/basic/js/parts/' + name + '.js',function() {
                            hui.each(_editor._parts[name],function(item) {
                                item.$ready();
                            })
                            _editor._parts[name] = [];
                        });
                    } else {
                        this._parts[name].push(info);
                    }
                }
            },
            loadPart : function(info) {
                this.defer(function() {
                    _editor._loadPart(info);
                })
                if (!window['op']) {
                    this.defer
                }
                if (window['op'] && op.part[info.name]) {
                    info.$ready();
                }
            },
            loadCSS : function(href) {
                if (!true) {
                    var self = this;
                    window.setTimeout(function() {self._loadCSS(href)},2000);
                    return;
                }
                this._loadCSS(href);
            },
            _loadCSS : function(href) {
                var e = document.createElement('link');
                e.setAttribute('rel','stylesheet');
                e.setAttribute('type','text/css');
                e.setAttribute('href',href);
                document.getElementsByTagName('head')[0].appendChild(e);
            },
            $scriptReady : function() {
                for (var i = 0; i < this.deferred.length; i++) {
                    if (typeof(this.deferred[i])=='function') {
                        this.deferred[i]();
                    }                  
                }
                this.scriptLoaded = true;
            }
        }
        ]]>
        </xsl:text>
        _editor.context = '<xsl:value-of select="$path"/>';
        
		window.onerror = function(errorMsg, url, lineNumber) {
			try {
				hui.request({
					url : _editor.context + 'services/issues/scripterror/',
					parameters : {
						message : errorMsg,
						file : url,
						line : lineNumber,
						url : document.location.href
					},
					$success : function() {
						hui.log('Sent error');
					} 
				})
			} catch (ignore) {}
		}
        
        <xsl:text disable-output-escaping="yes">//]]&gt;</xsl:text>
	</script>    
</xsl:template>

<xsl:template name="util:scripts-adaptive">
	<script src="{$path}{$timestamp-url}hui/lib/ios-orientationchange-fix.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
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
	<xsl:comment><![CDATA[[if lt IE 7]>
		<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie6.css"> </link>
	<![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style-ie7">
	<xsl:comment><![CDATA[[if IE 7]>
		<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie7.css"> </link>
	<![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style-ie8">
	<xsl:comment><![CDATA[[if IE 8]>
		<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie8.css"> </link>
	<![endif]]]></xsl:comment>
</xsl:template>

<xsl:template name="util:style-lt-ie9">
	<xsl:comment><![CDATA[[if lt IE 9]>
		<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><![CDATA[/css/msie_lt9.css"> </link>
	<![endif]]]></xsl:comment>
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
	        <link rel="stylesheet" type="text/css" href="{$path}{$timestamp-url}style/{$design}/css/style.dev.css"/>
        </xsl:when>
		<xsl:otherwise>
            <xsl:call-template name="util:lazy-style">
                <xsl:with-param name="href">
                    <xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>style/<xsl:value-of select="$design"/><xsl:text>/css/style.css</xsl:text>
                </xsl:with-param>
            </xsl:call-template>
		</xsl:otherwise>
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
    <!--<link rel="stylesheet" type="text/css" href="{$href}"/>-->
	<!--
    <script type="text/javascript">_editor.loadCSS('<xsl:value-of select="$href"/>');</script>
	-->
    <script>
        _editor.ready(function() {
            var e = document.createElement('link');
            e.setAttribute('rel','stylesheet');
            e.setAttribute('type','text/css');
            e.setAttribute('href','<xsl:value-of select="$href"/>');
            document.getElementsByTagName('head')[0].appendChild(e);
        });
    </script>
    <noscript>
    <link rel="stylesheet" type="text/css" href="{$href}"/>
    </noscript>
</xsl:template>

<xsl:template name="util:lazy-fonts">
    <xsl:param name="google"/>
	<script>
	  WebFontConfig = {
        google: {
          families: ['<xsl:value-of select="$google"/>']
        }
	  };
	  _editor.ready(function() {
	    var wf = document.createElement('script');
	    wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
	              '://ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js';
	    wf.type = 'text/javascript';
	    wf.async = 'true';
	    var s = document.getElementsByTagName('script')[0];
	    s.parentNode.insertBefore(wf, s);
	  });
	</script>
</xsl:template>

<xsl:template name="util:_style-hui-msie">
	<xsl:comment><![CDATA[[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>hui/css/msie6.css<xsl:value-of select="$timestamp-query"/><![CDATA["></link>
	<![endif]]]></xsl:comment>
	<xsl:comment><![CDATA[[if IE 7]>
	<link rel="stylesheet" type="text/css" href="]]><xsl:value-of select="$path"/><xsl:value-of select="$timestamp-url"/>hui/css/msie7.css<xsl:value-of select="$timestamp-query"/><![CDATA["></link>
	<![endif]]]></xsl:comment>
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
		<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
		<g:plusone size="small"></g:plusone>"
		<div id="fb-root"></div>
		<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
		<fb:like href="" send="false" layout="button_count" width="450" show_faces="false" font="lucida grande"></fb:like>
	</xsl:template>
-->
</xsl:stylesheet>