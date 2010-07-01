/** @constructor */
In2iGui.Gallery = function(options) {
	this.options = options || {};
	this.name = options.name;
	this.element = $(options.element);
	this.objects = [];
	this.nodes = [];
	this.selected = [];
	this.width = 100;
	this.height = 100;
	In2iGui.extend(this);
	if (this.options.source) {
		this.options.source.listen(this);
	}
}

In2iGui.Gallery.create = function(options) {
	options = options || {};
	options.element = new Element('div',{'class':'in2igui_gallery'});
	return new In2iGui.Gallery(options);
}

In2iGui.Gallery.prototype = {
	setObjects : function(objects) {
		this.objects = objects;
		this.render();
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
		this.element.update();
		var self = this;
		this.objects.each(function(object,i) {
			var url = self.resolveImageUrl(object);
			if (url!==null) {
				url = url.replace(/&amp;/,'&');
			}
			if (object.height<object.width) {
				var top = (self.height-(self.height*object.height/object.width))/2;
			} else {
				var top = 0;
			}
			var img = new Element('img',{src:url}).setStyle({margin:top+'px auto 0px'});
			var item = new Element('div',{'class':'in2igui_gallery_item'}).setStyle({'width':self.width+'px','height':self.height+'px'}).insert(img);
			item.observe('click',function() {
				self.itemClicked(i);
			});
			item.dragDropInfo = {kind:'image',icon:'common/image',id:object.id,title:object.name};
			item.onmousedown=function(e) {
				In2iGui.startDrag(e,item);
				return false;
			};
			item.observe('dblclick',function() {
				self.itemDoubleClicked(i);
			});
			self.element.insert(item);
			self.nodes.push(item);
		});
	},
	/** @private */
	updateUI : function() {
		var s = this.selected;
		this.nodes.each(function(node,i) {
			node.setClassName('in2igui_gallery_item_selected',n2i.inArray(s,i));
		});
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