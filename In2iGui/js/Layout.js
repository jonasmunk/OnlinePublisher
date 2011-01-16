/**
 * @constructor
 * @param {Object} options { element «Node | id», name: «String» }
 */
In2iGui.Layout = function(options) {
	this.name = options.name;
	this.options = options || {};
	this.element = n2i.get(options.element);
	In2iGui.extend(this);
}

In2iGui.Layout.prototype = {
	$$layout : function() {
		if (!n2i.browser.msie7 && !n2i.browser.msie8) {
			return;
		}
		if (this.diff===undefined) {
			var top = this.element.select('thead td')[0].firstDescendant().clientHeight;
			var foot = this.element.select('tfoot td')[0];
			var bottom = 0;
			if (foot) {
				bottom = foot.firstDescendant().clientHeight;
			}
			top+=this.element.cumulativeOffset().top;
			this.diff = bottom+top;
			if (this.element.parentNode!==document.body) {
				this.diff+=15;
			} else {
			}
		}
		var cell = this.element.select('tbody tr td')[0];
		cell.style.height=(n2i.getViewPortHeight()-this.diff)+'px';
	}
};


/**
 * @constructor
 * @param {Object} options { element «Node | id», name: «String» }
 */
In2iGui.Columns = function(options) {
	this.name = options.name;
	this.options = options || {};
	this.element = n2i.get(options.element);
	this.body = n2i.firstByTag(this.element,'tr');
	In2iGui.extend(this);
}

/**
 * Creates a new Columns opject
 */
In2iGui.Columns.create = function(options) {
	options = options || {};
	options.element = n2i.build('table',{'class' : 'in2igui_columns',html : '<tbody><tr></tr></tbody>'});
	return new In2iGui.Columns(options);
}

In2iGui.Columns.prototype = {
	addToColumn : function(index,widget) {
		var c = this.ensureColumn(index);
		c.appendChild(widget.getElement());
	},
	setColumnStyle : function(index,style) {
		var c = this.ensureColumn(index);
		n2i.setStyle(c,style);
	},
	setColumnWidth : function(index,width) {
		var c = this.ensureColumn(index);
		c.style.width=width+'px';
	},
	/** @private */
	ensureColumn : function(index) {
		var children = n2i.getChildren(this.body);
		for (var i=children.length-1;i<index;i++) {
			this.body.appendChild(n2i.build('td',{'class':'in2igui_columns_column'}));
		}
		return n2i.getChildren(this.body)[index];
	}
}

/* EOF */