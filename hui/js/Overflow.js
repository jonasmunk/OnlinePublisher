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
		var viewport = hui.window.getViewHeight(),
			parent = this.element.parentNode,
			top = hui.position.getTop(this.element),
			bottom = hui.position.getTop(parent)+parent.clientHeight,
			sibs = hui.get.after(this.element);
		for (var i=0; i < sibs.length; i++) {
			if (hui.style.get(sibs[i],'position')!='absolute') {
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
	/** @private */
	$$layout : function() {
		if (!this.options.dynamic) {return}
		/*
		var hasSiblings = false;
		var sibs = this.element.parentNode.childNodes;
		for (var i=0; i < sibs.length; i++) {
			if (sibs[i]!==this.element && hui.dom.isElement(sibs[i]) && sibs[i].nodeName!='script' && hui.style.get(sibs[i],'position')!='absolute') {
				hasSiblings = true;
				hui.log(sibs[i])
				break;
			}
		};
		if (!hasSiblings) {
			hui.log('Fast path!');
			this.$$resize();
			return;
		}*/
		this.element.style.height='0px';
		window.setTimeout(function() {
			this._calculate();
			this.$$resize();
		}.bind(this))
	},
	/** @private */
	$$resize : function() {
		var height;
		if (!this.options.dynamic) {
			if (this.options.vertical) {
				height = hui.window.getViewHeight();
				this.element.style.height = Math.max(0,height-this.options.vertical)+'px';
			}
			return;
		}
		if (this.diff===undefined) {
			this._calculate();
		}
		height = hui.window.getViewHeight();
		this.element.style.height = Math.max(0,height+this.diff)+'px';
	}
}

/* EOF */