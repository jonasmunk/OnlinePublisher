/** @constructor */
In2iGui.Picker = function(o) {
	o = this.options = n2i.override({itemWidth:100,itemHeight:150,itemsVisible:null,shadow:true,valueProperty:'value'},o);
	this.element = n2i.get(o.element);
	this.name = o.name;
	this.container = n2i.firstByClass(this.element,'in2igui_picker_container');
	this.content = n2i.firstByClass(this.element,'in2igui_picker_content');
	this.title = n2i.firstByClass(this.element,'in2igui_picker_title');
	this.objects = [];
	this.selected = null;
	this.value = null;
	this.addBehavior();
	In2iGui.extend(this);
}

In2iGui.Picker.create = function(o) {
	o = n2i.override({shadow:true},o);
	o.element = n2i.build('div',{'class':'in2igui_picker',
		html:'<div class="in2igui_picker_top"><div><div></div></div></div>'+
		'<div class="in2igui_picker_middle"><div class="in2igui_picker_middle">'+
		(o.title ? '<div class="in2igui_picker_title">'+o.title+'</div>' : '')+
		'<div class="in2igui_picker_container"><div class="in2igui_picker_content"></div></div>'+
		'</div></div>'+
		'<div class="in2igui_picker_bottom"><div><div></div></div></div>'});
	if (o.shadow==true) {
		n2i.addClass(o.element,'in2igui_picker_shadow')
	}
	return new In2iGui.Picker(o);
}

In2iGui.Picker.prototype = {
	addBehavior : function() {
		var self = this;
		n2i.listen(this.content,'mousedown',function(e) {
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
		var self = this;
		this.content.innerHTML='';
		this.container.scrollLeft=0;
		if (this.options.itemsVisible) {
			var width = this.options.itemsVisible*(this.options.itemWidth+14);
		} else {
			var width = this.container.clientWidth;
		}
		n2i.setStyle(this.container,{width:width+'px',height:(this.options.itemHeight+10)+'px'});
		this.content.style.width=(this.objects.length*(this.options.itemWidth+14))+'px';
		this.content.style.height=(this.options.itemHeight+10)+'px';
		n2i.each(this.objects,function(object,i) {
			var item = n2i.build('div',{'class':'in2igui_picker_item',title:object.title});
			if (self.value!=null && object[self.options.valueProperty]==self.value) {
				 n2i.addClass(item,'in2igui_picker_item_selected');
			}
			item.innerHTML = '<div class="in2igui_picker_item_middle"><div class="in2igui_picker_item_middle">'+
				'<div style="width:'+self.options.itemWidth+'px;height:'+self.options.itemHeight+'px; overflow: hidden; background-image:url(\''+object.image+'\')"><strong>'+n2i.escape(object.title)+'</strong></div>'+
				'</div></div>'+
				'<div class="in2igui_picker_item_bottom"><div><div></div></div></div>';
			n2i.listen(item,'mouseup',function() {
				self.selectionChanged(object[self.options.valueProperty])
			});
			self.content.appendChild(item);
		});
	},
	updateSelection : function() {
		var children = this.content.childNodes;
		for (var i=0; i < children.length; i++) {
			n2i.setClass(children[i],'in2igui_picker_item_selected',this.value!=null && this.objects[i][this.options.valueProperty]==this.value);
		};
	},
	selectionChanged : function(value) {
		if (this.dragging) return;
		if (this.value==value) return;
		this.value = value;
		this.updateSelection();
		this.fire('selectionChanged',value);
	},
	
	// Dragging
	startDrag : function(e) {
		e = new n2i.Event(e);
		e.stop();
		var self = this;
		this.dragX = e.left();
		this.dragScroll = this.container.scrollLeft;
		In2iGui.Picker.mousemove = function(e) {self.drag(e);return false;}
		In2iGui.Picker.mouseup = In2iGui.Picker.mousedown = function(e) {self.endDrag(e);return false;}
		n2i.listen(window.document,'mousemove',In2iGui.Picker.mousemove);
		n2i.listen(window.document,'mouseup',In2iGui.Picker.mouseup);
		n2i.listen(window.document,'mousedown',In2iGui.Picker.mouseup);
	},
	drag : function(e) {
		e = new n2i.Event();
		this.dragging = true;
		e.stop();
		this.container.scrollLeft=this.dragX-e.left()+this.dragScroll;
	},
	endDrag : function(e) {
		this.dragging = false;
		n2i.stop(e);
		n2i.unListen(window.document,'mousemove',In2iGui.Picker.mousemove);
		n2i.unListen(window.document,'mouseup',In2iGui.Picker.mouseup);
		n2i.unListen(window.document,'mousedown',In2iGui.Picker.mouseup);
		var size = this.options.itemWidth+14;
		n2i.ani(this.container,'scrollLeft',Math.round(this.container.scrollLeft/size)*size,500,{ease:n2i.ease.bounceOut});
	},
	$visibilityChanged : function() {
		this.container.style.display='none';
		if (this.options.itemsVisible) {
			var width = this.options.itemsVisible*(this.options.itemWidth+14);
		} else {
			var width = this.container.parentNode.clientWidth-12;
		}
		width = Math.max(width,0);
		n2i.setStyle(this.container,{width:width+'px',display:'block'});
	}
}

/* EOF */