/////////////////////////// Number /////////////////////////

/**
 * A number field
 * @constructor
 */
hui.ui.NumberField = function(o) {
	this.options = hui.override({min:0,max:10000,value:null,decimals:0,allowNull:false},o);	
	this.name = o.name;
	var e = this.element = hui.get(o.element);
	this.input = hui.get.firstByTag(e,'input');
	this.up = hui.get.firstByClass(e,'hui_numberfield_up');
	this.down = hui.get.firstByClass(e,'hui_numberfield_down');
	this.value = parseInt(this.options.value,10);
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
		hui.listen(this.input,'focus',function() {hui.cls.add(e,'hui_numberfield_focused')});
		hui.listen(this.input,'blur',this._onBlur.bind(this));
		hui.listen(this.input,'keyup',this._onKey.bind(this));
		hui.listen(this.up,'mousedown',this.upEvent.bind(this));
		//hui.listen(this.up,'dblclick',this.upEvent.bind(this));
		hui.listen(this.down,'mousedown',this.downEvent.bind(this));
		//hui.listen(this.down,'dblclick',this.upEvent.bind(this));
	},
	_onBlur : function() {
		hui.cls.remove(this.element,'hui_numberfield_focused');
		this.updateField();
	},
	_onKey : function(e) {
		e = e || window.event;
		if (e.keyCode==hui.KEY_UP) {
			hui.stop(e);
			this.upEvent();
		} else if (e.keyCode==hui.KEY_DOWN) {
			this.downEvent();
		} else {
			var parsed = parseInt(this.input.value,10);
			if (!isNaN(parsed)) {
				this.setLocalValue(parsed,true);
			} else {
				this.setLocalValue(null,true);
			}
		}
	},
	/** @private */
	downEvent : function(e) {
		hui.stop(e);
		if (this.value===null) {
			this.setLocalValue(this.options.min,true);
		} else {
			this.setLocalValue(this.value-1,true);
		}
		this.updateField();
	},
	/** @private */
	upEvent : function(e) {
		hui.stop(e);
		this.setLocalValue(this.value+1,true);
		this.updateField();
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
		value = parseInt(value,10);
		if (!isNaN(value)) {
			this.setLocalValue(value,false);
		}
		this.updateField();
	},
	/** @private */
	updateField : function() {
		this.input.value = this.value===null || this.value===undefined ? '' : this.value;
	},
	/** @private */
	setLocalValue : function(value,fire) {
		var orig = this.value;
		if (value===null || value===undefined && this.options.allowNull) {
			this.value = null;
		} else {
			this.value = Math.min(Math.max(value,this.options.min),this.options.max);
		}
		if (fire && orig!==this.value) {
			hui.ui.callAncestors(this,'childValueChanged',this.value);
			this.fire('valueChanged',this.value);
		}
	},
	/** Resets the field */
	reset : function() {
		if (this.options.allowNull) {
			this.value = null;
		} else {
			this.value = Math.min(Math.max(0,this.options.min),this.options.max);
		}
		this.updateField();
	}
}