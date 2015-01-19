// require & define https://curiosity-driven.org/minimal-loader#final
//!function(){function n(n,e){e in r?n(e,r[e]):i[e]?i[e].push(n):i[e]=[n]}function e(n,e){r[n]=e;var t=i[n];t&&(t.forEach(function(t){t(n,e)}),i[n]=0)}function t(e,t){var i=e.length;if(i){var r=[],f=0;e.forEach(n.bind(0,function(n,o){r[e.indexOf(n)]=o,++f>=i&&t.apply(0,r)}))}else t()}var i={},r={};require=t,define=function(n,i,r){r?t(i,function(){e(n,r.apply(0,arguments))}):e(n,i)}}();

// $script loader
(function(e,t){typeof module!="undefined"&&module.exports?module.exports=t():typeof define=="function"&&define.amd?define(t):this[e]=t()})("$script",function(){function h(e,t){for(var n=0,i=e.length;n<i;++n)if(!t(e[n]))return r;return 1}function p(e,t){h(e,function(e){return!t(e)})}function d(e,t,n){function g(e){return e.call?e():u[e]}function y(){if(!--m){u[o]=1,s&&s();for(var e in f)h(e.split("|"),g)&&!p(f[e],g)&&(f[e]=[])}}e=e[i]?e:[e];var r=t&&t.call,s=r?t:n,o=r?e.join(""):t,m=e.length;return setTimeout(function(){p(e,function(e){if(e===null)return y();if(l[e])return o&&(a[o]=1),l[e]==2&&y();l[e]=1,o&&(a[o]=1),v(!/^https?:\/\//.test(e)&&c?c+e+".js":e,y)})},0),d}function v(n,i){var u=e.createElement("script"),a=r;u.onload=u.onerror=u[o]=function(){if(u[s]&&!/^c|loade/.test(u[s])||a)return;u.onload=u[o]=null,a=1,l[n]=2,i()},u.async=1,u.src=n,t.insertBefore(u,t.lastChild)}var e=document,t=e.getElementsByTagName("head")[0],n="string",r=!1,i="push",s="readyState",o="onreadystatechange",u={},a={},f={},l={},c;return d.get=v,d.order=function(e,t,n){(function r(i){i=e.shift(),e.length?d(i,r):d(i,t,n)})()},d.path=function(e){c=e},d.ready=function(e,t,n){e=e[i]?e:[e];var r=[];return!p(e,function(e){u[e]||r[i](e)})&&h(e,function(e){return u[e]})?t():!function(e){f[e]=f[e]||[],f[e][i](t),n&&n(r)}(e.join("|")),d},d.done=function(e){d([null],e)},d});

(function(window,document) {    
window._editor = {
    deferred : [],
    scriptLoaded : false,
    ready : function(delegate) {
        if (document.readyState == 'complete') {
            delegate();
        }
    	else if (window.addEventListener) {
    		window.addEventListener('DOMContentLoaded',delegate,false);
    	}
        else if(document.addEventListener) {
    		document.addEventListener('load', delegate, false);
    	}
    	else if(typeof window.attachEvent != 'undefined') {
    		window.attachEvent('onload', delegate);
    	}
    },
    viewReady : function(func) {
        var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
        if (raf) {
            return raf(func);
        }
        this.ready(func);
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
        this.viewReady(function() {
            var e = document.createElement('link');
            e.setAttribute('rel','stylesheet');
            e.setAttribute('type','text/css');
            e.setAttribute('href',href);
            _editor.inject(e);
        });
    },
    inject : function(node) {
        var h = document.getElementsByTagName('head')[0];
        if (h) {
            h.appendChild(node);
        } else {
            this.ready(function() {
                _editor.inject(node);
            })
        }
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
})(window,document);
/*
require(['hui'],function() {
	//alert(hui.browser.webkit);
})*/