/////////////////////////// Style length /////////////////////////

/**
 * A date and time field
 * @constructor
 */
hui.ui.StyleLength = function(o) {
	this.options = hui.override({value:null,min:0,max:1000,units:['px','pt','em','%'],defaultUnit:'px',allowNull:false},o);	
	this.name = o.name;
	var e = this.element = hui.get(o.element);
	this.input = hui.get.firstByTag(e,'input');
	var as = e.getElementsByTagName('a');
	this.up = as[0];
	this.down = as[1];
	this.value = this.parseValue(this.options.value);
	hui.ui.extend(this);
	this.addBehavior();
}

hui.ui.StyleLength.prototype = {
	/** @private */
	addBehavior : function() {
		var e = this.element;
		hui.listen(this.input,'focus',function() {hui.cls.add(e,'hui_numberfield_focused')});
		hui.listen(this.input,'blur',this.blurEvent.bind(this));
		hui.listen(this.input,'keyup',this.keyEvent.bind(this));
		hui.listen(this.up,'mousedown',this.upEvent.bind(this));
		hui.listen(this.down,'mousedown',this.downEvent.bind(this));
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
	/** @private */
	blurEvent : function() {
		hui.cls.remove(this.element,'hui_numberfield_focused');
		this.updateInput();
	},
	/** @private */
	keyEvent : function(e) {
		e = e || window.event;
		if (e.keyCode==hui.KEY_UP) {
			hui.stop(e);
			this.upEvent();
		} else if (e.keyCode==hui.KEY_DOWN) {
			this.downEvent();
		} else {
			this.checkAndSetValue(this.parseValue(this.input.value));
		}
	},
	/** @private */
	updateInput : function() {
		this.input.value = this.getValue();
	},
	/** @private */
	checkAndSetValue : function(value) {
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
	/** @private */
	downEvent : function() {
		if (this.value) {
			this.checkAndSetValue({number:Math.max(this.options.min,this.value.number-1),unit:this.value.unit});
		} else {
			this.checkAndSetValue({number:this.options.min,unit:this.options.defaultUnit});
		}
		this.updateInput();
	},
	/** @private */
	upEvent : function() {
		if (this.value) {
			this.checkAndSetValue({number:Math.min(this.options.max,this.value.number+1),unit:this.value.unit});
		} else {
			this.checkAndSetValue({number:this.options.min+1,unit:this.options.defaultUnit});
		}
		this.updateInput();
	},
	
	// Public
	
	getValue : function() {
		return this.value ? this.value.number+this.value.unit : '';
	},
	setValue : function(value) {
		this.value = this.parseValue(value);
		this.updateInput();
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