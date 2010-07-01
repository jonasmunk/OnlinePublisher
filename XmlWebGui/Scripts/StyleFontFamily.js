function XWGStyleFontFamily(id,value,fontTitles,fontValues,onchange) {
	this.id=id;
	this.value=value;
	this.fontTitles=fontTitles.split(';');
	this.fontValues=fontValues.split(';');
	this.onchange=onchange;
	this.updateUI();
}

XWGStyleFontFamily.prototype.setValue = function(value) {
	this.value=value;
	this.updateUI();
};

XWGStyleFontFamily.prototype.clear = function() {
	this.value='';
	this.updateUI();
	this.fireChange();
};

XWGStyleFontFamily.prototype.getValue = function() {
	return this.value;
};

XWGStyleFontFamily.prototype.previous = function(value) {
	var len = this.fontValues.length;
	if (this.value.length==0) this.value=this.fontValues[0];
	for (var i=0;i<len;i++) {
		if (this.fontValues[i]==this.value) {
			if (i>0) {
				this.value=this.fontValues[i-1];
				break;
			}
			else {
				this.value=this.fontValues[this.fontValues.length-1];
				break;
			}
		}
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleFontFamily.prototype.next = function(value) {
	var len = this.fontValues.length;
	if (this.value.length==0) this.value=this.fontValues[0];
	for (var i=0;i<len;i++) {
		if (this.fontValues[i]==this.value) {
			if ((i+1)<len) {
				this.value=this.fontValues[i+1];
				break;
			}
			else {
				this.value=this.fontValues[0];
				break;
			}
		}
	}
	this.updateUI();
	this.fireChange();
};

XWGStyleFontFamily.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}

XWGStyleFontFamily.prototype.updateUI = function() {
	document.getElementById(this.id+'_display').innerHTML=this.getFontTitle(this.value);
};


XWGStyleFontFamily.prototype.getFontTitle = function(value) {
	for (var i=0;i<this.fontValues.length;i++) {
		if (this.fontValues[i]==value) {
			return this.fontTitles[i];
		}
	}
	return value;
};