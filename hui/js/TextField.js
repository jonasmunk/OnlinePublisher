/** @constructor */
hui.ui.TextField = function(options) {
	this.options = hui.override({placeholderElement:null,validator:null},options);
	var e = this.element = hui.get(options.element);
	this.element.setAttribute('autocomplete','off');
	this.value = this.validate(this.element.value);
	this.isPassword = this.element.type=='password';
	this.name = options.name;
	hui.ui.extend(this);
	this.addBehavior();
	if (this.options.placeholderElement && this.value!='') {
		hui.ui.fadeOut(this.options.placeholderElement,0);
	}
	this.checkPlaceholder();
	try { // IE hack
		if (e==document.activeElement) {
			this.focused();
		}
	} catch (e) {}
}

hui.ui.TextField.prototype = {
	addBehavior : function() {
		var e = this.element;
		hui.listen(e,'keyup',this.keyDidStrike.bind(this));
		var p = this.options.placeholderElement;
		hui.listen(e,'blur',this.onBlur.bind(this));
		if (p) {
			hui.listen(e,'focus',this.focused.bind(this));
			hui.listen(e,'blur',this.checkPlaceholder.bind(this));
			if (p) {
				p.style.cursor='text';
				hui.listen(p,'mousedown',this.focus.bind(this));
				hui.listen(p,'click',this.focus.bind(this));
			}
		}
	},
	focused : function() {
		var e = this.element,p = this.options.placeholderElement;
		if (p && e.value=='') {
			hui.ui.fadeOut(p,0);
		}
	},
	/** @private */
	validate : function(value) {
		var validator = this.options.validator, result;
		if (validator) {
			result = validator.validate(value);
			hui.setClass(this.element,'in2igui_invalid',!result.valid);
			return result.value;
		}
		return value;
	},
	checkPlaceholder : function() {
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
			var newValue = this.validate(this.element.value);
			var changed = newValue!==this.value;
			this.value = newValue;
			if (changed) {
				this.fire('valueChanged',this.value);
			}
		}
	},
	/** @private */
	onBlur : function() {
		hui.removeClass(this.element,'in2igui_invalid');
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
		this.value = this.validate(value);
	},
	isEmpty : function() {
		return this.value=='';
	},
	isBlank : function() {
		return this.value.strip()=='';
	},
	focus : function() {
		this.element.focus();
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
	}
};

/* EOF */