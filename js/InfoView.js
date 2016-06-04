/** @constructor */
hui.ui.InfoView = function(options) {
	this.options = hui.override({clickObjects:false},options);
	this.element = hui.get(options.element);
	this.body = hui.get.firstByTag(this.element,'tbody');
	this.name = options.name;
	hui.ui.extend(this);
}

hui.ui.InfoView.create = function(options) {
	options = options || {};
	var element = options.element = hui.build('div',{'class':'hui_infoview',html:'<table><tbody></tbody></table>'});
	if (options.height) {
		hui.style.set(element,{height:options.height+'px','overflow':'auto','overflowX':'hidden'});
	}
	if (options.margin) {
		element.style.margin = options.margin+'px';
	}
	return new hui.ui.InfoView(options);
}

hui.ui.InfoView.prototype = {
	addHeader : function(text) {
		var row = hui.build('tr',{parent:this.body});
		hui.build('th',{'class' : 'hui_infoview_header',colspan:'2',text:text,parent:row});
	},
	addProperty : function(label,text) {
		var row = hui.build('tr',{parent:this.body});
		hui.build('th',{parent:row,text:label});
		hui.build('td',{parent:row,text:text});
	},
	addObjects : function(label,objects) {
		if (!objects || objects.length==0) return;
		var row = hui.build('tr',{parent:this.body});
		row.appendChild(hui.build('th',{text:label}));
		var cell = hui.build('td',{parent:row});
		var click = this.options.clickObjects;
		hui.each(objects,function(obj) {
			var node = hui.build('div',{text:obj.title,parent:cell});
			if (click) {
				hui.cls.add(node,'hui_infoview_click')
				hui.listen(node,'click',function() {
					hui.ui.callDelegates(this,'objectWasClicked',obj);
				});
			}
		});
	},
	setBusy : function(busy) {
		hui.cls.set(this,element,'hui_infoview_busy',busy);
	},
	clear : function() {
		hui.dom.clear(this.body);
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