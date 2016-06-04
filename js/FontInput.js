/**
 * A component for font input
 * @constructor
 */
hui.ui.FontInput = function(options) {
	this.options = hui.override({value:null},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.button = hui.get.firstByClass(this.element,'hui_fontinput');
	this.dropdown = new hui.ui.DropDown({
		element : hui.get.firstByClass(this.element,'hui_dropdown'),
		items : [{text:'',value:''}].concat(hui.ui.FontPicker.fonts),
		listener : this
	});
	this.value = null;
	hui.ui.extend(this);
	this.setValue(this.options.value);
	this._addBehavior();
}

hui.ui.FontInput.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('span',{'class':'hui_colorinput',html:'<span class="hui_field_top"><span><span></span></span></span><span class="hui_field_middle"><span class="hui_field_middle"><span class="hui_field_content"><span class="hui_field_singleline"><input type="text" value=""/></span></span></span></span><span class="hui_field_bottom"><span><span></span></span></span><a tabindex="-1" class="hui_colorinput" href="javascript://"></a>'});
		
	return new hui.ui.ColorInput(options);
}

hui.ui.FontInput.prototype = {
	_addBehavior : function() {
		hui.listen(this.button, 'click',this._onButtonClick.bind(this));
	},
	_syncInput : function() {
		this.dropdown.setValue(this.value);
	},
	_fireChange : function() {
		hui.ui.callAncestors(this,'childValueChanged',this.value);
		this.fire('valueChanged',this.value);
	},
	_onBlur : function() {
		hui.Color.parse(this.value);
	},
	_onButtonClick : function() {
		if (hui.window.getViewHeight()<200) {
			this.fire('clickPicker',this.value)		
			return; // TODO: mini picker
		}
		if (!this.panel) {
			this.panel = hui.ui.BoundPanel.create({modal:true,variant:'light'});
			this.picker = hui.ui.FontPicker.create();
			this.picker.listen(this);
			this.panel.add(this.picker);
		}
		this.panel.position(this.button);
		this.panel.show();
	},
	/** @private */
	$select : function(font) {
		this.panel.hide();
		this.setValue(font.value);
		this._fireChange();
	},
	$valueChanged : function(value) {
		this.setValue(value);
		this._fireChange();
	},
	
	// Public...
	
	getValue : function() {
		return this.value;
	},
	setValue : function(value) {
		this.value = value;
		this._syncInput();
		this.button.style.fontFamily = value;
	},
	focus : function() {
		try {
			this.input.focus();
		} catch (e) {}		
	},
	reset : function() {
		this.setValue('');
	},
    destroy : function() {
        hui.dom.remove(this.element);
        if (this.panel) {
            this.panel.destroy();
            this.picker.destroy();
        }
    }
}

/* EOF */