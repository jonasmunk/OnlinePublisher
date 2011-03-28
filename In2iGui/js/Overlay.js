/**
 * @constructor
 */
In2iGui.Overlay = function(options) {
	this.options = options;
	this.element = n2i.get(options.element);
	this.content = n2i.byClass(this.element,'in2igui_inner_overlay')[1];
	this.name = options.name;
	this.icons = {};
	this.visible = false;
	In2iGui.extend(this);
	this.addBehavior();
}

/**
 * Creates a new overlay
 */
In2iGui.Overlay.create = function(options) {
	options = options || {};
	var e = options.element = n2i.build('div',{className:'in2igui_overlay',style:'display:none',html:'<div class="in2igui_inner_overlay"><div class="in2igui_inner_overlay"></div></div>'});
	document.body.appendChild(e);
	return new In2iGui.Overlay(options);
}

In2iGui.Overlay.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		this.hider = function(e) {
			if (self.boundElement) {
				if (In2iGui.isWithin(e,self.boundElement) || In2iGui.isWithin(e,self.element)) return;
				// TODO: should be unreg'ed but it fails
				//self.boundElement.stopObserving(self.hider);
				n2i.removeClass(self.boundElement,'in2igui_overlay_bound');
				self.boundElement = null;
				self.hide();
			}
		}
		n2i.listen(this.element,'mouseout',this.hider);
	},
	addIcon : function(key,icon) {
		var self = this;
		var element = n2i.build('div',{className:'in2igui_overlay_icon'});
		element.style.backgroundImage='url('+In2iGui.getIconUrl(icon,2)+')';
		n2i.listen(element,'click',function(e) {
			self.iconWasClicked(key,e);
		});
		this.icons[key]=element;
		this.content.appendChild(element);
	},
	addText : function(text) {
		this.content.appendChild(n2i.build('span',{'class':'in2igui_overlay_text',text:text}));
	},
	add : function(widget) {
		this.content.appendChild(widget.getElement());
	},
	hideIcons : function(keys) {
		for (var i=0; i < keys.length; i++) {
			this.icons[keys[i]].style.display='none';
		};
	},
	showIcons : function(keys) {
		for (var i=0; i < keys.length; i++) {
			this.icons[keys[i]].style.display='';
		};
	},
	iconWasClicked : function(key,e) {
		In2iGui.callDelegates(this,'iconWasClicked',key,e);
	},
	showAtElement : function(element,options) {
		options = options || {};
		In2iGui.positionAtElement(this.element,element,options);
		if (this.visible) return;
		if (n2i.browser.msie) {
			this.element.style.display='block';
		} else {
			n2i.setStyle(this.element,{'display':'block','opacity':0});
			n2i.ani(this.element,'opacity',1,150);
		}
		this.visible = true;
		if (options.autoHide) {
			this.boundElement = element;
			n2i.listen(element,'mouseout',this.hider);
			n2i.addClass(element,'in2igui_overlay_bound');
		}
		if (this.options.modal) {
			var zIndex = In2iGui.nextAlertIndex();
			this.element.style.zIndex=zIndex+1;
			In2iGui.showCurtain({widget:this,zIndex:zIndex});
		}
		return;
	},
	show : function(options) {
		options = options || {};
		if (!this.visible) {
			n2i.setStyle(this.element,{'display':'block',visibility:'hidden'});
		}
		if (options.element) {
			n2i.place({
				source:{element:this.element,vertical:0,horizontal:.5},
				target:{element:options.element,vertical:.5,horizontal:.5},
				insideViewPort:true
			});
		}
		if (this.visible) return;
		In2iGui.bounceIn(this.element);
		this.visible = true;
		if (options.autoHide && options.element) {
			this.boundElement = options.element;
			n2i.listen(options.element,'mouseout',this.hider);
			n2i.addClass(options.element,'in2igui_overlay_bound');
		}
		if (this.options.modal) {
			var zIndex = In2iGui.nextAlertIndex();
			this.element.style.zIndex=zIndex+1;
			var color = n2i.getStyle(document.body,'background-color');
			In2iGui.showCurtain({widget:this,zIndex:zIndex,color:color});
		}
	},
	/** private */
	$curtainWasClicked : function() {
		this.hide();
	},
	hide : function() {
		In2iGui.hideCurtain(this);
		this.element.style.display='none';
		this.visible = false;
	},
	clear : function() {
		In2iGui.destroyDescendants(this.content);
		this.content.innerHTML='';
	}
}

/* EOF */
