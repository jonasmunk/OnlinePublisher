function XWGStyleNumber(id,value,min,max,empty,onchange) {
	this.empty=(empty=='true');
	this.id=id;
	this.min=0;
	this.max=10;
	this.units=new Array('pt','px','pc','%','in','cm','mm','em','ex');
	this.onchange=onchange;
	this.setMin(min);
	this.setMax(max);
	this.setValue(value);
}

XWGStyleNumber.prototype.validateValue = function(value) {
	var parsed = parseInt(value);
	if (!isNaN(parsed)) {
		parsed = Math.round(parsed);
		if (this.min!=null && parsed<this.min) {
			return this.min;
		}
		else if (this.max!=null && parsed>this.max) {
			return this.max;
		}
		else {
			return parsed;
		}
	}
	else {
		if (this.empty) {
			return null;
		}
		else {
			return this.value;
		}
	}
};

XWGStyleNumber.prototype.setMin = function(value) {
	var parsed = parseInt(value);
	if (!isNaN(parsed)) {
		this.min = parsed;
	}
	else {
		this.min = null;
	}
};

XWGStyleNumber.prototype.setMax = function(value) {
	var parsed = parseInt(value);
	if (!isNaN(parsed)) {
		this.max = parsed;
	}
	else {
		this.max = null;
	}
};

XWGStyleNumber.prototype.setValue = function(value) {
	this.value = this.validateValue(value);
	this.updateUI();
};

XWGStyleNumber.prototype.clear = function(value) {
	this.value = null;
	this.unit = null;
	this.updateUI();
	this.fireChange();
};

XWGStyleNumber.prototype.getValue = function() {
	if (this.value!=null) {
		return this.value;
		}
	else {
		return '';
	}
};

XWGStyleNumber.prototype.getDefaultValue = function() {
	if (this.value!=null) {
		return this.value;
		}
	else if (this.min!=null) {
		return this.min;
	}
	else {
		return 0;
	}
};

XWGStyleNumber.prototype.up = function() {
	if (this.value==null) {
		this.value=this.getDefaultValue();
	}
	else {
		this.value = this.validateValue(this.value+1);
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleNumber.prototype.down = function() {
	if (this.value==null) {
		this.value=this.getDefaultValue();
	}
	else {
		this.value = this.validateValue(this.value-1);
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleNumber.prototype.valueChanged = function() {
	var input = this.validateValue(document.getElementById(this.id+'_input').value);
	this.value = input;
	this.updateUI();
	this.fireChange();
};

XWGStyleNumber.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}

XWGStyleNumber.prototype.updateUI = function() {
	document.getElementById(this.id+'_input').value=(this.value==null ? '' : this.value);
};