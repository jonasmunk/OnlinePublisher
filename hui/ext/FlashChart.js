/**
 * A dock
 * @constructor
 */
hui.ui.FlashChart = function(element,name,options) {
	this.options = N2i.override({},options);
	this.element = $(element);
	this.name = name;
	hui.ui.extend(this);
}

hui.ui.FlashChart.prototype = {
	load : function(url) {
		var self = this;
		var flash = this.findSWF(this.element.id+'_chart');
		hui.request({url:url,$success:function(t) {
			flash.load(t.responseText);
		}});
	},
	findSWF : function(movieName) {
		if (window[movieName]) {
			return window[movieName];
		} else {
			return document[movieName];
		}
	}
}

/* EOF */