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
	var e = options.element = hui.build('div',{'class':'hui_overflow'});
	if (options.height) {
		e.style.height=options.height+'px';
	}
	return new hui.ui.Overflow(options);
}

hui.ui.Overflow.prototype = {
	_calculate : function() {
		var viewport = hui.getViewPortHeight(),
			parent = this.element.parentNode,
			top = hui.getTop(this.element),
			bottom = hui.getTop(parent)+parent.clientHeight,
			sibs = hui.getAllNext(this.element);
		for (var i=0; i < sibs.length; i++) {
			if (hui.getStyle(sibs[i],'position')!='absolute') {
				bottom-=sibs[i].clientHeight;
			}
		}
		this.diff = -1 * (top + (viewport - bottom));
		if (hui.browser.webkit && this.element.parentNode.className=='hui_layout_center') {
			this.diff++;
		}
	},
	show : function() {
		this.element.style.display='';
		hui.ui.callVisible(this);
	},
	hide : function() {
		this.element.style.display='none';
		hui.ui.callVisible(this);
	},
	add : function(widgetOrNode) {
		if (widgetOrNode.getElement) {
			this.element.appendChild(widgetOrNode.getElement());
		} else {
			this.element.appendChild(widgetOrNode);
		}
		return this;
	},
	$$layoutChanged : function() {
		if (!this.options.dynamic) {return}
		this.element.style.height='0px';
		window.setTimeout(function() {
			this._calculate();
			this.$$layout();
		}.bind(this))
	},
	/** @private */
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
			this._calculate();
		}
		height = hui.getViewPortHeight();
		this.element.style.height = Math.max(0,height+this.diff)+'px';
	}
}

/* EOF */