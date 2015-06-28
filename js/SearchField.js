/** @constructor */
hui.ui.SearchField = function(options) {
	this.options = hui.override({expandedWidth:null},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	this.field = hui.get.firstByTag(this.element,'input');
	this.value = this.field.value;
	this.adaptive = hui.cls.has(this.element,'hui_searchfield_adaptive');
	this.initialWidth = null;
	hui.ui.extend(this);
	this._addBehavior();

	if (this.value!=='') {
		this._updateClass()
	}
}

hui.ui.SearchField.create = function(options) {
	options = options || {};
	
	options.element = hui.build('span',{
		'class' : options.adaptive ? 'hui_searchfield hui_searchfield_adaptive' : 'hui_searchfield',
		html : '<em class="hui_searchfield_placeholder"></em><a href="javascript:void(0);" class="hui_searchfield_reset"></a><span><span><input type="text"/></span></span>'
	});
	return new hui.ui.SearchField(options);
}

hui.ui.SearchField.prototype = {
	_addBehavior : function() {
		var self = this;
		hui.listen(this.field,'keyup',this._onKeyUp.bind(this));
		var reset = hui.get.firstByTag(this.element,'a');
		reset.tabIndex=-1;
		if (!hui.browser.ipad) {
			var focus = function() {self.field.focus();self.field.select()};
			hui.listen(this.element,'mousedown',focus);
			hui.listen(this.element,'mouseup',focus);
			hui.listen(hui.get.firstByTag(this.element,'em'),'mousedown',focus);
		} else {
			var focus = function() {self.field.focus();};
			hui.listen(hui.get.firstByTag(this.element,'em'),'click',focus);
		}
		hui.listen(reset,'mousedown',function(e) {
			hui.stop(e);
			self.reset();
			focus()
		});
		hui.listen(this.field,'focus',this._onFocus.bind(this));
		hui.listen(this.field,'blur',this._onBlur.bind(this));
	},
	_onFocus : function() {
		hui.ui.setKeyboardTarget(this);
		this.focused = true;
		this._updateClass();
		if (this.options.expandedWidth > 0) {
			if (this.initialWidth==null) {
				this.initialWidth = parseInt(hui.style.get(this.element,'width'));
			}
			hui.animate(this.element,'width',this.options.expandedWidth+'px',500,{ease:hui.ease.slowFastSlow});
		}
	},
	_onBlur : function() {
		hui.ui.setKeyboardTarget(null);
		this.focused = false;
		this._updateClass();
		if (this.initialWidth!==null) {
			hui.animate(this.element,'width',this.initialWidth+'px',500,{ease:hui.ease.slowFastSlow,delay:100});
		}
	},
	_onKeyUp : function(e) {
		this._fieldChanged();
		if (e.keyCode===hui.KEY_RETURN) {
			this.fire('submit');
		}
	},
	focus : function() {
		this.field.focus();
	},
	setValue : function(value) {
		this.field.value = value===undefined || value===null ? '' : value;
		this._fieldChanged();
	},
	getValue : function() {
		return this.field.value;
	},
	isEmpty : function() {
		return this.field.value=='';
	},
	isBlank : function() {
		return hui.isBlank(this.field.value);
	},
	reset : function() {
		this.field.value='';
		this._fieldChanged();
	},
	/** @private */
	_updateClass : function() {
		var className = 'hui_searchfield';
		if (this.adaptive) {
			className+=' hui_searchfield_adaptive';
		}
		if (this.focused && this.value!='') {
			className+=' hui_searchfield_focus_dirty';
		} else if (this.focused) {
			className+=' hui_searchfield_focus';
		} else if (this.value!='') {
			className+=' hui_searchfield_dirty';
		}
		this.element.className=className;
	},
	_fieldChanged : function() {
		if (this.field.value!=this.value) {
			this.value = this.field.value;
			this._updateClass();
			this.fireValueChange();
		}
	}
}

if (window.define) {
	define('hui.ui.SearchField',hui.ui.SearchField);
}
/* EOF */