/**
 * Overflow with scroll bars
 * @param {Object} The options
 * @constructor
 */
In2iGui.Overflow = function(options) {
	this.options = options;
	this.element = $(options.element);
	this.name = options.name;
	In2iGui.extend(this);
}

In2iGui.Overflow.create = function(options) {
	options = options || {};
	var e = options.element = new Element('div',{'class':'in2igui_overflow'});
	if (options.height) {
		e.setStyle({height:options.height+'px'});
	}
	return new In2iGui.Overflow(options);
}

In2iGui.Overflow.prototype = {
	calculate : function() {
		var top,bottom,parent,viewport;
		viewport = n2i.getInnerHeight();
		parent = $(this.element.parentNode);
		top = this.element.cumulativeOffset().top;
		bottom = parent.cumulativeOffset().top+parent.getDimensions().height;
		this.element.nextSiblings().each(function(node) {
			bottom-=node.clientHeight;
		})
		this.diff=-1*(top+(viewport-bottom));
	},
	add : function(widgetOrNode) {
		if (widgetOrNode.getElement) {
			this.element.insert(widgetOrNode.getElement());
		} else {
			this.element.insert(widgetOrNode);
		}
		return this;
	},
	$$layout : function() {
		if (!this.options.dynamic) {
			if (this.options.vertical) {
				var height = n2i.getInnerHeight();
				this.element.style.height = Math.max(0,height-this.options.vertical)+'px';
			}
			return;
		}
		if (this.diff===undefined) {
			this.calculate();
		}
		var height = n2i.getInnerHeight();
		this.element.style.height = Math.max(0,height+this.diff)+'px';
	}
}

/* EOF */