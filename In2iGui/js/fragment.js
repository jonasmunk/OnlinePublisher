/**
 * Simple container
 * @param {Object} The options
 * @constructor
 */
In2iGui.Fragment = function(options) {
	this.options = options;
	this.element = n2i.get(options.element);
	this.name = options.name;
	In2iGui.extend(this);
}

In2iGui.Fragment.prototype = {
	show : function() {
		this.element.style.display='block';
	},
	hide : function() {
		this.element.style.display='none';
	}
}

/* EOF */