/////////////////////////// Color input /////////////////////////

/**
 * A component for color input
 * @constructor
 */
hui.ui.ColorInput = function(options) {
	this.options = hui.override({value:null},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.button = hui.get.firstByTag('a',this.element);
	this.value = this.options.value;
	hui.ui.extend(this);
	this.setValue(this.value);
	this._addBehavior();
}

hui.ui.ColorInput.prototype = {
	_addBehavior : function() {
		alert(this.button)
	},
	
	getValue : function() {
		return this.value;
	},
	setValue : function(value) {
		this.value = value;
	},
	focus : function() {
		try {
			this.input.focus();
		} catch (e) {}		
	},
	reset : function() {
		this.setValue('');
	}
	
}

/* EOF */