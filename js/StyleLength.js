/////////////////////////// Style length /////////////////////////

/**
 * A date and time field
 * @constructor
 */
hui.ui.StyleLength = function(o) {
	this.options = hui.override({value:null,min:0,max:1000,units:['px','pt','em','%'],initialValue:null,defaultUnit:'px',allowNull:false},o);	
	this.name = o.name;
	var e = this.element = hui.get(o.element);
	this.input = hui.get.firstByTag(e,'input');
	var as = e.getElementsByTagName('a');
	this.up = as[0];
	this.down = as[1];
	this.value = this.parseValue(this.options.value);
	hui.ui.extend(this);
	this._addBehavior();
}

hui.ui.StyleLength.create = function(options) {
	options.element = hui.build('span',{'class':'hui_style_length hui_numberfield',html:'<span><span><input type="text"/><a class="hui_numberfield_up"></a><a class="hui_numberfield_down"></a></span></span>'})
	return new hui.ui.StyleLength(options);
}

hui.ui.StyleLength.prototype = {
	/** @private */
	_addBehavior : function() {
		var e = this.element;
		hui.listen(this.input,'focus',function() {hui.cls.add(e,'hui_numberfield_focused')});
		hui.listen(this.input,'blur',this._onBlur.bind(this));
		hui.listen(this.input,'keyup',this.keyEvent.bind(this));
		hui.listen(this.up,'mousedown',this._upEvent.bind(this));
		hui.listen(this.down,'mousedown',this._downEvent.bind(this));
	},
	/** @private */
	parseValue : function(value) {
		if (value===null || value===undefined) {
			return null;
		}
		var num = parseFloat(value,10);
		if (isNaN(num)) {
			return null;
		}
		var parsed = {number: num, unit:this.options.defaultUnit};
		for (var i=0; i < this.options.units.length; i++) {
			var unit = this.options.units[i];
			if (value.indexOf(unit)!=-1) {
				parsed.unit = unit;
				break;
			}
		};
		parsed.number = Math.max(this.options.min,Math.min(this.options.max,parsed.number));
		return parsed;
	},
	_onBlur : function() {
		hui.cls.remove(this.element,'hui_numberfield_focused');
		this._updateInput();
	},
	/** @private */
	keyEvent : function(e) {
		e = e || window.event;
		if (e.keyCode==hui.KEY_UP) {
			hui.stop(e);
			this._upEvent();
		} else if (e.keyCode==hui.KEY_DOWN) {
			this._downEvent();
		} else {
			this._checkAndSetValue(this.parseValue(this.input.value));
		}
	},
	/** @private */
	_updateInput : function() {
		this.input.value = this.getValue();
	},
	_checkAndSetValue : function(value) {
		var old = this.value;
		var changed = false;
		if (old===null && value===null) {
			// nothing
		} else if (old!=null && value!=null && old.number===value.number && old.unit===value.unit) {
			// nothing
		} else {
			changed = true;
		}
		this.value = value;
		if (changed) {
			hui.ui.callAncestors(this,'childValueChanged',this.input.value);
			this.fire('valueChanged',this.getValue());
		}
	},
	_setInitialValue : function() {
		if (!this.value && this.options.initialValue) {
			this.setValue(this.options.initialValue);
		}
	},
	_downEvent : function() {
		this._setInitialValue();
		if (this.value) {
			this._checkAndSetValue({number:Math.max(this.options.min,this.value.number-1),unit:this.value.unit});
		} else {
			this._checkAndSetValue({number:this.options.min,unit:this.options.defaultUnit});
		}
		this._updateInput();
	},
	_upEvent : function() {
		this._setInitialValue();
		if (this.value) {
			this._checkAndSetValue({number:Math.min(this.options.max,this.value.number+1),unit:this.value.unit});
		} else {
			this._checkAndSetValue({number:this.options.min+1,unit:this.options.defaultUnit});
		}
		this._updateInput();
	},
	
	// Public
	
	setInitialValue : function(value) {
		this.options.initialValue = value;
	},
	getValue : function() {
		return this.value ? this.value.number+this.value.unit : '';
	},
	setValue : function(value) {
		this.value = this.parseValue(value);
		this._updateInput();
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