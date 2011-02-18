/** @constructor */
In2iGui.InfoView = function(options) {
	this.options = n2i.override({clickObjects:false},options);
	this.element = n2i.get(options.element);
	this.body = this.element.select('tbody')[0];
	this.name = options.name;
	In2iGui.extend(this);
}

In2iGui.InfoView.create = function(options) {
	options = options || {};
	var element = options.element = n2i.build('div',{'class':'in2igui_infoview',html:'<table><tbody></tbody></table>'});
	if (options.height) {
		n2i.setStyle(element,{height:options.height+'px','overflow':'auto','overflowX':'hidden'});
	}
	if (options.margin) {
		element.style.margin = options.margin+'px';
	}
	return new In2iGui.InfoView(options);
}

In2iGui.InfoView.prototype = {
	addHeader : function(text) {
		var row = n2i.build('tr',{parent:this.body});
		n2i.build('th',{'class' : 'in2igui_infoview_header',colspan:'2',text:text,parent:row});
	},
	addProperty : function(label,text) {
		var row = n2i.build('tr',{parent:this.body});
		n2i.build('th',{parent:row,text:label});
		n2i.build('td',{parent:row,text:text});
	},
	addObjects : function(label,objects) {
		if (!objects || objects.length==0) return;
		var row = n2i.build('tr',{parent:this.body});
		row.appendChild(n2i.build('th',{text:label}));
		var cell = n2i.build('td',{parent:row});
		var click = this.options.clickObjects;
		n2i.each(objects,function(obj) {
			var node = n2i.build('div',{text:obj.title,parent:cell});
			if (click) {
				n2i.addClass(node,'in2igui_infoview_click')
				n2i.listen(node,'click',function() {
					In2iGui.callDelegates(this,'objectWasClicked',obj);
				});
			}
		});
	},
	setBusy : function(busy) {
		n2i.setClass(this,element,'in2igui_infoview_busy',busy);
	},
	clear : function() {
		n2i.dom.clear(this.body);
	},
	update : function(data) {
		this.clear();
		for (var i=0; i < data.length; i++) {
			switch (data[i].type) {
				case 'header': this.addHeader(data[i].value); break;
				case 'property': this.addProperty(data[i].label,data[i].value); break;
				case 'objects': this.addObjects(data[i].label,data[i].value); break;
			}
		};
	}
}

/* EOF */