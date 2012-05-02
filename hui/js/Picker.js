/** @constructor */
hui.ui.Picker = function(o) {
	o = this.options = hui.override({itemWidth:100,itemHeight:150,itemsVisible:null,shadow:true,valueProperty:'value'},o);
	this.element = hui.get(o.element);
	this.name = o.name;
	this.container = hui.get.firstByClass(this.element,'hui_picker_container');
	this.content = hui.get.firstByClass(this.element,'hui_picker_content');
	this.title = hui.get.firstByClass(this.element,'hui_picker_title');
	this.objects = [];
	this.selected = null;
	this.value = null;
	this.addBehavior();
	hui.ui.extend(this);
}

hui.ui.Picker.create = function(o) {
	o = hui.override({shadow:true},o);
	o.element = hui.build('div',{'class':'hui_picker',
		html:'<div class="hui_picker_middle"><div class="hui_picker_middle">'+
		(o.title ? '<div class="hui_picker_title">'+o.title+'</div>' : '')+
		'<div class="hui_picker_container"><div class="hui_picker_content"></div></div>'+
		'</div></div>'});
	if (o.shadow==true) {
		hui.cls.add(o.element,'hui_picker_shadow')
	}
	return new hui.ui.Picker(o);
}

hui.ui.Picker.prototype = {
	addBehavior : function() {
		hui.drag.register({
			element : this.element,
			onBeforeMove : this._onBeforeMove.bind(this),
			onMove : this._onMove.bind(this),
			onAfterMove : this._onAfterMove.bind(this)
		});
		return;
		var self = this;
		hui.listen(this.content,'mousedown',function(e) {
			self.startDrag(e);
			return false;
		});
	},
	setObjects : function(objects) {
		this.selected = null;
		this.objects = objects || [];
		this.updateUI();
	},
	setValue : function(value) {
		this.value = value;
		this.updateSelection();
	},
	getValue : function() {
		return this.value;
	},
	reset : function() {
		this.value = null;
		this.updateSelection();
	},
	updateUI : function() {
		var self = this,
			width;
		this.content.innerHTML='';
		this.container.scrollLeft=0;
		if (this.options.itemsVisible) {
			width = this.options.itemsVisible*(this.options.itemWidth+14);
		} else {
			width = this.container.clientWidth;
		}
		hui.style.set(this.container,{width:width+'px',height:(this.options.itemHeight+14)+'px'});
		this.content.style.width=(this.objects.length*(this.options.itemWidth+14))+'px';
		this.content.style.height=(this.options.itemHeight+14)+'px';
		hui.each(this.objects,function(object,i) {
			var item = hui.build('div',{
				'class' : 'hui_picker_item',
				title : object.title,
				html : '<div style="width:'+self.options.itemWidth+'px;height:'+self.options.itemHeight+'px; overflow: hidden; background-image:url(\''+object.image+'\')"><strong>'+hui.string.escape(object.title)+'</strong></div>',
				parent : self.content
			});
			if (self.value!=null && object[self.options.valueProperty]==self.value) {
				 hui.cls.add(item,'hui_picker_item_selected');
			}
			hui.listen(item,'mouseup',function() {
				self.selectionChanged(object[self.options.valueProperty])
			});
		});
	},
	updateSelection : function() {
		var children = this.content.childNodes;
		for (var i=0; i < children.length; i++) {
			hui.cls.set(children[i],'hui_picker_item_selected',this.value!=null && this.objects[i][this.options.valueProperty]==this.value);
		};
	},
	selectionChanged : function(value) {
		if (this.dragging) return;
		if (this.value==value) return;
		this.value = value;
		this.updateSelection();
		this.fire('select',value);
	},
	
	_onBeforeMove : function(e) {
		this.dragX = e.getLeft();
		this.dragScroll = this.container.scrollLeft;
		this.dragging = true;
	},
	_onMove : function(e) {
		this.container.scrollLeft=this.dragX-e.getLeft()+this.dragScroll;
	},
	_onAfterMove : function(e) {
		var size = this.options.itemWidth+14;
		hui.animate(this.container,'scrollLeft',Math.round(this.container.scrollLeft/size)*size,500,{ease:hui.ease.bounceOut});
		this.dragging = false;
	},
	
	$visibilityChanged : function() {
		if (!hui.dom.isVisible(this.container)) {return}
		this.container.style.display='none';
		var width;
		if (this.options.itemsVisible) {
			width = this.options.itemsVisible*(this.options.itemWidth+14);
		} else {
			width = this.container.parentNode.clientWidth-12;
		}
		width = Math.max(width,0);
		hui.style.set(this.container,{width:width+'px',display:'block'});
	}
}

/* EOF */