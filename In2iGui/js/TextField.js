/** @constructor */
In2iGui.TextField = function(options) {
	this.options = n2i.override({placeholderElement:null,validator:null},options);
	var e = this.element = n2i.get(options.element);
	this.element.setAttribute('autocomplete','off');
	this.value = this.validate(this.element.value);
	this.isPassword = this.element.type=='password';
	this.name = options.name;
	In2iGui.extend(this);
	this.addBehavior();
	if (this.options.placeholderElement && this.value!='') {
		In2iGui.fadeOut(this.options.placeholderElement,0);
	}
	this.checkPlaceholder();
	try { // IE hack
		if (e==document.activeElement) {
			this.focused();
		}
	} catch (e) {}
}

In2iGui.TextField.prototype = {
	addBehavior : function() {
		var e = this.element;
		n2i.listen(e,'keyup',this.keyDidStrike.bind(this));
		var p = this.options.placeholderElement;
		n2i.listen(e,'blur',this.onBlur.bind(this));
		if (p) {
			n2i.listen(e,'focus',this.focused.bind(this));
			n2i.listen(e,'blur',this.checkPlaceholder.bind(this));
			if (p) {
				p.style.cursor='text';
				n2i.listen(p,'mousedown',this.focus.bind(this));
				n2i.listen(p,'click',this.focus.bind(this));
			}
		}
	},
	focused : function() {
		var e = this.element,p = this.options.placeholderElement;
		if (p && e.value=='') {
			In2iGui.fadeOut(p,0);
		}
	},
	/** @private */
	validate : function(value) {
		var validator = this.options.validator, result;
		if (validator) {
			result = validator.validate(value);
			n2i.setClass(this.element,'in2igui_invalid',!result.valid);
			return result.value;
		}
		return value;
	},
	checkPlaceholder : function() {
		if (this.options.placeholderElement && this.value=='') {
			In2iGui.fadeIn(this.options.placeholderElement,200);
		}
		if (this.isPassword && !n2i.browser.msie) {
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
		n2i.removeClass(this.element,'in2igui_invalid');
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
		n2i.setClass(this.element,'in2igui_field_error',isError);
		if (typeof(error) == 'string') {
			In2iGui.showToolTip({text:error,element:this.element,key:this.name});
		}
		if (!isError) {
			In2iGui.hideToolTip({key:this.name});
		}
	}
};

/* EOF */