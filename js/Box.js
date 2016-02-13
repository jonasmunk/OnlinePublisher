/**
 * @constructor
 * @param {Object} options The options : {modal:false}
 */
hui.ui.Box = function(options) {
	this.options = hui.override({},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.visible = !this.options.absolute;
	hui.ui.extend(this);
	if (this.nodes.close) {
		hui.listen(this.nodes.close,'click',this._close.bind(this));
	}
};

/**
 * Creates a new box widget
 * @param {Object} options The options : {width:0,padding:0,absolute:false,closable:false}
 */
hui.ui.Box.create = function(options) {
	options = options || {};
  var variant = options.variant || 'standard';
  var complex = variant !== 'plain';
  var html = (options.closable ? '<a class="hui_box_close hui_box_close_' + variant + '" href="#"></a>' : '');
  if (complex) {
    html += '<div class="hui_box_top"><div><div></div></div></div>'+
      '<div class="hui_box_middle"><div class="hui_box_middle">';
  }
  if (options.title) {
    html+='<div class="hui_box_header"><strong class="hui_box_title">'+hui.string.escape(hui.ui.getTranslated(options.title))+'</strong></div>';
  }
  html += '<div class="hui_box_body" style="'+
			(options.padding ? 'padding: '+options.padding+'px;' : '')+
			(options.width ? 'width: '+options.width+'px;' : '')+
  '"></div>';
  if (complex) {
    html += '</div></div>'+
      '<div class="hui_box_bottom"><div><div></div></div></div>';
  }

	options.element = hui.build('div',{
		'class' : 'hui_box hui_box_' + variant,
		html : html,
		style : options.width ? options.width+'px' : null
	});
  if (options.absolute) {
    hui.cls.add(options.element,'hui_box_absolute');
  }
  if (variant) {
    hui.cls.add(options.element,'hui_box_' + variant);
  } 
	return new hui.ui.Box(options);
};

hui.ui.Box.prototype = {
	nodes : {
    	body : '.hui_box_body',
    	close : '.hui_box_close'
	},
	_close : function(e) {
		hui.stop(e);
		this.hide();
		this.fire('boxWasClosed'); // Deprecated
		this.fire('close');
	},
	shake : function() {
		hui.effect.shake({element:this.element});
	},

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
    var body = this.nodes.body;
		if (widget.getElement) {
			body.appendChild(widget.getElement());
		} else {
			body.appendChild(widget);
		}
	},
	/**
	 * Shows the box
	 */
	show : function() {
		var e = this.element;
		if (this.options.modal) {
			var index = hui.ui.nextPanelIndex();
			e.style.zIndex = index+1;
			hui.ui.showCurtain({widget:this,zIndex:index});
		}
		if (this.options.absolute) {
			hui.style.set(e,{ display : 'block', visibility : 'hidden' });
			var w = e.clientWidth;
			var top = (hui.window.getViewHeight() - e.clientHeight) / 2 + hui.window.getScrollTop();
			hui.style.set(e,{
				marginLeft : (w/-2)+'px',
				top : top+'px',
				display : 'block',
				visibility : 'visible'
			});
		} else {
			e.style.display = 'block';
		}
		this.visible = true;
		hui.ui.callVisible(this);
	},
	/** If the box is visible */
	isVisible : function() {
		return this.visible;
	},
	/** @private */
	$$layout : function() {
		if (this.options.absolute && this.visible) {
			var e = this.element;
			var w = e.clientWidth;
			var top = (hui.window.getViewHeight()-e.clientHeight)/2+hui.window.getScrollTop();
			hui.style.set(e,{'marginLeft':(w/-2)+'px',top:top+'px'});
		}
	},
	/**
	 * Hides the box
	 */
	hide : function() {
		hui.ui.hideCurtain(this);
		this.element.style.display='none';
		this.visible = false;
		hui.ui.callVisible(this);
	},
	/** @private */
	$curtainWasClicked : function() {
		this.fire('boxCurtainWasClicked');
		if (this.options.curtainCloses) {
			this._close();
		}
	}
};