/** A diagram
 * @constructor
 */
hui.ui.Diagram = function(options) {
	this.options = hui.override({layout:'D3'},options);;
	this.name = options.name;
	this.nodes = [];
	this.lines = [];
	this.data = {};
	this.translation = {x:0,y:0};
	this.element = hui.get(options.element);
	this.width = this.element.clientWidth;	
	this.height = this.element.clientHeight;
	this.layout = hui.ui.Diagram[this.options.layout];
	//this.layout = hui.ui.Diagram.Springy;
	this.layout.diagram = this;
	hui.ui.extend(this);
	if (options.source) {
		options.source.listen(this);
	}
	this._init();
}

hui.ui.Diagram.create = function(options) {
	options = hui.override({width:null,height:null},options);
	
	options.element = hui.build('div',{'class':'hui_diagram',parent:options.parent,style:{height:options.height+'px'}});
	
	return new hui.ui.Diagram(options);
}

hui.ui.Diagram.prototype = {
	_init : function() {
		this.background = hui.ui.Drawing.create({
			width: this.width || 0,
			height: this.height || 0
		});
		this.element.appendChild(this.background.element);
		this.fire('added');
	},
	$$layout : function() {
		var newWidth = this.element.clientWidth;
		var newHeight = this.element.clientHeight;
		if (newWidth === this.width && newHeight === this.height) {
			// Only re-layout if size actually changed
			return;
		}
		this.width = newWidth;	
		this.height = newHeight;
		this.background.setSize(this.width,this.height);
		this.layout.resize();
		this.layout.resume();
	},
	_getMagnet : function(from,to,node) {
		var margin = 1;
		var size = node.getSize();
		var center = node.getCenter();
		var topLeft = {
				x : center.x - size.width/2 - margin,
				y : center.y - size.height/2 - margin
			},
			bottomRight = {
				x : topLeft.x + size.width + margin * 2,
				y : topLeft.y + size.height + margin * 2
			};
		var hits = [];
		hits = hui.geometry.intersectLineRectangle(from,to,topLeft,bottomRight);
		if (hits.length>0) {
			return hits[0];
		}
		return to;
	},
	
	// Data ...
	
	/** @private */
	$objectsLoaded : function(data) {
		this.setData(data);
	},
	setData : function(data) {
		this.data = data;
		this.clear();
		var nodes = data.nodes,
			lines = data.lines || data.edges;
		if (!nodes || !lines) {
			return;
		}
		for (var i=0; i < nodes.length; i++) {
			if (nodes[i].type=='icon') {
				this.addIcon(nodes[i]);
			} else {
				this.addBox(nodes[i]);				
			}
		};
		for (var i=0; i < lines.length; i++) {
			this.addLine(lines[i]);
		};
		if (this.layout.loaded) {
			this.layout.populate();
		} else {
			this.play();
		}
	},
	/** Deprecated */
	play : function() {
		this.layout.start();
	},
	resume : function() {
		if (this.layout.resume) { this.layout.resume() }
	},
	expand : function() {
		if (this.layout.expand) { this.layout.expand() }
	},
	contract : function() {
		if (this.layout.contract) { this.layout.contract() }
	},
	/** @private */
	$sourceShouldRefresh : function() {
		return hui.dom.isVisible(this.element);
	},
	/** @private */
	$visibilityChanged : function() {
		if (hui.dom.isVisible(this.element)) {
			this.width = this.element.clientWidth;	
			this.height = this.element.clientHeight;
			this.background.setSize(this.width,this.height);
			if (this.options.source) {
				this.options.source.refreshFirst();
			}
		}
	},
	clear : function() {
		this.layout.clear();
		this.selection = null;
		this.background.clear();
		this.lines = [];
		for (var i = this.nodes.length - 1; i >= 0; i--){
			hui.dom.remove(this.nodes[i].element);
		};
		this.nodes = [];
		var lines = hui.get.byClass(this.element,'hui_diagram_line_label');
		for (var i = lines.length - 1; i >= 0; i--){
			hui.dom.remove(lines[i]);
		};
	},
	
	addBox : function(options) {
		var box = hui.ui.Diagram.Box.create(options,this);
		this.add(box);
	},
	
	addIcon : function(options) {
		var box = hui.ui.Diagram.Icon.create(options,this);
		this.add(box);
	},
	add : function(widget) {
		var e = widget.element;
		this.element.appendChild(e);
		widget.setCenter({x:this.width/2,y:this.height/2});
		this.nodes.push(widget);
	},
	addLine : function(options) {
		var from = this.getNode(options.from),
			to = this.getNode(options.to);
		if (from==null || to==null) {
			hui.log('Unable to build line...');
			hui.log(options);
			return;
		}
		var fromCenter = this._getCenter(from),
			toCenter = this._getCenter(to);
		var lineNode = this.background.addLine({ from: fromCenter, to: toCenter, color: options.color || '#999' ,end:{}}),
			line = { from: options.from, fromNode : from, to: options.to, toNode : to, node: lineNode };
		if (options.label) {
			line.label = hui.build('span',{parent:this.element,'class':'hui_diagram_line_label',text:options.label});
			this._updateLine(line);
		}
		//hui.listen(lineNode.node,'click',function() {alert(line)});
		this.lines.push(line);
	},
	
	
	_getCenter : function(widget) {
		return widget.getCenter();
		var e = widget.element;
		return {
			x : Math.round(parseInt(e.style.left)+e.clientWidth/2),
			y : Math.round(parseInt(e.style.top)+e.clientHeight/2)
		};
	},
	getNode : function(id) {
		return this._getNode(id,this.nodes);
	},
	getDataNode : function(id) {
		return this._getNode(id,this.data.nodes);
	},
	_getNode : function(id,nodes) {
		if (nodes) {
			for (var i=0; i < nodes.length; i++) {
				if (nodes[i].id == id) {
					return nodes[i];
				}
			};
		}
		return null;		
	},
	
	// Drawing...
	
	_updateLine : function(line) {
		if (!line.label) {
			return;
		}
		var from = line.node.getFrom(),
			to = line.node.getTo(),
			label = line.label;
		var middle = { x : from.x+(to.x-from.x)/2, y : from.y+(to.y-from.y)/2 };
		//var deg = Math.atan((from.y-to.y) / (from.x-to.x)) * 180/Math.PI;
		line.label.style.webkitTransform='rotate('+line.node.getDegree()+'deg)';
		//line.label.innerHTML = Math.round(hui.geometry.distance(from,to));
		var width = Math.round(hui.geometry.distance(from,to)-30);
		// TODO: cache width + height
		var w = label.huiWidth = label.huiWidth || label.clientWidth;
		var h = label.huiHeight = label.huiHeight || label.clientHeight;
		w = Math.min(w,width);
		hui.style.set(line.label,{
			left : (middle.x-w/2)+'px',
			top : (middle.y-h/2)+'px',
			maxWidth : Math.max(0,width)+'px',
			visibility : width>10 ? '' : 'hidden'
		});
	},
	__nodeMoved : function(widget) {
		var center = this._getCenter(widget);
		for (var i=0; i < this.lines.length; i++) {
			var line = this.lines[i];
			if (line.from == widget.id) {
				var magnet = this._getMagnet(line.node.getTo(),center,widget);
				line.node.setFrom(magnet);
				var magnet2 = this._getMagnet(center,this._getCenter(line.toNode),line.toNode);
				line.node.setTo(magnet2);
				this._updateLine(line);
			}
			else if (line.to == widget.id) {
				var magnet = this._getMagnet(line.node.getFrom(),center,widget);
				line.node.setTo(magnet);
				var magnet2 = this._getMagnet(center,this._getCenter(line.fromNode),line.fromNode);
				line.node.setFrom(magnet2);
				this._updateLine(line);
			}
		};
	},
	__select : function(widget) {
		if (this.selection) {
			this.selection.setSelected(false);
		}
		this.selection = widget;
		this.selection.setSelected(true);
	},
	__nodeOpen : function(widget) {
		this.fire('open',this.getDataNode(widget.id));
	}
}


hui.ui.Diagram.Arbor = {
	running : false,
	loaded : false,
	diagram : null,

	_load : function() {
		hui.require(hui.ui.context+'/hui/lib/jquery.min.js',function() {
			hui.require(hui.ui.context+'/hui/lib/arbor/lib/arbor.js',function() {
				this.loaded = true;
				this.start();
			}.bind(this))
		}.bind(this));		
	},
	
	start : function() {
		if (!this.loaded) {
			this._load();
			return;
		}
		if (window.arbor==undefined) {
			hui.log('Arbor is not available!');
			return;
		}
		
		var repulsion = 50,
			stiffness = 600,
			friction = 0.5,
			gravity = true,
			fps = 40,
			dt = 0.02 //0.02,			// timestep to use for stepping the simulation
			precision = 0.6;	// accuracy vs. speed in force calculations
		
		var diagram = this.diagram;
		
		var renderer = {
  			init:  function(system) {
				hui.log("starting",system);
			},
  			redraw : function() {
				var sel = diagram.selection ? diagram.selection.id : null;
				system.eachNode(function(node,point) {
					if (node.name!=sel) {
						node.data.setCenter(point);						
					}
				});
				system.eachEdge(function(edge,point1,point2) {
					if (edge.target.name==sel) {
						point2 = diagram._getCenter(diagram.selection)
					}
					if (edge.source.name==sel) {
						point1 = diagram._getCenter(diagram.selection)
					}
					var line = edge.data.node;
					if (edge.source.name!=sel) {
						line.setFrom(diagram._getMagnet(point2,point1,edge.source.data));
					}
					if (edge.target.name!=sel) {
						line.setTo(diagram._getMagnet(point1,point2,edge.target.data));						
					}
					diagram._updateLine(edge.data);
				}.bind(this));
			}.bind(this)
		}
		var system = this.particleSystem = arbor.ParticleSystem(repulsion, stiffness, friction, gravity, fps, dt, precision);
		system.screenSize(diagram.element.clientWidth, diagram.element.clientHeight);
		system.screenPadding(50,100);
		system.renderer = renderer
		
		this.populate();
	},
	populate : function() {
		var system = this.particleSystem,
			nodes = this.diagram.nodes,
			lines = this.diagram.lines;
		
		for (var i=0; i < nodes.length; i++) {
			system.addNode(nodes[i].id, nodes[i]);
		};
		
		hui.each(lines,function(line) {
			system.addEdge(line.from, line.to, line);
		})
		
		window.setTimeout(function() {
			system.stop();
		},6000)
	},
	clear : function() {
		if (this.particleSystem) {
			this.particleSystem.prune(function() {return true});
			this.particleSystem.stop();
		}
	}
}



hui.ui.Diagram.D3 = {
	loaded : false,
	diagram : null,

	_load : function() {
		hui.require(hui.ui.context+'/hui/lib/d3.v3/d3.v3.min.js',function() {
			this.loaded = true;
			this.start();
		}.bind(this))
	},
	
	resize : function() {
		if (this.layout) {
			this.layout.size([this.diagram.width,this.diagram.height]);
		}
	},
	
	start : function() {
		if (!this.loaded) {
			this._load();
			return;
		}
		var diagram = this.diagram,
			nodes = diagram.nodes,
			lines = diagram.lines,
			width = diagram.element.clientWidth,
			height = diagram.element.clientHeight;
		
		for (var i=0; i < lines.length; i++) {
			lines[i].source = this._findById(nodes,lines[i].from);
			lines[i].target = this._findById(nodes,lines[i].to);
		};
		
		var force = this.layout = d3.layout.force()
            .linkDistance(100)
            .friction(0.9)
            .gravity(0.1)
            .theta(0.3)
            .linkStrength(0.2)
			.charge(-1000)
			.distance(100)
			.nodes(this.diagram.nodes)
			.links(this.diagram.lines)
			.size([width, height]);
		
		var ticker = function() {
			var sel = diagram.selection ? diagram.selection.id : null;
			var nodes = force.nodes(),
				links = force.links();
			for (var i=0; i < nodes.length; i++) {
				var node = diagram.nodes[nodes[i].index];
				if (node.id!=sel) {
					node.setCenter(nodes[i]);
				}
			};
			for (var i=0; i < links.length; i++) {
				var link = links[i];
				var source = link.source,
					sourceCenter = link.source.center;
				var target = link.target,
					targetCenter = link.target;
				if (source==diagram.selection) {
					sourceCenter = diagram._getCenter(diagram.selection)
				}
				if (target==diagram.selection) {
					targetCenter = diagram._getCenter(diagram.selection)
				}
				var from = diagram._getMagnet(sourceCenter,targetCenter,source)
				var to = diagram._getMagnet(targetCenter,sourceCenter,target)
				link.node.setFrom(from);
				link.node.setTo(to);
				diagram._updateLine(link);
			};
		};
		force.start();
		force.gravity(0.5);
		for (var i=0; i < 10000; i++) {
			force.tick()
		};
		force.gravity(0.1);
		
		force.on("tick", ticker);
		
		force.start()
	},
	
	resume : function() {
		if (this.layout) { this.layout.start(); }
	},
	expand : function() {
		if (this.layout) {
			this.layout.linkDistance(this.layout.linkDistance() * 1.3);
			this.layout.charge(this.layout.charge() * 1.3);
			this.layout.start();
		}
	},
	contract : function() {
		if (this.layout) {
			this.layout.linkDistance(Math.max(0,this.layout.linkDistance() * 0.9));
			this.layout.charge(Math.min(0,this.layout.charge() * 0.9));
			this.layout.start();
		}
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
		return data;		
	},
	populate : function() {
		this.start();
	},
	clear : function() {
		if (this.layout) {
			this.layout.stop();
		}
	}
	
}



hui.ui.Diagram.Springy = {
	loaded : false,
	diagram : null,

	_load : function() {
		hui.require(hui.ui.context+'/hui/lib/springy-master/springy.js',function() {
			this.loaded = true;
			this.start();
		}.bind(this))
	},
	
	start : function() {
		if (!this.loaded) {
			this._load();
			return;
		}
		var diagram = this.diagram,
			nodes = diagram.nodes,
			lines = diagram.lines,
			width = diagram.element.clientWidth,
			height = diagram.element.clientHeight;
		
		var graph = new Graph();
		var cachedNodes = {},
			cachedLines = {};
		for (var i=0; i < nodes.length; i++) {
			cachedNodes[nodes[i].id] = graph.newNode(nodes[i]);
		};
		
		for (var i=0; i < lines.length; i++) {
			var edge = graph.newEdge(
				cachedNodes[lines[i].from],
				cachedNodes[lines[i].to]
			);
			cachedLines[edge.id] = lines[i];
		};
		
		var layout = new Layout.ForceDirected(graph, width, height, 0.4);
		
		var toScreen = function(p) {
			  return {
				  x : (p.x*width/10)+width/2,
				  y : (p.y*height/10)+height/2
			  }
		  }
		
		var renderer = new Renderer(layout,
			function clear() {
			  
			},
			function drawEdge(edge, p1, p2) {
				var sel = diagram.selection ? diagram.selection.id : null;
				p1 = toScreen(p1);
				p2 = toScreen(p2);
				var line = cachedLines[edge.id];
				if (sel!=edge.source.data.id) {
					var from = diagram._getMagnet(p1,p2,edge.source.data)
					line.node.setFrom(from);
				}
				if (sel!=edge.target.data.id) {
					var to = diagram._getMagnet(p1,p2,edge.target.data)
					line.node.setTo(to);
				}
				diagram._updateLine(line);
			},
			function drawNode(node, p) {
				var sel = diagram.selection ? diagram.selection.id : null;
				if (node.data.id==sel) return;
				node.data.setCenter(toScreen(p));
			}
		);
		renderer.start();
	},
	_findById : function(nodes,id) {
		for (var i = nodes.length - 1; i >= 0; i--){
			if (nodes[i].id===id) {
				return nodes[i];
			}
		};
		return null;
	},
	populate : function() {
		
	},
	clear : function() {
		
	}
	
}


/** A box in a diagram
 * @constructor
 */
hui.ui.Diagram.Box = function(options) {
	this.options = options;
	this.id = options.id;
	this.name = options.name;
	this.element = hui.get(options.element);	
	this.center = {};
	this.size = null;
	hui.ui.extend(this);
	hui.ui.Diagram.util.enableDragging(this)
}

hui.ui.Diagram.Box.create = function(options,diagram) {
	options = hui.override({title:'Untitled',diagram:diagram},options);
	var e = options.element = hui.build('div',{'class':'hui_diagram_box'});
	hui.build('h1',{text:options.title,parent:e});
	if (options.properties) {
		var table = hui.build('table',{parent:e})
		for (var i=0; i < options.properties.length; i++) {
			var p = options.properties[i];
			var tr = hui.build('tr',{parent:table});
			hui.build('th',{parent:tr,text:p.label});
			var td = hui.build('td',{parent:tr,text:p.value || ''});
			if (p.hint) {
				hui.build('em',{parent:td,text:p.hint});
			}
		};
	}
	return new hui.ui.Diagram.Box(options);
}

hui.ui.Diagram.Box.prototype = {
	_syncSize : function() {
		if (this.size) {
			return;
		}
		this.size = {
			width : this.element.offsetWidth,
			height : this.element.offsetHeight
		};
	},
	getSize : function() {
		this._syncSize();
		return this.size;
	},
	getCenter : function() {
		return this.center;
	},
	setCenter : function(point) {
		this._syncSize();
		this.center = {x : point.x, y : point.y};
		this._updateCenter();
	},
	_updateCenter : function() {
		this.element.style.top = Math.round(this.center.y - this.size.height/2)+'px';
		this.element.style.left = Math.round(this.center.x - this.size.width/2)+'px';
	},
	setSelected : function(selected) {
		hui.cls.set(this.element,'hui_diagram_box_selected',selected);
	}
}

if (hui.browser.webkit) {
	hui.ui.Diagram.Box.prototype._updateCenter = function() {
		this.element.style.WebkitTransform = 'translate3d(' + Math.round(this.center.x - this.size.width/2) + 'px,' + Math.round(this.center.y - this.size.height/2) + 'px,0)';      
	}
}




/** A box in a diagram
 * @constructor
 */
hui.ui.Diagram.Icon = function(options) {
	this.options = options;
	this.id = options.id;
	this.name = options.name;
	this.element = hui.get(options.element);	
	this.center = {};
	hui.ui.extend(this);
	hui.ui.Diagram.util.enableDragging(this)
}

hui.ui.Diagram.Icon.create = function(options,diagram) {
	options = hui.override({icon:'common/folder',diagram:diagram},options);
	var e = options.element = hui.build('div',{'class':'hui_diagram_icon'});
	e.appendChild(hui.ui.createIcon(options.icon,32));
	if (options.title) {
		hui.build('strong',{parent:e,text:options.title})
	}
	return new hui.ui.Diagram.Icon(options);
}

hui.ui.Diagram.Icon.prototype = {
	_syncSize : function() {
		if (this.size) {
			return;
		}
		this.size = {
			width : this.element.offsetWidth,
			height : this.element.offsetHeight
		};
	},
	getSize : function() {
		this._syncSize();
		return this.size;
	},
	getCenter : function() {
		return this.center;
	},
	setCenter : function(point) {
		var e = this.element;
		e.style.top = Math.round(point.y - e.clientHeight/2)+'px';
		e.style.left = Math.round(point.x - e.clientWidth/2)+'px';
		this.center = {x : point.x, y : point.y};
	},
	setSelected : function(selected) {
		hui.cls.set(this.element,'hui_diagram_icon_selected',selected);
	}
}

/** Utilities **/

hui.ui.Diagram.util = {
	enableDragging : function(obj) {
		var diagram = obj.options.diagram;
		hui.cls.add(obj.element,'hui_diagram_dragable');
		var dragState = null;
		hui.drag.register({
			touch : true,
			element : obj.element,
			onStart : function() {
				hui.cls.add(obj.element,'hui_diagram_dragging');
				obj.fixed = true;
			},
			onNotMoved : function() {
				diagram.__select(obj);
				diagram.fire('select',obj.id);
			},
			onBeforeMove : function(e) {
				diagram.__nodeMoved(obj);
				e = hui.event(e);
				obj.element.style.zIndex = hui.ui.nextPanelIndex();
                var pos = obj.getCenter();
				var size = obj.getSize();
                pos = {left:pos.x - size.width/2,top:pos.y - size.height/2};
				var diagramPosition = hui.position.get(diagram.element);
				dragState = {
					left : e.getLeft() - pos.left,
					top : e.getTop()-pos.top
				};
				obj.element.style.right = 'auto';
			},
 			onMove : function(e) {
				var top = (e.getTop()-dragState.top);
				var left = (e.getLeft()-dragState.left);
				var size = obj.getSize();
				top += size.height/2;
				left += size.width/2;
				obj.setCenter({x:left,y:top});
				obj.px = left;
				obj.py = top;
				diagram.__nodeMoved(obj);
 			},
			onEnd : function() {
				hui.cls.remove(obj.element,'hui_diagram_dragging');
				obj.fixed = false;
				hui.log('end')
			}
		});
		hui.listen(obj.element,'dblclick',function(e) {
			diagram.__nodeOpen(obj);
		});
	}
}