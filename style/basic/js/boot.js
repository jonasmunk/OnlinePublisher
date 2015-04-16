/* require & define https://curiosity-driven.org/minimal-loader#final*/
!function(){function n(n,e){e in r?n(e,r[e]):i[e]?i[e].push(n):i[e]=[n]}function e(n,e){r[n]=e;var t=i[n];t&&(t.forEach(function(t){t(n,e)}),i[n]=0)}function t(e,t){var i=e.length;if(i){var r=[],f=0;e.forEach(n.bind(0,function(n,o){r[e.indexOf(n)]=o,++f>=i&&t.apply(0,r)}))}else t()}var i={},r={};require=t,define=function(n,i,r){r?t(i,function(){e(n,r.apply(0,arguments))}):e(n,i)}}();

(function(window,document) {    
  window._editor = {
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
    loadPart : function(info) {
      require(['hui','hui.ui','op'],function() {
        _editor.loadScript(_editor.context+'style/basic/js/parts/' + info.name + '.js');
      });
      require(['op.part.'+info.name],info.$ready);
    },
    loadCSS : function(href) {
      this.viewReady(function() {
        _editor.inject(_editor._build('link',{
          rel : 'stylesheet',
          type : 'text/css',
          href : href
        }));
      });
    },
    _loaded : {},
    loadScript : function(src) {
      if (!this._loaded[src]) {
        this._loaded[src] = 1;
        _editor.inject(this._build('script',{async:'async',src:src}));
      }
    },
    _build : function(name,attributes) {
        var e = document.createElement(name);
        for (variable in attributes) {
          e.setAttribute(variable,attributes[variable]);
        }
        return e;        
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

    /**
     * Finds ‹noscript class="js-async"› and turns its contents into real tags
     */
    processNoscript : function() {
      this.ready(function() {
        var noscripts = document.getElementsByTagName('noscript');
        for (var i = 0; i < noscripts.length; i++) {
          var noscript = noscripts[i];
          if (noscript.className=='js-async') {
            var x = document.createElement('div');
            x.innerHTML = noscript.firstChild.nodeValue;
            var c = x.childNodes;
            for (var i = 0; i < c.length; i++) {
              noscript.parentNode.insertBefore(c[i],noscript);
            }
          }
        }
      });
    }
  }

  _editor.processNoscript();
})(window,document);