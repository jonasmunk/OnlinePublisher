/**
 * Simple container
 * @param {Object} The options
 * @constructor
 */
hui.ui.Fragment = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.name = options.name;
	hui.ui.extend(this);
}

hui.ui.Fragment.prototype = {
	show : function() {
		this.element.style.display='block';
		hui.ui.callVisible(this);
	},
	hide : function() {
		this.element.style.display='none';
		hui.ui.callVisible(this);
	},
	setHTML : function(html) {
		this.element.innerHTML = html;
    this.fireSizeChange();
	},
	setContent : function(htmlWidgetOrNode) {
		this.element.innerHTML = '';
		this.element.appendChild(htmlWidgetOrNode);
    this.fireSizeChange();
	}
}

/* EOF */