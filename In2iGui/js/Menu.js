/**
 * @constructor
 */
In2iGui.Menu = function(options) {
	this.options = n2i.override({autoHide:false,parentElement:null},options);
	this.element = n2i.get(options.element);
	this.name = options.name;
	this.value = null;
	this.subMenus = [];
	this.visible = false;
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Menu.create = function(options) {
	options = options || {};
	options.element = n2i.build('div',{'class':'in2igui_menu'});
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
			n2i.listen(this.element,'mouseout',x);
			if (this.options.parentElement) {
				n2i.listen(this.options.parentElement,'mouseout',x);
			}
		}
	},
	addDivider : function() {
		n2i.build('div',{'class':'in2igui_menu_divider',parent:this.element});
	},
	addItem : function(item) {
		var self = this;
		var element = n2i.build('div',{'class':'in2igui_menu_item',text:item.title});
		n2i.listen(element,'click',function(e) {
			n2i.stop(e);
			self.itemWasClicked(item.value);
		});
		if (item.children) {
			var sub = In2iGui.Menu.create({autoHide:true,parentElement:element});
			sub.addItems(item.children);
			n2i.listen(element,'mouseover',function(e) {
				sub.showAtElement(element,e,'horizontal');
			});
			self.subMenus.push(sub);
			n2i.addClass(element,'in2igui_menu_item_children');
		}
		this.element.appendChild(element);
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
	showAtPointer : function(e) {
		e = n2i.event(e);
		e.stop();
		this.showAtPoint({'top' : e.getTop(),'left' : e.getLeft()});
	},
	showAtElement : function(element,event,position) {
		event = n2i.event(event);
		event.stop();
		element = n2i.get(element);
		var point = n2i.getPosition(element);
		if (position=='horizontal') {
			point.left += element.clientWidth;
		} else if (position=='vertical') {
			point.top += element.clientHeight;
		}
		this.showAtPoint(point);
	},
	showAtPoint : function(pos) {
		var innerWidth = n2i.getViewPortWidth();
		var innerHeight = n2i.getViewPortHeight();
		var scrollTop = n2i.getScrollTop();
		var scrollLeft = n2i.getScrollLeft();
		if (!this.visible) {
			n2i.setStyle(this.element,{'display':'block','visibility':'hidden',opacity:0});
		}
		var width = this.element.clientWidth;
		var height = this.element.clientHeight;
		var left = Math.min(pos.left,innerWidth-width-26+scrollLeft);
		var top = Math.max(0,Math.min(pos.top,innerHeight-height-20+scrollTop));
		n2i.setStyle(this.element,{'top':top+'px','left':left+'px','visibility':'visible',zIndex:In2iGui.nextTopIndex()});
		if (!this.element.style.width) {
			this.element.style.width=(width+6)+'px';
		}
		if (!this.visible) {
			n2i.setStyle(this.element,{opacity:1});
			this.addHider();
			this.visible = true;
		}
	},
	hide : function() {
		if (!this.visible) return;
		var self = this;
		n2i.animate(this.element,'opacity',0,200,{onComplete:function() {
			self.element.style.display='none';
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
		n2i.listen(document.body,'click',this.hider);
	},
	removeHider : function() {
		n2i.unListen(document.body,'click',this.hider);
	}
}



/* EOF */