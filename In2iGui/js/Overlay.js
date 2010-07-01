/**
 * @constructor
 */
In2iGui.Overlay = function(options) {
	this.options = options;
	this.element = $(options.element);
	this.content = this.element.select('div.in2igui_inner_overlay')[1];
	this.name = options.name;
	this.icons = new Hash();
	this.visible = false;
	In2iGui.extend(this);
	this.addBehavior();
}

/**
 * Creates a new overlay
 */
In2iGui.Overlay.create = function(options) {
	options = options || {};
	var e = options.element = new Element('div').addClassName('in2igui_overlay').setStyle({'display':'none'});
	e.update('<div class="in2igui_inner_overlay"><div class="in2igui_inner_overlay"></div></div>');
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
				self.boundElement.removeClassName('in2igui_overlay_bound');
				self.boundElement = null;
				self.hide();
			}
		}
		this.element.observe('mouseout',this.hider);
	},
	addIcon : function(key,icon) {
		var self = this;
		var element = new Element('div').addClassName('in2igui_overlay_icon');
		element.setStyle({'backgroundImage':'url('+In2iGui.getIconUrl(icon,2)+')'});
		element.observe('click',function(e) {
			self.iconWasClicked(key,e);
		});
		this.icons.set(key,element);
		this.content.insert(element);
	},
	addText : function(text) {
		this.content.insert(new Element('span',{'class':'in2igui_overlay_text'}).update(text));
	},
	add : function(widget) {
		this.content.insert(widget.getElement());
	},
	hideIcons : function(keys) {
		var self = this;
		keys.each(function(key) {
			self.icons.get(key).hide();
		});
	},
	showIcons : function(keys) {
		var self = this;
		keys.each(function(key) {
			self.icons.get(key).show();
		});
	},
	iconWasClicked : function(key,e) {
		In2iGui.callDelegates(this,'iconWasClicked',key,e);
	},
	showAtElement : function(element,options) {
		options = options || {};
		In2iGui.positionAtElement(this.element,element,options);
		if (this.visible) return;
		if (n2i.browser.msie) {
			this.element.setStyle({'display':'block'});
		} else {
			this.element.setStyle({'display':'block','opacity':0});
			n2i.ani(this.element,'opacity',1,150);
		}
		this.visible = true;
		if (options.autoHide) {
			this.boundElement = element;
			element.observe('mouseout',this.hider);
			element.addClassName('in2igui_overlay_bound');
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
			this.element.setStyle({'display':'block',visibility:'hidden'});
		}
		if (options.element) {
			n2i.place({
				source:{element:this.element,vertical:0,horizontal:.5},
				target:{element:options.element,vertical:.5,horizontal:.5}
			});
		}
		if (this.visible) return;
		In2iGui.bounceIn(this.element);
		this.visible = true;
		if (options.autoHide && options.element) {
			this.boundElement = options.element;
			options.element.observe('mouseout',this.hider);
			options.element.addClassName('in2igui_overlay_bound');
		}
		if (this.options.modal) {
			var zIndex = In2iGui.nextAlertIndex();
			this.element.style.zIndex=zIndex+1;
			var color = $(document.body).getStyle('background-color');
			In2iGui.showCurtain({widget:this,zIndex:zIndex,color:color});
		}
	},
	/** private */
	$curtainWasClicked : function() {
		this.hide();
	},
	hide : function() {
		In2iGui.hideCurtain(this);
		this.element.setStyle({'display':'none'});
		this.visible = false;
	},
	clear : function() {
		In2iGui.destroyDescendants(this.content);
		this.content.update();
	}
}

/* EOF */
