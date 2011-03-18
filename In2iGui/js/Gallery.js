/** @constructor */
In2iGui.Gallery = function(options) {
	this.options = options || {};
	this.name = options.name;
	this.element = n2i.get(options.element);
	this.objects = [];
	this.nodes = [];
	this.selected = [];
	this.width = 100;
	this.height = 100;
	this.revealing = false;
	In2iGui.extend(this);
	if (this.options.source) {
		this.options.source.listen(this);
	}
	if (this.element.parentNode && n2i.hasClass(this.element.parentNode,'in2igui_overflow')) {
		this.revealing = true;
		n2i.listen(this.element.parentNode,'scroll',this._reveal.bind(this));
	}
}

In2iGui.Gallery.create = function(options) {
	options = options || {};
	options.element = n2i.build('div',{'class':'in2igui_gallery'});
	return new In2iGui.Gallery(options);
}

In2iGui.Gallery.prototype = {
	setObjects : function(objects) {
		this.objects = objects;
		this.render();
		this.fire('selectionReset');
	},
	getObjects : function() {
		return this.objects;
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
		this.element.innerHTML='';
		var self = this;
		n2i.each(this.objects,function(object,i) {
			var url = self.resolveImageUrl(object),
				top = 0;
			if (url!==null) {
				url = url.replace(/&amp;/,'&');
			}
			if (object.height<object.width) {
				top = (self.height-(self.height*object.height/object.width))/2;
			}
			var img = n2i.build('img',{style:'margin:'+top+'px auto 0px'});
			img.setAttribute(self.revealing ? 'data-src' : 'src', url );
			var item = n2i.build('div',{'class' : 'in2igui_gallery_item',style:'width:'+self.width+'px; height:'+self.height+'px'});
			item.appendChild(img);
			n2i.listen(item,'click',function() {
				self.itemClicked(i);
			});
			item.dragDropInfo = {kind:'image',icon:'common/image',id:object.id,title:object.name || object.title};
			item.onmousedown=function(e) {
				In2iGui.startDrag(e,item);
				return false;
			};
			n2i.listen(item,'dblclick',function() {
				self.itemDoubleClicked(i);
			});
			self.element.appendChild(item);
			self.nodes.push(item);
		});
		this._reveal();
	},
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
				item.className = 'in2igui_gallery_item in2igui_gallery_item_busy';
				img.onload = function() {
					item.className = 'in2igui_gallery_item';
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
			n2i.setClass(this.nodes[i],'in2igui_gallery_item_selected',n2i.inArray(s,i));
		};
	},
	/** @private */
	resolveImageUrl : function(img) {
		return In2iGui.resolveImageUrl(this,img,this.width,this.height);
		for (var i=0; i < this.delegates.length; i++) {
			if (this.delegates[i]['$resolveImageUrl']) {
				return this.delegates[i]['$resolveImageUrl'](img,this.width,this.height);
			}
		};
		return '';
	},
	/** @private */
	itemClicked : function(index) {
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
		this.fire('itemOpened',this.objects[index]);
	},
	/**
	 * Sets the lists data source and refreshes it if it is new
	 * @param {In2iGui.Source} source The source
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
	}
}

/* EOF */