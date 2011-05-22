/**
 * @constructor
 * @param {Object} options { element «Node | id», name: «String» }
 */
hui.ui.Layout = function(options) {
	this.name = options.name;
	this.options = options || {};
	this.element = hui.get(options.element);
	hui.ui.extend(this);
}

hui.ui.Layout.prototype = {
	$$layout : function() {
		if (hui.browser.gecko) {
			var center = hui.firstByClass(this.element,'in2igui_layout_center');
			if (center) {
				center.style.height='100%';
			}
		}
		if (!hui.browser.msie7 && !hui.browser.msie8 && !hui.browser.msie9) {
			return;
		}
		if (this.diff===undefined) {
			var head = hui.firstByClass(this.element,'in2igui_layout_top');
			var top = hui.firstByTag(head,'*').clientHeight;
			var foot = hui.firstByTag(hui.firstByTag(this.element,'tfoot'),'td');
			var bottom = 0;
			if (foot) {
				bottom = hui.firstByTag(foot,'*').clientHeight;
			}
			top += hui.getTop(this.element);
			this.diff = bottom+top;
			if (this.element.parentNode!==document.body) {
				this.diff+=15;
			} else {
			}
		}
		var tbody = hui.firstByTag(this.element,'tbody');
		var cell = hui.firstByTag(tbody,'td');
		cell.style.height = (hui.getViewPortHeight()-this.diff+5)+'px';
	}
};


/**
 * @constructor
 * @param {Object} options { element «Node | id», name: «String» }
 */
hui.ui.Columns = function(options) {
	this.name = options.name;
	this.options = options || {};
	this.element = hui.get(options.element);
	this.body = hui.firstByTag(this.element,'tr');
	hui.ui.extend(this);
}

/**
 * Creates a new Columns opject
 */
hui.ui.Columns.create = function(options) {
	options = options || {};
	options.element = hui.build('table',{'class' : 'in2igui_columns',html : '<tbody><tr></tr></tbody>'});
	return new hui.ui.Columns(options);
}

hui.ui.Columns.prototype = {
	addToColumn : function(index,widget) {
		var c = this.ensureColumn(index);
		c.appendChild(widget.getElement());
	},
	setColumnStyle : function(index,style) {
		var c = this.ensureColumn(index);
		hui.setStyle(c,style);
	},
	setColumnWidth : function(index,width) {
		var c = this.ensureColumn(index);
		c.style.width=width+'px';
	},
	/** @private */
	ensureColumn : function(index) {
		var children = hui.getChildren(this.body);
		for (var i=children.length-1;i<index;i++) {
			this.body.appendChild(hui.build('td',{'class':'in2igui_columns_column'}));
		}
		return hui.getChildren(this.body)[index];
	}
}

/* EOF */