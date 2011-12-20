/**
 * @constructor
 */
hui.ui.Drawing = function(options) {
	this.options = hui.override({width:200,height:200},options);
	this.element = hui.get(options.element);
	this.svg = this._build({tag:'svg',parent:this.element,attributes:{width:options.width,height:options.height}});
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
	if (options.width) {
		e.style.width=options.width+'px';
	}
	return new hui.ui.Drawing(options);
}

hui.ui.Drawing.prototype = {
	addLine : function(options) {
		var node = this._build({tag:'line',parent:this.svg,attributes:{x1:options.x1,y1:options.y1,x2:options.x2,y2:options.y2,style:'stroke:'+(options.color || '#000')+';stroke-width:'+(options.width || 1)}});
		return new hui.ui.Drawing.Line(node);
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

hui.ui.Drawing.Line = function(node) {
	this.node = node;
}

hui.ui.Drawing.Line.prototype = {
	setFrom : function(x,y) {
		this.node.setAttribute('x1',x);
		this.node.setAttribute('y1',y);
	},
	setTo : function(x,y) {
		this.node.setAttribute('x2',x);
		this.node.setAttribute('y2',y);
	}
}