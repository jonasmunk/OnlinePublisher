/**
 * A bar
 * <pre><strong>options:</strong> {
 *  element : «Element»,
 *  name : «String»
 * }
 * </pre>
 * @constructor
 * @param {Object} options The options
 */
hui.ui.Bar = function(options) {
	this.options = hui.override({},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.visible = hui.cls.has(this.element,'hui_bar_absolute') || this.element.style.display=='none' ? false : true;
	this.body = hui.get.firstByClass(this.element,'hui_bar_left');
	hui.ui.extend(this);
};


/**
 * Creates a new bar
 * <pre><strong>options:</strong> {
 *  variant : «null | 'window' | 'mini' | 'layout' | 'layout_mini' | 'window_mini'»,
 *  absolute : «true | <strong>false</strong>»,
 *
 *  name : «String»
 * }
 * </pre>
 * @static
 * @param {Object} options The options
 */
hui.ui.Bar.create = function(options) {
	options = options || {};
	var cls = 'hui_bar';
	if (options.variant) {
		cls+=' hui_bar_'+options.variant;
	}
	if (options.absolute) {
		cls+=' hui_bar_absolute';
	}
	options.element = hui.build('div',{
		'class' : cls
	});
	var body = hui.build('div',{'class':'hui_bar_body',parent:options.element});
	hui.build('div',{'class':'hui_bar_left',parent:body});
	return new hui.ui.Bar(options);
};

hui.ui.Bar.prototype = {
	/** Add the bar to the page */
	addToDocument : function() {
		document.body.appendChild(this.element);
	},
	/**
	 * Add a widget to the bar
	 * @param {Widget} widget The widget to add
	 */
	add : function(widget) {
		this.body.appendChild(widget.getElement());
	},
	/** Add a divider to the bar */
	addDivider : function() {
		hui.build('span',{'class':'hui_bar_divider',parent:this.body});
	},
	addToRight : function(widget) {
		var right = this._getRight();
		right.appendChild(widget.getElement());
	},
	placeAbove : function(widgetOrElement) {
		if (widgetOrElement.getElement) {
			widgetOrElement = widgetOrElement.getElement();
		}
		hui.position.place({
			source:{element:this.element,vertical:1,horizontal:0},
			target:{element:widgetOrElement,vertical:0,horizontal:0}
		});
		this.element.style.zIndex = hui.ui.nextTopIndex();
	},
	/** Change the visibility of the bar
	 * @param {Boolean} visible If the bar should be visible
	 */
	setVisible : function(visible) {
		if (this.visible===visible) {return;}
		if (visible) {
			this.show();
		} else {
			this.hide();
		}
	},
	/** Show the bar */
	show : function() {
		if (this.visible) {return;}
		if (this.options.absolute) {
			this.element.style.visibility='visible';
		} else {
			this.element.style.display='';
			hui.ui.reLayout();
		}
		this.visible = true;
		hui.ui.callVisible(this);
	},
	/** Hide the bar */
	hide : function() {
		if (!this.visible) {return;}
		if (this.options.absolute) {
			this.element.style.visibility='hidden';
		} else {
			this.element.style.display='none';
			hui.ui.reLayout();
		}
		this.visible = false;
		hui.ui.callVisible(this);
	},
	_getRight : function() {
		if (!this.right) {
			this.right = hui.get.firstByClass(this.element,'hui_bar_right');
			if (!this.right) {
				var body = hui.get.firstByClass(this.element,'hui_bar_body');
				this.right = hui.build('div',{'class':'hui_bar_right',parentFirst:body});
			}
		}
		return this.right;
	},
	select : function(key) {
		var children = hui.ui.getDescendants(this);
		hui.log(children);
		for (var i = 0; i < children.length; i++) {
			var child = children[i];
			if (child.getKey && child.setSelected) {
				child.setSelected(child.getKey()==key);
			}
		}
	},
	$clickButton : function(button) {
		this.fire('clickButton',button);
	}
};

/**
 * A bar button
 * <pre><strong>options:</strong> {
 *  element : «Element»,
 *  name : «String»
 * }
 * </pre>
 * @constructor
 * @param {Object} options The options
 */
hui.ui.Bar.Button = function(options) {
	this.options = hui.override({},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	hui.listen(this.element,'click',this._click.bind(this));
	hui.listen(this.element,'mousedown',this._mousedown.bind(this));
	hui.listen(this.element,'mouseup',hui.stop);
	hui.ui.extend(this);
};



/**
 * Creates a new bar button
 * <pre><strong>options:</strong> {
 *  icon : «String»,
 *
 *  name : «String»
 * }
 * </pre>
 * @static
 * @param {Object} options The options
 */
hui.ui.Bar.Button.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('a',{'class':'hui_bar_button'});
	if (options.icon) {
		e.appendChild(hui.ui.createIcon(options.icon,16));
	}
	return new hui.ui.Bar.Button(options);
};

hui.ui.Bar.Button.prototype = {
	_mousedown : function(e) {
		this.fire('mousedown');
		if (this.options.stopEvents) {
			hui.stop(e);
		}
	},
	_click : function(e) {
		this.fire('click');
		if (this.options.stopEvents) {
			hui.stop(e);
		}
		hui.ui.callAncestors(this,'$clickButton');
	},
	/** Mark the button as selected
	 * @param {Boolean} selected If it should be marked selected
	 */
	setSelected : function(selected) {
		hui.cls.set(this.element,'hui_bar_button_selected',selected);
	},
	getKey : function() {
		return this.options.key;
	}
};

/**
 * A bar text
 * <pre><strong>options:</strong> {
 *  element : «Element»,
 *  name : «String»
 * }
 * </pre>
 * @constructor
 * @param {Object} options The options
 */
hui.ui.Bar.Text = function(options) {
	this.options = hui.override({},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	hui.ui.extend(this);
};

hui.ui.Bar.Text.prototype = {
	/** Change the text
	 * @param {String} str The text
	 */
	setText : function(str) {
		hui.dom.setText(this.element,hui.ui.getTranslated(str));
	}
};