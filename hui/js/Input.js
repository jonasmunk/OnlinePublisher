/** @constructor */
hui.ui.Input = function(options) {
	this.options = hui.override({placeholderElement:null,validator:null},options);
	var e = this.element = hui.get(options.element);
	this.element.setAttribute('autocomplete','off');
	this.value = this._validate(this.element.value);
	this.isPassword = this.element.type=='password';
	this.name = options.name;
	hui.ui.extend(this);
	this._addBehavior();
	if (this.options.placeholderElement && this.value!='') {
		hui.ui.fadeOut(this.options.placeholderElement,0);
	}
	this._checkPlaceholder();
	try { // IE hack
		if (e==document.activeElement) {
			this._focused();
		}
	} catch (e) {}
}

hui.ui.Input.prototype = {
	_addBehavior : function() {
		var e = this.element,
			p = this.options.placeholderElement;
		hui.listen(e,'keyup',this.keyDidStrike.bind(this));
		hui.listen(e,'blur',this.onBlur.bind(this));
		if (p) {
			hui.listen(e,'focus',this._focused.bind(this));
			hui.listen(e,'blur',this._checkPlaceholder.bind(this));
			if (p) {
				p.style.cursor='text';
				hui.listen(p,'mousedown',this.focus.bind(this));
				hui.listen(p,'click',this.focus.bind(this));
			}
		}
		if (e.type=='submit') {
			hui.listen(e,'click',function(event) {
				this.fire('click',event);
			}.bind(this));
		}
	},
	_focused : function() {
		var e = this.element,p = this.options.placeholderElement;
		if (p && e.value=='') {
			hui.ui.fadeOut(p,0);
		}
	},
	/** @private */
	_validate : function(value) {
		var validator = this.options.validator;
		var result;
		if (validator) {
			result = validator.validate(value);
			hui.setClass(this.element,'hui_invalid',!result.valid);
			return result.value;
		}
		return value;
	},
	_checkPlaceholder : function() {
		if (this.options.placeholderElement && this.value=='') {
			hui.ui.fadeIn(this.options.placeholderElement,200);
		}
		if (this.isPassword && !hui.browser.msie) {
			this.element.type='password';
		}
	},
	/** @private */
	keyDidStrike : function() {
		if (this.value!==this.element.value) {
			var newValue = this._validate(this.element.value);
			var changed = newValue!==this.value;
			this.value = newValue;
			if (changed) {
				this.fire('valueChanged',this.value);
			}
		}
	},
	/** @private */
	onBlur : function() {
		hui.removeClass(this.element,'hui_invalid');
		this.element.value = this.value || '';
	},
	getValue : function() {
		return this.value;
	},
	setValue : function(value) {
		if (value===undefined || value===null) {
			value='';
		}
		this.element.value = value;
		this.value = this._validate(value);
	},
	isEmpty : function() {
		return this.value=='';
	},
	isBlank : function() {
		return hui.isBlank(this.value);
	},
	focus : function() {
		this.element.focus();
	},
	setError : function(error) {
		var isError = error ? true : false;
		hui.setClass(this.element,'hui_field_error',isError);
		if (typeof(error) == 'string') {
			hui.ui.showToolTip({text:error,element:this.element,key:this.name});
		}
		if (!isError) {
			hui.ui.hideToolTip({key:this.name});
		}
	}
};

/* EOF */