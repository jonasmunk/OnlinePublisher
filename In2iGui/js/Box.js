/**
 * @constructor
 * @param {Object} options The options : {modal:false}
 */
In2iGui.Box = function(options) {
	this.options = n2i.override({},options);
	this.name = options.name;
	this.element = n2i.get(options.element);
	this.body = n2i.firstByClass(this.element,'in2igui_box_body');
	this.close = n2i.firstByClass(this.element,'in2igui_box_close');
	if (this.close) {
		n2i.listen(this.close,'click',function(e) {
			n2i.stop(e);
			this.hide();
			this.fire('boxWasClosed');
		}.bind(this));
	}
	In2iGui.extend(this);
};

/**
 * Creates a new box widget
 * @param {Object} options The options : {width:0,padding:0,absolute:false,closable:false}
 */
In2iGui.Box.create = function(options) {
	options = options || {};
	options.element = n2i.build('div',{
		'class' : options.absolute ? 'in2igui_box in2igui_box_absolute' : 'in2igui_box',
		html : (options.closable ? '<a class="in2igui_box_close" href="#"></a>' : '')+
			'<div class="in2igui_box_top"><div><div></div></div></div>'+
			'<div class="in2igui_box_middle"><div class="in2igui_box_middle">'+
			(options.title ? '<div class="in2igui_box_header"><strong class="in2igui_box_title">'+n2i.escape(options.title)+'</strong></div>' : '')+
			'<div class="in2igui_box_body"'+(options.padding ? ' style="padding: '+options.padding+'px;"' : '')+'></div>'+
			'</div></div>'+
			'<div class="in2igui_box_bottom"><div><div></div></div></div>',
		style : options.width ? options.width+'px' : null
	});
	return new In2iGui.Box(options);
};

In2iGui.Box.prototype = {
	/**
	 * Adds the box to the end of the body
	 */
	addToDocument : function() {
		document.body.appendChild(this.element);
	},
	/**
	 * Adds a child widget or node
	 */
	add : function(widget) {
		if (widget.getElement) {
			this.body.appendChild(widget.getElement());
		} else {
			this.body.appendChild(widget);
		}
	},
	/**
	 * Shows the box
	 */
	show : function() {
		var e = this.element;
		if (this.options.modal) {
			var index = In2iGui.nextPanelIndex();
			e.style.zIndex=index+1;
			In2iGui.showCurtain({widget:this,zIndex:index});
		}
		if (this.options.absolute) {
			n2i.setStyle(e,{display:'block',visibility:'hidden'});
			var w = e.clientWidth;
			var top = (n2i.getInnerHeight()-e.clientHeight)/2+n2i.getScrollTop();
			n2i.setStyle(e,{'marginLeft':(w/-2)+'px',top:top+'px'});
			n2i.setStyle(e,{display:'block',visibility:'visible'});
		} else {
			e.style.display='block';
		}
		In2iGui.callVisible(this);
	},
	/**
	 * Hides the box
	 */
	hide : function() {
		In2iGui.hideCurtain(this);
		this.element.style.display='none';
	},
	/** @private */
	curtainWasClicked : function() {
		this.fire('boxCurtainWasClicked');
	}
};