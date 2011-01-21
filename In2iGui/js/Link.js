/**
 * @constructor
 * A link
 */
In2iGui.Link = function(options) {
	this.options = options;
	this.name = options.name;
	this.element = n2i.get(options.element);
	In2iGui.extend(this);
	this.addBehavior();
}

In2iGui.Link.prototype = {
	/** @private */
	addBehavior : function() {
		var self = this;
		n2i.listen(this.element,'click',function(e) {
			n2i.stop(e);
			window.setTimeout(function() {
				self.fire('click');
			});
		});
	}
}

/* EOF */