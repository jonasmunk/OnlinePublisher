/**
 * @constructor
 */
hui.ui.Drawing = function(options) {
	this.options = hui.override({width:200,height:200},options);
	this.element = hui.get(options.element);
	hui.log({width:options.width,height:options.height})
	this.svg = hui.ui.Drawing._build({tag:'svg',parent:this.element,attributes:{width:options.width,height:options.height}});
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
		hui.get(options.parent).appendChild(e);
	}
	return new hui.ui.Drawing(options);
}

hui.ui.Drawing.prototype = {
	setSize : function(width,height) {
		this.svg.setAttribute('width',width);
		this.svg.setAttribute('height',height);
		this.svg.style.width = width+'px';
		this.svg.style.height = height+'px';
		this.element.style.width = width+'px';
		this.element.style.height = height+'px';
	},
	clear : function() {
		hui.dom.clear(this.svg);
	},
	addLine : function(options) {
		options.parent = this.svg;
		return hui.ui.Drawing.Line.create(options);
	},
	addRect : function(options) {
		options.parent = this.svg;
		return hui.ui.Drawing.Rect.create(options);
	},
	addCircle : function(options) {
		options.parent = this.svg;
		return hui.ui.Drawing.Circle.create(options);
	},
	addArc : function(options) {
		options.parent = this.svg;
		return hui.ui.Drawing.Arc.create(options);
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
	}
}

hui.ui.Drawing._build = function(options) {
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
		for (var att in options.attributes) {
			node.setAttribute(att,options.attributes[att]);
		}
	}
	if (options.parent) {
		options.parent.appendChild(node);
	}
	return node;
}

if (hui.browser.msie8) {
	document.namespaces.add('v', 'urn:schemas-microsoft-com:vml', "#default#VML");
}



// Line

hui.ui.Drawing.Line = function(options) {
	this.node = options.node;
	this.endNode = options.endNode;
	this.from = options.from;
	this.to = options.to;
	this._updateEnds();
}

hui.ui.Drawing.Line.create = function(options) {
	if (!options.from) {
		options.from = {x:options.x1,y:options.y1};
	}
	if (!options.to) {
		options.to = {x:options.x2,y:options.y2};
	}
	
	var attributes = {
		x1 : options.from.x.toFixed(10),
		y1 : options.from.y.toFixed(10),
		x2 : options.to.x.toFixed(10),
		y2 : options.to.y.toFixed(10),
		style : 'stroke:'+(options.color || '#000')+';stroke-width:'+(options.width || 1)
	};
		
	options.node = hui.ui.Drawing._build({
		tag : 'line',
		parent : options.parent,
		attributes : attributes
	});
	if (options.end) {
		options.endNode = hui.ui.Drawing._build({
			tag : 'path',
			parent : options.parent,
			attributes : {d:'M 0 -1 L 5 10 L -5 10',fill:options.color || '#000'}
		})
	}
	return new hui.ui.Drawing.Line(options);
}

hui.ui.Drawing.Line.prototype = {
	setFrom : function(point) {
		this.from = point;
		this.node.setAttribute('x1',point.x.toFixed(10));
		this.node.setAttribute('y1',point.y.toFixed(10));
		this._updateEnds();
	},
	getFrom : function() {
		return this.from;
	},
	setTo : function(point) {
		this.to = point;
		this.node.setAttribute('x2',point.x.toFixed(10));
		this.node.setAttribute('y2',point.y.toFixed(10));
		this._updateEnds();
	},
	getTo : function() {
		return this.to;
	},
	_updateEnds : function() {
		//var deg = Math.atan((this.from.y-this.to.y) / (this.from.x-this.to.x)) * 180/Math.PI;
		if (this.endNode) {
			var deg = -90+Math.atan2(this.from.y-this.to.y, this.from.x-this.to.x)*180/Math.PI
			this.endNode.setAttribute('transform','translate('+(this.to.x.toFixed(10))+','+(this.to.y.toFixed(10))+') rotate('+(deg)+')')

		}
	},
	getDegree : function() {
		return Math.atan((this.from.y-this.to.y) / (this.from.x-this.to.x)) * 180/Math.PI;
	}
}



// Circle

hui.ui.Drawing.Circle = function(options) {
	this.node = options.node;
	this.properties = {};
}

hui.ui.Drawing.Circle.create = function(options) {
	var css = [];
	if (options.stroke) {
		if (options.stroke.color) {
			css.push('stroke:'+options.stroke.color);
		}
		if (options.stroke.width) {
			css.push('stroke-width:'+options.stroke.width);
		}
	}
	if (options.fill) {
		css.push('fill:'+options.fill);
	}
	options.node = hui.ui.Drawing._build({
		tag : 'circle',
		parent : options.parent,
		attributes : {
			cx : options.cx,
			cy : options.cy,
			r : options.r,
			style : css.join(';')
		}
	});
	return new hui.ui.Drawing.Circle(options);
};

hui.ui.Drawing.Circle.prototype = {
	setRadius : function(radius) {
		this.node.setAttribute("r",radius);
	},
	
	setCenter : function(point) {
		this.node.setAttribute('cx',point.x);
		this.node.setAttribute('cy',point.y);
	}
}



// Rect

hui.ui.Drawing.Rect = function(options) {
	this.node = options.node;
}

hui.ui.Drawing.Rect.create = function(options) {
	var css = [];
	if (options.stroke) {
		if (options.stroke.color) {
			css.push('stroke:'+options.stroke.color);
		}
		if (options.stroke.width) {
			css.push('stroke-width:'+options.stroke.width);
		}
	}
	if (options.fill) {
		css.push('fill:'+options.fill);
	}
	options.node = hui.ui.Drawing._build({
		tag : 'rect',
		parent : options.parent,
		attributes : {
			x : options.x,
			y : options.y,
			width : options.width,
			height : options.height,
			style : css.join(';')
		}
	});
	return new hui.ui.Drawing.Circle(options);
};

hui.ui.Drawing.Rect.prototype = {
	setPosition : function(point) {
		this.node.setAttribute('x',point.x);
		this.node.setAttribute('y',point.y);
	}
}


// Arc
hui.ui.Drawing.Arc = function(options) {
	this.node = options.node;
	this.options = hui.override({
		center : {x:100,y:100},
  		startDegrees : 0,
		endDegrees : 0,
  		innerRadius : 0, 
		outerRadius : 0,
		fill : '#eee'
	},options);
	this._redraw();
}

hui.ui.Drawing.Arc.create = function(options) {
	var css = [];
	if (options.stroke) {
		if (options.stroke.color) {
			css.push('stroke:'+options.stroke.color);
		}
		if (options.stroke.width) {
			css.push('stroke-width:'+options.stroke.width);
		}
	}
	options.node = hui.ui.Drawing._build({ tag : 'path' ,parent : options.parent, attributes : {fill : options.fill || '#000', style:css.join(';')}});
	var arc = new hui.ui.Drawing.Arc(options);
	return arc;
}

hui.ui.Drawing.Arc.prototype = {
	
	update : function(options) {
		this.options = hui.override(this.options,options);
		this._redraw();
	},
	_redraw : function() {
		var o = this.options,
			cx = o.center.x,
			cy = o.center.y,
			startRadians = (o.startDegrees || 0) * Math.PI/180,
			closeRadians = (o.endDegrees   || 0) * Math.PI/180,
			r1 = o.innerRadius,
			r2 = o.outerRadius;
		
		var points = [
			[
				cx + r2 * Math.cos(startRadians),
				cy + r2 * Math.sin(startRadians)
			],
			[
				cx + r2 * Math.cos(closeRadians),
				cy + r2 * Math.sin(closeRadians)
			],
			[
				cx + r1 * Math.cos(closeRadians),
				cy + r1 * Math.sin(closeRadians)
			],
			[
				cx + r1 * Math.cos(startRadians),
				cy + r1 * Math.sin(startRadians)
			]
		];

		var angleDiff = closeRadians - startRadians;
		var largeArc = (angleDiff % (Math.PI*2)) > Math.PI ? 1 : 0;
		var cmds = [
			"M"+points[0].join(),									// Move to P0
			"A"+[r2,r2,0,largeArc,1,points[1]].join(),				// Arc to  P1
			"L"+points[2].join(),									// Line to P2
			"A"+[r1,r1,0,largeArc,0,points[3]].join(),				// Arc to  P3
			"z" 		                               				// Close path (Line to P0)
		];		
		this.node.setAttribute('d',cmds.join(' '));
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


hui.geometry = {
	intersectLineLine : function(a1, a2, b1, b2) {
    
	    var ua_t = (b2.x - b1.x) * (a1.y - b1.y) - (b2.y - b1.y) * (a1.x - b1.x);
	    var ub_t = (a2.x - a1.x) * (a1.y - b1.y) - (a2.y - a1.y) * (a1.x - b1.x);
	    var u_b  = (b2.y - b1.y) * (a2.x - a1.x) - (b2.x - b1.x) * (a2.y - a1.y);

	    if ( u_b != 0 ) {
	        var ua = ua_t / u_b;
	        var ub = ub_t / u_b;

	        if ( 0 <= ua && ua <= 1 && 0 <= ub && ub <= 1 ) {
				return {
	                    x : a1.x + ua * (a2.x - a1.x),
	                    y : a1.y + ua * (a2.y - a1.y)
	               }
	        }
	    }

	    return null;
	},
	intersectLineRectangle : function(a1, a2, r1, r2) {
	    var min        = {x : Math.min(r1.x,r2.x),y : Math.min(r1.y,r2.y)};
	    var max        = {x : Math.max(r1.x,r2.x),y : Math.max(r1.y,r2.y)};
	    var topRight   = {x: max.x, y: min.y };
	    var bottomLeft = {x: min.x, y: max.y };
    
	    var inter1 = hui.geometry.intersectLineLine(min, topRight, a1, a2);
	    var inter2 = hui.geometry.intersectLineLine(topRight, max, a1, a2);
	    var inter3 = hui.geometry.intersectLineLine(max, bottomLeft, a1, a2);
	    var inter4 = hui.geometry.intersectLineLine(bottomLeft, min, a1, a2);
    
	    var result = [];

		if (inter1!=null) result.push(inter1);
		if (inter2!=null) result.push(inter2);
		if (inter3!=null) result.push(inter3);
		if (inter4!=null) result.push(inter4);
	    return result;
	},
	distance : function( point1, point2 ) {
		var xs = point2.x - point1.x;

		var ys = point2.y - point1.y;

		return Math.sqrt( xs * xs + ys * ys );
	}
}
