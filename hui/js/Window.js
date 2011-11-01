/**
 * @constructor
 */
hui.ui.Window = function(options) {
	this.element = hui.get(options.element);
	this.name = options.name;
	this.close = hui.firstByClass(this.element,'hui_window_close');
	this.titlebar = hui.firstByClass(this.element,'hui_window_titlebar');
	this.title = hui.firstByClass(this.titlebar,'hui_window_title');
	this.content = hui.firstByClass(this.element,'hui_window_body');
	this.front = hui.firstByClass(this.element,'hui_window_front');
	this.back = hui.firstByClass(this.element,'hui_window_back');
	if (this.back) {
		hui.effect.makeFlippable({container:this.element,front:this.front,back:this.back});
	}
	this.visible = false;
	hui.ui.extend(this);
	this.addBehavior();
}

hui.ui.Window.create = function(options) {
	options = hui.override({title:'Window',close:true},options);
	var html = '<div class="hui_window_front">'+(options.close ? '<div class="hui_window_close"></div>' : '')+
		'<div class="hui_window_titlebar"><div><div>';
		if (options.icon) {
			html+='<span class="hui_window_icon" style="background-image: url('+hui.ui.getIconUrl(options.icon,16)+')"></span>';
		}
	html+='<span class="hui_window_title">'+options.title+'</span></div></div></div>'+
		'<div class="hui_window_content"><div class="hui_window_content"><div class="hui_window_body" style="'+
		(options.width ? 'width:'+options.width+'px;':'')+
		(options.padding ? 'padding:'+options.padding+'px;':'')+
		'">'+
		'</div></div></div>'+
		'<div class="hui_window_bottom"><div class="hui_window_bottom"><div class="hui_window_bottom"></div></div></div></div>';
	options.element = hui.build('div',{'class':'hui_window'+(options.variant ? ' hui_window_'+options.variant : ''),html:html,parent:document.body});
	return new hui.ui.Window(options);
}

hui.ui.Window.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		if (this.close) {
			hui.listen(this.close,'click',function(e) {
				this.hide();
				this.fire('userClosedWindow');
			}.bind(this)
			);
			hui.listen(this.close,'mousedown',function(e) {hui.stop(e)});
		}
		this.titlebar.onmousedown = function(e) {self.startDrag(e);return false;};
		hui.listen(this.element,'mousedown',function() {
			self.element.style.zIndex=hui.ui.nextPanelIndex();
		});
	},
	setTitle : function(title) {
		hui.dom.setText(this.title,title);
	},
	show : function(options) {
		if (this.visible) {
			var scrollTop = hui.getScrollTop();
			var winTop = hui.getTop(this.element);
			if (winTop < scrollTop || winTop+this.element.clientHeight > hui.getViewPortHeight()+scrollTop) {
				hui.animate({node:this.element,css:{top:(scrollTop+40)+'px'},duration:500,ease:hui.ease.slowFastSlow});
			}
			this.element.style.zIndex=hui.ui.nextPanelIndex();
			return;
		}
		options = options || {};
		hui.setStyle(this.element,{
			zIndex : hui.ui.nextPanelIndex(), visibility : 'hidden', display : 'block'
		})
		var width = this.element.clientWidth;
		hui.setStyle(this.element,{
			width : width+'px' , visibility : 'visible'
		});
		if (options.avoid) {
			hui.place({insideViewPort : true, target : {element : options.avoid, vertical : .5, horizontal : 1}, source : {element : this.element, vertical : .5, horizontal : 0} });
		} else {
			if (!this.element.style.top) {
				this.element.style.top = (hui.getScrollTop()+40)+'px';
			}
			if (!this.element.style.left) {
				this.element.style.left = Math.round((hui.getViewPortWidth()-width)/2)+'px';
			}			
		}
		if (hui.browser.opacity) {
			hui.animate(this.element,'opacity',1,0);
		}
		this.visible = true;
		hui.ui.callVisible(this);
	},
	toggle : function() {
		(this.visible ? this.hide() : this.show() );
	},
	hide : function() {
		if (!this.visible) return;
		if (hui.browser.opacity) {
			hui.animate(this.element,'opacity',0,200,{onComplete:function() {
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
		hui.removeClass(this.element,'hui_window_dark');
		hui.removeClass(this.element,'hui_window_light');
		if (variant=='dark' || variant=='light') {
			hui.addClass(this.element,'hui_window_'+variant);
		}
	},
	flip : function() {
		if (this.back) {
			this.back.style.minHeight = this.element.clientHeight+'px';
			hui.effect.flip({element:this.element});
		}
	},

////////////////////////////// Dragging ////////////////////////////////

	/** @private */
	startDrag : function(e) {
		var event = new hui.Event(e);
		this.element.style.zIndex=hui.ui.nextPanelIndex();
		var pos = { top : hui.getTop(this.element), left : hui.getLeft(this.element) };
		this.dragState = {left:event.getLeft()-pos.left,top:event.getTop()-pos.top};
		this.latestPosition = {left: this.dragState.left, top:this.dragState.top};
		this.latestTime = new Date().getMilliseconds();
		var self = this;
		this.moveListener = function(e) {self.drag(e)};
		this.upListener = function(e) {self.endDrag(e)};
		hui.listen(document,'mousemove',this.moveListener);
		hui.listen(document,'mouseup',this.upListener);
		hui.listen(document,'mousedown',this.upListener);
		event.stop();
		document.body.onselectstart = function () { return false; };
		return false;
	},
	/** @private */
	calc : function(top,left) {
		// TODO: No need to do this all the time
		this.a = this.latestPosition.left-left;
		this.b = this.latestPosition.top-top;
		this.dist = Math.sqrt(Math.pow((this.a),2),Math.pow((this.b),2));
		this.latestTime = new Date().getMilliseconds();
		this.latestPosition = {'top':top,'left':left};
	},
	/** @private */
	drag : function(e) {
		var event = new hui.Event(e);
		this.element.style.right = 'auto';
		var top = (event.getTop()-this.dragState.top);
		var left = (event.getLeft()-this.dragState.left);
		this.element.style.top = Math.max(top,0)+'px';
		this.element.style.left = Math.max(left,0)+'px';
		//this.calc(top,left);
		return false;
	},
	/** @private */
	endDrag : function(e) {
		hui.unListen(document,'mousemove',this.moveListener);
		hui.unListen(document,'mouseup',this.upListener);
		hui.unListen(document,'mousedown',this.upListener);
		document.body.onselectstart = null;
	}
}

/* EOF */