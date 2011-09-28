/////////////////////////// Checkboxes ////////////////////////////////

/**
 * Multiple checkboxes
 * @constructor
 */
hui.ui.Checkboxes = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.name = options.name;
	this.items = options.items || [];
	this.sources = [];
	this.subItems = [];
	this.values = options.values || options.value || []; // values is deprecated
	hui.ui.extend(this);
	this.addBehavior();
	this.updateUI();
	if (options.url) {
		new hui.ui.Source({url:options.url,delegate:this});
	}
}

hui.ui.Checkboxes.create = function(o) {
	o.element = hui.build('div',{'class':o.vertical ? 'hui_checkboxes hui_checkboxes_vertical' : 'hui_checkboxes'});
	if (o.items) {
		hui.each(o.items,function(item) {
			var node = hui.build('a',{'class':'hui_checkbox',href:'javascript:void(0);',html:'<span><span></span></span>'+item.title});
			hui.ui.addFocusClass({element:node,'class':'hui_checkbox_focused'});
			o.element.appendChild(node);
		});
	}
	return new hui.ui.Checkboxes(o);
}

hui.ui.Checkboxes.prototype = {
	/** @private */
	addBehavior : function() {
		var checks = hui.byClass(this.element,'hui_checkbox');
		hui.each(checks,function(check,i) {
			hui.listen(check,'click',function(e) {
				hui.stop(e);
				this.flipValue(this.items[i].value);
			}.bind(this))
		}.bind(this))
	},
	getValue : function() {
		return this.values;
	},
	/** @deprecated */
	getValues : function() {
		return this.values;
	},
	checkValues : function() {
		var newValues = [];
		for (var i=0; i < this.values.length; i++) {
			var value = this.values[i],
				found = false,
				j;
			for (j=0; j < this.items.length; j++) {
				found = found || this.items[j].value===value;
			}
			for (j=0; j < this.subItems.length; j++) {
				found = found || this.subItems[j].hasValue(value);
			};
			if (found) {
				newValues.push(value);
			}
		};
		this.values=newValues;
	},
	setValue : function(values) {
		this.values=values;
		this.checkValues();
		this.updateUI();
	},
	/** @deprecated */
	setValues : function(values) {
		this.setValue(values);
	},
	flipValue : function(value) {
		hui.flipInArray(this.values,value);
		this.checkValues();
		this.updateUI();
		this.fire('valueChanged',this.values);
		hui.ui.callAncestors(this,'childValueChanged',this.values);
	},
	updateUI : function() {
		var i,item,found;
		for (i=0; i < this.subItems.length; i++) {
			this.subItems[i].updateUI();
		};
		var nodes = hui.byClass(this.element,'hui_checkbox');
		for (i=0; i < this.items.length; i++) {
			item = this.items[i];
			found = hui.inArray(this.values,item.value);
			hui.setClass(nodes[i],'hui_checkbox_selected',found);
		};
	},
	refresh : function() {
		for (var i=0; i < this.subItems.length; i++) {
			this.subItems[i].refresh();
		};
	},
	reset : function() {
		this.setValues([]);
	},
	registerSource : function(source) {
		source.parent = this;
		this.sources.push(source);
	},
	registerItem : function(item) {
		// If it is a number, treat it as such
		if (parseInt(item.value)==item.value) {
			item.value = parseInt(item.value);
		}
		this.items.push(item);
	},
	registerItems : function(items) {
		items.parent = this;
		this.subItems.push(items);
	},
	getLabel : function() {
		return this.options.label;
	},
	$itemsLoaded : function(items) {
		hui.each(items,function(item) {
			var node = hui.build('a',{'class':'hui_checkbox',href:'javascript:void(0);',html:'<span><span></span></span>'+hui.escape(item.title)});
			hui.listen(node,'click',function(e) {
				hui.stop(e);
				this.flipValue(item.value);
			}.bind(this))
			hui.ui.addFocusClass({element:node,'class':'hui_checkbox_focused'});
			this.element.appendChild(node);
			this.items.push(item);
		}.bind(this));
		this.checkValues();
		this.updateUI();
	}
}

/////////////////////// Checkbox items ///////////////////

/**
 * Check box items
 * @constructor
 */
hui.ui.Checkboxes.Items = function(options) {
	this.element = hui.get(options.element);
	this.name = options.name;
	this.parent = null;
	this.options = options;
	this.checkboxes = [];
	hui.ui.extend(this);
	if (this.options.source) {
		this.options.source.listen(this);
	}
}

hui.ui.Checkboxes.Items.prototype = {
	refresh : function() {
		if (this.options.source) {
			this.options.source.refresh();
		}
	},
	$itemsLoaded : function(items) {
		this.checkboxes = [];
		this.element.innerHTML='';
		var self = this;
		hui.each(items,function(item) {
			var node = hui.build('a',{'class':'hui_checkbox',href:'javascript://',html:'<span><span></span></span>'+item.title});
			hui.listen(node,'click',function(e) {
				hui.stop(e);
				node.focus();
				self.itemWasClicked(item)
			});
			hui.ui.addFocusClass({element:node,'class':'hui_checkbox_focused'});
			self.element.appendChild(node);
			self.checkboxes.push({title:item.title,element:node,value:item.value});
		});
		this.parent.checkValues();
		this.updateUI();
	},
	itemWasClicked : function(item) {
		this.parent.flipValue(item.value);
	},
	updateUI : function() {
		try {
		for (var i=0; i < this.checkboxes.length; i++) {
			var item = this.checkboxes[i];
			var index = hui.indexInArray(this.parent.values,item.value);
			hui.setClass(item.element,'hui_checkbox_selected',index!=-1);
		};
		} catch (e) {
			alert(typeof(this.parent.values));
			alert(e);
		}
	},
	hasValue : function(value) {
		for (var i=0; i < this.checkboxes.length; i++) {
			if (this.checkboxes[i].value==value) {
				return true;
			}
		};
		return false;
	}
}