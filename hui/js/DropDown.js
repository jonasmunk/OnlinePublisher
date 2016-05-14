////////////////////////// DropDown ///////////////////////////

/**
 * A drop down selector
 * @constructor
 */
hui.ui.DropDown = function(options) {
	this.options = hui.override({label:null,placeholder:null,url:null,source:null,focus:false},options);
	this.name = options.name;
	var e = this.element = hui.get(options.element);
	this.inner = e.getElementsByTagName('strong')[0];
	this.items = options.items || [];
	this.index = -1;
	this.value = this.options.value || null;
	this.dirty = true;
	this.busy = false;
	hui.ui.extend(this);
	if (options.listener) {
		this.listen(options.listener);
	}
	this._attach();
	this._updateIndex();
	this._updateUI();
	if (this.options.url) {
		this.options.source = new hui.ui.Source({url:this.options.url,delegate:this});
	} else if (this.options.source) {
		this.options.source.listen(this);
	}
}

hui.ui.DropDown.create = function(options) {
	options = options || {};
	var cls = 'hui_dropdown';
	if (options.variant) {
		cls+=' hui_dropdown_'+options.variant;
	}
	options.element = hui.build('a',{
		'class':cls,href:'javascript://',
		html:'<span><span><strong></strong></span></span>'
	});
	return new hui.ui.DropDown(options);
}

hui.ui.DropDown.prototype = {
	_attach : function() {
		hui.ui.addFocusClass({element:this.element,'class':'hui_dropdown_focused'});
		hui.listen(this.element,'click',this._click.bind(this));
		hui.listen(this.element,'blur',this._hideSelector.bind(this));
		hui.listen(this.element,'keydown',this._keyDown.bind(this));
		if (!this.options.focus) {
			hui.listen(this.element,'mousedown',function(e) {
				hui.stop(e);
			});
		}
	},
	_updateIndex : function() {
		this.index=-1;
		for (var i=0; i < this.items.length; i++) {
			if (this.items[i].value==this.value) {
				this.index=i;
			}
		};
	},
	_updateUI : function() {
		var selected = this.items[this.index];
		if (selected) {
			var text = selected.label || selected.title || selected.text || '';
			this.inner.innerHTML='';
			hui.dom.addText(this.inner,hui.string.wrap(text));
		} else if (this.options.placeholder) {
			this.inner.innerHTML='';
			this.inner.appendChild(hui.build('em',{text:hui.string.escape(this.options.placeholder)}));
		} else {
			this.inner.innerHTML='';
		}
		if (!this.selector) {
			return;
		}
		var as = this.selector.getElementsByTagName('a');
		for (var i=0; i < as.length; i++) {
			if (this.index==i) {
				hui.cls.add(as[i],'hui_selected');
			} else {
				as[i].className='';
			}
		};
	},
	_click : function(e) {
		if (this.busy) {return}
		hui.stop(e);
		if (this._selectorVisible) {
			this._hideSelector();
			//this.element.blur();
		} else {
			this._showSelector();
			this._hider = function(e) {
				e = hui.event(e);
				if (!e.isDescendantOf(this.element)) {
					this._hideSelector();
				}
			}.bind(this);
			hui.listen(document.body,'mousedown',this._hider);
		}
	},
	_showSelector : function() {
		this._buildSelector();
		var el = this.element, s=this.selector;
		if (this.options.focus) {
			el.focus();			
		}
		if (!this.items) return;
		var docHeight = hui.document.getHeight();
		if (docHeight<200) {
			var left = hui.position.getLeft(this.element);
			hui.style.set(this.selector,{'left':left+'px',top:'5px'});
		} else {
			var windowScrollTop = hui.window.getScrollTop();
			var scrollOffsetTop = hui.position.getScrollOffset(this.element).top;
			var scrollTop = windowScrollTop-scrollOffsetTop;
			hui.position.place({
				target : {element:this.element,vertical:1,horizontal:0},
				source : {element:this.selector,vertical:0,horizontal:0},
				top : scrollTop
			});
		}
		hui.style.set(s,{visibility:'hidden',display:'block',width:''});
		var height = Math.min(docHeight-hui.position.getTop(s)-5,200);
		var width = Math.max(el.clientWidth-5,100,s.clientWidth+20);
		var space = hui.window.getViewWidth()-hui.position.getLeft(el)-20;
		width = Math.min(width,space);
		hui.style.set(s,{visibility:'visible',width:width+'px',zIndex:hui.ui.nextTopIndex(),maxHeight:height+'px'});
		this._selectorVisible = true;
	},
	_hideSelector : function() {
		hui.unListen(document.body,'mousedown',this._hider);					
		if (!this.selector) {return}
		this.selector.style.display = 'none';
		this._selectorVisible = false;
	},
	_keyDown : function(e) {
		if (this.busy) {return}
		if (this.items.length==0) {
			return;
		}
		if (e.keyCode==40) {
			hui.stop(e);
			if (this.index>=this.items.length-1) {
				this.value=this.items[0].value;
			} else {
				this.value=this.items[this.index+1].value;
			}
			this._updateIndex();
			this._updateUI();
			this._fireChange();
		} else if (e.keyCode==38) {
			hui.stop(e);
			if (this.index>0) {
				this.index--;
			} else {
				this.index = this.items.length-1;
			}
			this.value = this.items[this.index].value;
			this._updateUI();
			this._fireChange();
		}
	},
	selectFirst : function() {
		if (this.items.length>0) {
			this.setValue(this.items[0].value);
		}
	},
	/** Get the value of the selected item */
	getValue : function() {
		return this.value;
	},
	setValue : function(value) {
		this.value = value;
		this._updateIndex();
		this._updateUI();
	},
	/** Set the value to null */
	reset : function() {
		this.setValue(null);
	},
	/** Get the label */
	getLabel : function() {
		return this.options.label;
	},
	/** Refresh the associated source */
	refresh : function() {
		if (this.options.source) {
			this.options.source.refresh();
		}
	},
	stress : function() {
		hui.ui.stress(this);
	},
	focus : function() {
		try {this.element.focus()} catch (ignore) {}
	},
	// TODO: Is this used?
	getItem : function() {
		if (this.index>=0) {
			return this.items[this.index];
		}
		return 0;
	},
	addItem : function(item) {
		this.items.push(item);
		this.dirty = true;
		this._updateIndex();
		this._updateUI();
	},
	setItems : function(items) {
		this.items = items;
		this.dirty = true;
		this.index = -1;
		this._updateIndex();
		this._updateUI();
	},
	/** @private */
	$itemsLoaded : function(items) {
		this.setItems(items);
	},
	/** @private */
	$sourceIsBusy : function() {
		this.busy = true;
		hui.style.setOpacity(this.element,.5);
	},
	/** @private */
	$sourceIsNotBusy : function() {
		this.busy = false;
		hui.style.setOpacity(this.element,1);
	},
	/** @private */
	$sourceShouldRefresh : function() {
		return hui.dom.isVisible(this.element);
	},
	/** @private */
	$visibilityChanged : function() {
		if (hui.dom.isVisible(this.element)) {
			if (this.options.source) {
				// If there is a source, make sure it is initially 
				this.options.source.refreshFirst();
			}			
		} else {
			this._hideSelector();
		}
	},
	_buildSelector : function() {
		if (!this.dirty || !this.items) {return};
		if (!this.selector) {
			this.selector = hui.build('div',{'class':'hui_dropdown_selector'});
			document.body.appendChild(this.selector);
			hui.listen(this.selector,'mousedown',function(e) {hui.stop(e)});
		} else {
			this.selector.innerHTML='';
		}
		var self = this;
		hui.each(this.items,function(item,i) {
			var e = hui.build('a',{href:'javascript://',text : item.label || item.title || item.text || ''});
			hui.listen(e,'mousedown',function(e) {
				hui.stop(e);
				self._itemClicked(item,i);
				hui.listenOnce(document.body,'mouseup',function(e) {hui.stop(e)});
			})
			if (i==self.index) {
				hui.cls.add(e,'hui_selected')
			};
			self.selector.appendChild(e);
		});
		this.dirty = false;
	},
	_itemClicked : function(item,index) {
		this.index = index;
		var changed = this.value!=this.items[index].value;
		this.value = this.items[index].value;
		this._updateUI();
		this._hideSelector();
		if (changed) {
			this._fireChange();
		}
	},
	_fireChange : function() {
		hui.ui.callAncestors(this,'childValueChanged',this.value);
		this.fire('valueChanged',this.value);
		hui.ui.firePropertyChange(this,'value',this.value);
	},
    destroy : function() {
        hui.dom.remove(this.element);
        if (this.selector) {
            hui.dom.remove(this.selector);
        }
    }
};