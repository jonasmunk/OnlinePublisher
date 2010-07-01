function XWGFormCheck(id,autoenable) {
	this.id=id;
	if (autoenable.length>0) {
		this.autoenable=autoenable.split(",");
	}
	else {
		this.autoenable = new Array();
	}
}

XWGFormCheck.prototype.focus = function() {
	return document.getElementById(this.id).focus();
};

XWGFormCheck.prototype.isSelected = function() {
	return document.getElementById(this.id).checked;
};

XWGFormCheck.prototype.setSelected = function(sel) {
	document.getElementById(this.id).checked=sel;
};

XWGFormCheck.prototype.disable = function() {
	document.getElementById(this.id).disabled=true;
};

XWGFormCheck.prototype.enable = function() {
	document.getElementById(this.id).disabled=false;
};

XWGFormCheck.prototype.changed = function() {
	if (this.isSelected()) {
		for (i=0;i<this.autoenable.length;i++) {
			eval(this.autoenable[i]+'.enable()');
		}
	}
	else {
		for (i=0;i<this.autoenable.length;i++) {
			eval(this.autoenable[i]+'.disable()');
		}
	}
};