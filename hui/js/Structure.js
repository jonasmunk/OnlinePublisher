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
		var b = hui.get.firstByClass(this.element,'hui_structure_bottom');
		var m = hui.get.firstByClass(this.element,'hui_structure_middle');
		if (m) {
			m.style.top = (t ? t.clientHeight+2 : 0)+'px'
			m.style.bottom = (b ? b.clientHeight+2 : 0)+'px'
		}
	}
}