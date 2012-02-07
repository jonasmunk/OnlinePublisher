/////////////////////////// Color field /////////////////////////

/**
 * A component for geo-location
 * @constructor
 */
hui.ui.ColorField = function(options) {
	this.options = hui.override({value:null},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.value = this.options.value;
	hui.ui.extend(this);
	this.setValue(this.value);
	this._addBehavior();
}

hui.ui.ColorField.prototype = {
	_addBehavior : function() {
		
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