function XWGStyleTextTransform(id,value,path,onchange) {
	this.id=id;
	this.value=value;
	this.path=path;
	this.onchange=onchange;
	this.updateUI();
}

XWGStyleTextTransform.prototype.setValue = function(value) {
	this.value=value;
	this.updateUI();
};

XWGStyleTextTransform.prototype.getValue = function() {
	return this.value;
};

XWGStyleTextTransform.prototype.switchValue = function(value) {
	if (this.value==value) {
		this.value='';
	}
	else {
		this.value=value;
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleTextTransform.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}

XWGStyleTextTransform.prototype.updateUI = function() {
	if (this.value=='none') {
		document.getElementById(this.id+'-none').src=this.path+'StyleTextTransformNoneHilited.gif';
	}
	else {
		document.getElementById(this.id+'-none').src=this.path+'StyleTextTransformNone.gif';
	}
	if (this.value=='capitalize') {
		document.getElementById(this.id+'-capitalize').src=this.path+'StyleTextTransformCapitalizeHilited.gif';
	}
	else {
		document.getElementById(this.id+'-capitalize').src=this.path+'StyleTextTransformCapitalize.gif';
	}
	if (this.value=='uppercase') {
		document.getElementById(this.id+'-uppercase').src=this.path+'StyleTextTransformUppercaseHilited.gif';
	}
	else {
		document.getElementById(this.id+'-uppercase').src=this.path+'StyleTextTransformUppercase.gif';
	}
	if (this.value=='lowercase') {
		document.getElementById(this.id+'-lowercase').src=this.path+'StyleTextTransformLowercaseHilited.gif';
	}
	else {
		document.getElementById(this.id+'-lowercase').src=this.path+'StyleTextTransformLowercase.gif';
	}
};