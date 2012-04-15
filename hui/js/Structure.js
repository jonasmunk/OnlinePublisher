/**
 * @constructor
 * @param {Object} options { element «Node | id», name: «String» }
 */
hui.ui.Structure = function(options) {
	this.name = options.name;
	this.options = options || {};
	this.element = hui.get(options.element);
	hui.ui.extend(this);
}

hui.ui.Structure.prototype = {
	$$resize : function() {
		var t = hui.get.firstByClass(this.element,'hui_structure_top');
		var m = hui.get.firstByClass(this.element,'hui_structure_middle');
		m.style.top = (t.clientHeight+2)+'px'
	}
}