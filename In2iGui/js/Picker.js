/** @constructor */
hui.ui.Picker = function(o) {
	o = this.options = hui.override({itemWidth:100,itemHeight:150,itemsVisible:null,shadow:true,valueProperty:'value'},o);
	this.element = hui.get(o.element);
	this.name = o.name;
	this.container = hui.firstByClass(this.element,'in2igui_picker_container');
	this.content = hui.firstByClass(this.element,'in2igui_picker_content');
	this.title = hui.firstByClass(this.element,'in2igui_picker_title');
	this.objects = [];
	this.selected = null;
	this.value = null;
	this.addBehavior();
	hui.ui.extend(this);
}

hui.ui.Picker.create = function(o) {
	o = hui.override({shadow:true},o);
	o.element = hui.build('div',{'class':'in2igui_picker',
		html:'<div class="in2igui_picker_top"><div><div></div></div></div>'+
		'<div class="in2igui_picker_middle"><div class="in2igui_picker_middle">'+
		(o.title ? '<div class="in2igui_picker_title">'+o.title+'</div>' : '')+
		'<div class="in2igui_picker_container"><div class="in2igui_picker_content"></div></div>'+
		'</div></div>'+
		'<div class="in2igui_picker_bottom"><div><div></div></div></div>'});
	if (o.shadow==true) {
		hui.addClass(o.element,'in2igui_picker_shadow')
	}
	return new hui.ui.Picker(o);
}

hui.ui.Picker.prototype = {
	addBehavior : function() {
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
		hui.setStyle(this.container,{width:width+'px',height:(this.options.itemHeight+10)+'px'});
		this.content.style.width=(this.objects.length*(this.options.itemWidth+14))+'px';
		this.content.style.height=(this.options.itemHeight+10)+'px';
		hui.each(this.objects,function(object,i) {
			var item = hui.build('div',{'class':'in2igui_picker_item',title:object.title});
			if (self.value!=null && object[self.options.valueProperty]==self.value) {
				 hui.addClass(item,'in2igui_picker_item_selected');
			}
			item.innerHTML = '<div class="in2igui_picker_item_middle"><div class="in2igui_picker_item_middle">'+
				'<div style="width:'+self.options.itemWidth+'px;height:'+self.options.itemHeight+'px; overflow: hidden; background-image:url(\''+object.image+'\')"><strong>'+hui.escape(object.title)+'</strong></div>'+
				'</div></div>'+
				'<div class="in2igui_picker_item_bottom"><div><div></div></div></div>';
			hui.listen(item,'mouseup',function() {
				self.selectionChanged(object[self.options.valueProperty])
			});
			self.content.appendChild(item);
		});
	},
	updateSelection : function() {
		var children = this.content.childNodes;
		for (var i=0; i < children.length; i++) {
			hui.setClass(children[i],'in2igui_picker_item_selected',this.value!=null && this.objects[i][this.options.valueProperty]==this.value);
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
		e = new hui.Event(e);
		e.stop();
		var self = this;
		this.dragX = e.left();
		this.dragScroll = this.container.scrollLeft;
		hui.ui.Picker.mousemove = function(e) {self.drag(e);return false;}
		hui.ui.Picker.mouseup = hui.ui.Picker.mousedown = function(e) {self.endDrag(e);return false;}
		hui.listen(window.document,'mousemove',hui.ui.Picker.mousemove);
		hui.listen(window.document,'mouseup',hui.ui.Picker.mouseup);
		hui.listen(window.document,'mousedown',hui.ui.Picker.mouseup);
	},
	drag : function(e) {
		e = new hui.Event(e);
		e.stop();
		this.dragging = true;
		this.container.scrollLeft=this.dragX-e.left()+this.dragScroll;
	},
	endDrag : function(e) {
		this.dragging = false;
		hui.stop(e);
		hui.unListen(window.document,'mousemove',hui.ui.Picker.mousemove);
		hui.unListen(window.document,'mouseup',hui.ui.Picker.mouseup);
		hui.unListen(window.document,'mousedown',hui.ui.Picker.mouseup);
		var size = this.options.itemWidth+14;
		hui.ani(this.container,'scrollLeft',Math.round(this.container.scrollLeft/size)*size,500,{ease:hui.ease.bounceOut});
	},
	$visibilityChanged : function() {
		this.container.style.display='none';
		var width;
		if (this.options.itemsVisible) {
			width = this.options.itemsVisible*(this.options.itemWidth+14);
		} else {
			width = this.container.parentNode.clientWidth-12;
		}
		width = Math.max(width,0);
		hui.setStyle(this.container,{width:width+'px',display:'block'});
	}
}

/* EOF */