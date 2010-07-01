function XWGStyleTextDecoration(id,value,path,onchange) {
	this.id=id;
	this.value=value;
	this.path=path;
	this.onchange=onchange;
	this.updateUI();
}

XWGStyleTextDecoration.prototype.setValue = function(value) {
	this.value=value;
	this.updateUI();
};

XWGStyleTextDecoration.prototype.getValue = function() {
	return this.value;
};

XWGStyleTextDecoration.prototype.switchValue = function(value) {
	if (this.value==value) {
		this.value='';
	}
	else {
		this.value=value;
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleTextDecoration.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}

XWGStyleTextDecoration.prototype.updateUI = function() {
	if (this.value=='none') {
		document.getElementById(this.id+'-none').src=this.path+'StyleTextDecorationNoneHilited.gif';
	}
	else {
		document.getElementById(this.id+'-none').src=this.path+'StyleTextDecorationNone.gif';
	}
	if (this.value=='underline') {
		document.getElementById(this.id+'-underline').src=this.path+'StyleTextDecorationUnderlineHilited.gif';
	}
	else {
		document.getElementById(this.id+'-underline').src=this.path+'StyleTextDecorationUnderline.gif';
	}
	if (this.value=='overline') {
		document.getElementById(this.id+'-overline').src=this.path+'StyleTextDecorationOverlineHilited.gif';
	}
	else {
		document.getElementById(this.id+'-overline').src=this.path+'StyleTextDecorationOverline.gif';
	}
	if (this.value=='line-through') {
		document.getElementById(this.id+'-line-through').src=this.path+'StyleTextDecorationLineThroughHilited.gif';
	}
	else {
		document.getElementById(this.id+'-line-through').src=this.path+'StyleTextDecorationLineThrough.gif';
	}
};