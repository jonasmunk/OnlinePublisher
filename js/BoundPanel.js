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
	this.content = hui.get.firstByClass(this.element,'hui_boundpanel_content');
	this.arrow = hui.get.firstByClass(this.element,'hui_boundpanel_arrow');
	this.arrowWide = 37;
	this.arrowNarrow = 18;
	if (options.variant=='light') {
		this.arrowWide = 23;
		this.arrowNarrow = 12;
	}
	hui.ui.extend(this);
};

/**
 * Creates a new bound panel
 * <br/><strong>options:</strong> { name: «name», top: «pixels», left: «pixels», padding: «pixels», width: «pixels», hideOnClick: «boolean» }
 * @param {Object} options The options
 */
hui.ui.BoundPanel.create = function(options) {
	options = hui.override({name:null, top:0, left:0, width:null, padding: null, modal: false, hideOnClick: false}, options);

	
	var html = 
		'<div class="hui_boundpanel_arrow"></div>'+
		'<div class="hui_boundpanel_top"><div><div></div></div></div>'+
		'<div class="hui_boundpanel_body"><div class="hui_boundpanel_body"><div class="hui_boundpanel_body"><div class="hui_boundpanel_content" style="';
	if (options.width) {
		html+='width:'+options.width+'px;';
	}
	if (options.padding) {
		html+='padding:'+options.padding+'px;';
	}
	html+='"></div></div></div></div>'+
		'<div class="hui_boundpanel_bottom"><div><div></div></div></div>';

	options.element = hui.build(
		'div',{
			'class' : options.variant ? 'hui_boundpanel hui_boundpanel_'+options.variant : 'hui_boundpanel',
			style:'display:none;zIndex:'+hui.ui.nextPanelIndex()+';top:'+options.top+'px;left:'+options.left+'px',
			html:html,
			parent:document.body
		}
	);
	return new hui.ui.BoundPanel(options);
};

/********************************* Public methods ***********************************/

hui.ui.BoundPanel.prototype = {
	/** Show or hide the panel */
	toggle : function() {
		if (!this.visible) {
			this.show();
		} else {
			this.hide();
		}
	},
	/** Shows the panel */
	show : function(options) {
		options = options || {};
		var target = options.target || this.options.target;

		if (target) {
			if (target.nodeName) {
				this.position(target);
			} else {
				this.position(hui.ui.get(target));
			}
		}
		if (this.visible) {
			this.element.style.zIndex = hui.ui.nextPanelIndex();
			return;
		}
		if (hui.browser.opacity) {
			hui.style.setOpacity(this.element,0);
		}
		var vert;
		if (this.relativePosition=='left') {
			vert = false;
			this.element.style.marginLeft='20px';
		} else if (this.relativePosition=='right') {
			vert = false;
			this.element.style.marginLeft='-20px';
		} else if (this.relativePosition=='top') {
			vert = true;
			this.element.style.marginTop='20px';
		} else if (this.relativePosition=='bottom') {
			vert = true;
			this.element.style.marginTop='-20px';
		}
		this.element.style.visibility = 'visible';
		this.element.style.display = 'block';
		var index = hui.ui.nextPanelIndex();
		this.element.style.zIndex = index;
		hui.ui.callVisible(this);
		if (hui.browser.opacity) {
			hui.animate(this.element,'opacity',1,300,{ease:hui.ease.fastSlow});
		}
		hui.animate(this.element,vert ? 'margin-top' : 'margin-left','0px',300,{ease:hui.ease.fastSlow});
		this.visible=true;
		if (this.options.modal) {
			hui.ui.showCurtain({widget:this,zIndex:index-1,transparent:this.options.modal=='transparent',color:'auto'});
		}
		if (this.options.hideOnClick) {
			this.hideListener = hui.listen(document.body,'click',function(e) {
				if (!hui.ui.isWithin(e,this.element)) {
					this.hide();
				}
			}.bind(this));
		}
	},
	/** @private */
	$curtainWasClicked : function() {
		hui.ui.hideCurtain(this);
		this.hide();
	},
	/** Hides the panel */
	hide : function() {
		if (!this.visible) {
			return;
		}
		if (!hui.browser.opacity) {
			this.element.style.display='none';
			hui.ui.callVisible(this);
		} else {
			hui.animate(this.element,'opacity',0,100,{ease:hui.ease.slowFast,$complete:function() {
				this.element.style.display='none';
				hui.ui.callVisible(this);
			}.bind(this)});
		}
		if (this.options.modal) {
			hui.ui.hideCurtain(this);
		}
		this.visible=false;
		hui.unListen(document.body,'click',this.hideListener);
	},
	/**
	 * If the panel is currently visible
	 */
	isVisible : function() {
		return this.visible;
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
	clear : function() {
		hui.ui.destroyDescendants(this.content);
		this.content.innerHTML='';
	},
	/**
	 * Adds som vertical space to the panel
	 * @param {pixels} height The height of the space in pixels
	 */
	addSpace : function(height) {
		this.add(hui.build('div',{style:'font-size:0px;height:'+height+'px'}));
	},
	_getDimensions : function() {
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
	$$childSizeChanged : function() {
    this._rePosition();
	},
  $$layout : function() {
    this._rePosition();
  },
  _rePosition : function() {
    if (this._latest) {
      this.position(this._latest);
    }    
  },
	/** Position the panel at a node
	 * @param {Node} node The node the panel should be positioned at 
	 */
	position : function(options) {
    this._latest = options;
		var node,
      position,
      nodeOffset,
      nodeScrollOffset;
		if (options.getElement) {
			node = options.getElement();
		} else if (options.element) {
			node = options.element;
			position = options.position;
		} else if (options.rect) {
			position = options.position;
      node = {
        offsetWidth : options.rect.width,
        offsetHeight : options.rect.height
      };
      nodeOffset = {
        left : options.rect.left, 
        top : options.rect.top
      };
      nodeScrollOffset = {left: 0, top: 0};
		} else {
			node = hui.get(options);
		}
		
    if (!nodeOffset) {
      nodeOffset = {left:hui.position.getLeft(node),top:hui.position.getTop(node)};            
    }
    if (!nodeScrollOffset) {
      nodeScrollOffset = hui.position.getScrollOffset(node);
    }
        
		var windowScrollOffset = {left:hui.window.getScrollLeft(),top:hui.window.getScrollTop()};
		var nodeLeft = nodeOffset.left-windowScrollOffset.left+hui.window.getScrollLeft();
		var nodeWidth = node.clientWidth || node.offsetWidth;
		var nodeHeight = node.clientHeight || node.offsetHeight;
        
		var panelDimensions = this._getDimensions();
		var viewportWidth = hui.window.getViewWidth();
		var viewportHeight = hui.window.getViewHeight();
        
		var arrowLeft, arrowTop, left, top;
		var positionOnScreen = {
			top : nodeOffset.top-windowScrollOffset.top-(nodeScrollOffset.top-windowScrollOffset.top)
		};
		var vertical = positionOnScreen.top / viewportHeight;
		
		if (position=='vertical') {
			vertical = vertical > 0.5 ? 0.9 : 0.1;
		}
		var min, max;
		if (vertical <= 0.1) {
			this.relativePosition='top';
			this.arrow.className = 'hui_boundpanel_arrow hui_boundpanel_arrow_top';
			if (this.options.variant=='light') {
				arrowTop = this.arrowNarrow*-1+1;
			} else {
				arrowTop = this.arrowNarrow*-1+2;
			}
			left = Math.min(viewportWidth-panelDimensions.width-2,Math.max(3,nodeLeft+(nodeWidth/2)-((panelDimensions.width)/2)));
			arrowLeft = (nodeLeft+nodeWidth/2)-left-this.arrowNarrow;
			top = nodeOffset.top+nodeHeight+8 - (nodeScrollOffset.top-windowScrollOffset.top);
		}
		else if (vertical >= 0.9) {
			this.relativePosition='bottom';
			this.arrow.className='hui_boundpanel_arrow hui_boundpanel_arrow_bottom';
			if (this.options.variant=='light') {
				arrowTop = panelDimensions.height-2;
			} else {
				arrowTop = panelDimensions.height-6;
			}
			left = Math.min(viewportWidth-panelDimensions.width-3,Math.max(3,nodeLeft+(nodeWidth/2)-((panelDimensions.width)/2)));
			arrowLeft = (nodeLeft+nodeWidth/2)-left-this.arrowNarrow;
			top = nodeOffset.top-panelDimensions.height - 5 - (nodeScrollOffset.top-windowScrollOffset.top);
		}
		else if (( nodeLeft + nodeWidth / 2 ) / viewportWidth < 0.5) {
			this.relativePosition='left';
			left = nodeLeft + nodeWidth + 10;
			this.arrow.className = 'hui_boundpanel_arrow hui_boundpanel_arrow_left';
			top = nodeOffset.top+(nodeHeight-panelDimensions.height) / 2;
			//top = Math.min(top,viewportHeight-panelDimensions.height+(windowScrollOffset.top+nodeScrollOffset.top));
			top-= (nodeScrollOffset.top-windowScrollOffset.top);
			min = windowScrollOffset.top + 3;
			max = windowScrollOffset.top + (viewportHeight-panelDimensions.height) - 3;
			top = Math.min(Math.max(top,min),max);
			arrowTop = nodeOffset.top - top;
			arrowTop -= (nodeScrollOffset.top-windowScrollOffset.top);
			arrowTop -= this.arrowWide / 2;
			arrowTop += nodeHeight / 2;
			if (this.options.variant == 'light') {
				arrowLeft= -11;
				arrowTop += 2;
			} else {
				arrowLeft = -14;
			}
		} else {
			this.relativePosition = 'right';
			left = nodeLeft - panelDimensions.width - 10;
			this.arrow.className = 'hui_boundpanel_arrow hui_boundpanel_arrow_right';
			top = nodeOffset.top + (nodeHeight-panelDimensions.height) / 2;
			//top = Math.min(top,viewportHeight-panelDimensions.height+(windowScrollOffset.top+nodeScrollOffset.top));
			top-= (nodeScrollOffset.top-windowScrollOffset.top);
			min = windowScrollOffset.top + 3;
			max = windowScrollOffset.top + (viewportHeight - panelDimensions.height)-3;
			top = Math.min(Math.max(top,min),max);
			arrowTop = nodeOffset.top-top;
			arrowTop -= (nodeScrollOffset.top - windowScrollOffset.top);
				arrowTop -= this.arrowWide/2;
				arrowTop += nodeHeight/2;
			if (this.options.variant == 'light') {
				arrowLeft = panelDimensions.width - 1;
				arrowTop += 2;
			} else {
				arrowLeft = panelDimensions.width - 4;
			}
		}
		this.arrow.style.marginTop = arrowTop + 'px';
		this.arrow.style.marginLeft = arrowLeft + 'px';
		if (this.visible) {
			hui.animate(this.element,'top',top+'px',500,{ease:hui.ease.fastSlow});
			hui.animate(this.element,'left',left+'px',500,{ease:hui.ease.fastSlow});
		} else {
			this.element.style.top = top + 'px';
			this.element.style.left = left + 'px';
		}
	},
  destroy : function() {
		hui.ui.hideCurtain(this);
    hui.dom.remove(this.element);
  }
};