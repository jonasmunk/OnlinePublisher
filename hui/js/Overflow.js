/**
 * Overflow with scroll bars
 * @param {Object} The options
 * @constructor
 */
hui.ui.Overflow = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.name = options.name;
	hui.ui.extend(this);
}

hui.ui.Overflow.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('div',{'class':'in2igui_overflow'});
	if (options.height) {
		e.style.height=options.height+'px';
	}
	return new hui.ui.Overflow(options);
}

hui.ui.Overflow.prototype = {
	calculate : function() {
		var top,bottom,parent,viewport;
		viewport = hui.getViewPortHeight();
		parent = this.element.parentNode;
		top = hui.getTop(this.element);
		bottom = hui.getTop(parent)+parent.clientHeight;
		var sibs = hui.getAllNext(this.element);
		for (var i=0; i < sibs.length; i++) {
			bottom-=sibs[i].clientHeight;
		};
		this.diff=-1*(top+(viewport-bottom));
		if (hui.browser.webkit) {
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
				height = hui.getViewPortHeight();
				this.element.style.height = Math.max(0,height-this.options.vertical)+'px';
			}
			return;
		}
		if (this.diff===undefined) {
			this.calculate();
		}
		height = hui.getViewPortHeight();
		this.element.style.height = Math.max(0,height+this.diff)+'px';
	}
}

/* EOF */