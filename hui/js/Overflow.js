/**
 * Overflow with scroll bars
 * @param options {Object} The options
 * @param options.dynamic {boolean} If the overflow show adjust its height
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
  var attributes = {
    'class' : 'hui_overflow',
    html : '<div class="hui_overflow_top"></div><div class="hui_overflow_bottom"></div>'
  };
	if (options.height) {
		attributes.style = {height:options.height+'px'};
	}
	options.element = hui.build('div',attributes);
	return new hui.ui.Overflow(options);
}

hui.ui.Overflow.prototype = {
	_checkShadows : function() {
		if (hui.browser.msie) {return}
		if (this.element.scrollTop > 0) {
			this.topShadow.style.display = 'block';
			this.topShadow.style.top = this.element.scrollTop+'px';
		} else {
			this.topShadow.style.display = 'none';
		}
		if(this.element.scrollHeight-this.element.scrollTop-this.element.clientHeight > 0) {
			this.bottomShadow.style.display = 'block';
			this.bottomShadow.style.top = (this.element.scrollTop+this.element.clientHeight-this.bottomShadow.clientHeight)+'px';
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
	$$layout : function() {
		if (!this.options.dynamic) {
			this._checkShadows();
			return
		}
		this.element.style.height = hui.position.getRemainingHeight(this.element)+'px';
		this._checkShadows();
	},
	/** @private */
	$visibilityChanged : function() {
		if (hui.dom.isVisible(this.element)) {
      this.$$layout();
    }
  }
}

/* EOF */