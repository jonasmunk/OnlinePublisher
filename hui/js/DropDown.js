////////////////////////// DropDown ///////////////////////////

/**
 * A drop down selector
 * @constructor
 */
hui.ui.DropDown = function(o) {
	this.options = hui.override({label:null,placeholder:null,url:null,source:null},o);
	this.name = o.name;
	var e = this.element = hui.get(o.element);
	this.inner = e.getElementsByTagName('strong')[0];
	this.items = o.items || [];
	this.index = -1;
	this.value = this.options.value || null;
	this.dirty = true;
	this.busy = false;
	hui.ui.extend(this);
	this._addBehavior();
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
	options.element = hui.build('a',{
		'class':'hui_dropdown',href:'javascript://',
		html:'<span><span><strong></strong></span></span>'
	});
	return new hui.ui.DropDown(options);
}

hui.ui.DropDown.prototype = {
	/** @private */
	_addBehavior : function() {
		hui.ui.addFocusClass({element:this.element,'class':'hui_dropdown_focused'});
		hui.listen(this.element,'click',this._click.bind(this));
		hui.listen(this.element,'blur',this._hideSelector.bind(this));
		hui.listen(this.element,'keydown',this._keyDown.bind(this));
	},
	/** @private */
	_updateIndex : function() {
		this.index=-1;
		for (var i=0; i < this.items.length; i++) {
			if (this.items[i].value==this.value) {
				this.index=i;
			}
		};
	},
	/** @private */
	_updateUI : function() {
		var selected = this.items[this.index];
		if (selected) {
			var text = selected.label || selected.title || selected.text || '';
			this.inner.innerHTML='';
			hui.dom.addText(this.inner,hui.wrap(text));
		} else if (this.options.placeholder) {
			this.inner.innerHTML='';
			this.inner.appendChild(hui.build('em',{text:hui.escape(this.options.placeholder)}));
		} else {
			this.inner.innerHTML='';
		}
		if (!this.selector) {
			return;
		}
		var as = this.selector.getElementsByTagName('a');
		for (var i=0; i < as.length; i++) {
			if (this.index==i) {
				hui.addClass(as[i],'hui_selected');
			} else {
				as[i].className='';
			}
		};
	},
	/** @private */
	_click : function(e) {
		if (this.busy) {return}
		hui.stop(e);
		this._buildSelector();
		var el = this.element, s=this.selector;
		el.focus();
		if (!this.items) return;
		var docHeight = hui.getDocumentHeight();
		if (docHeight<200) {
			var left = hui.getLeft(this.element);
			hui.setStyle(this.selector,{'left':left+'px',top:'5px'});
		} else {
			var windowScrollTop = hui.window.getScrollTop();
			var scrollOffsetTop = hui.getScrollOffset(this.element).top;
			var scrollTop = windowScrollTop-scrollOffsetTop;
			hui.place({
				target : {element:this.element,vertical:1,horizontal:0},
				source : {element:this.selector,vertical:0,horizontal:0},
				top : scrollTop
			});
		}
		hui.setStyle(s,{visibility:'hidden',display:'block',width:''});
		var height = Math.min(docHeight-hui.getTop(s)-5,200);
		var width = Math.max(el.clientWidth-5,100,s.clientWidth+20);
		var space = hui.getViewPortWidth()-hui.getLeft(el)-20;
		width = Math.min(width,space);
		hui.setStyle(s,{visibility:'visible',width:width+'px',zIndex:hui.ui.nextTopIndex(),maxHeight:height+'px'});
	},
	/** @private */
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
	getValue : function() {
		return this.value;
	},
	setValue : function(value) {
		this.value = value;
		this._updateIndex();
		this._updateUI();
	},
	reset : function() {
		this.setValue(null);
	},
	getLabel : function() {
		return this.options.label;
	},
	refresh : function() {
		if (this.options.source) {
			this.options.source.refresh();
		}
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
		hui.setOpacity(this.element,.5);
	},
	$sourceIsNotBusy : function() {
		this.busy = false;
		hui.setOpacity(this.element,1);
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
	/** @private */
	_hideSelector : function() {
		if (!this.selector) {return}
		this.selector.style.display='none';
	},
	/** @private */
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
			var e = hui.build('a',{href:'javascript://',text:item.label || item.title || item.text});
			hui.listen(e,'mousedown',function(e) {
				hui.stop(e);
				self._itemClicked(item,i);
			})
			if (i==self.index) {
				hui.addClass(e,'hui_selected')
			};
			self.selector.appendChild(e);
		});
		this.dirty = false;
	},
	/** @private */
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
	}
}