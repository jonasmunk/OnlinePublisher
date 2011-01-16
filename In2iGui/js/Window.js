/**
 * @constructor
 */
In2iGui.Window = function(options) {
	this.element = n2i.get(options.element);
	this.name = options.name;
	this.close = n2i.firstByClass(this.element,'in2igui_window_close');
	this.titlebar = n2i.firstByClass(this.element,'in2igui_window_titlebar');
	this.title = n2i.firstByClass(this.titlebar,'in2igui_window_title');
	this.content = n2i.firstByClass(this.element,'in2igui_window_body');
	this.front = n2i.firstByClass(this.element,'in2igui_window_front');
	this.back = n2i.firstByClass(this.element,'in2igui_window_back');
	if (this.back) {
		n2i.effect.makeFlippable({container:this.element,front:this.front,back:this.back});
	}
	this.visible = false;
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Window.create = function(options) {
	options = n2i.override({title:'Window',close:true},options);
	var html = '<div class="in2igui_window_front">'+(options.close ? '<div class="in2igui_window_close"></div>' : '')+
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
		'<div class="in2igui_window_bottom"><div class="in2igui_window_bottom"><div class="in2igui_window_bottom"></div></div></div></div>';
	options.element = n2i.build('div',{'class':'in2igui_window'+(options.variant ? ' in2igui_window_'+options.variant : ''),html:html,parent:document.body});
	return new In2iGui.Window(options);
}

In2iGui.Window.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		if (this.close) {
			n2i.listen(this.close,'click',function(e) {
				this.hide();
				this.fire('userClosedWindow');
			}.bind(this)
			);
			n2i.listen(this.close,'mousedown',function(e) {n2i.stop(e)});
		}
		this.titlebar.onmousedown = function(e) {self.startDrag(e);return false;};
		n2i.listen(this.element,'mousedown',function() {
			self.element.style.zIndex=In2iGui.nextPanelIndex();
		});
	},
	setTitle : function(title) {
		this.title.update(title);
	},
	show : function() {
		if (this.visible) return;
		n2i.setStyle(this.element,{
			zIndex : In2iGui.nextPanelIndex(), visibility : 'hidden', display : 'block', top: (n2i.getScrollTop()+40)+'px'
		})
		var width = this.element.clientWidth;
		n2i.setStyle(this.element,{
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
			this.element.style.display='none';
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
			this.back = n2i.build('div',{className:'in2igui_window_back'});
			this.element.insertBefore(this.back,this.front);
			n2i.effect.makeFlippable({container:this.element,front:this.front,back:this.back});
		}
		this.back.appendChild(In2iGui.getElement(widgetOrNode));
	},
	setVariant : function(variant) {
		n2i.removeClass(this.element,'in2igui_window_dark');
		n2i.removeClass(this.element,'in2igui_window_light');
		if (variant=='dark' || variant=='light') {
			n2i.addClass(this.element,'in2igui_window_'+variant);
		}
	},
	flip : function() {
		if (this.back) {
			this.back.style.minHeight = this.element.clientHeight+'px';
			n2i.effect.flip({element:this.element});
		}
	},

////////////////////////////// Dragging ////////////////////////////////

	/** @private */
	startDrag : function(e) {
		var event = new n2i.Event(e);
		this.element.style.zIndex=In2iGui.nextPanelIndex();
		var pos = { top : n2i.getTop(this.element), left : n2i.getLeft(this.element) };
		this.dragState = {left:event.left()-pos.left,top:event.top()-pos.top};
		this.latestPosition = {left: this.dragState.left, top:this.dragState.top};
		this.latestTime = new Date().getMilliseconds();
		var self = this;
		this.moveListener = function(e) {self.drag(e)};
		this.upListener = function(e) {self.endDrag(e)};
		n2i.listen(document,'mousemove',this.moveListener);
		n2i.listen(document,'mouseup',this.upListener);
		n2i.listen(document,'mousedown',this.upListener);
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
		var event = new n2i.Event(e);
		this.element.style.right = 'auto';
		var top = (event.top()-this.dragState.top);
		var left = (event.left()-this.dragState.left);
		this.element.style.top = Math.max(top,0)+'px';
		this.element.style.left = Math.max(left,0)+'px';
		//this.calc(top,left);
		return false;
	},
	/** @private */
	endDrag : function(e) {
		n2i.unListen(document,'mousemove',this.moveListener);
		n2i.unListen(document,'mouseup',this.upListener);
		n2i.unListen(document,'mousedown',this.upListener);
		document.body.onselectstart = null;
	}
}

/* EOF */