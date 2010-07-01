/**
 * @constructor
 * A link
 */
In2iGui.Link = function(options) {
	this.options = options;
	this.name = options.name;
	this.element = $(options.element);
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Link.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		this.element.observe('click',function(e) {
			self.fire('click');
			Event.stop(e);
		});
	}
}

/* EOF */