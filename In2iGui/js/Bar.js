/**
 * @constructor
 * @param {Object} options The options
 */
In2iGui.Bar = function(options) {
	this.options = n2i.override({},options);
	this.name = options.name;
	this.element = n2i.get(options.element);
	In2iGui.extend(this);
};

In2iGui.Bar.create = function(options) {
	options = options || {};
	var cls = 'in2igui_bar';
	if (options.variant) {
		cls+=' in2igui_bar_'+options.variant;
	}
	if (options.absolute) {
		cls+=' in2igui_bar_absolute';
	}
	options.element = n2i.build('div',{
		'class' : cls
	});
	n2i.build('div',{'class':'in2igui_bar_body',parent:options.element});
	return new In2iGui.Bar(options);
}

In2iGui.Bar.prototype = {
	addToDocument : function() {
		document.body.appendChild(this.element);
	},
	add : function(widget) {
		var body = n2i.firstByClass(this.element,'in2igui_bar_body');
		body.appendChild(widget.getElement());
	},
	placeAbove : function(widget) {
		n2i.place({
			source:{element:this.element,vertical:1,horizontal:0},
			target:{element:widget.getElement(),vertical:0,horizontal:0}
		});
		this.element.style.zIndex = In2iGui.nextTopIndex();
	},
	show : function() {
		this.element.style.visibility='visible';
	},
	hide : function() {
		this.element.style.visibility='hidden';
	}
}

/**
 * @constructor
 * @param {Object} options The options
 */
In2iGui.Bar.Button = function(options) {
	this.options = n2i.override({},options);
	this.name = options.name;
	this.element = n2i.get(options.element);
	n2i.listen(this.element,'click',this._click.bind(this));
	n2i.listen(this.element,'mousedown',this._mousedown.bind(this));
	n2i.listen(this.element,'mouseup',function(e) {n2i.stop(e)});
	In2iGui.extend(this);
};

In2iGui.Bar.Button.create = function(options) {
	options = options || {};
	var e = options.element = n2i.build('a',{'class':'in2igui_bar_button'});
	if (options.icon) {
		e.appendChild(In2iGui.createIcon(options.icon,1));
	}
	return new In2iGui.Bar.Button(options);
}

In2iGui.Bar.Button.prototype = {
	_mousedown : function(e) {
		this.fire('mousedown');
		if (this.options.stopEvents) {
			n2i.stop(e);
		}
	},
	_click : function(e) {
		this.fire('click');
		if (this.options.stopEvents) {
			n2i.stop(e);
		}
	},
	setSelected : function(highlighted) {
		n2i.setClass(this.element,'in2igui_bar_button_selected',highlighted);
	}
}