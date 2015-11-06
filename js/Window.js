/**
 * @constructor
 */
hui.ui.Window = function(options) {
	this.element = hui.get(options.element);
	this.name = options.name;
	this.close = hui.get.firstByClass(this.element,'hui_window_close');
	this.titlebar = hui.get.firstByClass(this.element,'hui_window_titlebar');
	this.title = hui.get.firstByClass(this.titlebar,'hui_window_title');
	this.content = hui.get.firstByClass(this.element,'hui_window_body');
	this.front = hui.get.firstByClass(this.element,'hui_window_front');
	this.back = hui.get.firstByClass(this.element,'hui_window_back');
	if (this.back) {
		hui.effect.makeFlippable({container:this.element,front:this.front,back:this.back});
	}
	this.visible = false;
	hui.ui.extend(this);
	this._addBehavior();
	if (options.listener) {
		this.listen(options.listener);
	}
}

hui.ui.Window.create = function(options) {
	options = hui.override({title:'Window',close:true},options);
	var html = '<div class="hui_window_front">'+(options.close ? '<div class="hui_window_close"></div>' : '')+
		'<div class="hui_window_titlebar"><div><div>';
		if (options.icon) {
			html+='<span class="hui_window_icon" style="background-image: url('+hui.ui.getIconUrl(options.icon,16)+')"></span>';
		}
	html+='<span class="hui_window_title">'+hui.ui.getTranslated(options.title)+'</span></div></div></div>'+
		'<div class="hui_window_content"><div class="hui_window_content"><div class="hui_window_body" style="'+
		(options.width ? 'width:'+options.width+'px;':'')+
		(options.height ? 'height:'+options.height+'px;':'')+
		(options.padding ? 'padding:'+options.padding+'px;':'')+
		(options.padding ? 'padding-bottom:'+Math.max(0,options.padding-2)+'px;':'')+
		'">'+
		'</div></div></div>'+
		'<div class="hui_window_bottom"><div class="hui_window_bottom"><div class="hui_window_bottom"></div></div></div></div>';
	var cls = 'hui_window'+(options.variant ? ' hui_window_'+options.variant : '');
	if (options.variant=='dark') {
		cls+=' hui_context_dark';
	}
	options.element = hui.build('div',{'class':cls,html:html,parent:document.body});
	if (options.variant=='dark') {
		hui.cls.add(options.element,'hui_context_dark');
	}
	return new hui.ui.Window(options);
}

hui.ui.Window.prototype = {
	_addBehavior : function() {
		var self = this;
		if (this.close) {
			hui.listen(this.close,'click',function(e) {
				this.hide();
				this.fire('userClosedWindow'); // TODO maybe rename to closeByUser
			}.bind(this));
			hui.listen(this.close,'mousedown',function(e) {hui.stop(e)});
		}
		hui.drag.register({
			element : this.titlebar,
			onStart : this._onDragStart.bind(this) ,
			onBeforeMove : this._onBeforeMove.bind(this) ,
 			onMove : this._onMove.bind(this),
			onAfterMove : this._onAfterMove.bind(this)
		});
		hui.listen(this.element,'mousedown',function() {
			self.element.style.zIndex = hui.ui.nextPanelIndex();
		});
	},
	setTitle : function(title) {
		hui.dom.setText(this.title,hui.ui.getTranslated(title));
	},
	_positionInView : function() {
		var scrollTop = hui.window.getScrollTop();
		var winTop = hui.position.getTop(this.element);
		if (winTop < scrollTop || winTop+this.element.clientHeight > hui.window.getViewHeight()+scrollTop) {
			hui.animate({node:this.element,css:{top:(scrollTop+40)+'px'},duration:500,ease:hui.ease.slowFastSlow});
		}
	},
	show : function(options) {
		if (this.visible) {
			this._positionInView();
			this.element.style.zIndex=hui.ui.nextPanelIndex();
			return;
		}
		options = options || {};
		hui.style.set(this.element,{
			zIndex : hui.ui.nextPanelIndex(), visibility : 'hidden', display : 'block'
		})
		var width = this.element.clientWidth;
		hui.style.set(this.element,{
			width : width+'px' , visibility : 'visible'
		});
		if (options.avoid) {
			hui.position.place({insideViewPort : true, target : {element : options.avoid, vertical : .5, horizontal : 1}, source : {element : this.element, vertical : .5, horizontal : 0} });
		} else {
			if (!this.element.style.top) {
				this.element.style.top = (hui.window.getScrollTop()+40)+'px';
			} else {
				this._positionInView();
			}
			if (!this.element.style.left) {
				this.element.style.left = Math.round((hui.window.getViewWidth()-width)/2)+'px';
			}			
		}
		if (hui.browser.opacity) {
			hui.animate(this.element,'opacity',1,0);
		}
		this.visible = true;
		hui.ui.callVisible(this);
	},
	toggle : function(options) {
		(this.visible ? this.hide() : this.show(options) );
	},
	hide : function() {
		if (!this.visible) return;
		if (hui.browser.opacity) {
			hui.animate(this.element,'opacity',0,100,{$complete:function() {
				this.element.style.display='none';
				hui.ui.callVisible(this);
			}.bind(this)});
		} else {
			this.element.style.display='none';
			hui.ui.callVisible(this);
		}
		this.visible = false;
	},
	add : function(widgetOrNode) {
		if (widgetOrNode.getElement) {
			this.content.appendChild(widgetOrNode.getElement());
		} else {
			this.content.appendChild(widgetOrNode);
		}
	},
	addToBack : function(widgetOrNode) {
		if (!this.back) {
			this.back = hui.build('div',{className:'hui_window_back'});
			this.element.insertBefore(this.back,this.front);
			hui.effect.makeFlippable({container:this.element,front:this.front,back:this.back});
		}
		this.back.appendChild(hui.ui.getElement(widgetOrNode));
	},
	setVariant : function(variant) {
		hui.cls.remove(this.element,'hui_window_dark');
		hui.cls.remove(this.element,'hui_window_light');
		hui.cls.remove(this.element,'hui_window_news');
		if (variant=='dark' || variant=='light' || variant=='news') {
			hui.cls.add(this.element,'hui_window_'+variant);
		}
		hui.cls.set(this.element,'hui_context_dark',variant=='dark');
	},
	flip : function() {
		if (this.back) {
			this.back.style.minHeight = this.element.clientHeight+'px';
			hui.effect.flip({element:this.element});
		}
	},
	setBusy : function(stringOrBoolean) {
		window.clearTimeout(this._busyTimer);
		if (stringOrBoolean===false) {
			if (this._busyCurtain) {
				this._busyCurtain.style.display = 'none';
			}
			return;
		}
		this._busyTimer = window.setTimeout(function() {
			var curtain = this._busyCurtain;
			if (!curtain) {
				curtain = this._busyCurtain = hui.build('div',{'class':'hui_window_busy',parentFirst:hui.get.firstByClass(this.element,'hui_window_content')})
			}
			curtain.innerHTML = hui.isString(stringOrBoolean) ? '<span>'+stringOrBoolean+'</span>' : '<span></span>';
			curtain.style.display = '';
		}.bind(this),300);
	},
	
	move : function(point) {
		hui.style.set(this.element,{top:point.top+'px',left:point.left+'px'});
	},

	_onDragStart : function(e) {
		this.element.style.zIndex = hui.ui.nextPanelIndex();
	},
	_onBeforeMove : function(e) {
		e = hui.event(e);
		var pos = hui.position.get(this.element);
		this.dragState = {left: e.getLeft() - pos.left,top:e.getTop()-pos.top};
		this.element.style.right = 'auto';
		hui.cls.add(this.element,'hui_window_dragging');
	},
	_onMove : function(e) {
		var top = (e.getTop()-this.dragState.top);
		var left = (e.getLeft()-this.dragState.left);
		this.element.style.top = Math.max(top,0)+'px';
		this.element.style.left = Math.max(left,0)+'px';
	},
	_onAfterMove : function() {
		hui.ui.callDescendants(this,'$$parentMoved');
		hui.cls.remove(this.element,'hui_window_dragging');
	},
    destroy : function() {
        hui.dom.remove(this.element);
    }
}

/* EOF */