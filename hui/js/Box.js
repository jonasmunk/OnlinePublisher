/**
 * @constructor
 * @param {Object} options The options : {modal:false}
 */
hui.ui.Box = function(options) {
	this.options = hui.override({},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.body = hui.firstByClass(this.element,'in2igui_box_body');
	this.close = hui.firstByClass(this.element,'in2igui_box_close');
	if (this.close) {
		hui.listen(this.close,'click',function(e) {
			hui.stop(e);
			this.hide();
			this.fire('boxWasClosed');
		}.bind(this));
	}
	hui.ui.extend(this);
};

/**
 * Creates a new box widget
 * @param {Object} options The options : {width:0,padding:0,absolute:false,closable:false}
 */
hui.ui.Box.create = function(options) {
	options = options || {};
	options.element = hui.build('div',{
		'class' : options.absolute ? 'in2igui_box in2igui_box_absolute' : 'in2igui_box',
		html : (options.closable ? '<a class="in2igui_box_close" href="#"></a>' : '')+
			'<div class="in2igui_box_top"><div><div></div></div></div>'+
			'<div class="in2igui_box_middle"><div class="in2igui_box_middle">'+
			(options.title ? '<div class="in2igui_box_header"><strong class="in2igui_box_title">'+hui.escape(options.title)+'</strong></div>' : '')+
			'<div class="in2igui_box_body" style="'+
			(options.padding ? 'padding: '+options.padding+'px;' : '')+
			(options.width ? 'width: '+options.width+'px;' : '')+
			'"></div>'+
			'</div></div>'+
			'<div class="in2igui_box_bottom"><div><div></div></div></div>',
		style : options.width ? options.width+'px' : null
	});
	return new hui.ui.Box(options);
};

hui.ui.Box.prototype = {
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
			var index = hui.ui.nextPanelIndex();
			e.style.zIndex=index+1;
			hui.ui.showCurtain({widget:this,zIndex:index});
		}
		if (this.options.absolute) {
			hui.setStyle(e,{display:'block',visibility:'hidden'});
			var w = e.clientWidth;
			var top = (hui.getViewPortHeight()-e.clientHeight)/2+hui.getScrollTop();
			hui.setStyle(e,{'marginLeft':(w/-2)+'px',top:top+'px'});
			hui.setStyle(e,{display:'block',visibility:'visible'});
		} else {
			e.style.display='block';
		}
		hui.ui.callVisible(this);
	},
	/**
	 * Hides the box
	 */
	hide : function() {
		hui.ui.hideCurtain(this);
		this.element.style.display='none';
	},
	/** @private */
	curtainWasClicked : function() {
		this.fire('boxCurtainWasClicked');
	}
};