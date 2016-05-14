/////////////////////////// Number /////////////////////////

/**
 * A number field
 * @constructor
 */
hui.ui.NumberField = function(o) {
	this.options = hui.override({min:0,max:undefined,value:null,tickSize:1,decimals:0,allowNull:false},o);	
	this.name = o.name;
	var e = this.element = hui.get(o.element);
	this.input = hui.get.firstByTag(e,'input');
	this.up = hui.get.firstByClass(e,'hui_numberfield_up');
	this.down = hui.get.firstByClass(e,'hui_numberfield_down');
	if (hui.isString(this.options.value)) {
		this.value = parseInt(this.options.value,10);
	} else {
		this.value = this.options.value;
	}
	if (isNaN(this.value)) {
		this.value = null;
	}
	hui.ui.extend(this);
	this._addBehavior();
}

/** Creates a new number field */
hui.ui.NumberField.create = function(o) {
	o.element = hui.build('span',{
		'class':'hui_numberfield',
		html:'<span><span><input type="text" value="'+(o.value!==undefined ? o.value : '0')+'"/><a class="hui_numberfield_up"></a><a class="hui_numberfield_down"></a></span></span>'
	});
	return new hui.ui.NumberField(o);
}

hui.ui.NumberField.prototype = {
	_addBehavior : function() {
		var e = this.element;
		hui.listen(this.input,'focus',this._onFocus.bind(this));
		hui.listen(this.input,'blur',this._onBlur.bind(this));
		hui.listen(this.input,'keyup',this._onKey.bind(this));
		hui.listen(this.up,'mousedown',this.upEvent.bind(this));
		//hui.listen(this.up,'dblclick',this.upEvent.bind(this));
		hui.listen(this.down,'mousedown',this.downEvent.bind(this));
		//hui.listen(this.down,'dblclick',this.upEvent.bind(this));
	},
	_onBlur : function() {
		hui.cls.remove(this.element,'hui_numberfield_focused');
		this._updateField();
		if (this.sliderPanel) {
			this.sliderPanel.hide();
		}
	},
	_onFocus : function() {
		hui.cls.add(this.element,'hui_numberfield_focused');
		this._showSlider();
		this._updateSlider();
	},
	_onKey : function(e) {
		e = e || window.event;
		if (e.keyCode==hui.KEY_UP) {
			hui.stop(e);
			this.upEvent();
		} else if (e.keyCode==hui.KEY_DOWN) {
			this.downEvent();
		} else {
			var parsed = parseFloat(this.input.value,10);
			if (!isNaN(parsed)) {
				this._setLocalValue(parsed,true);
			} else {
				this._setLocalValue(null,true);
			}
		}
	},
	/** @private */
	downEvent : function(e) {
		hui.stop(e);
		if (this.value===null) {
			this._setLocalValue(this.options.min,true);
		} else {
			this._setLocalValue(this.value-this.options.tickSize,true);
		}
		this._updateField();
	},
	/** @private */
	upEvent : function(e) {
		hui.stop(e);
		this._setLocalValue(this.value+this.options.tickSize,true);
		this._updateField();
	},
	/** Sets focus */
	focus : function() {
		try {
			this.input.focus();
		} catch (e) {}
	},
	/** Gets the value */
	getValue : function() {
		return this.value;
	},
	/** Gets the label */
	getLabel : function() {
		return this.options.label;
	},
	/** Sets the value */
	setValue : function(value) {
		if (value===null || value===undefined) {
			this._setLocalValue(null,false);
		} else {
			value = parseFloat(value,10);
			if (!isNaN(value)) {
				this._setLocalValue(value,false);
			}
		}
		this._updateField();
	},
	_updateField : function() {
		this.input.value = this.value===null || this.value===undefined ? '' : this.value;
	},
	_setLocalValue : function(value,fire) {
		var orig = this.value;
		if (value===null || value===undefined && this.options.allowNull) {
			this.value = null;
		} else {
			value = this._getValueWithinRange(value);
			this.value = this._round(value);
		}
		if (fire && orig!==this.value) {
			this.fireValueChange();
		}
		this._updateSlider();
	},
	_round : function(value) {
		if (this.options.decimals!==undefined) {
			var x = Math.pow(10,this.options.decimals);
			value = Math.round(value * x) / x;
		}
		return value;
	},
	/** Resets the field */
	reset : function() {
		if (this.options.allowNull) {
			this.value = null;
		} else {
			this.value = this._getValueWithinRange(0);
		}
		this._updateField();
	},
	_getValueWithinRange : function(value) {
		if (hui.isDefined(this.options.min)) {
			value = Math.max(value,this.options.min);
		}
		if (hui.isDefined(this.options.max)) {
			value = Math.min(value,this.options.max);
		}
		return value;
	},
	_onSliderChange : function(value) {
		var conv = this.options.min+(this.options.max-this.options.min)*value;
		this._setLocalValue(conv,true);
		this._updateField();
	},
	_showSlider : function() {
		if (this.options.min===undefined || this.options.max===undefined) {
			return;
		}
		if (!this.sliderPanel) {
			this.sliderPanel = hui.ui.BoundPanel.create({variant:'light'});
			this.slider = hui.ui.Slider.create({width:200})
			this.slider.element.style.margin='0 3px';
			this.slider.listen({$valueChanged : this._onSliderChange.bind(this)})
			this.sliderPanel.add(this.slider);
		}
		this.sliderPanel.position({element:this.element,position:'vertical'});
		this.sliderPanel.show();
	},
	_updateSlider : function() {
		if (this.slider) {
			this.slider.setValue((this.value -this.options.min) / (this.options.max-this.options.min))
		}
	},
	/** @private */
	$$parentMoved : function() {
		if (this.sliderPanel && this.sliderPanel.isVisible()) {
			this.sliderPanel.position({element:this.element,position:'vertical'});
			this.sliderPanel.show();
		}
	}
}