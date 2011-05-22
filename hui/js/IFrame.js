/**
 * A dock
 * @param {Object} The options
 * @constructor
 */
hui.ui.IFrame = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.name = options.name;
	hui.ui.extend(this);
}

hui.ui.IFrame.prototype = {
	/** Change the url of the iframe
	 * @param {String} url The url to change the iframe to
	 */
	setUrl : function(url) {
		this.element.setAttribute('src',url);
		//hui.getFrameDocument(this.element).location.href=url;
	},
	getDocument : function() {
		return hui.getFrameDocument(this.element);
	},
	getWindow : function() {
		return hui.getFrameWindow(this.element);
	},
	reload : function() {
		this.getWindow().location.reload();
	},
	show : function() {
		this.element.style.display='';
	},
	hide : function() {
		this.element.style.display='none';
	}
}

/* EOF */