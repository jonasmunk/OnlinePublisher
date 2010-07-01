/**
 * @constructor
 * @param {Object} options The options : {value:null}
 */
In2iGui.Selection = function(options) {
	this.options = n2i.override({value:null},options);
	this.element = $(options.element);
	this.name = options.name;
	this.items = [];
	this.subItems = [];
	this.selection=null;
	if (this.options.value!=null) {
		this.selection = {value:this.options.value};
	}
	In2iGui.extend(this);
}

/**
 * Creates a new selection widget
 * @param {Object} options The options : {width:0}
 */
In2iGui.Selection.create = function(options) {
	options = n2i.override({width:0},options);
	var e = options.element = new Element('div',{'class':'in2igui_selection'});
	if (options.width>0) e.setStyle({width:options.width+'px'});
	return new In2iGui.Selection(options);
}

In2iGui.Selection.prototype = {
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
		for (var i=0; i < this.items.length; i++) {
			if (this.items[i].value==value) {
				return this.items[i];
			}
		};
		for (var i=0; i < this.subItems.length; i++) {
			var items = this.subItems[i].items;
			for (var j=0; j < items.length; j++) {
				if (items[j].value==value) {
					return items[j];
				}
			};
		};
		return null;
	},
	/** Set the value to null */
	reset : function() {
		this.setValue(null);
	},
	/** @private */
	updateUI : function() {
		this.items.each(function(item) {
			item.element.setClassName('in2igui_selected',this.isSelection(item));
		}.bind(this));
		this.subItems.each(function(sub) {
			sub.updateUI();
		});
	},
	/** @private */
	changeSelection : function(item) {
		this.subItems.each(function(sub) {
			sub.selectionChanged(this.selection,item);
		});
		this.selection = item;
		this.updateUI();
		this.fireChange();
	},
	/** @private */
	fireChange : function() {
		this.fire('selectionChanged',this.selection);
		this.fireProperty('value',this.selection ? this.selection.value : null);
		this.fireProperty('kind',this.selection ? this.selection.kind : null);
	},
	/** @private */
	registerItems : function(items) {
		items.parent = this;
		this.subItems.push(items);
	},
	/** @private */
	registerItem : function(id,title,icon,badge,value,kind) {
		var element = $(id);
		var item = {id:id,title:title,icon:icon,badge:badge,element:element,value:value,kind:kind};
		this.items.push(item);
		this.addItemBehavior(element,item);
		this.selection = this.getSelectionWithValue(this.options.value);
	},
	/** @private */
	addItemBehavior : function(node,item) {
		node.observe('click',function() {
			this.itemWasClicked(item);
		}.bind(this));
		node.observe('dblclick',function() {
			this.itemWasDoubleClicked(item);
		}.bind(this));
		node.dragDropInfo = item;
	},
	/** Untested!! */
	setObjects : function(items) {
		this.items = [];
		items.each(function(item) {
			this.items.push(item);
			var node = new Element('div',{'class':'in2igui_selection_item'});
			item.element = node;
			this.element.insert(node);
			var inner = new Element('span',{'class':'in2igui_selection_label'}).update(item.title);
			if (item.icon) {
				node.insert(In2iGui.createIcon(item.icon,1));
			}
			node.insert(inner);
			node.observe('click',function() {
				this.itemWasClicked(item);
			}.bind(this));
			node.observe('dblclick',function(e) {
				this.itemWasDoubleClicked(item);
				e.stop();
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
		this.changeSelection(item);
	},
	/** @private */
	itemWasDoubleClicked : function(item) {
		this.fire('selectionWasOpened',item);
	}
}

/////////////////////////// Items ///////////////////////////

/**
 * @constructor
 * A group of items loaded from a source
 * @param {Object} options The options : {element,name,source}
 */
In2iGui.Selection.Items = function(options) {
	this.options = n2i.override({source:null},options);
	this.element = $(options.element);
	this.name = options.name;
	this.parent = null;
	this.items = [];
	In2iGui.extend(this);
	if (this.options.source) {
		this.options.source.listen(this);
	}
}

In2iGui.Selection.Items.prototype = {
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
		this.element.update();
		this.buildLevel(this.element,items,0);
		this.parent.updateUI();
	},
	/** @private */
	buildLevel : function(parent,items,inc) {
		if (!items) return;
		var hierarchical = this.isHierarchy(items);
		var open = inc==0;
		var level = new Element('div',{'class':'in2igui_selection_level'}).setStyle({display:open ? 'block' : 'none'});
		parent.insert(level);
		items.each(function(item) {
			var hasChildren = item.children && item.children.length>0;
			var left = inc*16+6;
			if (!hierarchical && inc>0 || hierarchical && !hasChildren) left+=13;
			var node = new Element('div',{'class':'in2igui_selection_item'}).setStyle({paddingLeft:left+'px'})
			if (item.badge) {
				node.insert(new Element('strong',{'class':'in2igui_selection_badge'}).update(item.badge));
			}
			if (hierarchical && hasChildren) {
				var self = this;
				node.insert(new Element('span',{'class':'in2igui_disclosure'}).observe('click',function(e) {
					e.stop();
					self.toggle(this);
				}));
			}
			var inner = new Element('span',{'class':'in2igui_selection_label'});
			if (item.icon) {
				node.insert(new Element('span',{'class':'in2igui_icon_1'}).setStyle({'backgroundImage' : 'url('+In2iGui.getIconUrl(item.icon,1)+')'}));
			}
			node.insert(inner.insert(item.title));
			node.observe('click',function(e) {
				this.parent.itemWasClicked(item);
			}.bind(this));
			node.observe('dblclick',function(e) {
				this.parent.itemWasDoubleClicked(item);
			}.bind(this));
			level.insert(node);
			var info = {title:item.title,icon:item.icon,badge:item.badge,kind:item.kind,element:node,value:item.value};
			node.dragDropInfo = info;
			this.items.push(info);
			this.buildLevel(level,item.children,inc+1);
		}.bind(this));
	},
	/** @private */
	toggle : function(node) {
		if (node.hasClassName('in2igui_disclosure_open')) {
			node.parentNode.next().hide();
			node.removeClassName('in2igui_disclosure_open');
		} else {
			node.parentNode.next().show();
			node.addClassName('in2igui_disclosure_open');
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
		this.items.each(function(item) {
			item.element.setClassName('in2igui_selected',this.parent.isSelection(item));
		}.bind(this));
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
	}
}
/* EOF */