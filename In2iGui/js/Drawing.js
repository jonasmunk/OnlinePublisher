/**
 * Overflow with scroll bars
 * @param {Object} The options
 * @constructor
 */
In2iGui.Drawing = function(options) {
	this.options = options;
	this.element = n2i.get(options.element);
	var svg = document.createElementNS('http://www.w3.org/2000/svg','svg');
	svg.setAttribute('width',400);
	svg.setAttribute('height',400);
	this.element.appendChild(svg);
	this.name = options.name;
	In2iGui.extend(this);
}

In2iGui.Drawing.create = function(options) {
	options = options || {};
	var e = options.element = n2i.build('div',{'class':'in2igui_drawing'});
	if (options.height) {
		e.style.height=options.height+'px';
	}
	return new In2iGui.Drawing(options);
}

In2iGui.Drawing.prototype = {
	build : function(options) {
		var node = document.createElementNS('http://www.w3.org/2000/svg',options.tag);
	}
}