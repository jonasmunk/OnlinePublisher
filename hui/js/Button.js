/**
 * @constructor
 * A button
 */
hui.ui.Button = function(options) {
	this.options = options;
	this.name = options.name;
	this.element = hui.get(options.element);
	this.enabled = !hui.hasClass(this.element,'hui_button_disabled');
	hui.ui.extend(this);
	this.addBehavior();
}

/**
 * Creates a new button
 */
hui.ui.Button.create = function(o) {
	o = hui.override({text:'',highlighted:false,enabled:true},o);
	var className = 'hui_button'+(o.highlighted ? ' hui_button_highlighted' : '');
	if (o.small) {
		className+=' hui_button_small';
	}
	if (!o.enabled) {
		className+=' hui_button_disabled';
	}
	var element = o.element = hui.build('a',{'class':className,href:'javascript://'});
	var element2 = document.createElement('span');
	element.appendChild(element2);
	var element3 = document.createElement('span');
	element2.appendChild(element3);
	if (o.icon) {
		var icon = hui.build('em',{'class':'hui_button_icon',style:'background-image:url('+hui.ui.getIconUrl(o.icon,16)+')'});
		if (!o.text || o.text.length==0) {
			hui.addClass(icon,'hui_button_icon_notext');
		}
		element3.appendChild(icon);
	}
	if (o.text && o.text.length>0) {
		hui.dom.addText(element3,o.text);
	}
	if (o.title && o.title.length>0) {
		hui.dom.addText(element3,o.title);
	}
	return new hui.ui.Button(o);
}

hui.ui.Button.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		hui.listen(this.element,'mousedown',function(e) {
			hui.stop(e);
		});
		hui.listen(this.element,'click',function(e) {
			hui.stop(e);
			self.clicked();
		});
	},
	/** @private */
	clicked : function() {
		if (this.enabled) {
			if (this.options.confirm) {
				hui.ui.confirmOverlay({
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
			var form = hui.ui.getAncestor(this,'hui_formula');
			if (form) {form.submit();}
		}
	},
	/** Registers a function as a click listener or issues a click */
	click : function(func) {
		if (func) {
			this.listen({$click:func});
			return this;
		} else {
			this.clicked();
		}
	},
	focus : function() {
		this.element.focus();
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
		hui.setClass(this.element,'hui_button_highlighted',highlighted);
	},
	/** @private */
	updateUI : function() {
		hui.setClass(this.element,'hui_button_disabled',!this.enabled);
	},
	/** Sets the button text */
	setText : function(text) {
		hui.dom.setText(this.element.getElementsByTagName('span')[1], text);
	},
	getData : function() {
		return this.options.data;
	}
}

////////////////////////////////// Buttons /////////////////////////////

/** @constructor */
hui.ui.Buttons = function(o) {
	this.name = o.name;
	this.element = hui.get(o.element);
	this.body = hui.firstByClass(this.element,'hui_buttons_body');
	hui.ui.extend(this);
}

hui.ui.Buttons.create = function(o) {
	o = hui.override({top:0},o);
	var e = o.element = hui.build('div',{'class':'hui_buttons'});
	if (o.align=='right') {
		hui.addClass(e,'hui_buttons_right');
	}
	if (o.align=='center') {
		hui.addClass(e,'hui_buttons_center');
	}
	if (o.top>0) {
		e.style.paddingTop=o.top+'px';
	}
	hui.build('div',{'class':'hui_buttons_body',parent:e});
	return new hui.ui.Buttons(o);
}

hui.ui.Buttons.prototype = {
	add : function(widget) {
		this.body.appendChild(widget.element);
		return this;
	}
}

/* EOF */