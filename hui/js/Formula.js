/**
 * @class
 * This is a formula
 */
hui.ui.Formula = function(options) {
	this.options = options;
	hui.ui.extend(this,options);
	this.addBehavior();
}

/** @static Creates a new formula */
hui.ui.Formula.create = function(o) {
	o = o || {};
	var atts = {'class':'in2igui_formula hui_formula'};
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
		var d = hui.ui.getDescendants(this);
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
		var d = hui.ui.getDescendants(this);
		for (var i=0; i < d.length; i++) {
			if (d[i].focus) {
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
				g.add(w);
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
	this.body = hui.firstByTag(this.element,'tbody');
	this.options = hui.override({above:true},options);
	hui.ui.extend(this);
}

/** Creates a new form group */
hui.ui.Formula.Group.create = function(options) {
	options = hui.override({above:true},options);
	var element = options.element = hui.build('table',
		{'class':'in2igui_formula_group'}
	);
	if (options.above) {
		hui.addClass(element,'in2igui_formula_group_above');
	}
	element.appendChild(hui.build('tbody'));
	return new hui.ui.Formula.Group(options);
}

hui.ui.Formula.Group.prototype = {
	add : function(widget) {
		var tr = hui.build('tr');
		this.body.appendChild(tr);
		var td = hui.build('td',{'class':'in2igui_formula_group'});
		if (widget.getLabel) {
			var label = widget.getLabel();
			if (label) {
				if (this.options.above) {
					hui.build('label',{text:label,parent:td});
				} else {
					var th = hui.build('th',{parent:tr});
					hui.build('label',{text:label,parent:th});
				}
			}
		}
		var item = hui.build('div',{'class':'in2igui_formula_item'});
		item.appendChild(widget.getElement());
		td.appendChild(item);
		tr.appendChild(td);
	},
	createButtons : function(options) {
		var tr = hui.build('tr',{parent:this.body});
		var td = hui.build('td',{colspan:this.options.above?1:2,parent:tr});
		var b = hui.ui.Buttons.create(options);
		td.appendChild(b.getElement());
		return b;
	}
}

///////////////////////// Text /////////////////////////

/**
 * A text fields
 * @constructor
 */
hui.ui.Formula.Text = function(options) {
	this.options = hui.override({label:null,key:null,lines:1,live:false,maxHeight:100},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	hui.ui.extend(this);
	this.input = hui.firstByClass(this.element,'in2igui_formula_text');
	this.multiline = this.input.tagName.toLowerCase() == 'textarea';
	this.placeholder = hui.firstByClass(this.element,'in2igui_field_placeholder');
	this.value = this.input.value;
	if (this.placeholder) {
		var self = this;
		hui.ui.onReady(function() {
			window.setTimeout(function() {
				self.value = self.input.value;
				self.updateClass();
			},500);
		});
	}
	this.addBehavior();
}

hui.ui.Formula.Text.create = function(options) {
	options = hui.override({lines:1},options);
	var node,input;
	if (options.lines>1 || options.multiline) {
		input = hui.build('textarea',
			{'class':'in2igui_formula_text','rows':options.lines,style:'height: 32px;'}
		);
		node = hui.build('span',{'class':'in2igui_formula_text_multiline'});
		node.appendChild(input);
	} else {
		input = hui.build('input',{'class':'in2igui_formula_text'});
		if (options.secret) {
			input.setAttribute('type','password');
		}
		node = hui.build('span',{'class':'in2igui_field_singleline'});
		node.appendChild(input);
	}
	if (options.value!==undefined) {
		input.value=options.value;
	}
	options.element = hui.ui.wrapInField(node);
	return new hui.ui.Formula.Text(options);
}

hui.ui.Formula.Text.prototype = {
	/** @private */
	addBehavior : function() {
		hui.ui.addFocusClass({element:this.input,classElement:this.element,'class':'in2igui_field_focused'});
		hui.listen(this.input,'keyup',this.onKeyUp.bind(this));
		var p = this.element.getElementsByTagName('em')[0];
		if (p) {
			this.updateClass();
			hui.listen(p,'mousedown',function() {
				window.setTimeout(function() {
					this.input.focus();
					this.input.select();
				}.bind(this)
			)}.bind(this));
			hui.listen(p,'mouseup',function() {
				this.input.focus();
				this.input.select();
			}.bind(this));
		}
	},
	updateClass : function() {
		hui.setClass(this.element,'in2igui_field_dirty',this.value.length>0);
	},
	/** @private */
	onKeyUp : function(e) {
		if (!this.multiline && e.keyCode===hui.KEY_RETURN) {
			this.fire('submit');
			var form = hui.ui.getAncestor(this,'in2igui_formula');
			if (form) {form.submit();}
			return;
		}
		if (this.input.value==this.value) {return;}
		this.value=this.input.value;
		this.updateClass();
		this.expand(true);
		hui.ui.callAncestors(this,'childValueChanged',this.input.value);
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
		return hui.isBlank(this.input.value);
	},
	setError : function(error) {
		var isError = error ? true : false;
		hui.setClass(this.element,'in2igui_field_error',isError);
		if (typeof(error) == 'string') {
			hui.ui.showToolTip({text:error,element:this.element,key:this.name});
		}
		if (!isError) {
			hui.ui.hideToolTip({key:this.name});
		}
	},
	// Expanding
	
	$visibilityChanged : function() {
		window.setTimeout(this.expand.bind(this));
	},
	/** @private */
	expand : function(animate) {
		if (!this.multiline) {return};
		if (!hui.dom.isVisible(this.element)) {return};
		var textHeight = hui.ui.getTextAreaHeight(this.input);
		textHeight = Math.max(32,textHeight);
		textHeight = Math.min(textHeight,this.options.maxHeight);
		if (animate) {
			this.updateOverflow();
			hui.animate(this.input,'height',textHeight+'px',300,{ease:hui.ease.slowFastSlow,onComplete:function() {
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
hui.ui.Formula.DateTime = function(o) {
	this.inputFormats = ['d-m-Y','d/m-Y','d/m/Y','d-m-Y H:i:s','d/m-Y H:i:s','d/m/Y H:i:s','d-m-Y H:i','d/m-Y H:i','d/m/Y H:i','d-m-Y H','d/m-Y H','d/m/Y H','d-m','d/m','d','Y','m-d-Y','m-d','m/d'];
	this.outputFormat = 'd-m-Y H:i:s';
	this.name = o.name;
	this.element = hui.get(o.element);
	this.input = hui.firstByTag(this.element,'input');
	this.options = hui.override({returnType:null,label:null,allowNull:true,value:null},o);
	this.value = this.options.value;
	hui.ui.extend(this);
	this.addBehavior();
	this.updateUI();
}

hui.ui.Formula.DateTime.create = function(options) {
	var node = hui.build('span',{'class':'in2igui_formula_text_singleline'});
	hui.build('input',{'class':'in2igui_formula_text',parent:node});
	options.element = hui.ui.wrapInField(node);
	return new hui.ui.Formula.DateTime(options);
}

hui.ui.Formula.DateTime.prototype = {
	addBehavior : function() {
		hui.ui.addFocusClass({element:this.input,classElement:this.element,'class':'in2igui_field_focused'});
		hui.listen(this.input,'blur',this.check.bind(this));
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
hui.ui.Formula.Number = function(o) {
	this.options = hui.override({min:0,max:10000,value:null,decimals:0,allowNull:false},o);	
	this.name = o.name;
	var e = this.element = hui.get(o.element);
	this.input = hui.firstByTag(e,'input');
	this.up = hui.firstByClass(e,'in2igui_number_up');
	this.down = hui.firstByClass(e,'in2igui_number_down');
	this.value = this.options.value;
	hui.ui.extend(this);
	this.addBehavior();
}

hui.ui.Formula.Number.create = function(o) {
	o.element = hui.build('span',{
		'class':'in2igui_number',
		html:'<span><span><input type="text" value="'+(o.value!==undefined ? o.value : '0')+'"/><a class="in2igui_number_up"></a><a class="in2igui_number_down"></a></span></span>'
	});
	return new hui.ui.Formula.Number(o);
}

hui.ui.Formula.Number.prototype = {
	addBehavior : function() {
		var e = this.element;
		hui.listen(this.input,'focus',function() {hui.addClass(e,'in2igui_number_focused')});
		hui.listen(this.input,'blur',this.blurEvent.bind(this));
		hui.listen(this.input,'keyup',this.keyEvent.bind(this));
		hui.listen(this.up,'mousedown',this.upEvent.bind(this));
		hui.listen(this.down,'mousedown',this.downEvent.bind(this));
	},
	blurEvent : function() {
		hui.removeClass(this.element,'in2igui_number_focused');
		this.updateField();
	},
	keyEvent : function(e) {
		e = e || window.event;
		if (e.keyCode==hui.KEY_UP) {
			hui.stop(e);
			this.upEvent();
		} else if (e.keyCode==hui.KEY_DOWN) {
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
	focus : function() {
		try {
			this.input.focus();
		} catch (e) {}
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
			hui.ui.callAncestors(this,'childValueChanged',this.value);
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
hui.ui.Formula.DropDown = function(o) {
	this.options = hui.override({label:null,placeholder:null,url:null,source:null},o);
	this.name = o.name;
	var e = this.element = hui.get(o.element);
	this.inner = e.getElementsByTagName('strong')[0];
	this.items = o.items || [];
	this.index = -1;
	this.value = this.options.value || null;
	this.dirty = true;
	hui.ui.extend(this);
	this._addBehavior();
	this._updateIndex();
	this._updateUI();
	if (this.options.url) {
		this.options.source = new hui.ui.Source({url:this.options.url,delegate:this});
	} else if (this.options.source) {
		this.options.source.listen(this);
	}
}

hui.ui.Formula.DropDown.create = function(options) {
	options = options || {};
	options.element = hui.build('a',{
		'class':'in2igui_dropdown',href:'#',
		html:'<span><span><strong></strong></span></span>'
	});
	return new hui.ui.Formula.DropDown(options);
}

hui.ui.Formula.DropDown.prototype = {
	/** @private */
	_addBehavior : function() {
		hui.ui.addFocusClass({element:this.element,'class':'in2igui_dropdown_focused'});
		hui.listen(this.element,'click',this._click.bind(this));
		hui.listen(this.element,'blur',this._hideSelector.bind(this));
		hui.listen(this.element,'keydown',this._keyDown.bind(this));
	},
	/** @private */
	_updateIndex : function() {
		this.index=-1;
		for (var i=0; i < this.items.length; i++) {
			if (this.items[i].value==this.value) {
				this.index=i;
			}
		};
	},
	/** @private */
	_updateUI : function() {
		var selected = this.items[this.index];
		if (selected) {
			var text = selected.label || selected.title || '';
			this.inner.innerHTML='';
			hui.dom.addText(this.inner,hui.wrap(text));
		} else if (this.options.placeholder) {
			this.inner.innerHTML='';
			this.inner.appendChild(hui.build('em',{text:hui.escape(this.options.placeholder)}));
		} else {
			this.inner.innerHTML='';
		}
		if (!this.selector) {
			return;
		}
		var as = this.selector.getElementsByTagName('a');
		for (var i=0; i < as.length; i++) {
			if (this.index==i) {
				hui.addClass(as[i],'in2igui_selected');
			} else {
				as[i].className='';
			}
		};
	},
	/** @private */
	_click : function(e) {
		hui.stop(e);
		this._buildSelector();
		var el = this.element, s=this.selector;
		el.focus();
		if (!this.items) return;
		var docHeight = hui.getDocumentHeight();
		if (docHeight<200) {
			var left = hui.getLeft(this.element);
			hui.setStyle(this.selector,{'left':left+'px',top:'5px'});
		} else {
			hui.place({
				target:{element:this.element,vertical:1,horizontal:0},
				source:{element:this.selector,vertical:0,horizontal:0}
			});
		}
		hui.setStyle(s,{visibility:'hidden',display:'block',width:''});
		var height = Math.min(docHeight-hui.getTop(s)-5,200);
		var width = Math.max(el.clientWidth-5,100,s.clientWidth+20);
		var space = hui.getViewPortWidth()-hui.getLeft(el)-20;
		width = Math.min(width,space);
		hui.setStyle(s,{visibility:'visible',width:width+'px',zIndex:hui.ui.nextTopIndex(),maxHeight:height+'px'});
	},
	/** @private */
	_keyDown : function(e) {
		if (this.items.length==0) {
			return;
		}
		if (e.keyCode==40) {
			hui.stop(e);
			if (this.index>=this.items.length-1) {
				this.value=this.items[0].value;
			} else {
				this.value=this.items[this.index+1].value;
			}
			this._updateIndex();
			this._updateUI();
			this._fireChange();
		} else if (e.keyCode==38) {
			hui.stop(e);
			if (this.index>0) {
				this.index--;
			} else {
				this.index = this.items.length-1;
			}
			this.value = this.items[this.index].value;
			this._updateUI();
			this._fireChange();
		}
	},
	selectFirst : function() {
		if (this.items.length>0) {
			this.setValue(this.items[0].value);
		}
	},
	getValue : function() {
		return this.value;
	},
	setValue : function(value) {
		this.value = value;
		this._updateIndex();
		this._updateUI();
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
	// TODO: Is this used?
	getItem : function() {
		if (this.index>=0) {
			return this.items[this.index];
		}
		return 0;
	},
	addItem : function(item) {
		this.items.push(item);
		this.dirty = true;
		this._updateIndex();
		this._updateUI();
	},
	setItems : function(items) {
		this.items = items;
		this.dirty = true;
		this.index = -1;
		this._updateIndex();
		this._updateUI();
	},
	/** @private */
	$itemsLoaded : function(items) {
		this.setItems(items);
	},
	/** @private */
	_hideSelector : function() {
		if (!this.selector) return;
		this.selector.style.display='none';
	},
	/** @private */
	_buildSelector : function() {
		if (!this.dirty || !this.items) {return};
		if (!this.selector) {
			this.selector = hui.build('div',{'class':'in2igui_dropdown_selector'});
			document.body.appendChild(this.selector);
			hui.listen(this.selector,'mousedown',function(e) {hui.stop(e)});
		} else {
			this.selector.innerHTML='';
		}
		var self = this;
		hui.each(this.items,function(item,i) {
			var e = hui.build('a',{href:'#',text:item.label || item.title});
			hui.listen(e,'mousedown',function(e) {
				hui.stop(e);
				self._itemClicked(item,i);
			})
			if (i==self.index) {
				hui.addClass(e,'in2igui_selected')
			};
			self.selector.appendChild(e);
		});
		this.dirty = false;
	},
	/** @private */
	_itemClicked : function(item,index) {
		this.index = index;
		var changed = this.value!=this.items[index].value;
		this.value = this.items[index].value;
		this._updateUI();
		this._hideSelector();
		if (changed) {
			this._fireChange();
		}
	},
	_fireChange : function() {
		hui.ui.callAncestors(this,'childValueChanged',this.value);
		this.fire('valueChanged',this.value);
	}
}


//////////////////////////// Radio buttons ////////////////////////////

/**
 * @constructor
 */
hui.ui.Formula.Radiobuttons = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.name = options.name;
	this.radios = [];
	this.value = options.value;
	this.defaultValue = this.value;
	hui.ui.extend(this);
}

hui.ui.Formula.Radiobuttons.prototype = {
	click : function() {
		this.value = !this.value;
		this.updateUI();
	},
	/** @private */
	updateUI : function() {
		for (var i=0; i < this.radios.length; i++) {
			var radio = this.radios[i];
			hui.setClass(hui.get(radio.id),'in2igui_selected',radio.value==this.value);
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
		var element = hui.get(radio.id);
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
hui.ui.Formula.Checkbox = function(o) {
	this.element = hui.get(o.element);
	this.control = hui.firstByTag(this.element,'span');
	this.options = o;
	this.name = o.name;
	this.value = o.value==='true' || o.value===true;
	hui.ui.extend(this);
	this.addBehavior();
}

/**
 * Creates a new checkbox
 */
hui.ui.Formula.Checkbox.create = function(o) {
	var e = o.element = hui.build('a',{'class':'in2igui_checkbox',href:'#',html:'<span><span></span></span>'});
	if (o.value) {
		hui.addClass(e,'in2igui_checkbox_selected');
	}
	return new hui.ui.Formula.Checkbox(o);
}

hui.ui.Formula.Checkbox.prototype = {
	/** @private */
	addBehavior : function() {
		hui.ui.addFocusClass({element:this.element,'class':'in2igui_checkbox_focused'});
		hui.listen(this.element,'click',this.click.bind(this));
	},
	/** @private */
	click : function(e) {
		hui.stop(e);
		this.element.focus();
		this.value = !this.value;
		this.updateUI();
		hui.ui.callAncestors(this,'childValueChanged',this.value);
		this.fire('valueChanged',this.value);
	},
	/** @private */
	updateUI : function() {
		hui.setClass(this.element,'in2igui_checkbox_selected',this.value);
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
hui.ui.Formula.Checkboxes = function(options) {
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

hui.ui.Formula.Checkboxes.create = function(o) {
	o.element = hui.build('div',{'class':o.vertical ? 'in2igui_checkboxes in2igui_checkboxes_vertical' : 'in2igui_checkboxes'});
	if (o.items) {
		hui.each(o.items,function(item) {
			var node = hui.build('a',{'class':'in2igui_checkbox',href:'javascript:void(0);',html:'<span><span></span></span>'+item.title});
			hui.ui.addFocusClass({element:node,'class':'in2igui_checkbox_focused'});
			o.element.appendChild(node);
		});
	}
	return new hui.ui.Formula.Checkboxes(o);
}

hui.ui.Formula.Checkboxes.prototype = {
	/** @private */
	addBehavior : function() {
		var checks = hui.byClass(this.element,'in2igui_checkbox');
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
		var nodes = hui.byClass(this.element,'in2igui_checkbox');
		for (i=0; i < this.items.length; i++) {
			item = this.items[i];
			found = hui.inArray(this.values,item.value);
			hui.setClass(nodes[i],'in2igui_checkbox_selected',found);
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
			var node = hui.build('a',{'class':'in2igui_checkbox',href:'javascript:void(0);',html:'<span><span></span></span>'+hui.escape(item.title)});
			hui.listen(node,'click',function(e) {
				hui.stop(e);
				this.flipValue(item.value);
			}.bind(this))
			hui.ui.addFocusClass({element:node,'class':'in2igui_checkbox_focused'});
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
hui.ui.Formula.Checkboxes.Items = function(options) {
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

hui.ui.Formula.Checkboxes.Items.prototype = {
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
			var node = hui.build('a',{'class':'in2igui_checkbox',href:'#',html:'<span><span></span></span>'+item.title});
			hui.listen(node,'click',function(e) {
				hui.stop(e);
				node.focus();
				self.itemWasClicked(item)
			});
			hui.ui.addFocusClass({element:node,'class':'in2igui_checkbox_focused'});
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
			hui.setClass(item.element,'in2igui_checkbox_selected',index!=-1);
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

///////////////////////// Tokens //////////////////////////////

/**
 * A tokens component
 * @constructor
 */
hui.ui.Formula.Tokens = function(o) {
	this.options = hui.override({label:null,key:null},o);
	this.element = hui.get(o.element);
	this.name = o.name;
	this.value = [''];
	hui.ui.extend(this);
	this.updateUI();
}

hui.ui.Formula.Tokens.create = function(o) {
	o = o || {};
	o.element = hui.build('div',{'class':'in2igui_tokens'});
	return new hui.ui.Formula.Tokens(o);
}

hui.ui.Formula.Tokens.prototype = {
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
		hui.each(this.value,function(value) {
			value = hui.trim(value);
			if (value.length>0) {
				out.push(value);
			}
		})
		return out;
	},
	getLabel : function() {
		return this.options.label;
	},
	updateUI : function() {
		this.element.innerHTML='';
		hui.each(this.value,function(value,i) {
			var input = hui.build('input',{'class':'in2igui_tokens_token',parent:this.element});
			if (this.options.width) {
				input.style.width=this.options.width+'px';
			}
			input.value = value;
			input.in2iguiIndex = i;
			hui.listen(input,'keyup',function() {
				this.inputChanged(input,i)
			}.bind(this));
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
		var input = hui.build('input',{'class':'in2igui_tokens_token'});
		if (this.options.width) {
			input.style.width = this.options.width+'px';
		}
		var i = this.value.length;
		this.value.push('');
		this.element.appendChild(input);
		var self = this;
		hui.listen(input,'keyup',function() {self.inputChanged(input,i)});
	}
}

/////////////////////////// Style length /////////////////////////

/**
 * A date and time field
 * @constructor
 */
hui.ui.Formula.StyleLength = function(o) {
	this.options = hui.override({value:null,min:0,max:1000,units:['px','pt','em','%'],defaultUnit:'px',allowNull:false},o);	
	this.name = o.name;
	var e = this.element = hui.get(o.element);
	this.input = hui.firstByTag(e,'input');
	var as = e.getElementsByTagName('a');
	this.up = as[0];
	this.down = as[1];
	this.value = this.parseValue(this.options.value);
	hui.ui.extend(this);
	this.addBehavior();
}

hui.ui.Formula.StyleLength.prototype = {
	/** @private */
	addBehavior : function() {
		var e = this.element;
		hui.listen(this.input,'focus',function() {hui.addClass(e,'in2igui_number_focused')});
		hui.listen(this.input,'blur',this.blurEvent.bind(this));
		hui.listen(this.input,'keyup',this.keyEvent.bind(this));
		hui.listen(this.up,'mousedown',this.upEvent.bind(this));
		hui.listen(this.down,'mousedown',this.downEvent.bind(this));
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
		hui.removeClass(this.element,'in2igui_number_focused');
		this.updateInput();
	},
	/** @private */
	keyEvent : function(e) {
		e = e || window.event;
		if (e.keyCode==hui.KEY_UP) {
			hui.stop(e);
			this.upEvent();
		} else if (e.keyCode==hui.KEY_DOWN) {
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
hui.ui.Formula.Location = function(options) {
	this.options = hui.override({value:null},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.chooser = hui.firstByTag(this.element,'a');
	this.latField = new hui.ui.TextField({element:hui.firstByTag(this.element,'input'),validator:new hui.ui.NumberValidator({min:-90,max:90,allowNull:true})});
	this.latField.listen(this);
	this.lngField = new hui.ui.TextField({element:this.element.getElementsByTagName('input')[1],validator:new hui.ui.NumberValidator({min:-180,max:180,allowNull:true})});
	this.lngField.listen(this);
	this.value = this.options.value;
	hui.ui.extend(this);
	this.setValue(this.value);
	this.addBehavior();
}

hui.ui.Formula.Location.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('div',{'class':'in2igui_location'});
	var b = hui.build('span',{html:'<span class="in2igui_location_latitude"><span><input/></span></span><span class="in2igui_location_longitude"><span><input/></span></span>'});
	e.appendChild(hui.ui.wrapInField(b));
	e.appendChild(hui.build('a',{'class':'in2igui_location_picker',href:'javascript:void(0);'}));
	return new hui.ui.Formula.Location(options);
}

hui.ui.Formula.Location.prototype = {
	/** @private */
	addBehavior : function() {
		hui.listen(this.chooser,'click',this.showPicker.bind(this));
		hui.ui.addFocusClass({element:this.latField.element,classElement:this.element,'class':'in2igui_field_focused'});
		hui.ui.addFocusClass({element:this.lngField.element,classElement:this.element,'class':'in2igui_field_focused'});
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
			this.picker = new hui.ui.LocationPicker();
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