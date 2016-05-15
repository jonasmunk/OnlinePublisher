(function(_) {

  var exhibit = {
    space : 'concrete',

    init : function() {
      var self = this;
      _.on(_.find('.js-spaces'),'tap',function(e) {
        e = _.event(e);
        var option = e.findByClass('js-spaces-option');
        if (option) {
          e.stop();
          var nw = option.getAttribute('data');
          hui.cls.remove(document.body,'exhibit-' + self.space);
          hui.cls.add(document.body,'exhibit-' + nw);
          self.space = nw;
          var x = _.findAll('.js-spaces-option');
          for (var i = 0; i < x.length; i++) {
            _.cls.remove(x[i],'is-selected');
          }
          _.cls.add(option,'is-selected');
        }
      })
    }
  }

  var viewer = {
    element : _.find('.js-viewer'),
    inner : _.find('.js-viewer-inner'),
    source : null,
    busy : false,
    zoomed : false,
    state : {
      rotation : 0,
      scale : 1,
      x: 0,
      y: 0
    },
    init : function() {
      var mc = new Hammer.Manager(this.element);

      // create a pinch and rotate recognizer
      // these require 2 pointers
      var pinch = new Hammer.Pinch();
      var rotate = new Hammer.Rotate();
      var pan = new Hammer.Pan();
      var tap = new Hammer.Tap();

      // we want to detect both the same time
      pan.recognizeWith(rotate);
      pan.recognizeWith(pinch);

      // add to the Manager
      mc.add([pinch, rotate, tap, pan]);
      var lmnt = this.element, inner = this.inner;
      var start = {
        rotation : undefined,
        scale : undefined,
        x : 0,
        y : 0
      };

      var session = {
        rotation : 0,
        scale : 0,
        x : 0,
        y : 0
      };
      var curRotation = 0;
      var self = this;

      mc.on("rotatestart panstart", function(ev) {
        if (self.busy) {return;}
        //_.log('-- ' + ev.type + ': rotate=' + ev.rotation + ', scale=' + ev.scale + ', move=' + ev.deltaX +'x'+ev.deltaY+', num=' + ev.srcEvent.targetTouches.length);
        start.rotation = ev.type=='rotatestart' ? ev.rotation : undefined;
        start.scale = ev.type=='rotatestart' ? ev.scale : undefined;
        start.x = start.x || ev.deltaX;
        start.y = start.y || ev.deltaY;
        session.rotation = 0;
        session.scale = 0;
        session.x = 0;
        session.y = 0;
      });

      mc.on("pinch rotate pan", function(ev) {
        if (self.busy) {return;}
        //_.log(ev.type + ': rotate=' + ev.rotation + ', scale=' + ev.scale + ', move=' + ev.deltaX +'x'+ev.deltaY+', num=' + ev.srcEvent.targetTouches.length);
        if (start.scale==undefined) {start.scale = ev.scale}
        if (ev.srcEvent.targetTouches.length == 2) {
          if (start.rotation==undefined) {start.rotation = ev.rotation}
          session.rotation = ev.rotation - start.rotation;
        }
        session.scale = 1 + ev.scale - start.scale;
        session.x = ev.deltaX - start.x;
        session.y = ev.deltaY - start.y;
        //_.log(ev);
        var rotation = (session.rotation + self.state.rotation) % 360;
        /*if (ev.type=='pan') {
          _.log(rotation);
        }*/
        if (rotation > 180) {
          rotation -= 360;
        } else if (rotation < -180) {
          rotation += 360;
        }
        var scale = session.scale * self.state.scale;
        //_.log((curRotation + self.state.rotation) + ' / ' + rotation);
        //_.log(session.x + 'x' + session.y);
        if (!true) {
          self.image.style.transform = 'translate3d(' + (session.x + self.state.x) + 'px,' + (session.y + self.state.y) + 'px,0) rotate(' + rotation + 'deg)';
          self.image.style.width = (500*scale) + 'px';
          self.image.style.height = (500*scale) + 'px';
        }
        else if (true) {
          inner.style.transform = 'translate3d(' + (session.x + self.state.x) + 'px,' + (session.y + self.state.y) + 'px,0) rotate(' + rotation + 'deg) scale(' + scale + ')';
        } else {
          inner.style.transform = 'translate3d(' + (session.x + self.state.x) + 'px,' + (session.y + self.state.y) + 'px,0) rotate(' + rotation + 'deg)';
          inner.style.width = (self.state.scale * ev.scale * 100) + '%';
          inner.style.height = (self.state.scale * ev.scale * 100) + '%';
          inner.style.left = ((1 - self.state.scale * ev.scale) * 50) + '%';
          inner.style.top = ((1 - self.state.scale * ev.scale) * 50) + '%';
        }

        //inner.style.marginTop = (ev.deltaY + self.state.top) + 'px';
        if (!self.zoomed && scale > 1.2) {
          //self.zoom();
          self.zoomed = true;
        }
      });
      mc.on("rotateend panend", function(ev) {
        if (self.busy) {return;}
        //_.log('-- ' + ev.type + ': rotate=' + ev.rotation + ', scale=' + ev.scale + ', move=' + ev.deltaX +'x'+ev.deltaY+', num=' + ev.srcEvent.targetTouches.length);
        self.state.scale *= session.scale;
        self.state.x += session.x;
        self.state.y += session.y;
        self.state.rotation += session.rotation;
        session = {
          rotation : 0,
          scale : 0,
          x : 0,
          y : 0
        };
        start = {
          rotation : undefined,
          scale : undefined,
          x : 0,
          y : 0
        };
        start.rotation = undefined;
        //_.log(self.state);
        //_.log(self.state.scale);
        if (self.state.scale<.4) {
          self.hide();
        }
      });
      var self = this;
      mc.on("tap", function(ev) {
        self.hide();
      });
    },
    zoom : function() {
      this.updateImage({node:this.inner,id:this.source.getAttribute('data-id'),width:0,height:0});
    },
    hide : function() {
      if (this.busy) {return;}
      this.busy = true;
      //_.log('hide')
      var lmnt = this.element,
        inner = this.inner;
      lmnt.style.transition = '';
      this._reset();
      this._setViewerSize();
      _.cls.add(lmnt,'is-small');
      _.cls.remove(lmnt,'is-full');
      inner.style.transition = 'all .5s';
      inner.style.transform = '';
      lmnt.style.transition = 'all .5s';
      lmnt.style.transform = '';
      //inner.style.width = '';
      //inner.style.height = '';
      //inner.style.top = '';
      //inner.style.left = '';
      var self = this;
      window.setTimeout(function() {
        this.source.style.opacity = '';
        window.setTimeout(function() {

          _.cls.remove(lmnt,'is-small');
          inner.style.transition = '';
          lmnt.style.transition = '';
          self.busy = false;
        },50)
      }.bind(this),550);
    },
    _reset : function() {
      this.state.rotation = 0;
      this.state.scale = 1;
      this.state.x = 0;
      this.state.y = 0;
      this.zoomed = false;
    },
    _setViewerSize : function() {

      var src = this.source;
      var element = this.element;
      var pos = _.position.get(src);
      pos.left -= src.parentNode.parentNode.scrollLeft;
      var w = _.window.getViewWidth();
      var h = _.window.getViewHeight();
      _.style.set(element,{
        transition: 'all .5s',
        top: pos.top + 'px',
        left: pos.left + 'px',
        right: (w - pos.left - src.clientWidth) + 'px',
        bottom: (h - pos.top - src.clientHeight) + 'px'
      })
    },
    getUrl : function(id,width,height) {
      var ratio = Math.min(2,window.devicePixelRatio || 1);
      width = width * ratio;
      height = height * ratio;
      var url = op.context + 'services/images/?id=' + id;
      if (width && height) {
        url += '&width=' + width + '&height=' + height + '&background=transparent&format=png';
      }
      return url;
    },

    updateImage : function(options) {
      _.cls.add(options.node,'is-loading-image');
      var width = options.width;
      width = Math.ceil(width / 100) * 100;
      var height = options.height;
      height = Math.ceil(height / 100) * 100;
      var url = this.getUrl(options.id,width,height);
      _.log(url);
      var img = new Image();
      img.onload = function() {
        _.style.set(options.node,{
          backgroundImage : 'url(' + url + ')'
        });
        _.cls.remove(options.node,'is-loading-image');
      }
      img.src = url;
    },

    show : function(options) {
      if (this.busy) {return;}
      var self = this;
      this.busy = true;
      this._reset();
      var src = this.source = options.src;
      var bg = _.style.get(src,'background-image');
      _.style.set(this.inner,{
        backgroundImage : bg
      });
      this.updateImage({node:this.inner,id:src.getAttribute('data-id'),width:hui.window.getViewWidth(),height:hui.window.getViewHeight()});
      this._setViewerSize();
      var element = this.element;
      this.inner.style.transform = '';
      if (!this.image) {
        //this.image = hui.build('img',{src:this.getUrl(src.getAttribute('data-id')),'class':'exhibit_viewer_image',style:{width:'500px',height:'500px'},parent:this.element})
      }
      src.style.opacity = '0';
      _.cls.add(element,'is-small');
      window.setTimeout(function() {
        _.cls.remove(element,'is-small');
        _.cls.add(element,'is-full');
        window.setTimeout(function() {
          element.style.transition = 'none';
          self.busy = false;
        },550)
      },150)
    }
  };
  viewer.init();

  var latestWide;
  var check = function() {
    var x = 1400 / 1227;
    var w = _.window.getViewWidth();
    var h = _.window.getViewHeight();
    var wide = w/h > 1;
    if (latestWide!==wide) {
      _.onDraw(function() {
        _.cls.set(document.body,'is-wide',wide);
      })
    }
    latestWide = wide;
  }
  _.listen(window,'resize',check);
  _.onReady(function() {
    check();
    _.cls.add(_.find('body'),'is-layout')
    exhibit.init();
  });

  _.on(_.find('.exhibit_paintings'),'tap',function(ev) {
    if (_.cls.has(ev.target,'exhibit_painting')) {
      viewer.show({src:ev.target});
    }
  })

})(hui)