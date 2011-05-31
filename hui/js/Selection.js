/**
 * @constructor
 * @param {Object} options The options : {value:null}
 */
hui.ui.Selection = function(options) {
	this.options = hui.override({value:null},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	this.items = [];
	this.subItems = [];
	this.busy = 0;
	this.selection=null;
	if (this.options.value!=null) {
		this.selection = {value:this.options.value};
	}
	hui.ui.extend(this);
}

/**
 * Creates a new selection widget
 * @param {Object} options The options : {width:0}
 */
hui.ui.Selection.create = function(options) {
	options = hui.override({width:0},options);
	var e = options.element = hui.build('div',{'class':'hui_selection'});
	if (options.width>0) {
		e.style.width = options.width+'px';
	}
	return new hui.ui.Selection(options);
}

hui.ui.Selection.prototype = {
	/** Get the selected item
	 * @returns {Object} The selected item, null if no selection */
	getValue : function() {
		return this.selection;
	},
	valueForProperty : function(p) {
		if (p==='value') {
			return this.selection ? this.selection.value : null;
		} else if (p==='kind') {
			return this.selection ? this.selection.kind : null;
		}
		return undefined;
	},
	/** Set the selected item
	 * @param {Object} value The selected item */
	setValue : function(value) {
		var item = this.getSelectionWithValue(value);
		if (item===null) {
			this.selection = null;
		} else {
			this.selection = item;
			this.kind=item.kind;
		}
		this.updateUI();
		this.fireChange();
	},
	/** @private */
	getSelectionWithValue : function(value) {
		var i;
		for (i=0; i < this.items.length; i++) {
			if (this.items[i].value==value) {
				return this.items[i];
			}
		};
		for (i=0; i < this.subItems.length; i++) {
			var items = this.subItems[i].items;
			for (var j=0; j < items.length; j++) {
				if (items[j].value==value) {
					return items[j];
				}
			};
		};
		return null;
	},
	/** Changes selection to the first item */
	selectFirst : function() {
		var i;
		for (i=0; i < this.items.length; i++) {
			this.changeSelection(this.items[i]);
			return;
		};
		for (i=0; i < this.subItems.length; i++) {
			var items = this.subItems[i].items;
			for (var j=0; j < items.length; j++) {
				this.changeSelection(this.items[j]);
				return;
			};
		};
	},
	/** Set the value to null */
	reset : function() {
		this.setValue(null);
	},
	/** @private */
	updateUI : function() {
		var i;
		for (i=0; i < this.items.length; i++) {
			hui.setClass(this.items[i].element,'hui_selected',this.isSelection(this.items[i]));
		};
		for (i=0; i < this.subItems.length; i++) {
			this.subItems[i].updateUI();
		};
	},
	/** @private */
	changeSelection : function(item) {
		for (var i=0; i < this.subItems.length; i++) {
			this.subItems[i].selectionChanged(this.selection,item);
		};
		this.selection = item;
		this.updateUI();
		this.fireChange();
	},
	/** @private */
	fireChange : function() {
		this.fire('selectionChanged',this.selection);
		this.fireProperty('value',this.selection ? this.selection.value : null);
		this.fireProperty('kind',this.selection ? this.selection.kind : null);
		for (var i=0; i < this.subItems.length; i++) {
			this.subItems[i].parentValueChanged();
		};
	},
	/** @private */
	registerItems : function(items) {
		items.parent = this;
		this.subItems.push(items);
	},
	/** @private */
	registerItem : function(id,title,icon,badge,value,kind) {
		var element = hui.get(id);
		var item = {id:id,title:title,icon:icon,badge:badge,element:element,value:value,kind:kind};
		this.items.push(item);
		this.addItemBehavior(element,item);
		this.selection = this.getSelectionWithValue(this.options.value);
	},
	/** @private */
	addItemBehavior : function(node,item) {
		hui.listen(node,'click',function() {
			this.itemWasClicked(item);
		}.bind(this));
		hui.listen(node,'dblclick',function(e) {
			hui.stop(e);
			this.itemWasDoubleClicked(item);
		}.bind(this));
		node.dragDropInfo = item;
	},
	/** Untested!! */
	setObjects : function(items) {
		this.items = [];
		hui.each(items,function(item) {
			this.items.push(item);
			var node = hui.build('div',{'class':'hui_selection_item'});
			item.element = node;
			this.element.appendChild(node);
			var inner = hui.build('span',{'class':'hui_selection_label',text:item.title});
			if (item.icon) {
				node.appendChild(hui.ui.createIcon(item.icon,1));
			}
			node.appendChild(inner);
			hui.listen(node,'click',function() {
				this.itemWasClicked(item);
			}.bind(this));
			hui.listen(node,'dblclick',function(e) {
				hui.stop(e);
				this.itemWasDoubleClicked(item);
			}.bind(this));
		}.bind(this));
	},
	/** @private */
	isSelection : function(item) {
		if (this.selection===null) {
			return false;
		}
		var selected = item.value==this.selection.value;
		if (this.selection.kind) {
			selected = selected && item.kind==this.selection.kind;
		}
		return selected;
	},
	
	/** @private */
	itemWasClicked : function(item) {
		if (this.busy>0) {return}
		this.changeSelection(item);
	},
	/** @private */
	itemWasDoubleClicked : function(item) {
		if (this.busy>0) {return}
		this.fire('selectionWasOpened',item);
	},
	_setBusy : function(busy) {
		this.busy+= busy ? 1 : -1;
		window.clearTimeout(this.busytimer);
		if (this.busy>0) {
			var e = this.element;
			this.busytimer = window.setTimeout(function() {
				hui.addClass(e,'hui_selection_busy');
			},300);
		} else {
			hui.removeClass(this.element,'hui_selection_busy');
		}
	},
	_checkValue : function() {
		if (!this.selection) {return}
		var item = this.getSelectionWithValue(this.selection.value);
		if (!item) {
			this.selectFirst();
		}
	}
}

/////////////////////////// Items ///////////////////////////

/**
 * @constructor
 * A group of items loaded from a source
 * @param {Object} options The options : {element,name,source}
 */
hui.ui.Selection.Items = function(options) {
	this.options = hui.override({source:null},options);
	this.element = hui.get(options.element);
	this.title = hui.get(this.element.id+'_title');
	this.name = options.name;
	this.disclosed = {};
	this.parent = null;
	this.items = [];
	hui.ui.extend(this);
	if (this.options.source) {
		this.options.source.listen(this);
	}
}

hui.ui.Selection.Items.prototype = {
	/**
	 * Refresh the underlying source
	 */
	refresh : function() {
		if (this.options.source) {
			this.options.source.refresh();
		}
	},
	/** @private */
	$objectsLoaded : function(objects) {
		this.$itemsLoaded(objects);
	},
	/** @private */
	$itemsLoaded : function(items) {
		this.items = [];
		this.element.innerHTML='';
		this.buildLevel(this.element,items,0,true);
		if (this.title) {
			this.title.style.display=this.items.length>0 ? 'block' : 'none';
		}
		this.parent.updateUI();
		this.parent._checkValue();
	},
	$sourceIsBusy : function() {
		this.parent._setBusy(true);
	},
	/** @private */
	$sourceIsNotBusy : function() {
		this.parent._setBusy(false);
	},
	/** @private */
	buildLevel : function(parent,items,inc,open) {
		if (!items) return;
		var hierarchical = this.isHierarchy(items);
		var level = hui.build('div',{'class':'hui_selection_level',style:(open ? 'display:block' : 'display:none'),parent:parent});
		hui.each(items,function(item) {
			if (item.type=='title') {
				hui.build('div',{'class':'hui_selection_title',html:'<span>'+item.title+'</span>',parent:level});
				return;
			}
			var hasChildren = item.children && item.children.length>0;
			var left = inc*16+6;
			if (!hierarchical && inc>0 || hierarchical && !hasChildren) {
				left+=13;
			}
			var node = hui.build('div',{'class':'hui_selection_item'});
			node.style.paddingLeft = left+'px';
			if (item.badge) {
				node.appendChild(hui.build('strong',{'class':'hui_selection_badge',text:item.badge}));
			}
			var subOpen = false;
			if (hierarchical && hasChildren) {
				var self = this;
				subOpen = this.disclosed[item.value]
				var cls = this.disclosed[item.value] ? 'hui_disclosure hui_disclosure_open' : 'hui_disclosure';
				var disc = hui.build('span',{'class':cls,parent:node});
				hui.listen(disc,'click',function(e) {
					hui.stop(e);
					self.toggle(disc,item);
				});
			}
			var inner = hui.build('span',{'class':'hui_selection_label',text:item.title});
			if (item.icon) {
				node.appendChild(hui.build('span',{'class':'hui_icon_1',style:'background-image: url('+hui.ui.getIconUrl(item.icon,1)+')'}));
			}
			node.appendChild(inner);
			hui.listen(node,'click',function(e) {
				this.parent.itemWasClicked(item);
			}.bind(this));
			hui.listen(node,'dblclick',function(e) {
				hui.stop(e);
				this.parent.itemWasDoubleClicked(item);
			}.bind(this));
			level.appendChild(node);
			var info = {title:item.title,icon:item.icon,badge:item.badge,kind:item.kind,element:node,value:item.value};
			node.dragDropInfo = info;
			this.items.push(info);
			this.buildLevel(level,item.children,inc+1,subOpen);
		}.bind(this));
	},
	/** @private */
	toggle : function(node,item) {
		if (hui.hasClass(node,'hui_disclosure_open')) {
			this.disclosed[item.value] = false;
			hui.getNext(node.parentNode).style.display='none';
			hui.removeClass(node,'hui_disclosure_open');
		} else {
			this.disclosed[item.value] = true;
			hui.getNext(node.parentNode).style.display='block';
			hui.addClass(node,'hui_disclosure_open');
		}
	},
	/** @private */
	isHierarchy : function(items) {
		if (!items) {return false};
		for (var i=0; i < items.length; i++) {
			if (items[i]!==null && items[i].children && items[i].children.length>0) {
				return true;
			}
		};
		return false;
	},
	/** Get the selection of this items group
	 * @returns {Object} The selected item or null */
	getValue : function() {
		if (this.parent.selection==null) {
			return null;
		}
		for (var i=0; i < this.items.length; i++) {
			if (this.items[i].value == this.parent.selection.value) {
				return this.items[i];
			}
		};
		return null;
	},
	/** @private */
	updateUI : function() {
		for (var i=0; i < this.items.length; i++) {
			hui.setClass(this.items[i].element,'hui_selected',this.parent.isSelection(this.items[i]));
		};
	},
	/** @private */
	selectionChanged : function(oldSelection,newSelection) {
		for (var i=0; i < this.items.length; i++) {
			var value = this.items[i].value;
			if (value == newSelection.value) {
				this.fireProperty('value',newSelection.value);
				return;
			}
		};
		this.fireProperty('value',null);
	},
	/**
	 * Called when the parent changes value, must fire its new value
	 * @private
	 */
	parentValueChanged : function() {
		for (var i=0; i < this.items.length; i++) {
			if (this.parent.isSelection(this.items[i])) {
				this.fireProperty('value',this.items[i].value);
				return;
			}
		};
		this.fireProperty('value',null);
	}
}
/* EOF */