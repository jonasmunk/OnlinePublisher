function XWGStyleFontWeight(id,value,path,onchange) {
	this.id=id;
	this.value=value;
	this.path=path;
	this.onchange=onchange;
	this.updateUI();
}

XWGStyleFontWeight.prototype.setValue = function(value) {
	this.value=value;
	this.updateUI();
};

XWGStyleFontWeight.prototype.getValue = function() {
	return this.value;
};

XWGStyleFontWeight.prototype.switchValue = function(value) {
	if (this.value==value) {
		this.value='';
	}
	else {
		this.value=value;
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleFontWeight.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}
XWGStyleFontWeight.prototype.updateUI = function() {
	if (this.value=='lighter') {
		document.getElementById(this.id+'-lighter').src=this.path+'StyleWeightLighterHilited.gif';
	}
	else {
		document.getElementById(this.id+'-lighter').src=this.path+'StyleWeightLighter.gif';
	}
	if (this.value=='normal') {
		document.getElementById(this.id+'-normal').src=this.path+'StyleWeightNormalHilited.gif';
	}
	else {
		document.getElementById(this.id+'-normal').src=this.path+'StyleWeightNormal.gif';
	}
	if (this.value=='bold') {
		document.getElementById(this.id+'-bold').src=this.path+'StyleWeightBoldHilited.gif';
	}
	else {
		document.getElementById(this.id+'-bold').src=this.path+'StyleWeightBold.gif';
	}
};