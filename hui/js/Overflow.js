/**
 * Overflow with scroll bars
 * @param {Object} The options
 * @constructor
 */
hui.ui.Overflow = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.topShadow = hui.get.firstByClass(this.element,'hui_overflow_top');
	this.bottomShadow = hui.get.firstByClass(this.element,'hui_overflow_bottom');
	hui.listen(this.element,'scroll',this._checkShadows.bind(this));
	this.name = options.name;
	hui.ui.extend(this);
}

hui.ui.Overflow.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('div',{'class':'hui_overflow',html:'<div class="hui_overflow_top"></div><div class="hui_overflow_bottom"></div>'});
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
		if (hui.browser.webkit && (this.element.parentNode.className=='hui_layout_center' || hui.cls.has(this.element.parentNode,'hui_layout_left'))) {
			this.diff++;
		}
	},
	_checkShadows : function() {
		if (hui.browser.msie) {return}
		if (this.element.scrollTop>0) {
			this.topShadow.style.display = 'block';
			this.topShadow.style.top = this.element.scrollTop+'px';
		} else {
			this.topShadow.style.display = 'none';
		}
		if(this.element.scrollHeight-this.element.scrollTop-this.element.clientHeight>0) {
			this.bottomShadow.style.display = 'block';
			this.bottomShadow.style.top = (this.element.scrollTop+this.element.clientHeight-8)+'px';
		} else {
			this.bottomShadow.style.display = 'none';
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
	$$childSizeChanged : function() {
		this._checkShadows();
	},
	/** @private */
	$$layout : function() {
		if (!this.options.dynamic) {
			this._checkShadows();
			return
		}
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
			this._checkShadows();
			return;
		}
		if (this.diff===undefined) {
			this._calculate();
		}
		height = hui.window.getViewHeight();
		this.element.style.height = Math.max(0,height+this.diff)+'px';
		this._checkShadows();
	}
}

/* EOF */