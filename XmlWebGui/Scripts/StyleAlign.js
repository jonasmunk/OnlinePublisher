function XWGStyleAlign(id,value,path,onchange) {
	this.id=id;
	this.value=value;
	this.path=path;
	this.onchange=onchange;
	this.updateUI();
}

XWGStyleAlign.prototype.setValue = function(value) {
	this.value=value;
	this.updateUI();
};

XWGStyleAlign.prototype.getValue = function() {
	return this.value;
};

XWGStyleAlign.prototype.switchValue = function(value) {
	if (this.value==value) {
		this.value='';
	}
	else {
		this.value=value;
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleAlign.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}
XWGStyleAlign.prototype.updateUI = function() {
	if (this.value=='left') {
		document.getElementById(this.id+'-left').src=this.path+'StyleAlignLeftHilited.gif';
	}
	else {
		document.getElementById(this.id+'-left').src=this.path+'StyleAlignLeft.gif';
	}
	if (this.value=='center') {
		document.getElementById(this.id+'-center').src=this.path+'StyleAlignCenterHilited.gif';
	}
	else {
		document.getElementById(this.id+'-center').src=this.path+'StyleAlignCenter.gif';
	}
	if (this.value=='right') {
		document.getElementById(this.id+'-right').src=this.path+'StyleAlignRightHilited.gif';
	}
	else {
		document.getElementById(this.id+'-right').src=this.path+'StyleAlignRight.gif';
	}
};