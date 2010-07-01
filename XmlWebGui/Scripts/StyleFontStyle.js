function XWGStyleFontStyle(id,value,path,onchange) {
	this.id=id;
	this.value=value;
	this.path=path;
	this.onchange=onchange;
	this.updateUI();
}

XWGStyleFontStyle.prototype.setValue = function(value) {
	this.value=value;
	this.updateUI();
};

XWGStyleFontStyle.prototype.getValue = function() {
	return this.value;
};

XWGStyleFontStyle.prototype.switchValue = function(value) {
	if (this.value==value) {
		this.value='';
	}
	else {
		this.value=value;
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleFontStyle.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}

XWGStyleFontStyle.prototype.updateUI = function() {
	if (this.value=='normal') {
		document.getElementById(this.id+'-normal').src=this.path+'StyleFontStyleNormalHilited.gif';
	}
	else {
		document.getElementById(this.id+'-normal').src=this.path+'StyleFontStyleNormal.gif';
	}
	if (this.value=='oblique') {
		document.getElementById(this.id+'-oblique').src=this.path+'StyleFontStyleObliqueHilited.gif';
	}
	else {
		document.getElementById(this.id+'-oblique').src=this.path+'StyleFontStyleOblique.gif';
	}
	if (this.value=='italic') {
		document.getElementById(this.id+'-italic').src=this.path+'StyleFontStyleItalicHilited.gif';
	}
	else {
		document.getElementById(this.id+'-italic').src=this.path+'StyleFontStyleItalic.gif';
	}
};