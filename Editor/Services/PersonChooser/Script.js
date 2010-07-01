function PersonChooser(path,delegate) {
	this.path = path;
	this.delegate = delegate;
}

PersonChooser.prototype.open = function() {
		URL=this.path+"Services/PersonChooser/";
		day = new Date();
		id = day.getTime();
		obj = window.open(URL, "Chooser", "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=500,height=400,left = 262,top = 234");
};

PersonChooser.prototype.selectPerson = function(id) {
	eval(this.delegate+'('+id+');')
};