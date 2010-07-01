/**
 * A dock
 * @param {Object} The options
 * @constructor
 */
In2iGui.Dock = function(options) {
	this.options = options;
	this.element = $(options.element);
	this.iframe = this.element.select('iframe')[0];
	this.name = options.name;
	In2iGui.extend(this);
	this.diff = -69;
	if (this.options.tabs) {
		this.diff-=15;
	}
}

In2iGui.Dock.prototype = {
	/** Change the url of the iframe
	 * @param {String} url The url to change the iframe to
	 */
	setUrl : function(url) {
		n2i.getFrameDocument(this.iframe).location.href=url;
	},
	/** @private */
	$$layout : function() {
		var height = n2i.getInnerHeight();
		this.iframe.style.height=(height+this.diff)+'px';
	}
}

/* EOF */