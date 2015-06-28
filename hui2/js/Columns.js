/**
 * @constructor
 * @param {Object} options { element «Node | id», name: «String» }
 */
hui.ui.Columns = function(options) {
	this.name = options.name;
	this.options = options || {};
	this.element = hui.get(options.element);
	this.body = hui.get.firstByTag(this.element,'tr');
	hui.ui.extend(this);
}

/**
 * Creates a new Columns opject
 */
hui.ui.Columns.create = function(options) {
	options = options || {};
	options.flexible = true;
	options.element = hui.build('table',{'class' : 'hui_columns',html : '<tbody><tr></tr></tbody>'});
	return new hui.ui.Columns(options);
}

hui.ui.Columns.prototype = {
	$$layout : function() {
		if (this.options.flexible) {
			return;
		}
		this.element.style.height = hui.position.getRemainingHeight(this.element)+'px';
		var children = hui.get.children(this.element);
		var left = 0;
		for (var i=0; i < children.length; i++) {
			var child = children[i];
			var width = (this.element.clientWidth/children.length);
			child.style.width = width+'px'
			child.style.position = 'absolute'
			child.style.marginLeft = left+'px';
			child.style.height = this.element.clientHeight+'px'
			left+=width;
		};
	},
	addToColumn : function(index,widget) {
		var c = this._ensureColumn(index);
		c.appendChild(widget.getElement());
	},
	setColumnStyle : function(index,style) {
		var c = this._ensureColumn(index);
		hui.style.set(c,style);
	},
	setColumnWidth : function(index,width) {
		var c = this._ensureColumn(index);
		c.style.width=width+'px';
	},
	_ensureColumn : function(index) {
		var children = hui.get.children(this.body);
		for (var i=children.length-1;i<index;i++) {
			this.body.appendChild(hui.build('td',{'class':'hui_columns_column'}));
		}
		return hui.get.children(this.body)[index];
	}
}

/* EOF */