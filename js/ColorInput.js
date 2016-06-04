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
	this.input.listen({$valueChanged:this._onInputChange.bind(this)})
	this.value = null;
	hui.ui.extend(this);
	this.setValue(this.options.value);
	this._addBehavior();
}

hui.ui.ColorInput.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('span',{'class':'hui_colorinput',html:'<span class="hui_field_top"><span><span></span></span></span><span class="hui_field_middle"><span class="hui_field_middle"><span class="hui_field_content"><span class="hui_field_singleline"><input type="text" value=""/></span></span></span></span><span class="hui_field_bottom"><span><span></span></span></span><a tabindex="-1" class="hui_colorinput" href="javascript://"></a>'});
		
	return new hui.ui.ColorInput(options);
}

hui.ui.ColorInput.prototype = {
	_addBehavior : function() {
		hui.ui.addFocusClass({element:this.input.element,classElement:this.element,'class':'hui_field_focused'});
		hui.listen(this.button, 'click',this._onButtonClick.bind(this));
	},
	_syncInput : function() {
		this.input.setValue(this.value);
	},
	_syncColorButton : function() {		
		this.button.innerHTML = this.value ? '' : '?';
		this.button.style.backgroundColor = this.value ? this.value : '';	
	},
	_onInputChange : function(value) {
		var changed = value!=this.value;
		this.value = value;
		this._syncColorButton();
		if (changed) {
			this._fireChange();
		}
	},
	_fireChange : function() {
		hui.ui.callAncestors(this,'childValueChanged',this.value);
		this.fire('valueChanged',this.value)		
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
		this._fireChange();
	},
	
	// Public...
	
	getValue : function() {
		return this.value;
	},
	setValue : function(value) {
		this.value = new hui.Color(value).toHex();
		this._syncInput();
		this._syncColorButton();
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
        }
    }
}

/* EOF */