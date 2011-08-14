/** @constructor */
hui.ui.Gallery = function(options) {
	this.options = options || {};
	this.name = options.name;
	this.element = hui.get(options.element);
	this.body = hui.firstByClass(this.element,'hui_gallery_body');
	this.objects = [];
	this.nodes = [];
	this.selected = [];
	this.width = 100;
	this.height = 100;
	this.revealing = false;
	hui.ui.extend(this);
	if (this.options.source) {
		this.options.source.listen(this);
	}
	if (this.element.parentNode && hui.hasClass(this.element.parentNode,'hui_overflow')) {
		this.revealing = true;
		hui.listen(this.element.parentNode,'scroll',this._reveal.bind(this));
	}
}

hui.ui.Gallery.create = function(options) {
	options = options || {};
	options.element = hui.build('div',{'class':'hui_gallery',html:'<div class="hui_gallery_progress"></div><div class="hui_gallery_body"></div>'});
	return new hui.ui.Gallery(options);
}

hui.ui.Gallery.prototype = {
	hide : function() {
		this.element.style.display='none';
	},
	show : function() {
		this.element.style.display='';
	},
	setObjects : function(objects) {
		this.objects = objects;
		this.render();
		this.fire('selectionReset');
	},
	getObjects : function() {
		return this.objects;
	},
	/** @private */
	$sourceShouldRefresh : function() {
		return this.element.style.display!='none';
	},
	/** @private */
	$objectsLoaded : function(objects) {
		this.setObjects(objects);
	},
	/** @private */
	$itemsLoaded : function(objects) {
		this.setObjects(objects);
	},
	/** @private */
	render : function() {
		this.nodes = [];
		this.maxRevealed=0;
		this.body.innerHTML='';
		var self = this;
		hui.each(this.objects,function(object,i) {
			var url = self.resolveImageUrl(object),
				top = 0;
			if (url!==null) {
				url = url.replace(/&amp;/,'&');
			}
			if (!self.revealing && object.height<object.width) {
				top = (self.height-(self.height*object.height/object.width))/2;
			}
			var img = hui.build('img',{style:'margin:'+top+'px auto 0px'});
			img.setAttribute(self.revealing ? 'data-src' : 'src', url );
			if (self.revealing) {
			//	img.style.visibility='hidden';
			}
			var item = hui.build('div',{'class' : 'hui_gallery_item',style:'width:'+self.width+'px; height:'+self.height+'px'});
			item.appendChild(img);
			hui.listen(item,'click',function() {
				self.itemClicked(i);
			});
			item.dragDropInfo = {kind:'image',icon:'common/image',id:object.id,title:object.name || object.title};
			item.onmousedown=function(e) {
				hui.ui.startDrag(e,item);
				return false;
			};
			hui.listen(item,'dblclick',function() {
				self.itemDoubleClicked(i);
			});
			self.body.appendChild(item);
			self.nodes.push(item);
		});
		this._reveal();
	},
	/** @private */
	$$layout : function() {
		if (this.nodes.length>0) {
			this._reveal();
		}
	},
	_reveal : function() {
		if (!this.revealing) {return}
		var container = this.element.parentNode;
		var limit = container.scrollTop+container.clientHeight;
		if (limit<=this.maxRevealed) {
			return;
		}
		this.maxRevealed = limit;
		for (var i=0,l=this.nodes.length; i < l; i++) {
			var item = this.nodes[i];
			if (item.revealed) {continue}
			if (item.offsetTop<limit) {
				var img = item.getElementsByTagName('img')[0];
				item.className = 'hui_gallery_item hui_gallery_item_busy';
				var self = this;
				img.onload = function() {
					this.parentNode.className = 'hui_gallery_item';
					if (this.height<this.width) {
						var top = (self.height-(self.height*this.height/this.width))/2;
						this.style.marginTop = top+'px';
					}
				}
				img.onerror = function() {
					this.parentNode.className = 'hui_gallery_item hui_gallery_item_error';
				}
				img.src = img.getAttribute('data-src');
				item.revealed = true;
			}
		};
	},
	/** @private */
	updateUI : function() {
		var s = this.selected;
		for (var i=0; i < this.nodes.length; i++) {
			hui.setClass(this.nodes[i],'hui_gallery_item_selected',hui.inArray(s,i));
		};
	},
	/** @private */
	resolveImageUrl : function(img) {
		return hui.ui.resolveImageUrl(this,img,this.width,this.height);
		for (var i=0; i < this.delegates.length; i++) {
			if (this.delegates[i]['$resolveImageUrl']) {
				return this.delegates[i]['$resolveImageUrl'](img,this.width,this.height);
			}
		};
		return '';
	},
	/** @private */
	itemClicked : function(index) {
		if (this.busy) {
			return;
		}
		this.selected = [index];
		this.fire('selectionChanged');
		this.updateUI();
	},
	getFirstSelection : function() {
		if (this.selected.length>0) {
			return this.objects[this.selected[0]];
		}
		return null;
	},
	/** @private */
	itemDoubleClicked : function(index) {
		if (this.busy) {
			return;
		}
		this.fire('itemOpened',this.objects[index]);
	},
	/**
	 * Sets the lists data source and refreshes it if it is new
	 * @param {hui.ui.Source} source The source
	 */
	setSource : function(source) {
		if (this.options.source!=source) {
			if (this.options.source) {
				this.options.source.removeDelegate(this);
			}
			source.listen(this);
			this.options.source = source;
			source.refresh();
		}
	},
	/** @private */
	$sourceIsBusy : function() {
		this._setBusy(true);
	},
	/** @private */
	$sourceIsNotBusy : function() {
		this._setBusy(false);
	},
	$visibilityChanged : function() {
		if (hui.dom.isVisible(this.element)) {
			this._reveal();
		}
	},
	_setBusy : function(busy) {
		this.busy = busy;
		window.clearTimeout(this.busytimer);
		if (busy) {
			var e = this.element;
			this.busytimer = window.setTimeout(function() {
				hui.addClass(e,'hui_gallery_busy');
			},300);
		} else {
			hui.removeClass(this.element,'hui_gallery_busy');
		}
	}
}

/* EOF */