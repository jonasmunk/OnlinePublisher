/**
 * @class
 * This is a formula
 */
hui.ui.Formula = function(options) {
	this.options = options;
	hui.ui.extend(this,options);
	this.addBehavior();
  // TODO Deprecated
	if (options.listener) {
		this.listen(options.listener);
	}
	if (options.listen) {
		this.listen(options.listen);
	}
}

/** @static Creates a new formula */
hui.ui.Formula.create = function(o) {
	o = o || {};
	var atts = {'class':'hui_formula hui_formula'};
	if (o.action) {
		atts.action=o.action;
	}
	if (o.method) {
		atts.method=o.method;
	}
	o.element = hui.build('form',atts);
	return new hui.ui.Formula(o);
}

hui.ui.Formula.prototype = {
	/** @private */
	addBehavior : function() {
		this.element.onsubmit=function() {return false;};
	},
	submit : function() {
		this.fire('submit');
	},
	/** Returns a map of all values of descendants */
	getValues : function() {
		var data = {};
		var d = hui.ui.getDescendants(this);
		for (var i=0; i < d.length; i++) {
			var widget = d[i];
			if (widget.options && widget.options.key && widget.getValue) {
				data[widget.options.key] = widget.getValue();
			} else if (widget.name && widget.getValue) {
				data[widget.name] = widget.getValue();
			}
		};
		return data;
	},
	/** Sets the values of the descendants */
	setValues : function(values) {
		if (!hui.isDefined(values)) {
			return;
		}
		var d = hui.ui.getDescendants(this);
		for (var i=0; i < d.length; i++) {
			if (d[i].options && (d[i].options.key || d[i].options.name)) {
				var key = d[i].options.key || d[i].options.name;
				if (key && values[key]!==undefined) {
					d[i].setValue(values[key]);
				}
			}
		}
	},
	/** Sets focus in the first found child */
	focus : function(key) {
		var d = hui.ui.getDescendants(this);
		for (var i=0; i < d.length; i++) {
			if (d[i].focus && (!key || (d[i].options && d[i].options.key==key) || d[i].name==key)) {
				d[i].focus();
				return;
			}
		}
	},
	/** Resets all descendants */
	reset : function() {
		var d = hui.ui.getDescendants(this);
		for (var i=0; i < d.length; i++) {
			if (d[i].reset) {
				d[i].reset();
			}
		}
	},
	/** Adds a widget to the form */
	add : function(widget) {
		this.element.appendChild(widget.getElement());
	},
	/** Creates a new form group and adds it to the form
	 * @returns {'hui.ui.Formula.Group'} group
	 */
	createGroup : function(options) {
		var g = hui.ui.Formula.Group.create(options);
		this.add(g);
		return g;
	},
	/** Builds and adds a new group according to a recipe
	 * @returns {'hui.ui.Formula.Group'} group
	 */
	buildGroup : function(options,recipe) {
		var g = this.createGroup(options);
		hui.each(recipe,function(item) {
			if (hui.ui.Formula[item.type]) {
				var w = hui.ui.Formula[item.type].create(item.options);
				g.add(w,item.label);
			}
			else if (hui.ui[item.type]) {
				var w = hui.ui[item.type].create(item.options);
				g.add(w,item.label);
			} else {
				hui.log('buildGroup: Unable to find type: '+item.type);
			}
		});
		return g;
	},
	/** @private */
	childValueChanged : function(value) {
		this.fire('valuesChanged',this.getValues());
	},
	show : function() {
		this.element.style.display='';
	},
	hide : function() {
		this.element.style.display='none';
	}
}

///////////////////////// Group //////////////////////////


/**
 * A form group
 * @constructor
 */
hui.ui.Formula.Group = function(options) {
	this.name = options.name;
	this.element = hui.get(options.element);
	this.body = hui.get.firstByTag(this.element,'tbody');
	this.options = hui.override({above:true},options);
	hui.ui.extend(this);
}

/** Creates a new form group */
hui.ui.Formula.Group.create = function(options) {
	options = hui.override({above:true},options);
	var element = options.element = hui.build('table',
		{'class':'hui_formula_fields'}
	);
	if (options.above) {
		hui.cls.add(element,'hui_formula_fields_above');
	}
	element.appendChild(hui.build('tbody'));
	return new hui.ui.Formula.Group(options);
}

hui.ui.Formula.Group.prototype = {
	add : function(widget,label) {
		var tr = hui.build('tr');
		this.body.appendChild(tr);
		var td = hui.build('td',{'class':'hui_formula_field'});
		if (!label && widget.getLabel) {
			label = widget.getLabel();
		}
		if (label) {
			label = hui.ui.getTranslated(label);
			if (this.options.above) {
				hui.build('label',{className:'hui_formula_field',text:label,parent:td});
			} else {
				var th = hui.build('th',{parent:tr,className:'hui_formula_middle'});
				hui.build('label',{className:'hui_formula_field',text:label,parent:th});
			}
		}
		var item = hui.build('div',{'class':'hui_formula_field_body'});
		item.appendChild(widget.getElement());
		td.appendChild(item);
		tr.appendChild(td);
	},
	createButtons : function(options) {
		var tr = hui.build('tr',{parent:this.body});
		var td = hui.build('td',{colspan:this.options.above ? 1 : 2, parent:tr});
		var b = hui.ui.Buttons.create(options);
		td.appendChild(b.getElement());
		return b;
	}
};

///////////////////////// Field //////////////////////////


/**
 * A form field
 * @constructor
 */
hui.ui.Formula.Field = function(options) {
	this.name = options.name;
	this.element = hui.get(options.element);
	hui.ui.extend(this);
}

hui.ui.Formula.Field.prototype = {
    setVisible : function(visible) {
        this.element.style.display = visible ? '' : 'none';
    }
};