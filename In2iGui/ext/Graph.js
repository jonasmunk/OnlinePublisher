In2iGui.Graph = function(options) {
	this.options = options;
	this.name = options.name;
	this.element = n2i.get(options.element);
	
	var impls = {force:In2iGui.Graph.Protoviz,graffle:In2iGui.Graph.Raphael};
	
	this.impl = impls[this.options.layout];
	
	this.impl.init(this);
	
	In2iGui.extend(this);
	if (options.source) {
		options.source.listen(this);
	}
}

In2iGui.Graph.prototype = {
	setData : function(data) {
		this.impl.setData(data);
	},
	$objectsLoaded : function(data) {
		n2i.log('Data loaded');
		this.setData(data);
	}
}

In2iGui.Graph.Protoviz = {
	init : function(parent) {
		this.parent = parent;
		var w = document.body.clientWidth,
  			h = document.body.clientHeight;

		this.vis = new pv.Panel()
			.canvas(this.parent.element)
		    .width(this.parent.element.clientWidth)
		    .height(this.parent.element.clientHeight)
		    .fillStyle("white")
		    .event("mousedown", pv.Behavior.pan())
		    .event("mousewheel", pv.Behavior.zoom());

		
	},
	convert : function(data) {
		var result = {nodes:[],links:[]};
		for (var i=0; i < data.nodes.length; i++) {
			var node = data.nodes[i];
			result.nodes.push(node)
		};
		for (var i=0; i < data.edges.length; i++) {
			var edge = data.edges[i];
			result.links.push({source:this.getIndex(edge.from,data.nodes),target:this.getIndex(edge.to,data.nodes),label:edge.label});
		};
		return result;
	},
	getIndex : function(id,nodes) {
		for (var i=0; i < nodes.length; i++) {
			if (id===nodes[i].id) {
				return i;
			}
		};
	},
	setData : function(data) {
		var colors = pv.Colors.category19();
		data = this.convert(data);
		
		var force = this.vis.add(pv.Layout.Force)
		    .nodes(data.nodes)
		    .links(data.links);
		
		
		force.link.add(pv.Line).lineWidth(2).anchor("center").add(pv.Label).text(function() {this.anchorTarget.label});

		force.node.add(pv.Dot)
		    .size(function(d) {return 40;return (d.linkDegree + 4) * Math.pow(this.scale, -1.5)})
		    .fillStyle(function(d) {return d.fix ? "brown" : colors(d.group)})
		    .strokeStyle(function() {return this.fillStyle().darker()})
		    .lineWidth(1)
		    .title(function(d) {return d.label})
		    .event("mousedown", pv.Behavior.drag())
		    .event("click", function(x) {console.log(data.nodes[x.index])})
		    .event("drag", force);

		force.node.add(pv.Label).text(function(d) {return d.label}).textAlign('center').textBaseline('middle');
		
		//force.link.add(pv.Label).text(function(d) {return d.label}).textAlign('left').textBaseline('middle');

		this.vis.render();
	}
	
}

In2iGui.Graph.Raphael = {
	init : function(parent) {
		this.parent = parent;
		n2i.log('Initializing');
		
		Raphael.fn.connection = function (obj1, obj2, line, bg, text) {
			if (obj1.line && obj1.from && obj1.to) {
				line = obj1;
				obj1 = line.from;
				obj2 = line.to;
			}
			var bb1 = obj1.getBBox(),
				bb2 = obj2.getBBox(),
				p = [{x: bb1.x + bb1.width / 2, y: bb1.y - 1},
				{x: bb1.x + bb1.width / 2, y: bb1.y + bb1.height + 1},
				{x: bb1.x - 1, y: bb1.y + bb1.height / 2},
				{x: bb1.x + bb1.width + 1, y: bb1.y + bb1.height / 2},
				{x: bb2.x + bb2.width / 2, y: bb2.y - 1},
				{x: bb2.x + bb2.width / 2, y: bb2.y + bb2.height + 1},
				{x: bb2.x - 1, y: bb2.y + bb2.height / 2},
				{x: bb2.x + bb2.width + 1, y: bb2.y + bb2.height / 2}],
				d = {}, dis = [];
			for (var i = 0; i < 4; i++) {
				for (var j = 4; j < 8; j++) {
					var dx = Math.abs(p[i].x - p[j].x),
						dy = Math.abs(p[i].y - p[j].y);
					if ((i == j - 4) || (((i != 3 && j != 6) || p[i].x < p[j].x) && ((i != 2 && j != 7) || p[i].x > p[j].x) && ((i != 0 && j != 5) || p[i].y > p[j].y) && ((i != 1 && j != 4) || p[i].y < p[j].y))) {
						dis.push(dx + dy);
						d[dis[dis.length - 1]] = [i, j];
					}
				}
			}
			if (dis.length == 0) {
				var res = [0, 4];
			} else {
				res = d[Math.min.apply(Math, dis)];
			}
			var x1 = p[res[0]].x,
				y1 = p[res[0]].y,
				x4 = p[res[1]].x,
				y4 = p[res[1]].y;
			dx = Math.max(Math.abs(x1 - x4) / 2, 10);
			dy = Math.max(Math.abs(y1 - y4) / 2, 10);
			var x2 = [x1, x1, x1 - dx, x1 + dx][res[0]].toFixed(3),
				y2 = [y1 - dy, y1 + dy, y1, y1][res[0]].toFixed(3),
				x3 = [0, 0, 0, 0, x4, x4, x4 - dx, x4 + dx][res[1]].toFixed(3),
				y3 = [0, 0, 0, 0, y1 + dy, y1 - dy, y4, y4][res[1]].toFixed(3);
			var path = ["M", x1.toFixed(3), y1.toFixed(3), "C", x2, y2, x3, y3, x4.toFixed(3), y4.toFixed(3)].join(",");
			if (line && line.line) {
				line.bg && line.bg.attr({path: path});
				line.line.attr({path: path});
				line.text.attr({x:x1+(x4-x1)/2,y:y4+(y1-y4)/2});
			} else {
				var color = typeof line == "string" ? line : "#000";
				return {
					line: this.path(path).attr({stroke: color, fill: "none", "stroke-width": 2, "stroke-opacity": .5}),
					from: obj1,
					to: obj2,
					text : this.text(x1+(x4-x1)/2, y4+(y1-y4)/2,text).attr({fill:'#fff'})
				};
			}
		};
	},


 	setData : function (data) {
	    var dragger = function () {
	        this.ox = this.type == "rect" ? this.attr("x") : this.attr("cx");
	        this.oy = this.type == "rect" ? this.attr("y") : this.attr("cy");
	        this.animate({"fill-opacity": .8}, 500);
	    },
        move = function (dx, dy) {
			var x = this.ox + dx,
				y = this.oy + dy;
            var att = this.type == "rect" ? {x: x, y: y} : {cx: this.ox + dx, cy: this.oy + dy};
            this.attr(att);
            for (var i = connections.length; i--;) {
                r.connection(connections[i]);
            }
			this.text.attr({x:x+(this.getBBox().width/2),y:y+15});
            r.safari();
        },
        up = function () {
            this.animate({"fill-opacity": .1}, 500);
        },
		el = this.parent.element,
		width = el.clientWidth,
		height = el.clientHeight,
        r = Raphael(el, width, height),
        connections = [],
		shapes = [],
		idsToShape = {};
		for (var i=0; i < data.nodes.length; i++) {
			var node = data.nodes[i],
				left = Math.random()*(width-100)+50,
				top = Math.random()*(height-100)+50,
				shape = r.rect(left, top, 20, 30, 5),
				text = r.text(left,top+15,node.label),
				box = text.getBBox();
			text.attr({x:left+(box.width+20)/2,fill:'#fff'});
			shape.attr({width:box.width+20});
			shape.text = text;
			shapes.push(shape);
			idsToShape[node.id] = shape;
		};
	    for (var i = 0, ii = shapes.length; i < ii; i++) {
	        var color = "#fff";//Raphael.getColor();
	        shapes[i].attr({fill: "#559DFF", stroke: color, "fill-opacity": .1, "stroke-width": 2, cursor: "move"});
	        shapes[i].drag(move, dragger, up);
	    }
		
		for (var i=0; i < data.edges.length; i++) {
			var edge = data.edges[i];
			connections.push(r.connection(idsToShape[edge.from], idsToShape[edge.to], "#fff",null,edge.label));
		};
	}
}