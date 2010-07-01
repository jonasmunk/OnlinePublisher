/**
 * A bound panel is a panel that is shown at a certain place
 * @constructor
 * @param {Object} options { element: «Node | id», name: «String» }
 */
In2iGui.BoundPanel = function(options) {
	this.element = $(options.element);
	this.name = options.name;
	this.visible = false;
	this.content=this.element.select('div.in2igui_boundpanel_content')[0];
	this.arrow=this.element.select('div.in2igui_boundpanel_arrow')[0];
	In2iGui.extend(this);
}

/**
 * Creates a new bound panel
 * <br/><strong>options:</strong> { name: «String», top: «pixels», left: «pixels», padding: «pixels», width: «pixels» }
 * @param {Object} options The options
 */
In2iGui.BoundPanel.create = function(options) {
	var options = n2i.override({name:null, top:0, left:0, width:null, padding: null}, options);
	var element = options.element = new Element('div',
		{'class':'in2igui_boundpanel'}).setStyle({'display':'none','zIndex':In2iGui.nextPanelIndex(),'top':options.top+'px','left':options.left+'px'});
	
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
	element.innerHTML=html;
	document.body.appendChild(element);
	return new In2iGui.BoundPanel(options);
}

/********************************* Public methods ***********************************/

In2iGui.BoundPanel.prototype = {
	/** Shows the panel */
	show : function() {
		if (!this.visible) {
			if (!n2i.browser.msie) {
				n2i.setOpacity(this.element,0);
			}
			if (this.relativePosition=='left') {
				this.element.style.marginLeft='30px';
			} else {
				this.element.style.marginLeft='-30px';
			}
			this.element.setStyle({
				visibility : 'hidden', display : 'block'
			})
			var width = this.element.clientWidth;
			this.element.setStyle({
				width : width+'px' , visibility : 'visible'
			});
			this.element.style.marginTop='0px';
			this.element.style.display='block';
			if (!n2i.browser.msie) {
				n2i.animate(this.element,'opacity',1,400,{ease:n2i.ease.fastSlow});
			}
			n2i.animate(this.element,'margin-left','0px',800,{ease:n2i.ease.bounce});
		}
		this.element.style.zIndex = In2iGui.nextPanelIndex();
		this.visible=true;
	},
	/** Hides the panel */
	hide : function() {
		if (n2i.browser.msie) {
			this.element.style.display='none';
		} else {
			n2i.animate(this.element,'opacity',0,300,{ease:n2i.ease.slowFast,hideOnComplete:true});
		}
		this.visible=false;
	},
	/**
	 * Adds a widget or element to the panel
	 * @param {Node | Widget} child The object to add
	 */
	add : function(child) {
		if (child.getElement) {
			this.content.insert(child.getElement());
		} else {
			this.content.insert(child);
		}
	},
	/**
	 * Adds som vertical space to the panel
	 * @param {pixels} height The height of the space in pixels
	 */
	addSpace : function(height) {
		this.add(new Element('div').setStyle({fontSize:'0px',height:height+'px'}));
	},
	/** @private */
	getDimensions : function() {
		if (this.element.style.display=='none') {
			this.element.style.visibility='hidden';
			this.element.style.display='block';
			var width = this.element.getWidth();
			var height = this.element.getHeight();
			this.element.style.display='none';
			this.element.style.visibility='';
		} else {
			var width = this.element.getWidth();
			var height = this.element.getHeight();
		}
		return {width:width,height:height};
	},
	/** Position the panel at a node
	 * @param {Node} node The node the panel should be positioned at 
	 */
	position : function(node) {
		node = $(node);
		var offset = node.cumulativeOffset();
		var scrollOffset = node.cumulativeScrollOffset();
		var dims = this.getDimensions();
		var winWidth = n2i.getInnerWidth();
		var nodeLeft = offset.left-scrollOffset.left+n2i.getScrollLeft();
		var nodeWidth = node.getWidth();
		var nodeHeight = node.getHeight();
		var nodeTop = offset.top-scrollOffset.top+n2i.getScrollTop();
		if ((nodeLeft+nodeWidth/2)/winWidth<.5) {
			this.relativePosition='left';
			var left = nodeLeft+nodeWidth+10;
			this.arrow.className='in2igui_boundpanel_arrow in2igui_boundpanel_arrow_left';
			var arrowLeft=-14;
		} else {
			this.relativePosition='right';
			var left = nodeLeft-dims.width-10;
			this.arrow.className='in2igui_boundpanel_arrow in2igui_boundpanel_arrow_right';
			var arrowLeft=dims.width-4;
		}
		var top = Math.max(0,nodeTop+(nodeHeight-dims.height)/2);
		this.arrow.style.marginTop = (dims.height-32)/2+Math.min(0,nodeTop+(nodeHeight-dims.height)/2)+'px';
		this.arrow.style.marginLeft = arrowLeft+'px';
		if (this.visible) {
			n2i.animate(this.element,'top',top+'px',500,{ease:n2i.ease.fastSlow});
			n2i.animate(this.element,'left',left+'px',500,{ease:n2i.ease.fastSlow});
		} else {
			this.element.style.top=top+'px';
			this.element.style.left=left+'px';
		}
	}
}

/* EOF */