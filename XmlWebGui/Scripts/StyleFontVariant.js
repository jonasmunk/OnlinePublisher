function XWGStyleFontVariant(id,value,path,onchange) {
	this.id=id;
	this.value=value;
	this.path=path;
	this.onchange=onchange;
	this.updateUI();
}

XWGStyleFontVariant.prototype.setValue = function(value) {
	this.value=value;
	this.updateUI();
};

XWGStyleFontVariant.prototype.getValue = function() {
	return this.value;
};

XWGStyleFontVariant.prototype.switchValue = function(value) {
	if (this.value==value) {
		this.value='';
	}
	else {
		this.value=value;
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleFontVariant.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}

XWGStyleFontVariant.prototype.updateUI = function() {
	if (this.value=='normal') {
		document.getElementById(this.id+'-normal').src=this.path+'StyleFontVariantNormalHilited.gif';
	}
	else {
		document.getElementById(this.id+'-normal').src=this.path+'StyleFontVariantNormal.gif';
	}
	if (this.value=='small-caps') {
		document.getElementById(this.id+'-small-caps').src=this.path+'StyleFontVariantSmallCapsHilited.gif';
	}
	else {
		document.getElementById(this.id+'-small-caps').src=this.path+'StyleFontVariantSmallCaps.gif';
	}
};