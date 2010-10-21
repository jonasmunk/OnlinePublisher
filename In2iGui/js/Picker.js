/** @constructor */
In2iGui.Picker = function(o) {
	o = this.options = n2i.override({itemWidth:100,itemHeight:150,itemsVisible:null,shadow:true,valueProperty:'value'},o);
	this.element = $(o.element);
	this.name = o.name;
	this.container = this.element.select('.in2igui_picker_container')[0];
	this.content = this.element.select('.in2igui_picker_content')[0];
	this.title = this.element.select('in2igui_picker_title')[0];
	this.objects = [];
	this.selected = null;
	this.value = null;
	this.addBehavior();
	In2iGui.extend(this);
}

In2iGui.Picker.create = function(o) {
	o = n2i.override({shadow:true},o);
	var element = new Element('div',{'class':'in2igui_picker'});
	element.update('<div class="in2igui_picker_top"><div><div></div></div></div>'+
	'<div class="in2igui_picker_middle"><div class="in2igui_picker_middle">'+
	(o.title ? '<div class="in2igui_picker_title">'+o.title+'</div>' : '')+
	'<div class="in2igui_picker_container"><div class="in2igui_picker_content"></div></div>'+
	'</div></div>'+
	'<div class="in2igui_picker_bottom"><div><div></div></div></div>');
	if (o.shadow==true) {
		element.addClassName('in2igui_picker_shadow')
	}
	o.element = element;
	return new In2iGui.Picker(o);
}

In2iGui.Picker.prototype = {
	addBehavior : function() {
		var self = this;
		this.content.observe('mousedown',function(e) {
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
		this.content.update();
		this.container.scrollLeft=0;
		if (this.options.itemsVisible) {
			var width = this.options.itemsVisible*(this.options.itemWidth+14);
		} else {
			var width = this.container.clientWidth;
		}
		this.container.setStyle({width:width+'px',height:(this.options.itemHeight+10)+'px'});
		this.content.style.width=(this.objects.length*(this.options.itemWidth+14))+'px';
		this.content.style.height=(this.options.itemHeight+10)+'px';
		this.objects.each(function(object,i) {
			var item = new Element('div',{'class':'in2igui_picker_item',title:object.title});
			if (self.value!=null && object[self.options.valueProperty]==self.value) item.addClassName('in2igui_picker_item_selected');
			item.update(
				'<div class="in2igui_picker_item_middle"><div class="in2igui_picker_item_middle">'+
				'<div style="width:'+self.options.itemWidth+'px;height:'+self.options.itemHeight+'px; overflow: hidden; background-image:url(\''+object.image+'\')"><strong>'+object.title+'</strong></div>'+
				'</div></div>'+
				'<div class="in2igui_picker_item_bottom"><div><div></div></div></div>'
			);
			item.observe('mouseup',function() {self.selectionChanged(object[self.options.valueProperty])});
			self.content.insert(item);			
		});
	},
	updateSelection : function() {
		var children = this.content.childNodes;
		for (var i=0; i < children.length; i++) {
			Element.setClassName(children[i],'in2igui_picker_item_selected',this.value!=null && this.objects[i][this.options.valueProperty]==this.value);
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
		e.stop();
		var self = this;
		this.dragX = Event.pointerX(e);
		this.dragScroll = this.container.scrollLeft;
		In2iGui.Picker.mousemove = function(e) {self.drag(e);return false;}
		In2iGui.Picker.mouseup = In2iGui.Picker.mousedown = function(e) {self.endDrag(e);return false;}
		window.document.observe('mousemove',In2iGui.Picker.mousemove);
		window.document.observe('mouseup',In2iGui.Picker.mouseup);
		window.document.observe('mousedown',In2iGui.Picker.mouseup);
	},
	drag : function(e) {
		this.dragging = true;
		Event.stop(e);
		this.container.scrollLeft=this.dragX-e.pointerX()+this.dragScroll;
	},
	endDrag : function(e) {
		this.dragging = false;
		Event.stop(e);
		window.document.stopObserving('mousemove',In2iGui.Picker.mousemove);
		window.document.stopObserving('mouseup',In2iGui.Picker.mouseup);
		window.document.stopObserving('mousedown',In2iGui.Picker.mouseup);
		var size = (this.options.itemWidth+14);
		n2i.ani(this.container,'scrollLeft',Math.round(this.container.scrollLeft/size)*size,500,{ease:n2i.ease.bounceOut});
	},
	$visibilityChanged : function() {
		this.container.setStyle({display:'none'});
		if (this.options.itemsVisible) {
			var width = this.options.itemsVisible*(this.options.itemWidth+14);
		} else {
			var width = this.container.parentNode.clientWidth-12;
		}
		width = Math.max(width,0);
		this.container.setStyle({width:width+'px',display:'block'});
	}
}

/* EOF */