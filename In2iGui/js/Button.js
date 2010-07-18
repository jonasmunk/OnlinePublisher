/**
 * @constructor
 * A button
 */
In2iGui.Button = function(options) {
	this.options = options;
	this.name = options.name;
	this.element = $(options.element);
	this.enabled = !this.element.hasClassName('in2igui_button_disabled');
	In2iGui.extend(this);
	this.addBehavior();
}

/**
 * Creates a new button
 */
In2iGui.Button.create = function(o) {
	var o = n2i.override({text:'',highlighted:false,enabled:true},o);
	var className = 'in2igui_button'+(o.highlighted ? ' in2igui_button_highlighted' : '');
	if (o.small && o.rounded) {
		className+=' in2igui_button_small_rounded';
	}
	if (!o.enabled) {
		className+=' in2igui_button_disabled';
	}
	var element = o.element = new Element('a',{'class':className,href:'#'});
	var element2 = new Element('span');
	element.appendChild(element2);
	var element3 = new Element('span');
	element2.appendChild(element3);
	if (o.icon) {
		var icon = new Element('em',{'class':'in2igui_button_icon'}).setStyle({'backgroundImage':'url('+In2iGui.getIconUrl(o.icon,1)+')'});
		if (!o.text || o.text.length==0) {
			icon.addClassName('in2igui_button_icon_notext');
		}
		element3.insert(icon);
	}
	if (o.text && o.text.length>0) {
		element3.insert(o.text);
	}
	if (o.title && o.title.length>0) {
		element3.insert(o.title);
	}
	return new In2iGui.Button(o);
}

In2iGui.Button.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		this.element.observe('click',function(e) {
			self.clicked();
			Event.stop(e);
		});
	},
	/** @private */
	clicked : function() {
		if (this.enabled) {
			if (this.options.confirm) {
				In2iGui.confirmOverlay({
					widget:this,
					text:this.options.confirm.text,
					okText:this.options.confirm.okText,
					cancelText:this.options.confirm.cancelText,
					onOk:this.fireClick.bind(this)
				});
			} else {
				this.fireClick();
			}
		} else {
			this.element.blur();
		}
	},
	/** @private */
	fireClick : function() {
		this.fire('click');
		if (this.options.submit) {
			var form = In2iGui.get().getAncestor(this,'in2igui_formula');
			if (form) {form.submit();}
		}
	},
	/** Registers a function as a click listener or issues a click */
	click : function(func) {
		if (func) {
			this.listen({$click:func});
		} else {
			this.clicked();
		}
	},
	/** Registers a function as a click handler */
	onClick : function(func) {
		this.listen({$click:func});
	},
	/** Enables or disables the button */
	setEnabled : function(enabled) {
		this.enabled = enabled;
		this.updateUI();
	},
	/** Enables the button */
	enable : function() {
		this.setEnabled(true);
	},
	/** Disables the button */
	disable : function() {
		this.setEnabled(false);
	},
	/** Sets whether the button is highlighted */
	setHighlighted : function(highlighted) {
		this.element.setClassName('in2igui_button_highlighted',highlighted);
	},
	/** @private */
	updateUI : function() {
		this.element.setClassName('in2igui_button_disabled',!this.enabled);
	},
	/** Sets the button text */
	setText : function(text) {
		this.element.getElementsByTagName('span')[1].innerHTML = text;
	}
}

////////////////////////////////// Buttons /////////////////////////////

/** @constructor */
In2iGui.Buttons = function(o) {
	this.name = o.name;
	this.element = $(o.element);
	this.body = this.element.select('.in2igui_buttons_body')[0];
	In2iGui.extend(this);
}

In2iGui.Buttons.create = function(o) {
	o = n2i.override({top:0},o);
	var e = o.element = new Element('div',{'class':'in2igui_buttons'});
	if (o.align=='right') {
		e.addClassName('in2igui_buttons_right');
	}
	if (o.align=='center') {
		e.addClassName('in2igui_buttons_center');
	}
	if (o.top>0) e.setStyle({paddingTop:o.top+'px'});
	e.insert(new Element('div',{'class':'in2igui_buttons_body'}));
	return new In2iGui.Buttons(o);
}

In2iGui.Buttons.prototype = {
	add : function(widget) {
		this.body.insert(widget.element);
		return this;
	}
}

/* EOF */