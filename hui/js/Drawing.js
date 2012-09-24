/**
 * @constructor
 */
hui.ui.Drawing = function(options) {
	this.options = hui.override({width:200,height:200},options);
	this.element = hui.get(options.element);
	hui.log({width:options.width,height:options.height})
	this.svg = this._build({tag:'svg',parent:this.element,attributes:{width:options.width,height:options.height}});
	this.element.appendChild(this.svg);
	this.name = options.name;
	hui.ui.extend(this);
}

hui.ui.Drawing.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('div',{'class':'hui_drawing',style:'position: relative; overflow: hidden;'});
	if (options.height) {
		e.style.height = options.height+'px';
	}
	if (options.width) {
		e.style.width = options.width+'px';
	}
	if (options.parent) {
		options.parent.appendChild(e);
	}
	return new hui.ui.Drawing(options);
}

hui.ui.Drawing.prototype = {
	setSize : function(width,height) {
		this.svg.setAttribute('width',width);
		this.svg.setAttribute('height',height);
		this.svg.style.width = width+'px';
		this.svg.style.height = height+'px';
	},
	clear : function() {
		hui.dom.clear(this.svg);
	},
	addLine : function(options) {
		var attributes = {
			x1 : options.x1,
			y1 : options.y1,
			x2 : options.x2,
			y2 : options.y2,
			style : 'stroke:'+(options.color || '#000')+';stroke-width:'+(options.width || 1)
		};
		if (options.from && options.to) {
			attributes.x1 = options.from.x,
			attributes.y1 = options.from.y,
			attributes.x2 = options.to.x,
			attributes.y2 = options.to.y
		}
		
		var node = this._build({
			tag : 'line',
			parent : this.svg,
			attributes : attributes
		});
		return new hui.ui.Drawing.Line(node);
	},
	addCircle : function(options) {
		var node = this._build({
			tag:'circle',
			parent:this.svg,
			attributes : {
				cx : options.cx,
				cy : options.cy,
				r : options.r,
				style : 'stroke:'+(options.color || '#000')+'; fill:'+(options.fill || '#fff')+'; stroke-width:'+(options.width==undefined ? 1 : options.width)}
		});
		return new hui.ui.Drawing.Circle(node);
	},
	addElement : function(options) {
		var node = hui.build('div',{style:'position:absolute;left:0;top:0;',parent:this.element,html:options.html}),
			element = new hui.ui.Drawing.Element(node);
		if (options.movable) {
			hui.drag.register({
				element : node,
				onBeforeMove : function(e) {
					this.fire('shapeWillMove',{shape:element,event:e});
				}.bind(this),
				onMove : function(e) {
					node.style.left = e.getLeft()+'px';
					node.style.top = e.getTop()+'px';
					this.fire('shapeMoved',{shape:element,event:e});
				}.bind(this),
				onAfterMove : function(e) {
					this.fire('shapeWasMoved',{shape:element,event:e});
				}.bind(this)
			})
		}
		return element;
	},
	_build : function(options) {
		if (false && (hui.browser.msie8 || hui.browser.msie7 || hui.browser.msie6)) {
			var line = document.createElement("v:line");
			line.setAttribute('from','0 0');
			line.setAttribute('to','100 100');
			line.setAttribute("fillcolor","#FF0000");
			line.setAttribute("strokeweight","2pt");
			return line;
			
			var frag = document.createDocumentFragment();
			frag.insertAdjacentHTML('beforeEnd',
				'<v:rect id="myRect" fillcolor="blue" style="top:10px;left:15px;width:50px;height:30px;position:absolute;"></biv:rect>'
			);
			document.body.appendChild(frag);
			return document.getElementById('myRect');
		} else {
			var node = document.createElementNS('http://www.w3.org/2000/svg',options.tag);
		}
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

if (hui.browser.msie8) {
	document.namespaces.add('v', 'urn:schemas-microsoft-com:vml', "#default#VML");
}



// Drawing

hui.ui.Drawing.Line = function(node) {
	this.node = node;
}

hui.ui.Drawing.Line.prototype = {
	setFrom : function(point) {
		this.node.setAttribute('x1',point.x);
		this.node.setAttribute('y1',point.y);
	},
	setTo : function(point) {
		this.node.setAttribute('x2',point.x);
		this.node.setAttribute('y2',point.y);
	}
}



// Circle

hui.ui.Drawing.Circle = function(node) {
	this.node = node;
}

hui.ui.Drawing.Circle.prototype = {
	setCenter : function(point) {
		this.node.setAttribute('cx',point.x);
		this.node.setAttribute('cy',point.y);
	}
}



// Element

hui.ui.Drawing.Element = function(node) {
	this.node = node;
}

hui.ui.Drawing.Element.prototype = {
	setPosition : function(point) {
		this.node.style.left = point.x+'px';
		this.node.style.top = point.y+'px';
	},
	setCenter : function(point) {
		this.node.style.left = (point.x - this.node.clientWidth/2)+'px';
		this.node.style.top = (point.y - this.node.clientHeight/2)+'px';
	}
}