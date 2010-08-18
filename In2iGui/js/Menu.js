/**
 * @constructor
 */
In2iGui.Menu = function(options) {
	this.options = n2i.override({autoHide:false,parentElement:null},options);
	this.element = $(options.element);
	this.name = options.name;
	this.value = null;
	this.subMenus = [];
	this.visible = false;
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Menu.create = function(options) {
	options = options || {};
	options.element = new Element('div').addClassName('in2igui_menu');
	var obj = new In2iGui.Menu(options);
	document.body.appendChild(options.element);
	return obj;
}

In2iGui.Menu.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		this.hider = function() {
			self.hide();
		}
		if (this.options.autoHide) {
			var x = function(e) {
				if (!In2iGui.isWithin(e,self.element) && (!self.options.parentElement || !In2iGui.isWithin(e,self.options.parentElement))) {
					if (!self.isSubMenuVisible()) {
						self.hide();
					}
				}
			};
			this.element.observe('mouseout',x);
			if (this.options.parentElement) {
				this.options.parentElement.observe('mouseout',x);
			}
		}
	},
	addDivider : function() {
		this.element.insert(new Element('div').addClassName('in2igui_menu_divider'));
	},
	addItem : function(item) {
		var self = this;
		var element = new Element('div').addClassName('in2igui_menu_item').update(item.title);
		element.observe('click',function(e) {
			self.itemWasClicked(item.value);
			Event.stop(e);
		});
		if (item.children) {
			var sub = In2iGui.Menu.create({autoHide:true,parentElement:element});
			sub.addItems(item.children);
			element.observe('mouseover',function(e) {
				sub.showAtElement(element,e,'horizontal');
			});
			//sub.listen({itemWasClicked:function(value) {self.itemWasClicked(value)}});
			self.subMenus.push(sub);
			element.addClassName('in2igui_menu_item_children');
			//element.observe('mouseleave',function() {
			//	sub.hide();
			//});
		}
		this.element.insert(element);
	},
	addItems : function(items) {
		for (var i=0; i < items.length; i++) {
			if (items[i]==null) {
				this.addDivider();
			} else {
				this.addItem(items[i]);
			}
		};
	},
	getValue : function() {
		return this.value;
	},
	itemWasClicked : function(value) {
		this.value = value;
		this.fire('itemWasClicked',value);
		this.fire('select',value);
		this.hide();
	},
	showAtPointer : function(event) {
		if (event) {
			Event.stop(event);
			//if (event.type!='click') this.ignoreNextClick=true;
		}
		this.showAtPoint({'top':event.pointerY(),'left':event.pointerX()});
	},
	showAtElement : function(element,event,position) {
		if (event) {
			Event.stop(event);
		}
		element = $(element);
		var point = element.cumulativeOffset();
		if (position=='horizontal') {
			point.left += element.getWidth();
		} else if (position=='vertical') {
			point.top += element.getHeight();
		}
		this.showAtPoint(point);
	},
	showAtPoint : function(pos) {
		var innerWidth = n2i.getInnerWidth();
		var innerHeight = n2i.getInnerHeight();
		var scrollTop = n2i.getScrollTop();
		var scrollLeft = n2i.getScrollLeft();
		if (!this.visible) {
			this.element.setStyle({'display':'block','visibility':'hidden',opacity:0});
		}
		var width = this.element.getWidth();
		var height = this.element.getHeight();
		var left = Math.min(pos.left,innerWidth-width-20+scrollLeft);
		var top = Math.max(0,Math.min(pos.top,innerHeight-height-20+scrollTop));
		this.element.setStyle({'top':top+'px','left':left+'px','visibility':'visible',zIndex:In2iGui.nextTopIndex(),'width':(width-2)+'px'});
		if (!this.visible) {
			//n2i.ani(this.element,'opacity',1,200);
			this.element.setStyle({opacity:1});
			this.addHider();
			this.visible = true;
		}
	},
	hide : function() {
		if (!this.visible) return;
		var self = this;
		n2i.ani(this.element,'opacity',0,200,{onComplete:function() {
			self.element.setStyle({'display':'none'});
		}});
		this.removeHider();
		for (var i=0; i < this.subMenus.length; i++) {
			this.subMenus[i].hide();
		};
		this.visible = false;
	},
	isSubMenuVisible : function() {
		for (var i=0; i < this.subMenus.length; i++) {
			if (this.subMenus[i].visible) return true;
		};
		return false;
	},
	addHider : function() {
		Element.observe(document.body,'click',this.hider);
	},
	removeHider : function() {
		Event.stopObserving(document.body,'click',this.hider);
	}
}



/* EOF */