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

hui.ui.Structure.create = function(options) {
	options = hui.override({},options);
	
	options.element = hui.dom.parse('<div class="hui_structure">'+
			'<div class="hui_structure_middle">'+
			'<div class="hui_structure_left"></div>'+
			'<div class="hui_structure_center"></div>'+
			'</div>'+
			'</div>');
	return new hui.ui.Structure(options);
}

hui.ui.Structure.prototype = {
	
	addLeft : function(widget) {
		var tbody = hui.get.firstByClass(this.element,'hui_structure_left');
		tbody.appendChild(widget.element);
	},
	
	addCenter : function(widget) {
		var tbody = hui.get.firstByClass(this.element,'hui_structure_center');
		tbody.appendChild(widget.element);
	},
	$$layout : function() {
		var t = hui.get.firstByClass(this.element,'hui_structure_top');
		var b = hui.get.firstByClass(this.element,'hui_structure_bottom');
		var m = hui.get.firstByClass(this.element,'hui_structure_middle');
		if (m) {
			m.style.top = (t ? t.clientHeight+2 : 0)+'px'
			m.style.bottom = (b ? b.clientHeight+2 : 0)+'px'
		}
	}
}