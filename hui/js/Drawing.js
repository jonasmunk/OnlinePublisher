/**
 * @constructor
 */
hui.ui.Drawing = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.svg = this._build({tag:'svg',parent:this.element,attributes:{width:400,height:400}});
	this.element.appendChild(this.svg);
	this.name = options.name;
	hui.ui.extend(this);
}

hui.ui.Drawing.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('div',{'class':'hui_drawing'});
	if (options.height) {
		e.style.height=options.height+'px';
	}
	return new hui.ui.Drawing(options);
}

hui.ui.Drawing.prototype = {
	addLine : function(options) {
		this._build({tag:'line',parent:this.svg,attributes:{x1:0,y1:0,x2:300,y2:300,style:'stroke:rgb(99,99,99);stroke-width:2'}});
	},
	_build : function(options) {
		var node = document.createElementNS('http://www.w3.org/2000/svg',options.tag);
		if (options.attributes) {
			for (att in options.attributes) {
				node.setAttribute(att,options.attributes[att]);
			}
		}
		if (options.parent) {
			options.parent.appendChild(node);
		}
		return node;
	}
}