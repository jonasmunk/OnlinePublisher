/**
 * @constructor
 */
In2iGui.Tabs = function(o) {
	o = o || {};
	this.name = o.name;
	this.element = n2i.get(o.element);
	this.activeTab = -1;
	var x = n2i.firstByClass(this.element,'in2igui_tabs_bar');
	this.bar = n2i.firstByTag(x,'ul');
	this.tabs = [];
	var nodes = this.bar.getElementsByTagName('li');
	for (var i=0; i < nodes.length; i++) {
		this.tabs.push(nodes[i]);
	};
	this.contents = n2i.byClass(this.element,'in2igui_tabs_tab');
	this.addBehavior();
	In2iGui.extend(this);
}

In2iGui.Tabs.create = function(options) {
	options = options || {};
	var e = options.element = n2i.build('div',{'class':'in2igui_tabs'});
	var cls = 'in2igui_tabs_bar';
	if (options.small) {
		cls+=' in2igui_tabs_bar_small';
	}
	if (options.centered) {
		cls+=' in2igui_tabs_bar_centered';
	}
	var bar = n2i.build('div',{'class' : cls, parent : e});
	n2i.build('ul',{parent:bar});
	return new In2iGui.Tabs(options);
}

In2iGui.Tabs.prototype = {
	/** @private */
	addBehavior : function() {
		for (var i=0; i < this.tabs.length; i++) {
			this.addTabBehavior(this.tabs[i],i);
		};
	},
	/** @private */
	addTabBehavior : function(tab,index) {	
		n2i.listen(tab,'click',function() {
			this.tabWasClicked(index);
		}.bind(this))
	},
	/** @private */
	registerTab : function(obj) {
		obj.parent = this;
		this.tabs.push(obj);
	},
	/** @private */
	tabWasClicked : function(index) {
		this.activeTab = index;
		this.updateGUI();
	},
	/** @private */
	updateGUI : function() {
		for (var i=0; i < this.tabs.length; i++) {
			n2i.setClass(this.tabs[i],'in2igui_tabs_selected',i==this.activeTab);
			this.contents[i].style.display = i==this.activeTab ? 'block' : 'none';
		};
	},
	createTab : function(options) {
		options = options || {};
		var tab = n2i.build('li',{html:'<a><span><span>'+n2i.escape(options.title)+'</span></span></a>',parent:this.bar});
		this.addTabBehavior(tab,this.tabs.length);
		this.tabs.push(tab);
		var e = options.element = n2i.build('div',{'class':'in2igui_tabs_tab'});
		if (options.padding>0) {
			e.style.padding = options.padding+'px';
		}
		this.contents.push(e);
		this.element.appendChild(e);
		if (this.activeTab==-1) {
			this.activeTab=0;
			n2i.addClass(tab,'in2igui_tabs_selected');
		} else {
			e.style.display='none';
		}
		return new In2iGui.Tab(options);
	}
};

/**
 * @constructor
 */
In2iGui.Tab = function(o) {
	this.name = o.name;
	this.element = n2i.get(o.element);
}

In2iGui.Tab.prototype = {
	add : function(widget) {
		this.element.appendChild(widget.element);
	}
}

/* EOF */