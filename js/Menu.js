/**
 * @constructor
 */
hui.ui.Menu = function(options) {
	this.options = hui.override({autoHide:false,parentElement:null},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	this.value = null;
	this.subMenus = [];
	this.visible = false;
	hui.ui.extend(this);
	this._addBehavior();
}

hui.ui.Menu.create = function(options) {
	options = options || {};
	options.element = hui.build('div',{'class':'hui_menu'});
	var obj = new hui.ui.Menu(options);
	document.body.appendChild(options.element);
	return obj;
}

hui.ui.Menu.prototype = {
	_addBehavior : function() {
		this.hider = function() {
			this.hide()
			this.fire('cancel');
		}.bind(this);
		if (this.options.autoHide) {
			var x = function(e) {
				if (!hui.ui.isWithin(e,this.element) && (!this.options.parentElement || !hui.ui.isWithin(e,this.options.parentElement))) {
					if (!this._isSubMenuVisible()) {
						this.hide();
					}
				}
			}.bind(this);
			hui.listen(this.element,'mouseout',x);
			if (this.options.parentElement) {
				hui.listen(this.options.parentElement,'mouseout',x);
			}
		}
	},
	addDivider : function() {
		hui.build('div',{'class':'hui_menu_divider',parent:this.element});
	},
	addItem : function(item) {
		var self = this;
		var element = hui.build('div',{'class':'hui_menu_item',text:item.title || item.text});
		hui.listen(element,'click',function(e) {
			hui.stop(e);
			self._onItemClick(item.value);
		});
		if (item.children && item.children.length>0) {
			var sub = hui.ui.Menu.create({autoHide:true,parentElement:element});
			sub.addItems(item.children);
			hui.listen(element,'mouseover',function(e) {
				sub.showAtElement(element,e,'horizontal');
			});
			self.subMenus.push(sub);
			hui.cls.add(element,'hui_menu_item_children');
			sub.listen({
				$select : function(value) {
					self.hide();
					self.fire('select',value);
				}
			})
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
	_onItemClick : function(value) {
		this.value = value;
		this.fire('itemWasClicked',value);
		this.fire('select',value);
		this.hide();
	},
	showAtPointer : function(e) {
		e = hui.event(e);
		e.stop();
		this.showAtPoint({'top' : e.getTop(),'left' : e.getLeft()});
	},
	showAtElement : function(element,event,position) {
		event = hui.event(event);
		event.stop();
		element = hui.get(element);
		var point = hui.position.get(element);
		if (position=='horizontal') {
			point.left += element.clientWidth;
		} else if (position=='vertical') {
			point.top += element.clientHeight;
		}
		this.showAtPoint(point);
	},
	showAtPoint : function(pos) {
		var innerWidth = hui.window.getViewWidth();
		var innerHeight = hui.window.getViewHeight();
		var scrollTop = hui.window.getScrollTop();
		var scrollLeft = hui.window.getScrollLeft();
		if (!this.visible) {
			hui.style.set(this.element,{'display':'block','visibility':'hidden',opacity:0});
		}
		var width = this.element.clientWidth;
		var height = this.element.clientHeight;
		var left = Math.min(pos.left,innerWidth-width-26+scrollLeft);
		var top = Math.max(0,Math.min(pos.top,innerHeight-height-20+scrollTop));
		hui.style.set(this.element,{'top':top+'px','left':left+'px','visibility':'visible',zIndex:hui.ui.nextTopIndex()});
		if (!this.element.style.width) {
			this.element.style.width=(width+6)+'px';
		}
		if (!this.visible) {
			hui.style.set(this.element,{opacity:1});
			this._addHider();
			this.visible = true;
		}
	},
	hide : function(options) {
		if (!this.visible) {return};
		if (true || options && options.immediate) {
			this.element.style.display='none';
		} else {
			hui.animate(this.element, 'opacity', 0, 200, {
				onComplete : function() {
					this.element.style.display='none';
				}.bind(this)
			});			
		}
		this._removeHider();
		for (var i=0; i < this.subMenus.length; i++) {
			this.subMenus[i].hide();
		};
		this.visible = false;
		this.fire('hide');
	},
	isVisible : function() {
		return this.visible;
	},
	_isSubMenuVisible : function() {
		for (var i=0; i < this.subMenus.length; i++) {
			if (this.subMenus[i].visible) return true;
		};
		return false;
	},
	_addHider : function() {
		hui.listen(document.body,'click',this.hider);
	},
	_removeHider : function() {
		hui.unListen(document.body,'click',this.hider);
	}
}



/* EOF */