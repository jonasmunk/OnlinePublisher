/** A diagram
 * @constructor
 */
hui.ui.Diagram = function(options) {
	this.options = options;
	this.name = options.name;
	this.nodes = [];
	this.lines = [];
	this.element = hui.get(options.element);	
	hui.ui.extend(this);
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
			width: this.options.width,
			height: this.options.height
		});
		this.element.appendChild(this.background.element);
	},
	
	addBox : function(options) {
		options.container = this;
		var box = hui.ui.Diagram.Box.create(options);
		this.add(box);
	},
	add : function(widget) {
		var e = widget.element;
		this.element.appendChild(e);
		e.style.left = (Math.random()*(this.options.width - e.clientWidth))+'px';
		e.style.top = (Math.random()*(this.options.height - e.clientHeight))+'px';
		this.nodes.push(widget);
	},
	addLine : function(options) {
		var from = this.getNode(options.from),
			to = this.getNode(options.to),
			fromCenter = this._getCenter(from),
			toCenter = this._getCenter(to);
			
		var line = this.background.addLine({ from: fromCenter, to: toCenter, color: '#999' });
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
	var html = '<h1>'+options.title+'</h1>';
	html+='<table>';
	for (var i=0; i < options.properties.length; i++) {
		var p = options.properties[i];
		html+='<tr><th>'+p.label+'</th><td>'+p.value+'</td></tr>';
	};
	html+='</table>';
	options.element = hui.build('div',{'class':'hui_diagram_box',html:html});
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