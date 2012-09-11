/** A diagram
 * @constructor
 */
hui.ui.Diagram = function(options) {
	this.options = options;
	this.name = options.name;
	this.nodes = [];
	this.lines = [];
	this.element = hui.get(options.element);
	this.width = this.element.clientWidth;	
	this.height = this.element.clientHeight;	
	hui.ui.extend(this);
	if (options.source) {
		options.source.listen(this);
	}
	this._init();
}

hui.ui.Diagram.create = function(options) {
	options = hui.override({width:400,height:500},options);
	
	options.element = hui.build('div',{'class':'hui_diagram'});
	
	return new hui.ui.Diagram(options);
}

hui.ui.Diagram.prototype = {
	_init : function() {
		this.background = hui.ui.Drawing.create({
			width: this.options.width || 0,
			height: this.options.height || 0
		});
		this.element.appendChild(this.background.element);
		hui.onReady(function() {
			this.width = this.element.clientWidth;	
			this.height = this.element.clientHeight;
			this.background.setSize(this.width,this.height)
		}.bind(this))

	},
	
	_initParticleSystem : function() {
		hui.require('http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js',function() {
			hui.require(hui.ui.context+'/hui/lib/arbor/lib/arbor.js',function() {
				var repulsion = 100,	// the force repelling nodes from each other
					stiffness = 600,	// the rigidity of the edges
					friction = 0.5,		// the amount of damping in the system
					gravity = false,	// an additional force attracting nodes to the origin
					fps = 55,			// frames per second
					dt = 0.02,			// timestep to use for stepping the simulation
					precision = 0.6;	// accuracy vs. speed in force calculations
		
				var myRenderer = {
		  			init:  function(system){ console.log("starting",system) },
		  			redraw : function() {
						hui.log('redraw')
						system.eachNode(function(node,point) {
							//if (draggedNode==node) {
							//	return;
							//}
							node.data.setCenter(point);
						});
						system.eachEdge(function(edge,point1,point2) {
							var line = edge.data.node;
							//hui.log(hui.toJSON(point1))
							line.setFrom(point1);
							line.setTo(point2);
							return;
							if (edge.source!==draggedNode) {
								line.setFrom(point1);
							} else {
								line.setFrom(draggedPoint);
							}
							if (edge.target!==draggedNode) {
								line.setTo(point2);
							} else {
								line.setTo(draggedPoint);
							}
						});
					}
				}
				var system = this.particleSystem = arbor.ParticleSystem(repulsion, stiffness, friction, gravity, fps, dt, precision);
				system.stop();
				system.screenSize(this.element.clientWidth, this.element.clientHeight);
				system.screenPadding(50,100);
				system.renderer = myRenderer
		
				for (var i=0; i < this.nodes.length; i++) {
					system.addNode(this.nodes[i].id, this.nodes[i]);
				};
		
				hui.each(this.lines,function(line) {
					system.addEdge(line.from, line.to, line);
			
				})
				system.start();
				window.setTimeout(function() {
					system.stop();
				},2000)
			}.bind(this))
		}.bind(this));
	},
	
	// Data ...
	
	/** @private */
	$objectsLoaded : function(data) {
		this.clear();
		var nodes = data.nodes,
			lines = data.lines;
		for (var i=0; i < nodes.length; i++) {
			this.addBox(nodes[i]);
		};
		for (var i=0; i < lines.length; i++) {
			this.addLine(lines[i]);
		};
		this._initParticleSystem();
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
		if (this.particleSystem) {
			this.particleSystem.stop();
		}
		this.background.clear();
		this.lines = [];
		for (var i = this.nodes.length - 1; i >= 0; i--){
			hui.dom.remove(this.nodes[i].element);
		};
		this.nodes = [];
	},
	
	addBox : function(options) {
		options.container = this;
		var box = hui.ui.Diagram.Box.create(options);
		this.add(box);
	},
	add : function(widget) {
		var e = widget.element;
		this.element.appendChild(e);
		e.style.left = (Math.random()*(this.width - e.clientWidth))+'px';
		e.style.top = (Math.random()*(this.height - e.clientHeight))+'px';
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
			
		var line = this.background.addLine({ from: fromCenter, to: toCenter, color: options.color || '#999' });
		this.lines.push({ from: options.from, to: options.to, node: line });
	},
	_getCenter : function(widget) {
		var e = widget.element;
		return {
			x : Math.round(parseInt(e.style.left)+e.clientWidth/2),
			y : Math.round(parseInt(e.style.top)+e.clientHeight/2)
		};
	},
	getNode : function(id) {
		for (var i=0; i < this.nodes.length; i++) {
			if (this.nodes[i].id == id) {
				return this.nodes[i];
			}
		};
		return null;
	},
	__nodeMoved : function(widget) {
		var center = this._getCenter(widget);
		for (var i=0; i < this.lines.length; i++) {
			var line = this.lines[i];
			if (line.from==widget.id) {
				line.node.setFrom(center);
			}
			else if (line.to==widget.id) {
				line.node.setTo(center);
			}
		};
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
	hui.ui.extend(this);
	this._addBehavior();
}

hui.ui.Diagram.Box.create = function(options) {
	var e = options.element = hui.build('div',{'class':'hui_diagram_box'});
	hui.build('h1',{text:options.title || 'Untitled',parent:e});
	if (options.properties) {
		var table = hui.build('table',{parent:e})
		for (var i=0; i < options.properties.length; i++) {
			var p = options.properties[i];
			var tr = hui.build('tr',{parent:table});
			hui.build('th',{parent:tr,text:p.label});
			hui.build('td',{parent:tr,text:p.value});
		};
	}
	return new hui.ui.Diagram.Box(options);
}

hui.ui.Diagram.Box.prototype = {
	_addBehavior : function() {
		hui.drag.register({
			element : this.element,
			onStart : this._onDragStart.bind(this),
			onBeforeMove : this._onBeforeMove.bind(this) ,
 			onMove : this._onMove.bind(this),
			onEnd : this._onDragEnd.bind(this)
		});
	},
	setCenter : function(point) {
		var e = this.element;
		e.style.top = Math.round(point.y - e.clientHeight/2)+'px';
		e.style.left = Math.round(point.x - e.clientWidth/2)+'px';
	},
	_onDragStart : function() {
		hui.cls.add(this.element,'hui_diagram_box_dragging');
	},
	_onBeforeMove : function(e) {
		e = hui.event(e);
		this.element.style.zIndex = hui.ui.nextPanelIndex();
		var pos = hui.position.get(this.element);
		var container = hui.position.get(this.options.container.element);
		this.dragState = {left: e.getLeft() - pos.left + container.left,top:e.getTop()-pos.top + container.top};
		this.element.style.right = 'auto';
	},
	_onMove : function(e) {
		var top = (e.getTop()-this.dragState.top);
		var left = (e.getLeft()-this.dragState.left);
		this.element.style.top = Math.max(top,0)+'px';
		this.element.style.left = Math.max(left,0)+'px';
		this.options.container.__nodeMoved(this);
	},
	_onDragEnd : function() {
		hui.cls.remove(this.element,'hui_diagram_box_dragging');
		//hui.ui.callDescendants(this,'$$parentMoved');
	}
}