/**
 * A bound panel is a panel that is shown at a certain place
 * @constructor
 * @param {Object} options { element: «Node | id», name: «String» }
 */
hui.ui.BoundPanel = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.name = options.name;
	this.visible = false;
	this.content = hui.firstByClass(this.element,'in2igui_boundpanel_content');
	this.arrow = hui.firstByClass(this.element,'in2igui_boundpanel_arrow');
	hui.ui.extend(this);
}

/**
 * Creates a new bound panel
 * <br/><strong>options:</strong> { name: «String», top: «pixels», left: «pixels», padding: «pixels», width: «pixels» }
 * @param {Object} options The options
 */
hui.ui.BoundPanel.create = function(options) {
	options = hui.override({name:null, top:0, left:0, width:null, padding: null}, options);

	
	var html = 
		'<div class="in2igui_boundpanel_arrow"></div>'+
		'<div class="in2igui_boundpanel_top"><div><div></div></div></div>'+
		'<div class="in2igui_boundpanel_body"><div class="in2igui_boundpanel_body"><div class="in2igui_boundpanel_body"><div class="in2igui_boundpanel_content" style="';
	if (options.width) {
		html+='width:'+options.width+'px;';
	}
	if (options.padding) {
		html+='padding:'+options.padding+'px;';
	}
	html+='"></div></div></div></div>'+
		'<div class="in2igui_boundpanel_bottom"><div><div></div></div></div>';

	options.element = hui.build(
		'div',{
			'class':'in2igui_boundpanel',
			style:'display:none;zIndex:'+hui.ui.nextPanelIndex()+';top:'+options.top+'px;left:'+options.left+'px',
			html:html,
			parent:document.body
		}
	);
	return new hui.ui.BoundPanel(options);
}

/********************************* Public methods ***********************************/

hui.ui.BoundPanel.prototype = {
	toggle : function() {
		if (!this.visible) {
			this.show();
		} else {
			this.hide();
		}
	},
	/** Shows the panel */
	show : function() {
		if (!this.visible) {
			if (this.options.target) {
				this.position(hui.ui.get(this.options.target));
			}
			if (hui.browser.opacity) {
				hui.setOpacity(this.element,0);
			}
			var vert;
			if (this.relativePosition=='left') {
				vert = false;
				this.element.style.marginLeft='30px';
			} else if (this.relativePosition=='right') {
				vert = false;
				this.element.style.marginLeft='-30px';
			} else if (this.relativePosition=='top') {
				vert = true;
				this.element.style.marginTop='30px';
			} else if (this.relativePosition=='bottom') {
				vert = true;
				this.element.style.marginTop='-30px';
			}
			hui.setStyle(this.element,{
				visibility : 'hidden', display : 'block'
			})
			var width = this.element.clientWidth;
			hui.setStyle(this.element,{
				width : width+'px' , visibility : 'visible'
			});
			this.element.style.display='block';
			if (hui.browser.opacity) {
				hui.animate(this.element,'opacity',1,400,{ease:hui.ease.fastSlow});
			}
			hui.animate(this.element,vert ? 'margin-top' : 'margin-left','0px',800,{ease:hui.ease.bounce});
		}
		this.element.style.zIndex = hui.ui.nextPanelIndex();
		this.visible=true;
	},
	/** Hides the panel */
	hide : function() {
		if (hui.browser.msie) {
			this.element.style.display='none';
		} else {
			hui.animate(this.element,'opacity',0,300,{ease:hui.ease.slowFast,hideOnComplete:true});
		}
		this.visible=false;
	},
	/**
	 * Adds a widget or element to the panel
	 * @param {Node | Widget} child The object to add
	 */
	add : function(child) {
		if (child.getElement) {
			this.content.appendChild(child.getElement());
		} else {
			this.content.appendChild(child);
		}
	},
	/**
	 * Adds som vertical space to the panel
	 * @param {pixels} height The height of the space in pixels
	 */
	addSpace : function(height) {
		this.add(hui.build('div',{style:'font-size:0px;height:'+height+'px'}));
	},
	/** @private */
	getDimensions : function() {
		var width, height;
		if (this.element.style.display=='none') {
			this.element.style.visibility='hidden';
			this.element.style.display='block';
			width = this.element.clientWidth;
			height = this.element.clientHeight;
			this.element.style.display='none';
			this.element.style.visibility='';
		} else {
			width = this.element.clientWidth;
			height = this.element.clientHeight;
		}
		return {width:width,height:height};
	},
	/** Position the panel at a node
	 * @param {Node} node The node the panel should be positioned at 
	 */
	position : function(node) {
		if (node.getElement) {
			node = node.getElement();
		}
		node = hui.get(node);
		var offset = {left:hui.getLeft(node),top:hui.getTop(node)};
		var scrollOffset = {left:hui.getScrollLeft(),top:hui.getScrollTop()};
		var dims = this.getDimensions();
		var viewportWidth = hui.getViewPortWidth();
		var viewportHeight = hui.getViewPortHeight();
		var nodeLeft = offset.left-scrollOffset.left+hui.getScrollLeft();
		var nodeWidth = node.clientWidth;
		var nodeHeight = node.clientHeight;
		var nodeTop = offset.top-scrollOffset.top+hui.getScrollTop();
		var arrowLeft, arrowTop, left, top;
		var vertical = (nodeTop-scrollOffset.top)/viewportHeight;
		
		if (vertical<.1) {
			this.relativePosition='top';
			this.arrow.className = 'in2igui_boundpanel_arrow in2igui_boundpanel_arrow_top';
			arrowTop = -16;
			left = Math.min(viewportWidth-dims.width,Math.max(0,nodeLeft+(nodeWidth/2)-((dims.width)/2)));
			arrowLeft = (nodeLeft+nodeWidth/2)-left-18;
			top = nodeTop+nodeHeight+8;
		}
		else if (vertical>.9) {
			this.relativePosition='bottom';
			this.arrow.className='in2igui_boundpanel_arrow in2igui_boundpanel_arrow_bottom';
			arrowTop = dims.height-6;
			left = Math.min(viewportWidth-dims.width,Math.max(0,nodeLeft+(nodeWidth/2)-((dims.width)/2)));
			arrowLeft = (nodeLeft+nodeWidth/2)-left-18;
			top = nodeTop-dims.height-5;
		}
		else if ((nodeLeft+nodeWidth/2)/viewportWidth<.5) {
			this.relativePosition='left';
			left = nodeLeft+nodeWidth+10;
			this.arrow.className='in2igui_boundpanel_arrow in2igui_boundpanel_arrow_left';
			arrowLeft=-14;
			top = Math.max(0,nodeTop+(nodeHeight-dims.height)/2);
			arrowTop = (dims.height-32)/2+Math.min(0,nodeTop+(nodeHeight-dims.height)/2);
		} else {
			this.relativePosition='right';
			left = nodeLeft-dims.width-10;
			this.arrow.className='in2igui_boundpanel_arrow in2igui_boundpanel_arrow_right';
			arrowLeft=dims.width-4;
			top = Math.max(0,nodeTop+(nodeHeight-dims.height)/2);
			arrowTop = (dims.height-32)/2+Math.min(0,nodeTop+(nodeHeight-dims.height)/2);
		}
		this.arrow.style.marginTop = arrowTop+'px';
		this.arrow.style.marginLeft = arrowLeft+'px';
		if (this.visible) {
			hui.animate(this.element,'top',top+'px',500,{ease:hui.ease.fastSlow});
			hui.animate(this.element,'left',left+'px',500,{ease:hui.ease.fastSlow});
		} else {
			this.element.style.top=top+'px';
			this.element.style.left=left+'px';
		}
	}
}

/* EOF */