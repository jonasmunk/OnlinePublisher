In2iGui.BarFormSelect = function(id) {
	this.id=id;
}

In2iGui.BarFormSelect.prototype.getValue = function() {
	var element = document.getElementById(this.id);
	return element.options[element.selectedIndex].value;
};

In2iGui.BarFormSelect.prototype.getValues = function() {
	var output = new Array();
	var element = document.getElementById(this.id);
	for (var i=0;i<element.options.length;i++) {
		if (element.options[i].selected==true) {
			output[output.length]=element.options[i].value;
		}
	}
	return output;
};

In2iGui.BarFormSelect.prototype.getText = function() {
	var element = document.getElementById(this.id);
	return element.options[element.selectedIndex].text;
};

In2iGui.BarFormSelect.prototype.setValue = function(value) {
	var element = document.getElementById(this.id);
	for (var i=0;i<element.options.length;i++) {
		if (element.options[i].value==value) {
			element.options[i].selected=true;
		}
		else {
			element.options[i].selected=false;
		}
	}
};

In2iGui.BarFormSelect.prototype.setValues = function(arr) {
	var values = arr.split(',');
	var element = document.getElementById(this.id);
	for (var i=0;i<element.options.length;i++) {
		if (isValueInArray(element.options[i].value,values)) {
			element.options[i].selected=true;
		}
		else {
			element.options[i].selected=false;
		}
	}
};

In2iGui.BarFormSelect.prototype.focus = function() {
	return document.getElementById(this.id).focus();
};

In2iGui.BarFormSelect.prototype.disable = function() {
	document.getElementById(this.id).disabled=true;
};

In2iGui.BarFormSelect.prototype.enable = function() {
	document.getElementById(this.id).disabled=false;
};

function isValueInArray(value,arr) {
	var found = false;
	for (i=0;i<arr.length && !found;i++) {
		if (arr[i]==value) {
			found=true;
		}
	}
	return found;
}