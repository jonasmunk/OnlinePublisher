/////////////////////////// Date time /////////////////////////

/**
 * A date and time field
 * @constructor
 */
hui.ui.DateTimeField = function(o) {
	this.inputFormats = ['d-m-Y','d/m-Y','d/m/Y','d-m-Y H:i:s','d/m-Y H:i:s','d/m/Y H:i:s','d-m-Y H:i','d/m-Y H:i','d/m/Y H:i','d-m-Y H','d/m-Y H','d/m/Y H','d-m','d/m','d','Y','m-d-Y','m-d','m/d'];
	this.outputFormat = 'd-m-Y H:i:s';
	this.name = o.name;
	this.element = hui.get(o.element);
	this.input = hui.get.firstByTag(this.element,'input');
	this.options = hui.override({returnType:null,label:null,allowNull:true,value:null},o);
	this.value = this.options.value;
	hui.ui.extend(this);
	this._addBehavior();
	this._updateUI();
}

hui.ui.DateTimeField.create = function(options) {
	var node = hui.build('span',{'class':'hui_field_singleline'});
	hui.build('input',{'class':'hui_formula_text',parent:node});
	options.element = hui.ui.wrapInField(node);
	return new hui.ui.DateTimeField(options);
}

hui.ui.DateTimeField.prototype = {
	_addBehavior : function() {
		hui.ui.addFocusClass({element:this.input,classElement:this.element,'class':'hui_field_focused'});
		hui.listen(this.input,'blur',this._onBlur.bind(this));
		hui.listen(this.input,'focus',this._onFocus.bind(this));
	},
	focus : function() {
		try {this.input.focus();} catch (ignore) {}
	},
	reset : function() {
		this.setValue('');
	},
	setValue : function(value) {
		if (!value) {
			this.value = null;
		} else if (value.constructor==Date) {
			this.value = value;
		} else {
			this.value = new Date();
			this.value.setTime(parseInt(value)*1000);
		}
		this._updateUI();
	},
	_check : function() {
		var str = this.input.value;
		var parsed = null;
		for (var i=0; i < this.inputFormats.length && parsed==null; i++) {
			parsed = Date.parseDate(str,this.inputFormats[i]);
		};
		if (this.options.allowNull || parsed!=null) {
			this.value = parsed;
		}
		this._updateUI();
	},
	getValue : function() {
		if (this.value!=null && this.options.returnType=='seconds') {
			return Math.round(this.value.getTime()/1000);
		}
		return this.value;
	},
	getElement : function() {
		return this.element;
	},
	getLabel : function() {
		return this.options.label;
	},
	_updateUI : function() {
		if (this.value) {
			this.input.value = this.value.dateFormat(this.outputFormat);
		} else {
			this.input.value = ''
		}
	},
	_onBlur : function() {
		this._check();
		if (this.panel) {
			this.panel.hide();
		}
	},
	_onFocus : function() {
		this._showPanel();
	},
	_showPanel : function() {
		if (!this.panel) {
			this.panel = hui.ui.BoundPanel.create({variant:'light'});
			var b = hui.ui.Buttons.create();
			b.add(hui.ui.Button.create({
				text : 'Idag',
				small : true,
				variant : 'paper',
				listener : {$click:this.goToday.bind(this)}
			}));
			b.add(hui.ui.Button.create({
				text : '12:00',
				small : true,
				variant : 'paper',
				listener : {$click:function() {this.setHour(12)}.bind(this)}
			}));
			b.add(hui.ui.Button.create({
				text : '00:00',
				small : true,
				variant : 'paper',
				listener : {$click:function() {this.setHour(0)}.bind(this)}
			}));
			this.panel.add(b)
		}
		this.panel.position({element:this.element,position:'vertical'});
		this.panel.show();
	},
	goToday : function() {
		this.setValue(new Date());
	},
	setHour : function(hours) {
		var newDate = this.value==null ? new Date() : new Date(this.value.getTime());
		newDate.setMilliseconds(0);
		newDate.setSeconds(0);
		newDate.setMinutes(0);
		newDate.setHours(hours);
		this.setValue(newDate);
	}
}