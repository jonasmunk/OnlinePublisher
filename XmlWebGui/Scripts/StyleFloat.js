function XWGStyleFloat(id,value,path,onchange) {
	this.id=id;
	this.value=value;
	this.path=path;
	this.onchange=onchange;
	this.updateUI();
}

XWGStyleFloat.prototype.setValue = function(value) {
	this.value=value;
	this.updateUI();
};

XWGStyleFloat.prototype.getValue = function() {
	return this.value;
};

XWGStyleFloat.prototype.switchValue = function(value) {
	if (this.value==value) {
		this.value='';
	}
	else {
		this.value=value;
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleFloat.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}

XWGStyleFloat.prototype.updateUI = function() {
	if (this.value=='left') {
		document.getElementById(this.id+'-left').src=this.path+'StyleFloatLeftHilited.gif';
	}
	else {
		document.getElementById(this.id+'-left').src=this.path+'StyleFloatLeft.gif';
	}
	if (this.value=='right') {
		document.getElementById(this.id+'-right').src=this.path+'StyleFloatRightHilited.gif';
	}
	else {
		document.getElementById(this.id+'-right').src=this.path+'StyleFloatRight.gif';
	}
};