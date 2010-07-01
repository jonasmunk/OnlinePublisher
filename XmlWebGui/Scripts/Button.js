In2iGui.Button = function(id,style) {
	this.id=id;
	this.style=style;
}

In2iGui.Button.prototype.focus = function() {
	document.getElementById(this.id).focus();
};