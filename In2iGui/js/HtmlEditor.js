/**
 * @constructor
 */
In2iGui.HtmlEditor = function(options) {
	this.name = options.name;
	var e = this.element = $(options.element);
	this.options = n2i.override({debug:false,value:'',autoHideToolbar:true,style:'font-family: sans-serif;'},options);
	In2iGui.extend(this);
}

In2iGui.HtmlEditor.prototype = {
	
}