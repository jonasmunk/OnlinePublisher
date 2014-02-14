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
	<meta http-equiv="X-UA-Compatible" content="IE=Edge"></meta>
	<xsl:if test="p:meta/p:description">
		<meta name="Description" content="{p:meta/p:description}"></meta>
	</xsl:if>
	<meta name="robots" content="index,follow"></meta>
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



<!-- Scripts -->

<xsl:template name="util:scripts-build">
    <xsl:call-template name="util:_scripts-errorhandler"/>
    <xsl:call-template name="util:_scripts-msie"/>
	<xsl:choose>
		<xsl:when test="$preview='true'">
			<script src="{$path}{$timestamp-url}hui/bin/minimized.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/Editor.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}hui/js/Pages.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}style/{$design}/js/script.private.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
            <xsl:call-template name="util:_scripts-config"/>
		</xsl:when>
		<xsl:when test="$development='true'">
			<script src="{$path}{$timestamp-url}hui/bin/joined.site.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
			<script src="{$path}{$timestamp-url}style/basic/js/OnlinePublisher.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
            <xsl:call-template name="util:_scripts-config"/>
			<script src="{$path}{$timestamp-url}style/{$design}/js/script.dev.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
        </xsl:when>
		<xsl:otherwise>
			<script src="{$path}{$timestamp-url}style/{$design}/js/script.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
            <xsl:call-template name="util:_scripts-config"/>
		</xsl:otherwise>
	</xsl:choose>
    <xsl:call-template name="util:_scripts-preview"/>
</xsl:template>

<xsl:template name="util:scripts">
    <xsl:call-template name="util:_scripts-errorhandler"/>
    <xsl:call-template name="util:_scripts-msie"/>
	<xsl:choose>
		<xsl:when test="$preview='true'">
			<xsl:choose>
				<xsl:when test="$development='true'">
					<script src="{$path}{$timestamp-url}hui/bin/combined.js{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
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
</xsl:template>

<xsl:template name="util:_scripts-preview">
    
	<xsl:if test="$preview='true' and $mini!='true'">
		<script src="editor.js?version={$timestamp}" type="text/javascript"><xsl:comment/></script>
		<script src="{$path}{$timestamp-url}Editor/Template/{$template}/js/editor.php{$timestamp-query}" type="text/javascript"><xsl:comment/></script>
	</xsl:if>
    
	<xsl:if test="//movie:movie">
		<script src="http://vjs.zencdn.net/4.1/video.js"><xsl:comment/></script>
	</xsl:if>
    
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
	</xsl:comment></script>
</xsl:template>

<xsl:template name="util:_scripts-errorhandler">
	<script type="text/javascript">
		window.onerror = function(errorMsg, url, lineNumber) {
			try {
				hui.request({
					url : '<xsl:value-of select="$path"/>services/issues/scripterror/',
					parameters : {
						message : errorMsg,
						file : url,
						line : lineNumber,
						url : document.location.href
					},
					onSuccess : function() {
						hui.log('Sent error');
					} 
				})
			} catch (ignore) {}
		}
        
        window._editor = {
            deferred : [],
            
            defer : function(func) {
                deferred[deferred.length+1] = func;
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
            }
        }
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

<xsl:template name="util:require">
<!--
 RequireJS 2.1.10 Copyright (c) 2010-2014, The Dojo Foundation All Rights Reserved.
 Available via the MIT or new BSD license.
 see: http://github.com/jrburke/requirejs for details
-->
    <script type="text/javascript">
    <xsl:text disable-output-escaping="yes">
        <![CDATA[var requirejs,require,define;
(function(ca){function G(b){return"[object Function]"===N.call(b)}function H(b){return"[object Array]"===N.call(b)}function v(b,c){if(b){var d;for(d=0;d<b.length&&(!b[d]||!c(b[d],d,b));d+=1);}}function U(b,c){if(b){var d;for(d=b.length-1;-1<d&&(!b[d]||!c(b[d],d,b));d-=1);}}function s(b,c){return ga.call(b,c)}function j(b,c){return s(b,c)&&b[c]}function B(b,c){for(var d in b)if(s(b,d)&&c(b[d],d))break}function V(b,c,d,g){c&&B(c,function(c,h){if(d||!s(b,h))g&&"object"===typeof c&&c&&!H(c)&&!G(c)&&!(c instanceof
RegExp)?(b[h]||(b[h]={}),V(b[h],c,d,g)):b[h]=c});return b}function t(b,c){return function(){return c.apply(b,arguments)}}function da(b){throw b;}function ea(b){if(!b)return b;var c=ca;v(b.split("."),function(b){c=c[b]});return c}function C(b,c,d,g){c=Error(c+"\nhttp://requirejs.org/docs/errors.html#"+b);c.requireType=b;c.requireModules=g;d&&(c.originalError=d);return c}function ha(b){function c(a,e,b){var f,n,c,d,g,h,i,I=e&&e.split("/");n=I;var m=l.map,k=m&&m["*"];if(a&&"."===a.charAt(0))if(e){n=
I.slice(0,I.length-1);a=a.split("/");e=a.length-1;l.nodeIdCompat&&R.test(a[e])&&(a[e]=a[e].replace(R,""));n=a=n.concat(a);d=n.length;for(e=0;e<d;e++)if(c=n[e],"."===c)n.splice(e,1),e-=1;else if(".."===c)if(1===e&&(".."===n[2]||".."===n[0]))break;else 0<e&&(n.splice(e-1,2),e-=2);a=a.join("/")}else 0===a.indexOf("./")&&(a=a.substring(2));if(b&&m&&(I||k)){n=a.split("/");e=n.length;a:for(;0<e;e-=1){d=n.slice(0,e).join("/");if(I)for(c=I.length;0<c;c-=1)if(b=j(m,I.slice(0,c).join("/")))if(b=j(b,d)){f=b;
g=e;break a}!h&&(k&&j(k,d))&&(h=j(k,d),i=e)}!f&&h&&(f=h,g=i);f&&(n.splice(0,g,f),a=n.join("/"))}return(f=j(l.pkgs,a))?f:a}function d(a){z&&v(document.getElementsByTagName("script"),function(e){if(e.getAttribute("data-requiremodule")===a&&e.getAttribute("data-requirecontext")===i.contextName)return e.parentNode.removeChild(e),!0})}function g(a){var e=j(l.paths,a);if(e&&H(e)&&1<e.length)return e.shift(),i.require.undef(a),i.require([a]),!0}function u(a){var e,b=a?a.indexOf("!"):-1;-1<b&&(e=a.substring(0,
b),a=a.substring(b+1,a.length));return[e,a]}function m(a,e,b,f){var n,d,g=null,h=e?e.name:null,l=a,m=!0,k="";a||(m=!1,a="_@r"+(N+=1));a=u(a);g=a[0];a=a[1];g&&(g=c(g,h,f),d=j(p,g));a&&(g?k=d&&d.normalize?d.normalize(a,function(a){return c(a,h,f)}):c(a,h,f):(k=c(a,h,f),a=u(k),g=a[0],k=a[1],b=!0,n=i.nameToUrl(k)));b=g&&!d&&!b?"_unnormalized"+(Q+=1):"";return{prefix:g,name:k,parentMap:e,unnormalized:!!b,url:n,originalName:l,isDefine:m,id:(g?g+"!"+k:k)+b}}function q(a){var e=a.id,b=j(k,e);b||(b=k[e]=new i.Module(a));
return b}function r(a,e,b){var f=a.id,n=j(k,f);if(s(p,f)&&(!n||n.defineEmitComplete))"defined"===e&&b(p[f]);else if(n=q(a),n.error&&"error"===e)b(n.error);else n.on(e,b)}function w(a,e){var b=a.requireModules,f=!1;if(e)e(a);else if(v(b,function(e){if(e=j(k,e))e.error=a,e.events.error&&(f=!0,e.emit("error",a))}),!f)h.onError(a)}function x(){S.length&&(ia.apply(A,[A.length,0].concat(S)),S=[])}function y(a){delete k[a];delete W[a]}function F(a,e,b){var f=a.map.id;a.error?a.emit("error",a.error):(e[f]=
!0,v(a.depMaps,function(f,c){var d=f.id,g=j(k,d);g&&(!a.depMatched[c]&&!b[d])&&(j(e,d)?(a.defineDep(c,p[d]),a.check()):F(g,e,b))}),b[f]=!0)}function D(){var a,e,b=(a=1E3*l.waitSeconds)&&i.startTime+a<(new Date).getTime(),f=[],c=[],h=!1,k=!0;if(!X){X=!0;B(W,function(a){var i=a.map,m=i.id;if(a.enabled&&(i.isDefine||c.push(a),!a.error))if(!a.inited&&b)g(m)?h=e=!0:(f.push(m),d(m));else if(!a.inited&&(a.fetched&&i.isDefine)&&(h=!0,!i.prefix))return k=!1});if(b&&f.length)return a=C("timeout","Load timeout for modules: "+
f,null,f),a.contextName=i.contextName,w(a);k&&v(c,function(a){F(a,{},{})});if((!b||e)&&h)if((z||fa)&&!Y)Y=setTimeout(function(){Y=0;D()},50);X=!1}}function E(a){s(p,a[0])||q(m(a[0],null,!0)).init(a[1],a[2])}function L(a){var a=a.currentTarget||a.srcElement,e=i.onScriptLoad;a.detachEvent&&!Z?a.detachEvent("onreadystatechange",e):a.removeEventListener("load",e,!1);e=i.onScriptError;(!a.detachEvent||Z)&&a.removeEventListener("error",e,!1);return{node:a,id:a&&a.getAttribute("data-requiremodule")}}function M(){var a;
for(x();A.length;){a=A.shift();if(null===a[0])return w(C("mismatch","Mismatched anonymous define() module: "+a[a.length-1]));E(a)}}var X,$,i,K,Y,l={waitSeconds:7,baseUrl:"./",paths:{},bundles:{},pkgs:{},shim:{},config:{}},k={},W={},aa={},A=[],p={},T={},ba={},N=1,Q=1;K={require:function(a){return a.require?a.require:a.require=i.makeRequire(a.map)},exports:function(a){a.usingExports=!0;if(a.map.isDefine)return a.exports?a.exports:a.exports=p[a.map.id]={}},module:function(a){return a.module?a.module:
a.module={id:a.map.id,uri:a.map.url,config:function(){return j(l.config,a.map.id)||{}},exports:K.exports(a)}}};$=function(a){this.events=j(aa,a.id)||{};this.map=a;this.shim=j(l.shim,a.id);this.depExports=[];this.depMaps=[];this.depMatched=[];this.pluginMaps={};this.depCount=0};$.prototype={init:function(a,e,b,f){f=f||{};if(!this.inited){this.factory=e;if(b)this.on("error",b);else this.events.error&&(b=t(this,function(a){this.emit("error",a)}));this.depMaps=a&&a.slice(0);this.errback=b;this.inited=
!0;this.ignore=f.ignore;f.enabled||this.enabled?this.enable():this.check()}},defineDep:function(a,e){this.depMatched[a]||(this.depMatched[a]=!0,this.depCount-=1,this.depExports[a]=e)},fetch:function(){if(!this.fetched){this.fetched=!0;i.startTime=(new Date).getTime();var a=this.map;if(this.shim)i.makeRequire(this.map,{enableBuildCallback:!0})(this.shim.deps||[],t(this,function(){return a.prefix?this.callPlugin():this.load()}));else return a.prefix?this.callPlugin():this.load()}},load:function(){var a=
this.map.url;T[a]||(T[a]=!0,i.load(this.map.id,a))},check:function(){if(this.enabled&&!this.enabling){var a,e,b=this.map.id;e=this.depExports;var f=this.exports,c=this.factory;if(this.inited)if(this.error)this.emit("error",this.error);else{if(!this.defining){this.defining=!0;if(1>this.depCount&&!this.defined){if(G(c)){if(this.events.error&&this.map.isDefine||h.onError!==da)try{f=i.execCb(b,c,e,f)}catch(d){a=d}else f=i.execCb(b,c,e,f);this.map.isDefine&&void 0===f&&((e=this.module)?f=e.exports:this.usingExports&&
(f=this.exports));if(a)return a.requireMap=this.map,a.requireModules=this.map.isDefine?[this.map.id]:null,a.requireType=this.map.isDefine?"define":"require",w(this.error=a)}else f=c;this.exports=f;if(this.map.isDefine&&!this.ignore&&(p[b]=f,h.onResourceLoad))h.onResourceLoad(i,this.map,this.depMaps);y(b);this.defined=!0}this.defining=!1;this.defined&&!this.defineEmitted&&(this.defineEmitted=!0,this.emit("defined",this.exports),this.defineEmitComplete=!0)}}else this.fetch()}},callPlugin:function(){var a=
this.map,b=a.id,d=m(a.prefix);this.depMaps.push(d);r(d,"defined",t(this,function(f){var d,g;g=j(ba,this.map.id);var J=this.map.name,u=this.map.parentMap?this.map.parentMap.name:null,p=i.makeRequire(a.parentMap,{enableBuildCallback:!0});if(this.map.unnormalized){if(f.normalize&&(J=f.normalize(J,function(a){return c(a,u,!0)})||""),f=m(a.prefix+"!"+J,this.map.parentMap),r(f,"defined",t(this,function(a){this.init([],function(){return a},null,{enabled:!0,ignore:!0})})),g=j(k,f.id)){this.depMaps.push(f);
if(this.events.error)g.on("error",t(this,function(a){this.emit("error",a)}));g.enable()}}else g?(this.map.url=i.nameToUrl(g),this.load()):(d=t(this,function(a){this.init([],function(){return a},null,{enabled:!0})}),d.error=t(this,function(a){this.inited=!0;this.error=a;a.requireModules=[b];B(k,function(a){0===a.map.id.indexOf(b+"_unnormalized")&&y(a.map.id)});w(a)}),d.fromText=t(this,function(f,c){var g=a.name,J=m(g),k=O;c&&(f=c);k&&(O=!1);q(J);s(l.config,b)&&(l.config[g]=l.config[b]);try{h.exec(f)}catch(j){return w(C("fromtexteval",
"fromText eval for "+b+" failed: "+j,j,[b]))}k&&(O=!0);this.depMaps.push(J);i.completeLoad(g);p([g],d)}),f.load(a.name,p,d,l))}));i.enable(d,this);this.pluginMaps[d.id]=d},enable:function(){W[this.map.id]=this;this.enabling=this.enabled=!0;v(this.depMaps,t(this,function(a,b){var c,f;if("string"===typeof a){a=m(a,this.map.isDefine?this.map:this.map.parentMap,!1,!this.skipMap);this.depMaps[b]=a;if(c=j(K,a.id)){this.depExports[b]=c(this);return}this.depCount+=1;r(a,"defined",t(this,function(a){this.defineDep(b,
a);this.check()}));this.errback&&r(a,"error",t(this,this.errback))}c=a.id;f=k[c];!s(K,c)&&(f&&!f.enabled)&&i.enable(a,this)}));B(this.pluginMaps,t(this,function(a){var b=j(k,a.id);b&&!b.enabled&&i.enable(a,this)}));this.enabling=!1;this.check()},on:function(a,b){var c=this.events[a];c||(c=this.events[a]=[]);c.push(b)},emit:function(a,b){v(this.events[a],function(a){a(b)});"error"===a&&delete this.events[a]}};i={config:l,contextName:b,registry:k,defined:p,urlFetched:T,defQueue:A,Module:$,makeModuleMap:m,
nextTick:h.nextTick,onError:w,configure:function(a){a.baseUrl&&"/"!==a.baseUrl.charAt(a.baseUrl.length-1)&&(a.baseUrl+="/");var b=l.shim,c={paths:!0,bundles:!0,config:!0,map:!0};B(a,function(a,b){c[b]?(l[b]||(l[b]={}),V(l[b],a,!0,!0)):l[b]=a});a.bundles&&B(a.bundles,function(a,b){v(a,function(a){a!==b&&(ba[a]=b)})});a.shim&&(B(a.shim,function(a,c){H(a)&&(a={deps:a});if((a.exports||a.init)&&!a.exportsFn)a.exportsFn=i.makeShimExports(a);b[c]=a}),l.shim=b);a.packages&&v(a.packages,function(a){var b,
a="string"===typeof a?{name:a}:a;b=a.name;a.location&&(l.paths[b]=a.location);l.pkgs[b]=a.name+"/"+(a.main||"main").replace(ja,"").replace(R,"")});B(k,function(a,b){!a.inited&&!a.map.unnormalized&&(a.map=m(b))});if(a.deps||a.callback)i.require(a.deps||[],a.callback)},makeShimExports:function(a){return function(){var b;a.init&&(b=a.init.apply(ca,arguments));return b||a.exports&&ea(a.exports)}},makeRequire:function(a,e){function g(f,c,d){var j,l;e.enableBuildCallback&&(c&&G(c))&&(c.__requireJsBuild=
!0);if("string"===typeof f){if(G(c))return w(C("requireargs","Invalid require call"),d);if(a&&s(K,f))return K[f](k[a.id]);if(h.get)return h.get(i,f,a,g);j=m(f,a,!1,!0);j=j.id;return!s(p,j)?w(C("notloaded",'Module name "'+j+'" has not been loaded yet for context: '+b+(a?"":". Use require([])"))):p[j]}M();i.nextTick(function(){M();l=q(m(null,a));l.skipMap=e.skipMap;l.init(f,c,d,{enabled:!0});D()});return g}e=e||{};V(g,{isBrowser:z,toUrl:function(b){var e,d=b.lastIndexOf("."),g=b.split("/")[0];if(-1!==
d&&(!("."===g||".."===g)||1<d))e=b.substring(d,b.length),b=b.substring(0,d);return i.nameToUrl(c(b,a&&a.id,!0),e,!0)},defined:function(b){return s(p,m(b,a,!1,!0).id)},specified:function(b){b=m(b,a,!1,!0).id;return s(p,b)||s(k,b)}});a||(g.undef=function(b){x();var c=m(b,a,!0),e=j(k,b);d(b);delete p[b];delete T[c.url];delete aa[b];U(A,function(a,c){a[0]===b&&A.splice(c,1)});e&&(e.events.defined&&(aa[b]=e.events),y(b))});return g},enable:function(a){j(k,a.id)&&q(a).enable()},completeLoad:function(a){var b,
c,f=j(l.shim,a)||{},d=f.exports;for(x();A.length;){c=A.shift();if(null===c[0]){c[0]=a;if(b)break;b=!0}else c[0]===a&&(b=!0);E(c)}c=j(k,a);if(!b&&!s(p,a)&&c&&!c.inited){if(l.enforceDefine&&(!d||!ea(d)))return g(a)?void 0:w(C("nodefine","No define call for "+a,null,[a]));E([a,f.deps||[],f.exportsFn])}D()},nameToUrl:function(a,b,c){var f,d,g;(f=j(l.pkgs,a))&&(a=f);if(f=j(ba,a))return i.nameToUrl(f,b,c);if(h.jsExtRegExp.test(a))f=a+(b||"");else{f=l.paths;a=a.split("/");for(d=a.length;0<d;d-=1)if(g=a.slice(0,
d).join("/"),g=j(f,g)){H(g)&&(g=g[0]);a.splice(0,d,g);break}f=a.join("/");f+=b||(/^data\:|\?/.test(f)||c?"":".js");f=("/"===f.charAt(0)||f.match(/^[\w\+\.\-]+:/)?"":l.baseUrl)+f}return l.urlArgs?f+((-1===f.indexOf("?")?"?":"&")+l.urlArgs):f},load:function(a,b){h.load(i,a,b)},execCb:function(a,b,c,d){return b.apply(d,c)},onScriptLoad:function(a){if("load"===a.type||ka.test((a.currentTarget||a.srcElement).readyState))P=null,a=L(a),i.completeLoad(a.id)},onScriptError:function(a){var b=L(a);if(!g(b.id))return w(C("scripterror",
"Script error for: "+b.id,a,[b.id]))}};i.require=i.makeRequire();return i}var h,x,y,D,L,E,P,M,q,Q,la=/(\/\*([\s\S]*?)\*\/|([^:]|^)\/\/(.*)$)/mg,ma=/[^.]\s*require\s*\(\s*["']([^'"\s]+)["']\s*\)/g,R=/\.js$/,ja=/^\.\//;x=Object.prototype;var N=x.toString,ga=x.hasOwnProperty,ia=Array.prototype.splice,z=!!("undefined"!==typeof window&&"undefined"!==typeof navigator&&window.document),fa=!z&&"undefined"!==typeof importScripts,ka=z&&"PLAYSTATION 3"===navigator.platform?/^complete$/:/^(complete|loaded)$/,
Z="undefined"!==typeof opera&&"[object Opera]"===opera.toString(),F={},r={},S=[],O=!1;if("undefined"===typeof define){if("undefined"!==typeof requirejs){if(G(requirejs))return;r=requirejs;requirejs=void 0}"undefined"!==typeof require&&!G(require)&&(r=require,require=void 0);h=requirejs=function(b,c,d,g){var u,m="_";!H(b)&&"string"!==typeof b&&(u=b,H(c)?(b=c,c=d,d=g):b=[]);u&&u.context&&(m=u.context);(g=j(F,m))||(g=F[m]=h.s.newContext(m));u&&g.configure(u);return g.require(b,c,d)};h.config=function(b){return h(b)};
h.nextTick="undefined"!==typeof setTimeout?function(b){setTimeout(b,4)}:function(b){b()};require||(require=h);h.version="2.1.10";h.jsExtRegExp=/^\/|:|\?|\.js$/;h.isBrowser=z;x=h.s={contexts:F,newContext:ha};h({});v(["toUrl","undef","defined","specified"],function(b){h[b]=function(){var c=F._;return c.require[b].apply(c,arguments)}});if(z&&(y=x.head=document.getElementsByTagName("head")[0],D=document.getElementsByTagName("base")[0]))y=x.head=D.parentNode;h.onError=da;h.createNode=function(b){var c=
b.xhtml?document.createElementNS("http://www.w3.org/1999/xhtml","html:script"):document.createElement("script");c.type=b.scriptType||"text/javascript";c.charset="utf-8";c.async=!0;return c};h.load=function(b,c,d){var g=b&&b.config||{};if(z)return g=h.createNode(g,c,d),g.setAttribute("data-requirecontext",b.contextName),g.setAttribute("data-requiremodule",c),g.attachEvent&&!(g.attachEvent.toString&&0>g.attachEvent.toString().indexOf("[native code"))&&!Z?(O=!0,g.attachEvent("onreadystatechange",b.onScriptLoad)):
(g.addEventListener("load",b.onScriptLoad,!1),g.addEventListener("error",b.onScriptError,!1)),g.src=d,M=g,D?y.insertBefore(g,D):y.appendChild(g),M=null,g;if(fa)try{importScripts(d),b.completeLoad(c)}catch(j){b.onError(C("importscripts","importScripts failed for "+c+" at "+d,j,[c]))}};z&&!r.skipDataMain&&U(document.getElementsByTagName("script"),function(b){y||(y=b.parentNode);if(L=b.getAttribute("data-main"))return q=L,r.baseUrl||(E=q.split("/"),q=E.pop(),Q=E.length?E.join("/")+"/":"./",r.baseUrl=
Q),q=q.replace(R,""),h.jsExtRegExp.test(q)&&(q=L),r.deps=r.deps?r.deps.concat(q):[q],!0});define=function(b,c,d){var g,h;"string"!==typeof b&&(d=c,c=b,b=null);H(c)||(d=c,c=null);!c&&G(d)&&(c=[],d.length&&(d.toString().replace(la,"").replace(ma,function(b,d){c.push(d)}),c=(1===d.length?["require"]:["require","exports","module"]).concat(c)));if(O){if(!(g=M))P&&"interactive"===P.readyState||U(document.getElementsByTagName("script"),function(b){if("interactive"===b.readyState)return P=b}),g=P;g&&(b||
(b=g.getAttribute("data-requiremodule")),h=F[g.getAttribute("data-requirecontext")])}(h?h.defQueue:S).push([b,c,d])};define.amd={jQuery:!0};h.exec=function(b){return eval(b)};h(r)}})(this);]]>
    </xsl:text>
    </script>
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
    <script type="text/javascript">_editor.loadCSS('<xsl:value-of select="$href"/>');</script>
    <noscript>
    <link rel="stylesheet" type="text/css" href="{$href}"/>
    </noscript>
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
	<xsl:if test="//movie:movie">
		<link href="http://vjs.zencdn.net/4.1/video-js.css" rel="stylesheet"/>
	</xsl:if>
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
				<span class="shared_frame_{$variant}_top"><span><span><xsl:comment/></span></span></span>
				<span class="shared_frame_{$variant}_middle">
					<span class="shared_frame_{$variant}_middle">
						<span class="shared_frame_{$variant}_content">
							<xsl:copy-of select="$content"/>
						</span>
					</span>
				</span>
				<span class="shared_frame_{$variant}_bottom"><span><span><xsl:comment/></span></span></span>
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