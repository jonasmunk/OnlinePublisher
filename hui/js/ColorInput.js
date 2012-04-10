/////////////////////////// Color input /////////////////////////

/**
 * A component for color input
 * @constructor
 */
hui.ui.ColorInput = function(options) {
	this.options = hui.override({value:null},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.button = hui.get.firstByTag(this.element,'a');
	this.input = new hui.ui.Input({
		element : hui.get.firstByTag(this.element,'input'),
		validator : {
			validate : function(value) {
				var color = new hui.Color(value);
				return {valid:true,value:color.toHex()};
			}
		}
	});
	this.value = this.options.value;
	hui.ui.extend(this);
	this._syncValue();
	this._addBehavior();
}

hui.ui.ColorInput.prototype = {
	_addBehavior : function() {
		hui.listen(this.button, 'click',this._onButtonClick.bind(this));
	},
	_syncValue : function() {
		this.button.style.backgroundColor = this.value;
		this.input.setValue(this.value);
	},
	getValue : function() {
		return this.value;
	},
	setValue : function(value) {
		this.value = value;
		this._syncValue();
	},
	focus : function() {
		try {
			this.input.focus();
		} catch (e) {}		
	},
	reset : function() {
		this.setValue('');
	},
	_onBlur : function() {
		hui.Color.parse(this.value);
	},
	_onButtonClick : function() {
		if (!this.panel) {
			this.panel = hui.ui.BoundPanel.create({modal:true});
			this.picker = hui.ui.ColorPicker.create();
			this.picker.listen(this);
			this.panel.add(this.picker);
		}
		this.panel.position(this.button);
		this.panel.show();
	},
	/** @private */
	$colorWasSelected : function(color) {
		this.panel.hide();
		this.setValue(color);
	}
}

/* EOF */