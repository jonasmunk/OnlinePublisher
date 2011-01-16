/**
 * A dock
 * @param {Object} The options
 * @constructor
 */
In2iGui.IFrame = function(options) {
	this.options = options;
	this.element = n2i.get(options.element);
	this.name = options.name;
	In2iGui.extend(this);
}

In2iGui.IFrame.prototype = {
	/** Change the url of the iframe
	 * @param {String} url The url to change the iframe to
	 */
	setUrl : function(url) {
		n2i.getFrameDocument(this.element).location.href=url;
	},
	getDocument : function() {
		return n2i.getFrameDocument(this.element);
	},
	getWindow : function() {
		return n2i.getFrameWindow(this.element);
	},
	reload : function() {
		this.getWindow().location.reload();
	}
}

/* EOF */