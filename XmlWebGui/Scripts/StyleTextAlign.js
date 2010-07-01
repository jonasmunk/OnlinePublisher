function XWGStyleTextAlign(id,value,path,onchange) {
	this.id=id;
	this.value=value;
	this.path=path;
	this.onchange=onchange;
	this.updateUI();
}

XWGStyleTextAlign.prototype.setValue = function(value) {
	this.value=value;
	this.updateUI();
};

XWGStyleTextAlign.prototype.getValue = function() {
	return this.value;
};

XWGStyleTextAlign.prototype.switchValue = function(value) {
	if (this.value==value) {
		this.value='';
	}
	else {
		this.value=value;
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleTextAlign.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}
XWGStyleTextAlign.prototype.updateUI = function() {
	if (this.value=='left') {
		document.getElementById(this.id+'-left').src=this.path+'StyleTextAlignLeftHilited.gif';
	}
	else {
		document.getElementById(this.id+'-left').src=this.path+'StyleTextAlignLeft.gif';
	}
	if (this.value=='center') {
		document.getElementById(this.id+'-center').src=this.path+'StyleTextAlignCenterHilited.gif';
	}
	else {
		document.getElementById(this.id+'-center').src=this.path+'StyleTextAlignCenter.gif';
	}
	if (this.value=='right') {
		document.getElementById(this.id+'-right').src=this.path+'StyleTextAlignRightHilited.gif';
	}
	else {
		document.getElementById(this.id+'-right').src=this.path+'StyleTextAlignRight.gif';
	}
	if (this.value=='justify') {
		document.getElementById(this.id+'-justify').src=this.path+'StyleTextAlignJustifyHilited.gif';
	}
	else {
		document.getElementById(this.id+'-justify').src=this.path+'StyleTextAlignJustify.gif';
	}
};