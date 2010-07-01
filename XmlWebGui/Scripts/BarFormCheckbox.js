In2iGui.BarFormCheckbox = function(id) {
	this.id=id;
}

In2iGui.BarFormCheckbox.prototype.focus = function() {
	return document.getElementById(this.id).focus();
};

In2iGui.BarFormCheckbox.prototype.isSelected = function() {
	return document.getElementById(this.id).checked;
};

In2iGui.BarFormCheckbox.prototype.setSelected = function(sel) {
	document.getElementById(this.id).checked=sel;
};

In2iGui.BarFormCheckbox.prototype.disable = function() {
	document.getElementById(this.id).disabled=true;
};

In2iGui.BarFormCheckbox.prototype.enable = function() {
	document.getElementById(this.id).disabled=false;
};