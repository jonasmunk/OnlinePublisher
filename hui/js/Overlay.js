/**
 * @constructor
 */
hui.ui.Overlay = function(options) {
	this.options = options;
	this.element = hui.get(options.element);
	this.content = hui.get.byClass(this.element,'hui_inner_overlay')[1];
	this.name = options.name;
	this.icons = {};
	this.visible = false;
	hui.ui.extend(this);
	this._addBehavior();
}

/**
 * Creates a new overlay
 */
hui.ui.Overlay.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('div',{className:'hui_overlay'+(options.variant ? ' hui_overlay_'+options.variant : ''),style:'display:none',html:'<div class="hui_inner_overlay"><div class="hui_inner_overlay"></div></div>'});
	document.body.appendChild(e);
	return new hui.ui.Overlay(options);
}

hui.ui.Overlay.prototype = {
	_addBehavior : function() {
		var self = this;
/*		this.hider = function(e) {
			if (self.boundElement) {
				if (hui.ui.isWithin(e,self.boundElement) || hui.ui.isWithin(e,self.element)) return;
				// TODO: should be unreg'ed but it fails
				//self.boundElement.stopObserving(self.hider);
				hui.cls.remove(self.boundElement,'hui_overlay_bound');
				self.boundElement = null;
				self.hide();
			}
		}
		hui.listen(this.element,'mouseout',this.hider);*/
	},
	addIcon : function(key,icon) {
		var self = this;
		var element = hui.build('div',{className:'hui_overlay_icon'});
		element.style.backgroundImage='url('+hui.ui.getIconUrl(icon,32)+')';
		hui.listen(element,'click',function(e) {
			self._iconWasClicked(key,e);
		});
		this.icons[key]=element;
		this.content.appendChild(element);
	},
	addText : function(text) {
		this.content.appendChild(hui.build('span',{'class':'hui_overlay_text',text:text}));
	},
	add : function(widget) {
		this.content.appendChild(widget.getElement());
	},
	hideIcons : function(keys) {
		for (var i=0; i < keys.length; i++) {
			this.icons[keys[i]].style.display='none';
		};
	},
	showIcons : function(keys) {
		for (var i=0; i < keys.length; i++) {
			this.icons[keys[i]].style.display='';
		};
	},
	_iconWasClicked : function(key,e) {
		hui.ui.callDelegates(this,'iconWasClicked',key,e);
	},
	showAtElement : function(element,options) {
		options = options || {};
		hui.ui.positionAtElement(this.element,element,options);
		if (options.autoHide) {
			// important to do even if visible, sine element may have changed
			this._autoHide(element);
		}
		if (this.visible) {
			return;
		}
		if (hui.browser.msie) {
			this.element.style.display = 'block';
		} else {
			hui.style.set(this.element,{display : 'block',opacity : 0});
			hui.animate(this.element,'opacity',1,150);
		}
		var zIndex = options.zIndex === undefined ? options.zIndex : hui.ui.nextAlertIndex();
		if (this.options.modal) {
			this.element.style.zIndex = hui.ui.nextAlertIndex();
			hui.ui.showCurtain({ widget : this, zIndex : zIndex });
		} else {
			this.element.style.zIndex = zIndex;
		}
		this.visible = true;
	},
	_autoHide : function(element) {
		hui.cls.add(element,'hui_overlay_bound');
		hui.unListen(document.body,'mousemove',this._hider);
		this._hider = function(e) {
			if (!hui.ui.isWithin(e,element) && !hui.ui.isWithin(e,this.element)) {
				try {
					hui.unListen(document.body,'mousemove',this._hider);
					hui.cls.remove(element,'hui_overlay_bound');
					this.hide();
				} catch (e) {
					hui.log('unable to stop listening: document='+document);
				}
			}
		}.bind(this)
		hui.listen(document.body,'mousemove',this._hider);
	},
	show : function(options) {
		options = options || {};
		if (!this.visible) {
			hui.style.set(this.element,{'display':'block',visibility:'hidden'});
		}
		if (options.element) {
			hui.position.place({
				source : {element:this.element,vertical:0,horizontal:.5},
				target : {element:options.element,vertical:.5,horizontal:.5},
				insideViewPort : true,
				viewPartMargin : 9
			});
		}
		if (options.autoHide && options.element) {
			this._autoHide(options.element);
		}
		if (this.visible) return;
		hui.effect.bounceIn({element:this.element});
		this.visible = true;
		if (this.options.modal) {
			var zIndex = hui.ui.nextAlertIndex();
			this.element.style.zIndex=zIndex+1;
			hui.ui.showCurtain({widget:this,zIndex:zIndex,color:'auto'});
		}
	},
	/** private */
	$curtainWasClicked : function() {
		this.hide();
	},
	hide : function() {
		hui.ui.hideCurtain(this);
		this.element.style.display='none';
		this.visible = false;
	},
	clear : function() {
		hui.ui.destroyDescendants(this.content);
		this.content.innerHTML='';
	}
};

/* EOF */
