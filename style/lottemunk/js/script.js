var ctrl = {
	
	nodes : {},
	
	attach : function() {
		var head = hui.get('head'),
			title = hui.get('title'),
			broen = hui.get('broen'),
			about = hui.get('about'),
			press = hui.get('pressphotos'),
			theater = hui.get('theater'),
			theater_photo = hui.get.firstByClass(theater,'photo');
		
		hui.parallax.listen({
			min : 0,
			max : 246,
			$ : function(pos) {
				head.style.height = ((1-pos)*146+100)+'px';
				title.style.fontSize = ((1-pos)*30+50)+'px';
				broen.style.opacity = 1-hui.ease.quadOut(pos);
			}
		})
		hui.parallax.listen({
			element : about,
			$ : function(pos) {
				hui.cls.set(about,'visible',pos>.2 && pos<.8)
			}
		})
		hui.parallax.listen({
			element : press,
			$ : function(pos) {
				hui.cls.set(press,'visible',pos>.2 && pos<.8);
				hui.cls.set(press,'saturated',pos>.1 && pos<.9)
			}
		})
		hui.parallax.listen({
			element : theater,
			$ : function(pos) {
				//hui.log(pos)
				hui.cls.set(document.body,'dark',pos>0 && pos<1);
				var show = pos>.3 && pos<.7;
				if (this.shown!=show) {
					hui.cls.set(document.body,'full',show);
					if (show) {
						hui.animate({node:theater_photo,css:{opacity:show ? 1 : 0},ease:hui.ease.flicker,duration:3000});
					}
					this.shown = show;
				}
			},
			$resize : function(width,height) {
				theater.style.height = Math.round(height*.8)+'px';
			}
		})
		hui.parallax._resize();
/*		var change = function() {
			theater.style.height = Math.round(hui.window.getViewHeight()*.8)+'px';
		}
		hui.listen(window,'resize',change);
		change();*/
	},
	_scroll : function() {
		var pos = document.body.scrollTop,
			n = this.nodes;
		n.head.style.height = Math.min(246,Math.max(246-pos,100))+'px';
		n.title.style.top = Math.min(30,Math.max(30-pos+20,10))+'px';
		n.title.style.fontSize = Math.min(80,Math.max(80-pos+100,50))+'px';
	}
}

hui.onReady(ctrl.attach.bind(ctrl))


hui.parallax = {
	
	_listeners : [],
	
	_init : function() {
		if (this._listening) {
			return;
		}
		this._listening = true;
		hui.listen(window,'scroll',this._scroll.bind(this));
		hui.listen(window,'resize',this._resize.bind(this));
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
		hui.log(this._listeners.length)
		var pos = hui.window.getScrollTop(),
			viewHeight = hui.window.getViewHeight();
		for (var i = this._listeners.length - 1; i >= 0; i--) {
			var l = this._listeners[i];
			
			if (l.element) {
				var top = hui.position.getTop(l.element);
				top+= l.element.clientHeight/2;
				var diff = top-pos;
				l.$( diff / viewHeight);
				continue;
			}
			
			var x = (pos-l.min)/(l.max-l.min);
			var y = hui.between(0,x,1);
			
			if (l._latest!==y) {
				l.$(y);
				l._latest=y;			
			}
		}
	},
	
	listen : function(info) {
		this._listeners.push(info);
		this._init();
	}
}

hui.between = function(min,value,max) {
	var result = Math.min(max,Math.max(min,value));
	return isNaN(result) ? min : result;
}