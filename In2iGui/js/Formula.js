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
	o.element = n2i.build('form',atts);
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
		this.element.appendChild(widget.getElement());
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
		n2i.each(recipe,function(item) {
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
In2iGui.Formula.Group = function(options) {
	this.name = options.name;
	this.element = n2i.get(options.element);
	this.body = n2i.firstByTag(this.element,'tbody');
	this.options = n2i.override({above:true},options);
	In2iGui.extend(this);
}

/** Creates a new form group */
In2iGui.Formula.Group.create = function(options) {
	options = n2i.override({above:true},options);
	var element = options.element = n2i.build('table',
		{'class':'in2igui_formula_group'}
	);
	if (options.above) {
		n2i.addClass(element,'in2igui_formula_group_above');
	}
	element.appendChild(n2i.build('tbody'));
	return new In2iGui.Formula.Group(options);
}

In2iGui.Formula.Group.prototype = {
	add : function(widget) {
		var tr = n2i.build('tr');
		this.body.appendChild(tr);
		var td = n2i.build('td',{'class':'in2igui_formula_group'});
		if (widget.getLabel) {
			var label = widget.getLabel();
			if (label) {
				if (this.options.above) {
					n2i.build('label',{text:label,parent:td});
				} else {
					var th = n2i.build('th',{parent:tr});
					n2i.build('label',{text:label,parent:th});
				}
			}
		}
		var item = n2i.build('div',{'class':'in2igui_formula_item'});
		item.appendChild(widget.getElement());
		td.appendChild(item);
		tr.appendChild(td);
	},
	createButtons : function(options) {
		var tr = n2i.build('tr',{parent:this.body});
		var td = n2i.build('td',{colspan:this.options.above?1:2,parent:tr});
		var b = In2iGui.Buttons.create(options);
		td.appendChild(b.getElement());
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
	this.element = n2i.get(options.element);
	this.name = options.name;
	In2iGui.extend(this);
	this.input = n2i.firstByClass(this.element,'in2igui_formula_text');
	this.multiline = this.input.tagName.toLowerCase() == 'textarea';
	this.placeholder = n2i.firstByClass(this.element,'in2igui_field_placeholder');
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
		input = n2i.build('textarea',
			{'class':'in2igui_formula_text','rows':options.lines,style:'height: 32px;'}
		);
		node = n2i.build('span',{'class':'in2igui_formula_text_multiline'});
		node.appendChild(input);
	} else {
		input = n2i.build('input',{'class':'in2igui_formula_text'});
		node = n2i.build('span',{'class':'in2igui_field_singleline'});
		node.appendChild(input);
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
		n2i.listen(this.input,'keyup',this.onKeyUp.bind(this));
		var p = this.element.getElementsByTagName('em')[0];
		if (p) {
			this.updateClass();
			n2i.listen(p,'mousedown',function() {
				window.setTimeout(function() {
					this.input.focus();
					this.input.select();
				}.bind(this)
			)}.bind(this));
			n2i.listen(p,'mouseup',function() {
				this.input.focus();
				this.input.select();
			}.bind(this));
		}
	},
	updateClass : function() {
		n2i.setClass(this.element,'in2igui_field_dirty',this.value.length>0);
	},
	/** @private */
	onKeyUp : function(e) {
		if (!this.multiline && e.keyCode===n2i.KEY_RETURN) {
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
		return n2i.isBlank(this.input.value);
	},
	setError : function(error) {
		var isError = error ? true : false;
		n2i.setClass(this.element,'in2igui_field_error',isError);
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
	this.element = n2i.get(o.element);
	this.input = n2i.firstByTag(this.element,'input');
	this.options = n2i.override({returnType:null,label:null,allowNull:true,value:null},o);
	this.value = this.options.value;
	In2iGui.extend(this);
	this.addBehavior();
	this.updateUI();
}

In2iGui.Formula.DateTime.create = function(options) {
	var node = n2i.build('span',{'class':'in2igui_formula_text_singleline'});
	n2i.build('input',{'class':'in2igui_formula_text',parent:node});
	options.element = In2iGui.wrapInField(node);
	return new In2iGui.Formula.DateTime(options);
}

In2iGui.Formula.DateTime.prototype = {
	addBehavior : function() {
		In2iGui.addFocusClass({element:this.input,classElement:this.element,'class':'in2igui_field_focused'});
		n2i.listen(this.input,'blur',this.check.bind(this));
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
	var e = this.element = n2i.get(o.element);
	this.input = n2i.firstByTag(e,'input');
	this.up = n2i.firstByClass(e,'in2igui_number_up');
	this.down = n2i.firstByClass(e,'in2igui_number_down');
	this.value = this.options.value;
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Formula.Number.create = function(o) {
	o.element = n2i.build('span',{
		'class':'in2igui_number',
		html:'<span><span><input type="text" value="'+(o.value!==undefined ? o.value : '0')+'"/><a class="in2igui_number_up"></a><a class="in2igui_number_down"></a></span></span>'
	});
	return new In2iGui.Formula.Number(o);
}

In2iGui.Formula.Number.prototype = {
	addBehavior : function() {
		var e = this.element;
		n2i.listen(this.input,'focus',function() {n2i.addClass(e,'in2igui_number_focused')});
		n2i.listen(this.input,'blur',this.blurEvent.bind(this));
		n2i.listen(this.input,'keyup',this.keyEvent.bind(this));
		n2i.listen(this.up,'mousedown',this.upEvent.bind(this));
		n2i.listen(this.down,'mousedown',this.downEvent.bind(this));
	},
	blurEvent : function() {
		n2i.removeClass(this.element,'in2igui_number_focused');
		this.updateField();
	},
	keyEvent : function(e) {
		e = e || window.event;
		if (e.keyCode==n2i.KEY_UP) {
			n2i.stop(e);
			this.upEvent();
		} else if (e.keyCode==n2i.KEY_DOWN) {
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
	var e = this.element = n2i.get(o.element);
	this.inner = e.getElementsByTagName('strong')[0];
	this.items = o.items || [];
	this.index = -1;
	this.value = this.options.value || null;
	this.dirty = true;
	In2iGui.extend(this);
	this._addBehavior();
	this._updateIndex();
	this._updateUI();
	if (this.options.url) {
		this.options.source = new In2iGui.Source({url:this.options.url,delegate:this});
	} else if (this.options.source) {
		this.options.source.listen(this);
	}
}

In2iGui.Formula.DropDown.create = function(options) {
	options = options || {};
	options.element = n2i.build('a',{
		'class':'in2igui_dropdown',href:'#',
		html:'<span><span><strong></strong></span></span>'
	});
	return new In2iGui.Formula.DropDown(options);
}

In2iGui.Formula.DropDown.prototype = {
	/** @private */
	_addBehavior : function() {
		In2iGui.addFocusClass({element:this.element,'class':'in2igui_dropdown_focused'});
		n2i.listen(this.element,'click',this._click.bind(this));
		n2i.listen(this.element,'blur',this._hideSelector.bind(this));
		n2i.listen(this.element,'keydown',this._keyDown.bind(this));
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
			n2i.dom.addText(this.inner,n2i.wrap(text));
		} else if (this.options.placeholder) {
			this.inner.innerHTML='';
			this.inner.appendChild(n2i.build('em',{text:n2i.escape(this.options.placeholder)}));
		} else {
			this.inner.innerHTML='';
		}
		if (!this.selector) {
			return;
		}
		var as = this.selector.getElementsByTagName('a');
		for (var i=0; i < as.length; i++) {
			if (this.index==i) {
				n2i.addClass(as[i],'in2igui_selected');
			} else {
				as[i].className='';
			}
		};
	},
	/** @private */
	_click : function(e) {
		n2i.stop(e);
		this._buildSelector();
		var el = this.element, s=this.selector;
		el.focus();
		if (!this.items) return;
		var docHeight = n2i.getDocumentHeight();
		if (docHeight<200) {
			var left = n2i.getLeft(this.element);
			n2i.setStyle(this.selector,{'left':left+'px',top:'5px'});
		} else {
			n2i.place({
				target:{element:this.element,vertical:1,horizontal:0},
				source:{element:this.selector,vertical:0,horizontal:0}
			});
		}
		n2i.setStyle(s,{visibility:'hidden',display:'block',width:''});
		var height = Math.min(docHeight-n2i.getTop(s)-5,200);
		var width = Math.max(el.clientWidth-5,100,s.clientWidth+20);
		var space = n2i.getViewPortWidth()-n2i.getLeft(el)-20;
		width = Math.min(width,space);
		n2i.setStyle(s,{visibility:'visible',width:width+'px',zIndex:In2iGui.nextTopIndex(),maxHeight:height+'px'});
	},
	/** @private */
	_keyDown : function(e) {
		if (this.items.length==0) {
			return;
		}
		if (e.keyCode==40) {
			n2i.stop(e);
			if (this.index>=this.items.length-1) {
				this.value=this.items[0].value;
			} else {
				this.value=this.items[this.index+1].value;
			}
			this._updateIndex();
			this._updateUI();
			this._fireChange();
		} else if (e.keyCode==38) {
			n2i.stop(e);
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
			this.selector = n2i.build('div',{'class':'in2igui_dropdown_selector'});
			document.body.appendChild(this.selector);
			n2i.listen(this.selector,'mousedown',function(e) {n2i.stop(e)});
		} else {
			this.selector.innerHTML='';
		}
		var self = this;
		n2i.each(this.items,function(item,i) {
			var e = n2i.build('a',{href:'#',text:item.label || item.title});
			n2i.listen(e,'mousedown',function(e) {
				n2i.stop(e);
				self._itemClicked(item,i);
			})
			if (i==self.index) {
				n2i.addClass(e,'in2igui_selected')
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
	this.element = n2i.get(options.element);
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
			n2i.setClass(n2i.get(radio.id),'in2igui_selected',radio.value==this.value);
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
		var element = n2i.get(radio.id);
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
	this.element = n2i.get(o.element);
	this.control = n2i.firstByTag(this.element,'span');
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
	var e = o.element = n2i.build('a',{'class':'in2igui_checkbox',href:'#',html:'<span><span></span></span>'});
	if (o.value) {
		n2i.addClass(e,'in2igui_checkbox_selected');
	}
	return new In2iGui.Formula.Checkbox(o);
}

In2iGui.Formula.Checkbox.prototype = {
	/** @private */
	addBehavior : function() {
		In2iGui.addFocusClass({element:this.element,'class':'in2igui_checkbox_focused'});
		n2i.listen(this.element,'click',this.click.bind(this));
	},
	/** @private */
	click : function(e) {
		n2i.stop(e);
		this.element.focus();
		this.value = !this.value;
		this.updateUI();
		In2iGui.callAncestors(this,'childValueChanged',this.value);
		this.fire('valueChanged',this.value);
	},
	/** @private */
	updateUI : function() {
		n2i.setClass(this.element,'in2igui_checkbox_selected',this.value);
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
	this.element = n2i.get(options.element);
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
	o.element = n2i.build('div',{'class':o.vertical ? 'in2igui_checkboxes in2igui_checkboxes_vertical' : 'in2igui_checkboxes'});
	if (o.items) {
		n2i.each(o.items,function(item) {
			var node = n2i.build('a',{'class':'in2igui_checkbox',href:'javascript:void(0);',html:'<span><span></span></span>'+item.title});
			In2iGui.addFocusClass({element:node,'class':'in2igui_checkbox_focused'});
			o.element.appendChild(node);
		});
	}
	return new In2iGui.Formula.Checkboxes(o);
}

In2iGui.Formula.Checkboxes.prototype = {
	/** @private */
	addBehavior : function() {
		var checks = n2i.byClass(this.element,'in2igui_checkbox');
		n2i.each(checks,function(check,i) {
			n2i.listen(check,'click',function(e) {
				n2i.stop(e);
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
		n2i.flipInArray(this.values,value);
		this.checkValues();
		this.updateUI();
		this.fire('valueChanged',this.values);
		In2iGui.callAncestors(this,'childValueChanged',this.values);
	},
	updateUI : function() {
		var i,item,found;
		for (i=0; i < this.subItems.length; i++) {
			this.subItems[i].updateUI();
		};
		var nodes = n2i.byClass(this.element,'in2igui_checkbox');
		for (i=0; i < this.items.length; i++) {
			item = this.items[i];
			found = n2i.inArray(this.values,item.value);
			n2i.setClass(nodes[i],'in2igui_checkbox_selected',found);
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
		n2i.each(items,function(item) {
			var node = n2i.build('a',{'class':'in2igui_checkbox',href:'javascript:void(0);',html:'<span><span></span></span>'+n2i.escape(item.title)});
			n2i.listen(node,'click',function(e) {
				n2i.stop(e);
				this.flipValue(item.value);
			}.bind(this))
			In2iGui.addFocusClass({element:node,'class':'in2igui_checkbox_focused'});
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
In2iGui.Formula.Checkboxes.Items = function(options) {
	this.element = n2i.get(options.element);
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
		this.element.innerHTML='';
		var self = this;
		n2i.each(items,function(item) {
			var node = n2i.build('a',{'class':'in2igui_checkbox',href:'#',html:'<span><span></span></span>'+item.title});
			n2i.listen(node,'click',function(e) {
				n2i.stop(e);
				node.focus();
				self.itemWasClicked(item)
			});
			In2iGui.addFocusClass({element:node,'class':'in2igui_checkbox_focused'});
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
			var index = n2i.indexInArray(this.parent.values,item.value);
			n2i.setClass(item.element,'in2igui_checkbox_selected',index!=-1);
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
In2iGui.Formula.Tokens = function(o) {
	this.options = n2i.override({label:null,key:null},o);
	this.element = n2i.get(o.element);
	this.name = o.name;
	this.value = [''];
	In2iGui.extend(this);
	this.updateUI();
}

In2iGui.Formula.Tokens.create = function(o) {
	o = o || {};
	o.element = n2i.build('div',{'class':'in2igui_tokens'});
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
		n2i.each(this.value,function(value) {
			value = n2i.trim(value);
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
		n2i.each(this.value,function(value,i) {
			var input = n2i.build('input',{'class':'in2igui_tokens_token',parent:this.element});
			if (this.options.width) {
				input.style.width=this.options.width+'px';
			}
			input.value = value;
			input.in2iguiIndex = i;
			n2i.listen(input,'keyup',function() {
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
		var input = n2i.build('input',{'class':'in2igui_tokens_token'});
		if (this.options.width) {
			input.style.width = this.options.width+'px';
		}
		var i = this.value.length;
		this.value.push('');
		this.element.appendChild(input);
		var self = this;
		n2i.listen(input,'keyup',function() {self.inputChanged(input,i)});
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
	var e = this.element = n2i.get(o.element);
	this.input = n2i.firstByTag(e,'input');
	var as = e.getElementsByTagName('a');
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
		n2i.listen(this.input,'focus',function() {n2i.addClass(e,'in2igui_number_focused')});
		n2i.listen(this.input,'blur',this.blurEvent.bind(this));
		n2i.listen(this.input,'keyup',this.keyEvent.bind(this));
		n2i.listen(this.up,'mousedown',this.upEvent.bind(this));
		n2i.listen(this.down,'mousedown',this.downEvent.bind(this));
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
		n2i.removeClass(this.element,'in2igui_number_focused');
		this.updateInput();
	},
	/** @private */
	keyEvent : function(e) {
		e = e || window.event;
		if (e.keyCode==n2i.KEY_UP) {
			n2i.stop(e);
			this.upEvent();
		} else if (e.keyCode==n2i.KEY_DOWN) {
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
	this.element = n2i.get(options.element);
	this.chooser = n2i.firstByTag(this.element,'a');
	this.latField = new In2iGui.TextField({element:n2i.firstByTag(this.element,'input'),validator:new In2iGui.NumberValidator({min:-90,max:90,allowNull:true})});
	this.latField.listen(this);
	this.lngField = new In2iGui.TextField({element:this.element.getElementsByTagName('input')[1],validator:new In2iGui.NumberValidator({min:-180,max:180,allowNull:true})});
	this.lngField.listen(this);
	this.value = this.options.value;
	In2iGui.extend(this);
	this.setValue(this.value);
	this.addBehavior();
}

In2iGui.Formula.Location.create = function(options) {
	options = options || {};
	var e = options.element = n2i.build('div',{'class':'in2igui_location'});
	var b = n2i.build('span',{html:'<span class="in2igui_location_latitude"><span><input/></span></span><span class="in2igui_location_longitude"><span><input/></span></span>'});
	e.appendChild(In2iGui.wrapInField(b));
	e.appendChild(n2i.build('a',{'class':'in2igui_location_picker',href:'javascript:void(0);'}));
	return new In2iGui.Formula.Location(options);
}

In2iGui.Formula.Location.prototype = {
	/** @private */
	addBehavior : function() {
		n2i.listen(this.chooser,'click',this.showPicker.bind(this));
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