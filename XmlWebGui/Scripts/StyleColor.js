function XWGStyleColor(id,value,path,onchange) {
	this.id=id;
	this.value=value;
	this.path=path;
	this.onchange=onchange;
	this.updateUI();
}

XWGStyleColor.prototype.setValue = function(value) {
	this.value=value;
	this.updateUI();
};

XWGStyleColor.prototype.hover = function(value) {
	//window.status=value;
};

XWGStyleColor.prototype.returnColor = function(value) {
	this.value=value;
	this.updateUI();
	this.fireChange();
};

XWGStyleColor.prototype.clear = function() {
	this.value='';
	this.updateUI();
	this.fireChange();
};

XWGStyleColor.prototype.openPicker = function() {
	var width=300;
	var height=330;
	if (navigator.userAgent.indexOf('Gecko')!=-1) {
		width+=10;
		height+=10;
	}
    winColor = window.open(this.path+'Scripts/StyleColor.htm?id='+this.id,'winColor'+this.id,'status=no,left='+100+',top='+100+',toolbar=no,location=no,scrollbars=no,width='+width+',height='+height);
    winColor.focus;
    window.blur;
};

XWGStyleColor.prototype.getValue = function() {
	return this.value;
};

XWGStyleColor.prototype.fireChange = function() {
	if (this.onchange!='') {
		eval(this.onchange);
	}
}

XWGStyleColor.prototype.updateUI = function() {
	document.getElementById(this.id+'_display').style.backgroundColor=this.value;
};