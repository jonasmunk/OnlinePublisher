/**
 * A dock
 * @param {Object} The options
 * @constructor
 */
hui.ui.Dock = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.iframe = hui.firstByTag(this.element,'iframe');
	this.progress = hui.firstByClass(this.element,'hui_dock_progress');
	hui.listen(this.iframe,'load',this._load.bind(this));
	//if (this.iframe.contentWindow) {
	//	this.iframe.contentWindow.addEventListener('DOMContentLoaded',function() {this._load();hui.log('Fast path!')}.bind(this));
	//}
	this.name = options.name;
	hui.ui.extend(this);
	this.diff = -69;
	if (this.options.tabs) {
		this.diff-=15;
	}
	this.busy = true;
	hui.ui.listen(this);
}

hui.ui.Dock.prototype = {
	/** Change the url of the iframe
	 * @param {String} url The url to change the iframe to
	 */
	setUrl : function(url) {
		this._setBusy(true);
		hui.getFrameDocument(this.iframe).location.href='about:blank';
		hui.getFrameDocument(this.iframe).location.href=url;
	},
	_load : function() {
		this._setBusy(false);
	},
	_setBusy : function(busy) {
		if (busy) {
			hui.log(this.iframe.clientWidth)
			hui.setStyle(this.progress,{display:'block',style:'height:'+this.iframe.clientHeight+'px;width:'+this.iframe.clientWidth+'px'});
		} else {
			this.progress.style.display = 'none';
		}
	},
	/** @private */
	$frameLoaded : function(win) {
		if (win==hui.getFrameWindow(this.iframe)) {
			this._setBusy(false);
		}
	},
	/** @private */
	$$layout : function() {
		var height = hui.getViewPortHeight();
		this.iframe.style.height=(height+this.diff)+'px';
		this.progress.style.width=(this.iframe.clientWidth)+'px';
		this.progress.style.height=(height+this.diff)+'px';
	}
}

/* EOF */