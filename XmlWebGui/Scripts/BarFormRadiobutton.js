In2iGui.BarFormRadiobutton = function(id) {
	this.id=id;
}

In2iGui.BarFormRadiobutton.prototype.focus = function() {
	return document.getElementById(this.id).focus();
};

In2iGui.BarFormRadiobutton.prototype.isSelected = function() {
	return document.getElementById(this.id).checked;
};

In2iGui.BarFormRadiobutton.prototype.setSelected = function(sel) {
	document.getElementById(this.id).checked=sel;
};

In2iGui.BarFormRadiobutton.prototype.disable = function() {
	document.getElementById(this.id).disabled=true;
};

In2iGui.BarFormRadiobutton.prototype.enable = function() {
	document.getElementById(this.id).disabled=false;
};