In2iGui.FormDisclosure = function(id,graphics) {
	this.graphics=graphics;
	this.id=id;
	this.toggler = document.getElementById(id+'_toggler');
	this.body = document.getElementById(id+'_body');
	this.toggler.in2iGuiController = this;
	this.toggler.onclick = function(event) {return this.in2iGuiController.toggle(event,this);};
	this.toggler.ondblclick = function(event) {return false;};
	this.toggleImage = this.toggler.getElementsByTagName('img')[0];
}

In2iGui.FormDisclosure.prototype.toggle = function(event,item) {
	if (this.body.style.display=='none') {
		this.body.style.display='';
		this.toggleImage.src = this.graphics+'Expanded.png';
	} else {
		this.body.style.display='none';
		this.toggleImage.src = this.graphics+'Collapsed.png';
	}
	return false;
}