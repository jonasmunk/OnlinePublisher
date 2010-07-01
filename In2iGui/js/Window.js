/**
 * @constructor
 */
In2iGui.Window = function(options) {
	this.element = $(options.element);
	this.name = options.name;
	this.close = this.element.select('.in2igui_window_close')[0];
	this.titlebar = this.element.select('.in2igui_window_titlebar')[0];
	this.title = this.titlebar.select('.in2igui_window_title')[0];
	this.content = this.element.select('.in2igui_window_body')[0];
	this.visible = false;
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Window.create = function(options) {
	options = n2i.override({title:'Window',close:true},options);
	var element = options.element = new Element('div',{'class':'in2igui_window'+(options.variant ? ' in2igui_window_'+options.variant : '')});
	var html = (options.close ? '<div class="in2igui_window_close"></div>' : '')+
		'<div class="in2igui_window_titlebar"><div><div>';
		if (options.icon) {
			html+='<span class="in2igui_window_icon" style="background-image: url('+In2iGui.getIconUrl(options.icon,1)+')"></span>';
		}
	html+='<span class="in2igui_window_title">'+options.title+'</span></div></div></div>'+
		'<div class="in2igui_window_content"><div class="in2igui_window_content"><div class="in2igui_window_body" style="'+
		(options.width ? 'width:'+options.width+'px;':'')+
		(options.padding ? 'padding:'+options.padding+'px;':'')+
		'">'+
		'</div></div></div>'+
		'<div class="in2igui_window_bottom"><div class="in2igui_window_bottom"><div class="in2igui_window_bottom"></div></div></div>';
	element.update(html);
	document.body.appendChild(element);
	return new In2iGui.Window(options);
}

In2iGui.Window.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		if (this.close) {
			this.close.observe('click',function(e) {
				this.hide();
				this.fire('userClosedWindow');
			}.bind(this)
			).observe('mousedown',function(e) {e.stop();});
		}
		this.titlebar.onmousedown = function(e) {self.startDrag(e);return false;};
		this.element.observe('mousedown',function() {
			self.element.style.zIndex=In2iGui.nextPanelIndex();
		})
	},
	setTitle : function(title) {
		this.title.update(title);
	},
	show : function() {
		if (this.visible) return;
		this.element.setStyle({
			zIndex : In2iGui.nextPanelIndex(), visibility : 'hidden', display : 'block', top: (n2i.getScrollTop()+40)+'px'
		})
		var width = this.element.clientWidth;
		this.element.setStyle({
			width : width+'px' , visibility : 'visible'
		});
		if (!n2i.browser.msie) {
			n2i.ani(this.element,'opacity',1,0);
		}
		this.visible = true;
		In2iGui.callVisible(this);
	},
	toggle : function() {
		(this.visible ? this.hide() : this.show() );
	},
	hide : function() {
		if (!this.visible) return;
		if (!n2i.browser.msie) {
			n2i.ani(this.element,'opacity',0,200,{hideOnComplete:true});
		} else {
			this.element.setStyle({display:'none'});
		}
		this.visible = false;
	},
	add : function(widgetOrNode) {
		if (widgetOrNode.getElement) {
			this.content.insert(widgetOrNode.getElement());
		} else {
			this.content.insert(widgetOrNode);
		}
	},
	setVariant : function(variant) {
		this.element.removeClassName('in2igui_window_dark');
		this.element.removeClassName('in2igui_window_light');
		if (variant=='dark' || variant=='light') {
			this.element.addClassName('in2igui_window_'+variant);
		}
	},

////////////////////////////// Dragging ////////////////////////////////

	/** @private */
	startDrag : function(e) {
		var event = Event.extend(e || window.event);
		this.element.style.zIndex=In2iGui.nextPanelIndex();
		var pos = this.element.cumulativeOffset();
		this.dragState = {left:event.pointerX()-pos.left,top:event.pointerY()-pos.top};
		this.latestPosition = {left: this.dragState.left, top:this.dragState.top};
		this.latestTime = new Date().getMilliseconds();
		var self = this;
		this.moveListener = function(e) {self.drag(e)};
		this.upListener = function(e) {self.endDrag(e)};
		Event.observe(document,'mousemove',this.moveListener);
		Event.observe(document,'mouseup',this.upListener);
		Event.observe(document,'mousedown',this.upListener);
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
		var event = Event.extend(e);
		this.element.style.right = 'auto';
		var top = (event.pointerY()-this.dragState.top);
		var left = (event.pointerX()-this.dragState.left);
		this.element.style.top = Math.max(top,0)+'px';
		this.element.style.left = Math.max(left,0)+'px';
		//this.calc(top,left);
		return false;
	},
	/** @private */
	endDrag : function(e) {
		Event.stopObserving(document,'mousemove',this.moveListener);
		Event.stopObserving(document,'mouseup',this.upListener);
		Event.stopObserving(document,'mousedown',this.upListener);
		document.body.onselectstart = null;
	}
}

/* EOF */