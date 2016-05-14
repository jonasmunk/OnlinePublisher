hui.parallax = {
  
  _listeners : [],
  
  _init : function() {
    if (this._listening) {
      return;
    }
    this._listening = true;
    hui.listen(window,'scroll',this._scroll.bind(this));
    hui.listen(window,'resize',this._resize.bind(this));
    hui.onReady(this._resize.bind(this));
  },
  _resize : function() {
    for (var i = this._listeners.length - 1; i >= 0; i--) {
      var l = this._listeners[i];
      if (l.$resize) {
        l.$resize(hui.window.getViewWidth(),hui.window.getViewHeight());
      }
    }
    this._scroll();
  },
  _scroll : function() {
    var pos = hui.window.getScrollTop(),
      viewHeight = hui.window.getViewHeight();
    for (var i = this._listeners.length - 1; i >= 0; i--) {
      var l = this._listeners[i];
      if (!l.$scroll) {
        continue;
      }
      if (l.debug && !l.debugElement) {
        l.debugElement = hui.build('div',{style:'position: absolute; border-top: 1px solid red; left: 0; right: 0;',parent:document.body});
      }
      
      if (l.element) {
        var top = hui.position.getTop(l.element);
        top+= l.element.clientHeight/2;
        var diff = top-pos;
        var scroll = ( diff / viewHeight);
        if (l.debugElement) {
          l.debugElement.style.top = top+'px';
          l.debugElement.innerHTML = '<span>'+scroll+'</span>';
        }
        l.$scroll( scroll );
        continue;
      }
      
      var x = (pos-l.min)/(l.max-l.min);
      var y = hui.between(0,x,1);
      
      if (l._latest!==y) {
        l.$scroll(y);
        l._latest=y;
      }
    }
  },
  
  listen : function(info) {
    this._listeners.push(info);
    this._init();
  }
};