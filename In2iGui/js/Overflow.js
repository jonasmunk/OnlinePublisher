/**
 * Overflow with scroll bars
 * @param {Object} The options
 * @constructor
 */
In2iGui.Overflow = function(options) {
	this.options = options;
	this.element = n2i.get(options.element);
	this.name = options.name;
	In2iGui.extend(this);
}

In2iGui.Overflow.create = function(options) {
	options = options || {};
	var e = options.element = n2i.build('div',{'class':'in2igui_overflow'});
	if (options.height) {
		e.style.height=options.height+'px';
	}
	return new In2iGui.Overflow(options);
}

In2iGui.Overflow.prototype = {
	calculate : function() {
		var top,bottom,parent,viewport;
		viewport = n2i.getViewPortHeight();
		parent = this.element.parentNode;
		top = n2i.getTop(this.element);
		bottom = n2i.getTop(parent)+parent.clientHeight;
		var sibs = n2i.getAllNext(this.element);
		for (var i=0; i < sibs.length; i++) {
			bottom-=sibs[i].clientHeight;
		};
		this.diff=-1*(top+(viewport-bottom));
		if (n2i.browser.webkit) {
			this.diff++;
		}
	},
	add : function(widgetOrNode) {
		if (widgetOrNode.getElement) {
			this.element.appendChild(widgetOrNode.getElement());
		} else {
			this.element.appendChild(widgetOrNode);
		}
		return this;
	},
	$$layout : function() {
		var height;
		if (!this.options.dynamic) {
			if (this.options.vertical) {
				height = n2i.getViewPortHeight();
				this.element.style.height = Math.max(0,height-this.options.vertical)+'px';
			}
			return;
		}
		if (this.diff===undefined) {
			this.calculate();
		}
		height = n2i.getViewPortHeight();
		this.element.style.height = Math.max(0,height+this.diff)+'px';
	}
}

/* EOF */