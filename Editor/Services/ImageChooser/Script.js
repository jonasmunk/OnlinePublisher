function ImageChooser(path,delegate) {
	this.path = path;
	this.delegate = delegate;
	this.win = null;
}

ImageChooser.prototype.open = function() {
		URL=this.path+"Services/ImageChooser/";
		this.win = window.open(URL, "Chooser", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=750,height=400,left = 262,top = 234");
		this.win.focus();
};

ImageChooser.prototype.close = function() {
		if (this.win!=null) {
			this.win.close();
		}
};

ImageChooser.prototype.selectImage = function(id) {
	eval(this.delegate+'('+id+');')
};