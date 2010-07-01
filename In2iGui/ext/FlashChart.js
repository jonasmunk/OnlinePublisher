/**
 * A dock
 * @constructor
 */
In2iGui.FlashChart = function(element,name,options) {
	this.options = N2i.override({},options);
	this.element = $(element);
	this.name = name;
	In2iGui.extend(this);
}

In2iGui.FlashChart.prototype = {
	load : function(url) {
		var self = this;
		var flash = this.findSWF(this.element.id+'_chart');
		new Ajax.Request(url,{onSuccess:function(t) {
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