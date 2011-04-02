/**
 * A dock
 * @param {Object} The options
 * @constructor
 */
In2iGui.Dock = function(options) {
	this.options = options;
	this.element = n2i.get(options.element);
	this.iframe = n2i.firstByTag(this.element,'iframe');
	this.progress = n2i.firstByClass(this.element,'in2igui_dock_progress');
	n2i.listen(this.iframe,'load',this._load.bind(this));
	//if (this.iframe.contentWindow) {
	//	this.iframe.contentWindow.addEventListener('DOMContentLoaded',function() {this._load();n2i.log('Fast path!')}.bind(this));
	//}
	this.name = options.name;
	In2iGui.extend(this);
	this.diff = -69;
	if (this.options.tabs) {
		this.diff-=15;
	}
	this.busy = true;
}

In2iGui.Dock.prototype = {
	/** Change the url of the iframe
	 * @param {String} url The url to change the iframe to
	 */
	setUrl : function(url) {
		this._setBusy(true);
		n2i.getFrameDocument(this.iframe).location.href=url;
	},
	_load : function() {
		this._setBusy(false);
	},
	_setBusy : function(busy) {
		if (busy) {
			n2i.setStyle(this.progress,{display:'block',height:this.iframe.clientHeight});
		} else {
			this.progress.style.display = 'none';
		}
	},
	/** @private */
	$$layout : function() {
		var height = n2i.getViewPortHeight();
		this.iframe.style.height=(height+this.diff)+'px';
		this.progress.style.height=(height+this.diff)+'px';
	}
}

/* EOF */