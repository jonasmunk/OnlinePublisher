function XWGStyleSize(id,value,path,onchange) {
	this.id=id;
	this.units=new Array('px','pt','pc','%','in','cm','mm','em','ex');
	this.path=path;
	this.onchange=onchange;
	this.setValue(value);
}

XWGStyleSize.prototype.setValue = function(value) {
	if (value.length>0) {
		var arr = value.split(" ");
		this.value = parseFloat(value);
		this.unit = value.substr(String(this.value).length,value.length);
	}
	else {
		this.value = null;
		this.unit = null;
	}
	this.updateUI();
};

XWGStyleSize.prototype.clear = function(value) {
	this.value = null;
	this.unit = null;
	this.updateUI();
	this.fireChange();
};

XWGStyleSize.prototype.getValue = function() {
	if (this.value!=null) {
		return this.value+this.unit;
		}
	else {
		return '';
	}
};

XWGStyleSize.prototype.up = function() {
	if (this.value==null) {
		this.value=12;
		this.switchUnit();
	}
	else {
		this.value++;
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleSize.prototype.down = function() {
	if (this.value==null) {
		this.value=12;
		this.switchUnit();
	}
	else {
		this.value--;
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleSize.prototype.valueChanged = function() {
	var input = parseFloat(document.getElementById(this.id+'_input').value);
	if (isNaN(input)) {
		this.value=null;
		this.unit=null;
	}
	else {
		this.value = input;
		if (this.unit==null) this.switchUnit();
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleSize.prototype.switchUnit = function() {
	if (this.unit==null) {
		this.unit=this.units[0];
	}
	else {
		for (var i=0;i<this.units.length;i++) {
			if (this.unit==this.units[i]) {
				if ((i+1)<this.units.length) {
					this.unit=this.units[i+1];
					break;
				}
				else {
					this.unit=this.units[0];
					break;
				}
			}
		}
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleSize.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}

XWGStyleSize.prototype.updateUI = function() {
	document.getElementById(this.id+'_input').value=(this.value==null ? '' : this.value);
	document.getElementById(this.id+'_unit').innerHTML=(this.unit==null ? '' : this.unit);
};