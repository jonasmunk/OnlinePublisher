/**
 * @constructor
 */
hui.ui.Radiobuttons = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.name = options.name;
	this.radios = [];
	this.value = options.value;
	this.defaultValue = this.value;
	this.enabled = true;
	hui.ui.extend(this);
}

hui.ui.Radiobuttons.prototype = {
/*	click : function() {
		this.value = !this.value;
		this.updateUI();
	},*/
	/** @private */
	updateUI : function() {
		for (var i=0; i < this.radios.length; i++) {
			var radio = this.radios[i];
			hui.cls.set(hui.get(radio.id),'hui_radiobutton_selected',radio.value==this.value);
		};
	},
	setValue : function(value) {
		this.value = value;
		this.updateUI();
	},
	getValue : function() {
		if (!this.enabled) {
			return null;
		}
		return this.value;
	},
	reset : function() {
		this.setValue(this.defaultValue);
	},
	setEnabled : function(enabled) {
		this.enabled = enabled == true;
		hui.cls.set(this.element,'hui_radiobuttons_disabled',!this.enabled);
	},
	registerRadiobutton : function(radio) {
		this.radios.push(radio);
		var element = hui.get(radio.id);
		var self = this;
		element.onclick = function() {
			if (!self.enabled) {
				return;
			}
			self.setValue(radio.value);
			self.fire('valueChanged',radio.value);
			hui.ui.callAncestors(self,'childValueChanged',radio.value);
		}
	}
}