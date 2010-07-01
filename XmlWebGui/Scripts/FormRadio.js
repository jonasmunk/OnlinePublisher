function XWGFormRadio(id,autoenable,others) {
	this.id=id;
	if (autoenable.length>0) {
		this.autoenable=autoenable.split(",");
	}
	else {
		this.autoenable = new Array();
	}
	if (others.length>0) {
		this.others=others.split(",");
	}
	else {
		this.others = new Array();
	}
}

XWGFormRadio.prototype.focus = function() {
	return document.getElementById(this.id).focus();
};

XWGFormRadio.prototype.isSelected = function() {
	return document.getElementById(this.id).checked;
};

XWGFormRadio.prototype.setSelected = function(sel) {
	document.getElementById(this.id).checked=sel;
	this.changed();
};

XWGFormRadio.prototype.disable = function() {
	document.getElementById(this.id).disabled=true;
};

XWGFormRadio.prototype.enable = function() {
	document.getElementById(this.id).disabled=false;
};

XWGFormRadio.prototype.changed = function() {
	if (this.isSelected()) {
		for (i=0;i<this.autoenable.length;i++) {
			eval(this.autoenable[i]+'.enable()');
		}
		var cmd='';
		for (i=0;i<this.others.length;i++) {
			cmd+=this.others[i]+'.setSelected(false);';
		}
		eval(cmd);
	}
	else {
		for (i=0;i<this.autoenable.length;i++) {
			eval(this.autoenable[i]+'.disable()');
		}
	}
};