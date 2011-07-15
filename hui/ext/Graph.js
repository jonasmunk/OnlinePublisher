hui.ui.Graph = function(options) {
	this.options = options;
	this.name = options.name;
	this.element = hui.get(options.element);
	this.ready = false;
	this.defered = [];
	
	var impls = {force:hui.ui.Graph.Protoviz,graffle:hui.ui.Graph.Raphael,d3:hui.ui.Graph.D3};
	
	this.impl = impls[this.options.layout];
	
	hui.ui.extend(this);
	hui.log('Initializing implementation...');
	this.impl.init(this);
	if (options.source) {
		options.source.listen(this);
	}
}

hui.ui.Graph.prototype = {
	setData : function(data) {
		this._defer(function() {
			this.impl.setData(data);
		}.bind(this));
	},
	_defer : function(func) {
		if (this.ready) {
			func();
		} else {
			hui.log('Defering function')
			this.defered.push(func);
		}
	},
	/** @private */
	$objectsLoaded : function(data) {
		hui.log('Data loaded');
		this.setData(data);
	},
	/** @private */
	implIsReady : function() {
		hui.log('Implementation is ready!');
		this.ready = true;
		for (var i=0; i < this.defered.length; i++) {
			this.defered[i]();
		};
	},
	/** @private */
	implNodeWasClicked : function(node) {
		this.fire('clickNode',node);
	},
	show : function() {
		hui.log('graph.show');
		this.element.style.display='block';
	},
	$$layout : function() {
		hui.log('graph.layout');
		this.impl.resize(this.element.parentNode.clientWidth,this.element.parentNode.clientHeight);
	},
	$$layoutChanged : function() {
		hui.log('graph.layoutChanged');
		window.setTimeout(this.$$layout.bind(this),100);
	}
}

hui.ui.Graph.Protoviz = {
	init : function(parent) {
		this.parent = parent;
		hui.require(hui.ui.context+'/hui/lib/protovis-3.2/protovis-r3.2.js',function() {
			var w = document.body.clientWidth,
  			h = document.body.clientHeight;

			this.vis = new pv.Panel()
				.canvas(this.parent.element)
			    .width(this.parent.element.clientWidth)
			    .height(this.parent.element.clientHeight)
			    .fillStyle("white")
			    .event("mousedown", pv.Behavior.pan())
			    .event("mousewheel", pv.Behavior.zoom());
			hui.log('Protoviz initialized')
			parent.implIsReady();
		}.bind(this))		
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
		hui.log('Setting data...')
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

hui.ui.Graph.D3 = {
	init : function(parent) {
		this.parent = parent;
		var self = this;
		hui.require(hui.ui.context+'/hui/lib/d3/d3.js',function() {
			hui.log('d3 loaded');
			hui.require(hui.ui.context+'/hui/lib/d3/d3.geom.js',function() {
				hui.log('d3.geom loaded');
				hui.require(hui.ui.context+'/hui/lib/d3/d3.layout.js',function() {
					hui.log('d3.layout loaded');
					self._init();
					parent.implIsReady();
				})
			})
		});
	},
	resize : function(width,height) {
		if (this.vis) {
			this.vis.attr('width',width);
			this.vis.attr('height',height);
		}
		if (this.layout) {
			this.layout.size([width,height]);
			this.layout.start();
		}
	},
	_init : function() {
		hui.log('Creating visualization...');
		var w = this.parent.element.clientWidth,
	    h = this.parent.element.clientHeight,
	    fill = d3.scale.category20();
	
		this.vis = d3.select(this.parent.element)
			.append("svg:svg")
			.attr("width", w)
			.attr("height", h);
		
	},
	_onClickNode : function(node) {
		this.parent.implNodeWasClicked(node);
	},
	_findById : function(nodes,id) {
		for (var i = nodes.length - 1; i >= 0; i--){
			if (nodes[i].id===id) {
				return i;
			}
		};
		return null;
	},
	_convert : function(data) {
		var nodes = data.nodes;
		data.links = data.edges;
		for (var i = data.links.length - 1; i >= 0; i--){
			var link = data.links[i];
			link.source = this._findById(nodes,link.from);
			link.target = this._findById(nodes,link.to);
		};
		hui.log(data);
		return data;
		
		return {"nodes":[{"name":"Person","group":1,"icon":"monochrome/person"},{"name":"Email","group":1},{"name":"Group","group":1}],"links":[{"source":1,"target":0,"value":1},{"source":0,"target":0,"value":2},{"source":1,"target":2,"value":2}]};
	},
	
	setData : function(data) {
		var w = this.parent.element.clientWidth,
	    h = this.parent.element.clientHeight;
		var json = this._convert(data);
		
		var force = this.layout = d3.layout.force()
			.charge(-200)
			.gravity(0.10)
			.distance(100)
			.nodes(json.nodes)
			.links(json.links)
			.size([w, h]);
		var link = this.vis.selectAll("line.link")
			.data(json.links)
			.enter().append("svg:line")
			.attr("class", "hui_graph_link")
			.style("stroke-width", function(d) { return d.label=='Friends' ? 3 : 1 })
			.attr("x1", function(d) { return d.source.x; })
			.attr("y1", function(d) { return d.source.y; })
			.attr("x2", function(d) { return d.target.x; })
			.attr("y2", function(d) { return d.target.y; });
	
		var node = this.vis.selectAll("circle.node")
			.data(json.nodes)
			.enter()
			.append("svg:g")
			.attr('class','hui_node')
			.attr("cx", function(d) { return d.x; })
			.attr("cy", function(d) { return d.y; })
			.style("fill",'none')
			.call(force.drag);
			node.on('click',this._onClickNode.bind(this));
		var self = this;	
		node.each(function(individual) {
			var x = d3.select(this);
			var icon = self.buildIcon(individual.icon,x);
		})
//			node.attr("transform", "translate("+(w*Math.random())+","+(h*Math.random())+")")
		
			/*var circle = node
				.append('svg:circle').attr('r',10)
				.attr("class", "node")
				//.attr("cx", function(d) { return d.x; })
	      		//.attr("cy", function(d) { return d.y; })
	     		.style("fill", function(d) { return fill(d.group); })
	      		;*/
		var text = node
			.append('svg:text')
			.attr('class','hui_graph_label')
			.attr("dx", "13")
			.attr("dy", "5")
			.text(function(d) { return d.label; })
	
		node.append("svg:title").text(function(d) { return d.name; });
	
	  	this.vis.style("opacity", 1e-6)
	    	.transition()
			.duration(2000)
			.style("opacity", 1);
	
		force.on("tick", function() {
			link.attr("x1", function(d) { return d.source.x; })
				.attr("y1", function(d) { return d.source.y; })
				.attr("x2", function(d) { return d.target.x; })
				.attr("y2", function(d) { return d.target.y; });
	
	    	node.attr("transform", function(d) { return "translate("+d.x+","+d.y+")" })
		});
		force.start()
		hui.log('Starting...');
	},
	buildIcon : function(icon,parent) {
		if (icon=='monochrome/person') {
			var node = parent.append('svg:path').attr('class','hui_graph_icon');
			node.attr('d','M-9.315,10c0,0-0.575-2.838,1.863-3.951c1.763-0.799,2.174-0.949,2.512-1.2 c0.138-0.087,0.263-0.198,0.438-0.351c0.661-0.561,0.562-1.324,1.038-1.562c0.474-0.225,0.424,0.238,0.524,0 c0.101-0.225-0.075-1.799,0-1.551c0.062,0.252-0.863-1.636-0.901-2.611C-3.888-2.439-4.702-2.99-4.613-3.651 c0.212-1.513,1.472-2.322,1.472-2.322s-2.423-0.454-1.36-1.478c1.062-1.012,1.474-1.4,2.6-2.076c1.138-0.663,2.674-0.599,4.163,0 C3.749-8.914,4.124-8.489,4.61-7.602c0.425,0.762,0.45,1.326,0.413,1.813C4.986-5.314,5.049-4.926,5.049-4.926 s0.499,0.112,0.513,0.837c0.013,0.687-0.175,1.699-0.551,2.162C4.861-1.752,4.599-1.114,4.197-0.264 C3.812,0.574,3.3,1.898,3.3,1.898s0.012,0.725,0,0.926c-0.039,0.45,0.649,0.012,0.962,0.512c0.312,0.501,0.1,0.85,0.799,1.162 c0.688,0.312,2.639,1.562,3.588,2.151C9.762,7.337,9.262,10,9.262,10H-9.315z').attr('fill-rule','evenodd');
		} else if (icon=='monochrome/folder') {
			var node = parent.append('svg:g').attr('class','hui_graph_icon');
			node.append('svg:polygon').attr('points','-10,-2 -8.461,8 8.461,8 10,-2');
			node.append('svg:polygon').attr('points','8,-5 -0.77,-5 -3.846,-8 -8,-8 -8,-5.384 -8,-4 8,-4');
		} else if (icon=='monochrome/image') {
			var node = parent.append('svg:g').attr('class','hui_graph_icon');
			node.append('svg:path').attr('d','M7.5-5.625v11.25h-15v-11.25H7.5 M10-8.125h-20v16.25h20V-8.125L10-8.125z');
			node.append('svg:path').attr('d','M-6.25,4.375h12.5l0,0l0,0v-4.534L4.271-3.75L1.584,2.054L0,0.435c0,0-2.193,0.815-2.917,2.065 c-1.151-0.625-1.776,0-1.776,0L-6.25,4.375z');
			node.append('svg:circle').attr('cx','-2.819').attr('cy','-2.818').attr('r','1.875');
		} else {
			var node = parent.append('svg:g').attr('class','hui_graph_icon');
			node.append('svg:polygon').attr('points','0.909,-8.182 0.909,-2.727 6.364,-2.727');
			node.append('svg:polygon').attr('points','-8.182,-10 -0.91,-10 -0.91,-0.909 8.182,-0.909 8.182,10 -8.182,10');
		}
	}
}

hui.ui.Graph.Raphael = {
	init : function(parent) {
		this.parent = parent;
		hui.require(hui.ui.context+'/hui/lib/raphael-min.js',function() {
			hui.log('Raphael is loadd');
			this._extend();
			parent.implIsReady()
		}.bind(this));
	},
	_extend : function() {
		hui.log('Extending Raphael...')
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
		}
	},

 	setData : function (data) {
		hui.log(data);
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