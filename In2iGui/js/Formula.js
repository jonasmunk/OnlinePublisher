/**
 * @class
 * This is a formula
 */
In2iGui.Formula = function(options) {
	this.options = options;
	In2iGui.extend(this,options);
	this.addBehavior();
}

/** @static Creates a new formula */
In2iGui.Formula.create = function(o) {
	o = o || {};
	var atts = {'class':'in2igui_formula'};
	if (o.action) {
		atts.action=o.action;
	}
	if (o.method) {
		atts.method=o.method;
	}
	o.element = new Element('form',atts);
	return new In2iGui.Formula(o);
}

In2iGui.Formula.prototype = {
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
		var d = In2iGui.get().getDescendants(this);
		for (var i=0; i < d.length; i++) {
			if (d[i].options && d[i].options.key && d[i].getValue) {
				data[d[i].options.key] = d[i].getValue();
			} else if (d[i].name && d[i].getValue) {
				data[d[i].name] = d[i].getValue();
			}
		};
		return data;
	},
	/** Sets the values of the descendants */
	setValues : function(values) {
		var d = In2iGui.get().getDescendants(this);
		for (var i=0; i < d.length; i++) {
			if (d[i].options && d[i].options.key) {
				var key = d[i].options.key;
				if (key && values[key]!=undefined) {
					d[i].setValue(values[key]);
				}
			}
		}
	},
	/** Sets focus in the first found child */
	focus : function() {
		var d = In2iGui.get().getDescendants(this);
		for (var i=0; i < d.length; i++) {
			if (d[i].focus) {
				d[i].focus();
				return;
			}
		}
	},
	/** Resets all descendants */
	reset : function() {
		var d = In2iGui.get().getDescendants(this);
		for (var i=0; i < d.length; i++) {
			if (d[i].reset) {
				d[i].reset();
			}
		}
	},
	/** Adds a widget to the form */
	add : function(widget) {
		this.element.insert(widget.getElement());
	},
	/** Creates a new form group and adds it to the form
	 * @returns {'In2iGui.Formula.Group'} group
	 */
	createGroup : function(options) {
		var g = In2iGui.Formula.Group.create(options);
		this.add(g);
		return g;
	},
	/** Builds and adds a new group according to a recipe
	 * @returns {'In2iGui.Formula.Group'} group
	 */
	buildGroup : function(options,recipe) {
		var g = this.createGroup(options);
		recipe.each(function(item) {
			if (In2iGui.Formula[item.type]) {
				var w = In2iGui.Formula[item.type].create(item.options);
				g.add(w);
			}
		});
		return g;
	},
	/** @private */
	childValueChanged : function(value) {
		this.fire('valuesChanged',this.getValues());
	}
}

///////////////////////// Group //////////////////////////


/**
 * A form group
 * @constructor
 */
In2iGui.Formula.Group = function(options) {
	this.name = options.name;
	this.element = $(options.element);
	this.body = this.element.select('tbody')[0];
	this.options = n2i.override({above:true},options);
	In2iGui.extend(this);
}

/** Creates a new form group */
In2iGui.Formula.Group.create = function(options) {
	options = n2i.override({above:true},options);
	var element = options.element = new Element('table',
		{'class':'in2igui_formula_group'}
	);
	if (options.above) {
		element.addClassName('in2igui_formula_group_above');
	}
	element.insert(new Element('tbody'));
	return new In2iGui.Formula.Group(options);
}

In2iGui.Formula.Group.prototype = {
	add : function(widget) {
		var tr = new Element('tr');
		this.body.appendChild(tr);
		var td = new Element('td',{'class':'in2igui_formula_group'});
		if (widget.getLabel) {
			var label = widget.getLabel();
			if (label) {
				if (this.options.above) {
					td.insert(new Element('label').insert(label));
				} else {
					var th = new Element('th');
					th.insert(new Element('label').insert(label));
					tr.insert(th);
				}
			}
		}
		td.insert(new Element('div',{'class':'in2igui_formula_item'}).insert(widget.getElement()));
		tr.insert(td);
	},
	createButtons : function(options) {
		var tr = new Element('tr');
		this.body.insert(tr);
		var td = new Element('td',{colspan:this.options.above?1:2});
		tr.insert(td);
		var b = In2iGui.Buttons.create(options);
		td.insert(b.getElement());
		return b;
	}
}

///////////////////////// Text /////////////////////////

/**
 * A text fields
 * @constructor
 */
In2iGui.Formula.Text = function(options) {
	this.options = n2i.override({label:null,key:null,lines:1,live:false,maxHeight:100},options);
	this.element = $(options.element);
	this.name = options.name;
	In2iGui.extend(this);
	this.input = this.element.select('.in2igui_formula_text')[0];
	this.multiline = this.input.tagName.toLowerCase() == 'textarea';
	this.placeholder = this.element.select('.in2igui_field_placeholder')[0];
	this.value = this.input.value;
	if (this.placeholder) {
		var self = this;
		In2iGui.onDomReady(function() {
			window.setTimeout(function() {
				self.value = self.input.value;
				self.updateClass();
			},500);
		});
	}
	this.addBehavior();
}

In2iGui.Formula.Text.create = function(options) {
	options = n2i.override({lines:1},options);
	var node,input;
	if (options.lines>1 || options.multiline) {
		input = new Element('textarea',
			{'class':'in2igui_formula_text','rows':options.lines,style:'height: 32px;'}
		);
		node = new Element('span',{'class':'in2igui_formula_text_multiline'}).insert(input);
	} else {
		input = new Element('input',{'class':'in2igui_formula_text'});
		node = new Element('span',{'class':'in2igui_field_singleline'}).insert(input);
	}
	if (options.value!==undefined) {
		input.value=options.value;
	}
	options.element = In2iGui.wrapInField(node);
	return new In2iGui.Formula.Text(options);
}

In2iGui.Formula.Text.prototype = {
	/** @private */
	addBehavior : function() {
		In2iGui.addFocusClass({element:this.input,classElement:this.element,'class':'in2igui_field_focused'});
		this.input.observe('keyup',this.onKeyUp.bind(this));
		var p = this.element.select('em')[0];
		if (p) {
			this.updateClass();
			p.observe('mousedown',function(){window.setTimeout(function() {this.input.focus();this.input.select();}.bind(this))}.bind(this));
			p.observe('mouseup',function(){this.input.focus();this.input.select();}.bind(this));
		}
	},
	updateClass : function() {
		this.element.setClassName('in2igui_field_dirty',this.value.length>0);
	},
	/** @private */
	onKeyUp : function(e) {
		if (!this.multiline && e.keyCode===Event.KEY_RETURN) {
			this.fire('submit');
			var form = In2iGui.get().getAncestor(this,'in2igui_formula');
			if (form) {form.submit();}
			return;
		}
		if (this.input.value==this.value) {return;}
		this.value=this.input.value;
		this.updateClass();
		this.expand(true);
		In2iGui.callAncestors(this,'childValueChanged',this.input.value);
		this.fire('valueChanged',this.input.value);
	},
	updateFromNode : function(node) {
		if (node.firstChild) {
			this.setValue(node.firstChild.nodeValue);
		} else {
			this.setValue(null);
		}
	},
	updateFromObject : function(data) {
		this.setValue(data.value);
	},
	focus : function() {
		try {
			this.input.focus();
		} catch (e) {}
	},
	select : function() {
		try {
			this.input.focus();
			this.input.select();
		} catch (e) {}
	},
	reset : function() {
		this.setValue('');
	},
	setValue : function(value) {
		if (value===undefined || value===null) {
			value='';
		}
		this.value = value;
		this.input.value = value;
		this.expand(true);
	},
	getValue : function() {
		return this.input.value;
	},
	getLabel : function() {
		return this.options.label;
	},
	isEmpty : function() {
		return this.input.value=='';
	},
	isBlank : function() {
		return this.input.value.strip()=='';
	},
	setError : function(error) {
		var isError = error ? true : false;
		this.element.setClassName('in2igui_field_error',isError);
		if (typeof(error) == 'string') {
			In2iGui.showToolTip({text:error,element:this.element,key:this.name});
		}
		if (!isError) {
			In2iGui.hideToolTip({key:this.name});
		}
	},
	// Expanding
	
	$visibilityChanged : function() {
		window.setTimeout(this.expand.bind(this));
	},
	/** @private */
	expand : function(animate) {
		if (!this.multiline) {return};
		n2i.log('Visible:'+n2i.dom.isVisible(this.element));
		if (!n2i.dom.isVisible(this.element)) {return};
		var textHeight = In2iGui.getTextAreaHeight(this.input);
		textHeight = Math.max(32,textHeight);
		textHeight = Math.min(textHeight,this.options.maxHeight);
		if (animate) {
			this.updateOverflow();
			n2i.animate(this.input,'height',textHeight+'px',300,{ease:n2i.ease.slowFastSlow,onComplete:function() {
				this.updateOverflow();
				}.bind(this)
			});
		} else {
			this.input.style.height=textHeight+'px';
			this.updateOverflow();
		}
	},
	updateOverflow : function() {
		if (!this.multiline) return;
		this.input.style.overflowY=this.input.clientHeight>=this.options.maxHeight ? 'auto' : 'hidden';
	}
}

/////////////////////////// Date time /////////////////////////

/**
 * A date and time field
 * @constructor
 */
In2iGui.Formula.DateTime = function(o) {
	this.inputFormats = ['d-m-Y','d/m-Y','d/m/Y','d-m-Y H:i:s','d/m-Y H:i:s','d/m/Y H:i:s','d-m-Y H:i','d/m-Y H:i','d/m/Y H:i','d-m-Y H','d/m-Y H','d/m/Y H','d-m','d/m','d','Y','m-d-Y','m-d','m/d'];
	this.outputFormat = 'd-m-Y H:i:s';
	this.name = o.name;
	this.element = $(o.element);
	this.input = this.element.select('input')[0];
	this.options = n2i.override({returnType:null,label:null,allowNull:true,value:null},o);
	this.value = this.options.value;
	In2iGui.extend(this);
	this.addBehavior();
	this.updateUI();
}

In2iGui.Formula.DateTime.create = function(options) {
	var input = new Element('input',{'class':'in2igui_formula_text'});
	var node = new Element('span',{'class':'in2igui_formula_text_singleline'}).insert(input);
	options.element = In2iGui.wrapInField(node);
	return new In2iGui.Formula.DateTime(options);
}

In2iGui.Formula.DateTime.prototype = {
	addBehavior : function() {
		In2iGui.addFocusClass({element:this.input,classElement:this.element,'class':'in2igui_field_focused'});
		this.input.observe('blur',this.check.bind(this));
	},
	updateFromNode : function(node) {
		if (node.firstChild) {
			this.setValue(node.firstChild.nodeValue);
		} else {
			this.setValue(null);
		}
	},
	updateFromObject : function(data) {
		this.setValue(data.value);
	},
	focus : function() {
		try {this.input.focus();} catch (ignore) {}
	},
	reset : function() {
		this.setValue('');
	},
	setValue : function(value) {
		if (!value) {
			this.value = null;
		} else if (value.constructor==Date) {
			this.value = value;
		} else {
			this.value = new Date();
			this.value.setTime(parseInt(value)*1000);
		}
		this.updateUI();
	},
	check : function() {
		var str = this.input.value;
		var parsed = null;
		for (var i=0; i < this.inputFormats.length && parsed==null; i++) {
			parsed = Date.parseDate(str,this.inputFormats[i]);
		};
		if (this.options.allowNull || parsed!=null) {
			this.value = parsed;
		}
		this.updateUI();
	},
	getValue : function() {
		if (this.value!=null && this.options.returnType=='seconds') {
			return Math.round(this.value.getTime()/1000);
		}
		return this.value;
	},
	getElement : function() {
		return this.element;
	},
	getLabel : function() {
		return this.options.label;
	},
	updateUI : function() {
		if (this.value) {
			this.input.value = this.value.dateFormat(this.outputFormat);
		} else {
			this.input.value = ''
		}
	}
}

/////////////////////////// Number /////////////////////////

/**
 * A date and time field
 * @constructor
 */
In2iGui.Formula.Number = function(o) {
	this.options = n2i.override({min:0,max:10000,value:null,decimals:0,allowNull:false},o);	
	this.name = o.name;
	var e = this.element = $(o.element);
	this.input = e.select('input')[0];
	this.up = e.select('.in2igui_number_up')[0];
	this.down = e.select('.in2igui_number_down')[0];
	this.value = this.options.value;
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Formula.Number.create = function(o) {
	var e = o.element = new Element('span',{'class':'in2igui_number'});
	e.update('<span><span><input type="text" value="'+(o.value!==undefined ? o.value : '0')+'"/><a class="in2igui_number_up"></a><a class="in2igui_number_down"></a></span></span>');
	return new In2iGui.Formula.Number(o);
}

In2iGui.Formula.Number.prototype = {
	addBehavior : function() {
		var e = this.element;
		this.input.observe('focus',function() {e.addClassName('in2igui_number_focused')});
		this.input.observe('blur',this.blurEvent.bind(this));
		this.input.observe('keyup',this.keyEvent.bind(this));
		//this.input.observe('keypress',this.keyEvent.bind(this));
		this.up.observe('mousedown',this.upEvent.bind(this));
		this.down.observe('mousedown',this.downEvent.bind(this));
	},
	blurEvent : function() {
		this.element.removeClassName('in2igui_number_focused');
		this.updateField();
	},
	keyEvent : function(e) {
		if (e.keyCode==Event.KEY_UP) {
			e.stop();
			this.upEvent();
		} else if (e.keyCode==Event.KEY_DOWN) {
			this.downEvent();
		} else {
			var parsed = parseInt(this.input.value,10);
			if (!isNaN(parsed)) {
				this.setLocalValue(parsed,true);
			} else {
				this.setLocalValue(null,true);
			}
		}
	},
	downEvent : function() {
		if (this.value===null) {
			this.setLocalValue(this.options.min,true);
		} else {
			this.setLocalValue(this.value-1,true);
		}
		this.updateField();
	},
	upEvent : function() {
		this.setLocalValue(this.value+1,true);
		this.updateField();
	},
	getValue : function() {
		return this.value;
	},
	getLabel : function() {
		return this.options.label;
	},
	setValue : function(value) {
		value = parseInt(value,10);
		if (!isNaN(value)) {
			this.setLocalValue(value,false);
		}
		this.updateField();
	},
	updateField : function() {
		this.input.value = this.value===null || this.value===undefined ? '' : this.value;
	},
	setLocalValue : function(value,fire) {
		var orig = this.value;
		if (value===null || value===undefined && this.options.allowNull) {
			this.value = null;
		} else {
			this.value = Math.min(Math.max(value,this.options.min),this.options.max);
		}
		if (fire && orig!==this.value) {
			In2iGui.callAncestors(this,'childValueChanged',this.value);
			this.fire('valueChanged',this.value);
		}
	},
	reset : function() {
		if (this.options.allowNull) {
			this.value = null;
		} else {
			this.value = Math.min(Math.max(0,this.options.min),this.options.max);
		}
		this.updateField();
	}
}

////////////////////////// DropDown ///////////////////////////

/**
 * A drop down selector
 * @constructor
 */
In2iGui.Formula.DropDown = function(o) {
	this.options = n2i.override({label:null,placeholder:null,url:null,source:null},o);
	this.name = o.name;
	var e = this.element = $(o.element);
	this.inner = e.getElementsByTagName('strong')[0];
	this.items = o.items || [];
	this.index = -1;
	this.value = this.options.value || null;
	this.dirty = true;
	In2iGui.extend(this);
	this.addBehavior();
	this.updateIndex();
	this.updateUI();
	if (this.options.url) {
		this.options.source = new In2iGui.Source({url:this.options.url,delegate:this});
	} else if (this.options.source) {
		this.options.source.listen(this);
	}
}

In2iGui.Formula.DropDown.create = function(options) {
	options = options || {};
	options.element = new Element('a',{'class':'in2igui_dropdown',href:'#'}).update(
		'<span><span><strong></strong></span></span>'
	);
	return new In2iGui.Formula.DropDown(options);
}

In2iGui.Formula.DropDown.prototype = {
	/** @private */
	addBehavior : function() {
		In2iGui.addFocusClass({element:this.element,'class':'in2igui_dropdown_focused'});
		this.element.observe('click',this.clicked.bind(this));
		this.element.observe('blur',this.hideSelector.bind(this));
		this.element.observe('keydown',this._keyDown.bind(this));
	},
	/** @private */
	updateIndex : function() {
		this.index=-1;
		for (var i=0; i < this.items.length; i++) {
			if (this.items[i].value==this.value) {
				this.index=i;
			}
		};
	},
	/** @private */
	updateUI : function() {
		var selected = this.items[this.index];
		if (selected) {
			var text = selected.label || selected.title || '';
			this.inner.innerHTML='';
			n2i.dom.addText(this.inner,n2i.wrap(text));
		} else if (this.options.placeholder) {
			this.inner.update(new Element('em').update(this.options.placeholder.escapeHTML()));
		} else {
			this.inner.innerHTML='';
		}
		if (!this.selector) {
			return;
		}
		this.selector.select('a').each(function(a,i) {
			if (this.index==i) {
				a.addClassName('in2igui_selected');
			}
			else {a.className=''};
		}.bind(this));
	},
	/** @private */
	clicked : function(e) {
		e.stop();
		this.buildSelector();
		var el = this.element, s=this.selector;
		el.focus();
		if (!this.items) return;
		var docHeight = n2i.getDocumentHeight();
		if (docHeight<200) {
			var left = this.element.cumulativeOffset().left;
			this.selector.setStyle({'left':left+'px',top:'5px'});
		} else {
			n2i.place({
				target:{element:this.element,vertical:1,horizontal:0},
				source:{element:this.selector,vertical:0,horizontal:0}
			});
		}
		s.setStyle({visibility:'hidden',display:'block',width:''});
		var height = Math.min(docHeight-s.cumulativeOffset().top-5,200);
		var width = Math.max(el.getWidth()-5,100,s.getWidth()+20);
		var space = n2i.getDocumentWidth()-el.cumulativeOffset().left-20;
		width = Math.min(width,space);
		s.setStyle({visibility:'visible',width:width+'px',zIndex:In2iGui.nextTopIndex(),maxHeight:height+'px'});
	},
	getValue : function() {
		return this.value;
	},
	/** @private */
	_keyDown : function(e) {
		if (this.items.length==0) {
			return;
		}
		if (e.keyCode==40) {
			e.stop();
			if (this.index>=this.items.length-1) {
				this.value=this.items[0].value;
			} else {
				this.value=this.items[this.index+1].value;
			}
			this.updateIndex();
			this.updateUI();
			this.fireChange();
		} else if (e.keyCode==38) {
			e.stop();
			if (this.index>0) {
				this.index--;
			} else {
				this.index = this.items.length-1;
			}
			this.value = this.items[this.index].value;
			this.updateUI();
			this.fireChange();
		}
	},
	setValue : function(value) {
		this.value = value;
		this.updateIndex();
		this.updateUI();
	},
	reset : function() {
		this.setValue(null);
	},
	getLabel : function() {
		return this.options.label;
	},
	refresh : function() {
		if (this.options.source) {
			this.options.source.refresh();
		}
	},
	getItem : function(value) {
		if (this.index>=0) {
			return this.items[this.index];
		}
		return 0;
	},
	addItem : function(item) {
		this.items.push(item);
		this.dirty = true;
		this.updateIndex();
		this.updateUI();
	},
	setItems : function(items) {
		this.items = items;
		this.dirty = true;
		this.index = -1;
		this.updateIndex();
		this.updateUI();
	},
	/** @private */
	$itemsLoaded : function(items) {
		this.setItems(items);
	},
	/** @private */
	hideSelector : function() {
		if (!this.selector) return;
		this.selector.hide();
	},
	/** @private */
	buildSelector : function() {
		if (!this.dirty || !this.items) return;
		if (!this.selector) {
			this.selector = new Element('div',{'class':'in2igui_dropdown_selector'});
			document.body.appendChild(this.selector);
			this.selector.observe('mousedown',function(e) {e.stop()});
		} else {
			this.selector.update();
		}
		var self = this;
		this.items.each(function(item,i) {
			var e = new Element('a',{href:'#'}).update(item.label || item.title).observe('mousedown',function(e) {
				e.stop();
				self.itemClicked(item,i);
			})
			if (i==self.index) e.addClassName('in2igui_selected');
			self.selector.insert(e);
		});
		this.dirty = false;
	},
	/** @private */
	itemClicked : function(item,index) {
		this.index = index;
		var changed = this.value!=this.items[index].value;
		this.value = this.items[index].value;
		this.updateUI();
		this.hideSelector();
		if (changed) {
			this.fireChange();
		}
	},
	fireChange : function() {
		In2iGui.callAncestors(this,'childValueChanged',this.value);
		this.fire('valueChanged',this.value);
	}
}


//////////////////////////// Radio buttons ////////////////////////////

/**
 * @constructor
 */
In2iGui.Formula.Radiobuttons = function(options) {
	this.options = options;
	this.element = $(options.element);
	this.name = options.name;
	this.radios = [];
	this.value = options.value;
	this.defaultValue = this.value;
	In2iGui.extend(this);
}

In2iGui.Formula.Radiobuttons.prototype = {
	click : function() {
		this.value = !this.value;
		this.updateUI();
	},
	/** @private */
	updateUI : function() {
		for (var i=0; i < this.radios.length; i++) {
			var radio = this.radios[i];
			$(radio.id).setClassName('in2igui_selected',radio.value==this.value);
		};
	},
	setValue : function(value) {
		this.value = value;
		this.updateUI();
	},
	getValue : function() {
		return this.value;
	},
	reset : function() {
		this.setValue(this.defaultValue);
	},
	registerRadiobutton : function(radio) {
		this.radios.push(radio);
		var element = $(radio.id);
		var self = this;
		element.onclick = function() {
			self.setValue(radio.value);
			self.fire('valueChanged',radio.value);
		}
	}
}


///////////////////////////// Checkbox /////////////////////////////////

/**
 * A check box
 * @constructor
 */
In2iGui.Formula.Checkbox = function(o) {
	this.element = $(o.element);
	this.control = this.element.select('span')[0];
	this.options = o;
	this.name = o.name;
	this.value = o.value==='true' || o.value===true;
	In2iGui.extend(this);
	this.addBehavior();
}

/**
 * Creates a new checkbox
 */
In2iGui.Formula.Checkbox.create = function(o) {
	var e = o.element = new Element('a',{'class':'in2igui_checkbox',href:'#'});
	if (o.value) {
		e.addClassName('in2igui_checkbox_selected');
	}
	e.update('<span><span></span></span>');
	return new In2iGui.Formula.Checkbox(o);
}

In2iGui.Formula.Checkbox.prototype = {
	/** @private */
	addBehavior : function() {
		In2iGui.addFocusClass({element:this.element,'class':'in2igui_checkbox_focused'});
		this.element.observe('click',this.click.bind(this));
	},
	/** @private */
	click : function(e) {
		e.stop();
		this.element.focus();
		this.value = !this.value;
		this.updateUI();
		In2iGui.callAncestors(this,'childValueChanged',this.value);
		this.fire('valueChanged',this.value);
	},
	/** @private */
	updateUI : function() {
		this.element.setClassName('in2igui_checkbox_selected',this.value);
	},
	/** Sets the value
	 * @param {Boolean} value Whether the checkbox is checked
	 */
	setValue : function(value) {
		this.value = value===true || value==='true';
		this.updateUI();
	},
	/** Gets the value
	 * @return {Boolean} Whether the checkbox is checked
	 */
	getValue : function() {
		return this.value;
	},
	/** Resets the checkbox */
	reset : function() {
		this.setValue(false);
	},
	/** Gets the label
	 * @return {String} The checkbox label
	 */
	getLabel : function() {
		return this.options.label;
	}
}

/////////////////////////// Checkboxes ////////////////////////////////

/**
 * Multiple checkboxes
 * @constructor
 */
In2iGui.Formula.Checkboxes = function(options) {
	this.options = options;
	this.element = $(options.element);
	this.name = options.name;
	this.items = options.items || [];
	this.sources = [];
	this.subItems = [];
	this.values = options.values || options.value || []; // values is deprecated
	In2iGui.extend(this);
	this.addBehavior();
	this.updateUI();
	if (options.url) {
		new In2iGui.Source({url:options.url,delegate:this});
	}
}

In2iGui.Formula.Checkboxes.create = function(o) {
	o.element = new Element('div',{'class':o.vertical ? 'in2igui_checkboxes in2igui_checkboxes_vertical' : 'in2igui_checkboxes'});
	if (o.items) {
		o.items.each(function(item) {
			var node = new Element('a',{'class':'in2igui_checkbox',href:'javascript:void(0);'}).update(
				'<span><span></span></span>'+item.title
			);
			In2iGui.addFocusClass({element:node,'class':'in2igui_checkbox_focused'});
			o.element.insert(node);
		});
	}
	return new In2iGui.Formula.Checkboxes(o);
}

In2iGui.Formula.Checkboxes.prototype = {
	/** @private */
	addBehavior : function() {
		this.element.select('a.in2igui_checkbox').each(function(check,i) {
			check.observe('click',function(e) {
				e.stop();
				this.flipValue(this.items[i].value);
			}.bind(this))
		}.bind(this));
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
			var value = this.values[i];
			var found = false;
			for (var j=0; j < this.items.length; j++) {
				found = found || this.items[j].value===value;
			}
			for (var j=0; j < this.subItems.length; j++) {
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
		n2i.flipInArray(this.values,value);
		this.checkValues();
		this.updateUI();
		this.fire('valueChanged',this.values);
		In2iGui.callAncestors(this,'childValueChanged',this.values);
	},
	updateUI : function() {
		for (var i=0; i < this.subItems.length; i++) {
			this.subItems[i].updateUI();
		};
		var nodes = this.element.select('a.in2igui_checkbox');
		this.items.each(function(item,i) {
			nodes[i].setClassName('in2igui_checkbox_selected',this.values.indexOf(item.value)!==-1);
		}.bind(this));
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
		items.each(function(item) {
			var node = new Element('a',{'class':'in2igui_checkbox',href:'javascript:void(0);'}).update(
				'<span><span></span></span>'+item.title
			);
			node.observe('click',function(e) {
				e.stop();
				this.flipValue(item.value);
			}.bind(this))
			In2iGui.addFocusClass({element:node,'class':'in2igui_checkbox_focused'});
			this.element.insert(node);
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
In2iGui.Formula.Checkboxes.Items = function(options) {
	this.element = $(options.element);
	this.name = options.name;
	this.parent = null;
	this.options = options;
	this.checkboxes = [];
	In2iGui.extend(this);
	if (this.options.source) {
		this.options.source.listen(this);
	}
}

In2iGui.Formula.Checkboxes.Items.prototype = {
	refresh : function() {
		if (this.options.source) {
			this.options.source.refresh();
		}
	},
	$itemsLoaded : function(items) {
		this.checkboxes = [];
		this.element.update();
		var self = this;
		items.each(function(item) {
			var node = new Element('a',{'class':'in2igui_checkbox',href:'#'}).update(
				'<span><span></span></span>'+item.title
			).observe('click',function(e) {e.stop();node.focus();self.itemWasClicked(item)});
			In2iGui.addFocusClass({element:node,'class':'in2igui_checkbox_focused'});
			self.element.insert(node);
			self.checkboxes.push({title:item.title,element:node,value:item.value});
		})
		this.parent.checkValues();
		this.updateUI();
	},
	itemWasClicked : function(item) {
		this.parent.flipValue(item.value);
	},
	updateUI : function() {
		for (var i=0; i < this.checkboxes.length; i++) {
			var item = this.checkboxes[i];
			item.element.setClassName('in2igui_checkbox_selected',this.parent.values.indexOf(item.value)!=-1);
		};
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

///////////////////////// Tokens //////////////////////////////

/**
 * A tokens component
 * @constructor
 */
In2iGui.Formula.Tokens = function(o) {
	this.options = n2i.override({label:null,key:null},o);
	this.element = $(o.element);
	this.name = o.name;
	this.value = [''];
	In2iGui.extend(this);
	this.updateUI();
}

In2iGui.Formula.Tokens.create = function(o) {
	o = o || {};
	o.element = new Element('div').addClassName('in2igui_tokens');
	return new In2iGui.Formula.Tokens(o);
}

In2iGui.Formula.Tokens.prototype = {
	setValue : function(objects) {
		this.value = objects;
		this.value.push('');
		this.updateUI();
	},
	reset : function() {
		this.value = [''];
		this.updateUI();
	},
	getValue : function() {
		var out = [];
		this.value.each(function(value) {
			value = value.strip();
			if (value.length>0) out.push(value);
		})
		return out;
	},
	getLabel : function() {
		return this.options.label;
	},
	updateUI : function() {
		this.element.update();
		this.value.each(function(value,i) {
			var input = new Element('input').addClassName('in2igui_tokens_token');
			if (this.options.width) {
				input.setStyle({width:this.options.width+'px'});
			}
			input.value = value;
			input.in2iguiIndex = i;
			this.element.insert(input);
			input.observe('keyup',function() {this.inputChanged(input,i)}.bind(this));
		}.bind(this));
	},
	/** @private */
	inputChanged : function(input,index) {
		if (index==this.value.length-1 && input.value!=this.value[index]) {
			this.addField();
		}
		this.value[index] = input.value;
	},
	/** @private */
	addField : function() {
		var input = new Element('input').addClassName('in2igui_tokens_token');
		if (this.options.width) {
			input.setStyle({width:this.options.width+'px'});
		}
		var i = this.value.length;
		this.value.push('');
		this.element.insert(input);
		var self = this;
		input.observe('keyup',function() {self.inputChanged(input,i)});
	}
}

/////////////////////////// Style length /////////////////////////

/**
 * A date and time field
 * @constructor
 */
In2iGui.Formula.StyleLength = function(o) {
	this.options = n2i.override({value:null,min:0,max:1000,units:['px','pt','em','%'],defaultUnit:'px',allowNull:false},o);	
	this.name = o.name;
	var e = this.element = $(o.element);
	this.input = e.select('input')[0];
	var as = e.select('a');
	this.up = as[0];
	this.down = as[1];
	this.value = this.parseValue(this.options.value);
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Formula.StyleLength.prototype = {
	/** @private */
	addBehavior : function() {
		var e = this.element;
		this.input.observe('focus',function() {e.addClassName('in2igui_number_focused')});
		this.input.observe('blur',this.blurEvent.bind(this));
		this.input.observe('keyup',this.keyEvent.bind(this));
		this.up.observe('mousedown',this.upEvent.bind(this));
		this.down.observe('mousedown',this.downEvent.bind(this));
	},
	/** @private */
	parseValue : function(value) {
		var num = parseFloat(value,10);
		if (isNaN(num)) {
			return null;
		}
		var parsed = {number: num, unit:this.options.defaultUnit};
		for (var i=0; i < this.options.units.length; i++) {
			var unit = this.options.units[i];
			if (value.indexOf(unit)!=-1) {
				parsed.unit = unit;
				break;
			}
		};
		parsed.number = Math.max(this.options.min,Math.min(this.options.max,parsed.number));
		return parsed;
	},
	/** @private */
	blurEvent : function() {
		this.element.removeClassName('in2igui_number_focused');
		this.updateInput();
	},
	/** @private */
	keyEvent : function(e) {
		if (e.keyCode==Event.KEY_UP) {
			e.stop();
			this.upEvent();
		} else if (e.keyCode==Event.KEY_DOWN) {
			this.downEvent();
		} else {
			this.checkAndSetValue(this.parseValue(this.input.value));
		}
	},
	/** @private */
	updateInput : function() {
		this.input.value = this.getValue();
	},
	/** @private */
	checkAndSetValue : function(value) {
		var old = this.value;
		var changed = false;
		if (old===null && value===null) {
			// nothing
		} else if (old!=null && value!=null && old.number===value.number && old.unit===value.unit) {
			// nothing
		} else {
			changed = true;
		}
		this.value = value;
		if (changed) {
			this.fire('valueChanged',this.getValue());
		}
	},
	/** @private */
	downEvent : function() {
		if (this.value) {
			this.checkAndSetValue({number:Math.max(this.options.min,this.value.number-1),unit:this.value.unit});
		} else {
			this.checkAndSetValue({number:this.options.min,unit:this.options.defaultUnit});
		}
		this.updateInput();
	},
	/** @private */
	upEvent : function() {
		if (this.value) {
			this.checkAndSetValue({number:Math.min(this.options.max,this.value.number+1),unit:this.value.unit});
		} else {
			this.checkAndSetValue({number:this.options.min+1,unit:this.options.defaultUnit});
		}
		this.updateInput();
	},
	
	// Public
	
	getValue : function() {
		return this.value ? this.value.number+this.value.unit : '';
	},
	setValue : function(value) {
		this.value = this.parseValue(value);
		this.updateInput();
	}
}

/////////////////////////// Style length /////////////////////////

/**
 * A component for geo-location
 * @constructor
 */
In2iGui.Formula.Location = function(options) {
	this.options = n2i.override({value:null},options);
	this.name = options.name;
	this.element = $(options.element);
	this.chooser = this.element.select('a')[0];
	this.latField = new In2iGui.TextField({element:this.element.select('input')[0],validator:new In2iGui.NumberValidator({min:-90,max:90,allowNull:true})});
	this.latField.listen(this);
	this.lngField = new In2iGui.TextField({element:this.element.select('input')[1],validator:new In2iGui.NumberValidator({min:-180,max:180,allowNull:true})});
	this.lngField.listen(this);
	this.value = this.options.value;
	In2iGui.extend(this);
	this.setValue(this.value);
	this.addBehavior();
}

In2iGui.Formula.Location.create = function(options) {
	options = options || {};
	var e = options.element = new Element('div',{'class':'in2igui_location'});
	var b = new Element('span');
	b.update('<span class="in2igui_location_latitude"><span><input/></span></span><span class="in2igui_location_longitude"><span><input/></span></span>');
	e.insert(In2iGui.wrapInField(b));
	e.insert('<a class="in2igui_location_picker" href="javascript:void(0);"></a>');
	return new In2iGui.Formula.Location(options);
}

In2iGui.Formula.Location.prototype = {
	/** @private */
	addBehavior : function() {
		this.chooser.observe('click',this.showPicker.bind(this));
		In2iGui.addFocusClass({element:this.latField.element,classElement:this.element,'class':'in2igui_field_focused'});
		In2iGui.addFocusClass({element:this.lngField.element,classElement:this.element,'class':'in2igui_field_focused'});
	},
	getLabel : function() {
		return this.options.label;
	},
	reset : function() {
		this.setValue();
	},
	getValue : function() {
		return this.value;
	},
	setValue : function(loc) {
		if (loc) {
			this.latField.setValue(loc.latitude);
			this.lngField.setValue(loc.longitude);
			this.value = loc;
		} else {
			this.latField.setValue();
			this.lngField.setValue();
			this.value = null;
		}
		this.updatePicker();
	},
	updatePicker : function() {
		if (this.picker) {
			this.picker.setLocation(this.value);
		}
	},
	/** @private */
	showPicker : function() {
		if (!this.picker) {
			this.picker = new In2iGui.LocationPicker();
			this.picker.listen(this);
		}
		this.picker.show({node:this.chooser,location:this.value});
	},
	$locationChanged : function(loc) {
		this.setValue(loc);
	},
	$valueChanged : function() {
		var lat = this.latField.getValue();
		var lng = this.lngField.getValue();
		if (lat===null || lng===null) {
			this.value = null;
		} else {
			this.value = {latitude:lat,longitude:lng};
		}
		this.updatePicker();
	}
}

/* EOF */