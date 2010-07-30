
/** @constructor */
In2iGui.Toolbar = function(options) {
	this.element = $(options.element);
	this.name = options.name;
	In2iGui.extend(this);
}

In2iGui.Toolbar.create = function(options) {
	options = options || {};
	options.element = new Element('div',{'class':'in2igui_toolbar'});
	if (options.labels==false) {
		options.element.addClassName('in2igui_toolbar_nolabels');
	}
	return new In2iGui.Toolbar(options);
}

In2iGui.Toolbar.prototype = {
	add : function(widget) {
		this.element.appendChild(widget.getElement());
	},
	addDivider : function() {
		this.element.appendChild(new Element('span',{'class':'in2igui_divider'}));
	}
}



/////////////////////// Revealing toolbar ////////////////////////

/** @constructor */
In2iGui.RevealingToolbar = function(options) {
	this.element = $(options.element);
	this.name = options.name;
	In2iGui.extend(this);
}

In2iGui.RevealingToolbar.create = function(options) {
	options = options || {};
	options.element = new Element('div',{'class':'in2igui_revealing_toolbar'}).setStyle({'display':'none'});
	document.body.appendChild(options.element);
	var rev = new In2iGui.RevealingToolbar(options);
	var toolbar = In2iGui.Toolbar.create();
	rev.setToolbar(toolbar);
	return rev;
}

In2iGui.RevealingToolbar.prototype = {
	setToolbar : function(widget) {
		this.toolbar = widget;
		this.element.appendChild(widget.getElement());
	},
	getToolbar : function() {
		return this.toolbar;
	},
	show : function(instantly) {
		this.element.style.display='';
		n2i.ani(this.element,'height','58px',instantly ? 0 : 600,{ease:n2i.ease.slowFastSlow});
	},
	hide : function() {
		n2i.ani(this.element,'height','0px',500,{ease:n2i.ease.slowFastSlow,hideOnComplete:true});
	}
}



/////////////////////// Icon ///////////////////

/** @constructor */
In2iGui.Toolbar.Icon = function(options) {
	this.options = options;
	this.element = $(options.element);
	this.name = options.name;
	this.enabled = !this.element.hasClassName('in2igui_toolbar_icon_disabled');
	this.element.tabIndex=this.enabled ? 0 : -1;
	this.icon = this.element.select('.in2igui_icon')[0];
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Toolbar.Icon.create = function(options) {
	var element = options.element = new Element('a',{'class':'in2igui_toolbar_icon'});
	var icon = new Element('span',{'class':'in2igui_icon'}).setStyle({'backgroundImage':'url('+In2iGui.getIconUrl(options.icon,2)+')'});
	var inner = new Element('span',{'class':'in2igui_toolbar_inner_icon'});
	var innerest = new Element('span',{'class':'in2igui_toolbar_inner_icon'});
	element.insert(inner);
	inner.insert(innerest);
	var title = new Element('strong');
	title.innerHTML=options.title;
	if (options.overlay) {
		var overlay = new Element('span',{'class':'in2igui_icon_overlay'}).setStyle({'backgroundImage':'url('+In2iGui.getIconUrl('overlay/'+options.overlay,2)+')'});
		icon.insert(overlay);
	}
	innerest.insert(icon);
	innerest.insert(title);
	return new In2iGui.Toolbar.Icon(options);
}

In2iGui.Toolbar.Icon.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		this.element.onclick = function() {
			self.wasClicked();
		}
	},
	/** Sets wether the icon should be enabled */
	setEnabled : function(enabled) {
		this.enabled = enabled;
		this.element.tabIndex=enabled ? 0 : -1;
		this.element.setClassName('in2igui_toolbar_icon_disabled',!this.enabled);
	},
	/** Disables the icon */
	disable : function() {
		this.setEnabled(false);
	},
	/** Enables the icon */
	enable : function() {
		this.setEnabled(true);
	},
	/** Sets wether the icon should be selected */
	setSelected : function(selected) {
		this.element.setClassName('in2igui_toolbar_icon_selected',selected);
	},
	/** @private */
	wasClicked : function() {
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
		}
	},
	/** @private */
	fireClick : function() {
		In2iGui.callDelegates(this,'toolbarIconWasClicked');
		In2iGui.callDelegates(this,'click');
	}
}


/////////////////////// Search field ///////////////////////

/** @constructor */
In2iGui.Toolbar.SearchField = function(options) {
	this.options = options;
	this.element = $(options.element);
	this.name = options.name;
	this.field = this.element.select('input')[0];
	this.value = this.field.value;
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Toolbar.SearchField.create = function(options) {
	options = options || {};
	var e = options.element = new Element('div',{'class': options.adaptive ? 'in2igui_toolbar_search in2igui_toolbar_search_adaptive' : 'in2igui_toolbar_search'});
	e.update('<div class="in2igui_searchfield"><strong class="in2igui_searchfield_button"></strong><div><div><input type="text"/></div></div></div>'+
		'<span>'+options.title+'</span>');
	return new In2iGui.Toolbar.SearchField(options);
}

In2iGui.Toolbar.SearchField.prototype = {
	getValue : function() {
		return this.field.value;
	},
	addBehavior : function() {
		var self = this;
		this.field.onkeyup = function() {
			self.fieldChanged();
		}
		if (!this.options.adaptive) {
			this.field.onfocus = function() {
				n2i.ani(this,'width','120px',500,{ease:n2i.ease.slowFastSlow});
			}
			this.field.onblur = function() {
				n2i.ani(this,'width','80px',500,{ease:n2i.ease.slowFastSlow});
			}
		}
	},
	fieldChanged : function() {
		if (this.field.value!=this.value) {
			this.value=this.field.value;
			In2iGui.callDelegates(this,'valueChanged');
			In2iGui.firePropertyChange(this,'value',this.value);
		}
	}
}


//////////////////////// Badge ///////////////////////

/** @constructor */
In2iGui.Toolbar.Badge = function(options) {
	this.element = $(options.element);
	this.name = options.name;
	this.label = this.element.select('strong')[0];
	this.text = this.element.select('span')[0];
	In2iGui.extend(this);
}

In2iGui.Toolbar.Badge.prototype = {
	setLabel : function(str) {
		this.label.update(str);
	},
	setText : function(str) {
		this.text.update(str);
	}
}

/* EOF */