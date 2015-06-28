/**
 * @constructor
 */
hui.ui.Tabs = function(o) {
	o = o || {};
	this.name = o.name;
	this.element = hui.get(o.element);
	this.activeTab = -1;
	var x = hui.get.firstByClass(this.element,'hui_tabs_bar');
	this.bar = hui.get.firstByTag(x,'ul');
	this.tabs = [];
	var nodes = this.bar.getElementsByTagName('li');
	for (var i=0; i < nodes.length; i++) {
		if (!hui.browser.msie) {
			hui.get.firstByTag(nodes[i],'a').removeAttribute('href');
		}
		this.tabs.push(nodes[i]);
	};
	this.contents = hui.get.byClass(this.element,'hui_tabs_tab');
	this.addBehavior();
	hui.ui.extend(this);
}

hui.ui.Tabs.create = function(options) {
	options = options || {};
	var e = options.element = hui.build('div',{'class':'hui_tabs'});
	var cls = 'hui_tabs_bar';
	if (options.small) {
		cls+=' hui_tabs_bar_small';
	}
	if (options.centered) {
		cls+=' hui_tabs_bar_centered';
	}
	var bar = hui.build('div',{'class' : cls, parent : e});
	hui.build('ul',{parent:bar});
	return new hui.ui.Tabs(options);
}

hui.ui.Tabs.prototype = {
	/** @private */
	addBehavior : function() {
		for (var i=0; i < this.tabs.length; i++) {
			this.addTabBehavior(this.tabs[i],i);
		};
	},
	/** @private */
	addTabBehavior : function(tab,index) {	
		hui.listen(tab,'click',function() {
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
		hui.ui.callVisible(this);
	},
	/** @private */
	updateGUI : function() {
		for (var i=0; i < this.tabs.length; i++) {
			hui.cls.set(this.tabs[i],'hui_tabs_selected',i==this.activeTab);
			this.contents[i].style.display = i==this.activeTab ? 'block' : 'none';
		};
	},
	createTab : function(options) {
		options = options || {};
		var tab = hui.build('li',{html:'<a><span><span>'+hui.string.escape(options.title)+'</span></span></a>',parent:this.bar});
		this.addTabBehavior(tab,this.tabs.length);
		this.tabs.push(tab);
		var e = options.element = hui.build('div',{'class':'hui_tabs_tab'});
		if (options.padding>0) {
			e.style.padding = options.padding+'px';
		}
		this.contents.push(e);
		this.element.appendChild(e);
		if (this.activeTab==-1) {
			this.activeTab=0;
			hui.cls.add(tab,'hui_tabs_selected');
		} else {
			e.style.display='none';
		}
		return new hui.ui.Tab(options);
	}
};

/**
 * @constructor
 */
hui.ui.Tab = function(o) {
	this.name = o.name;
	this.element = hui.get(o.element);
}

hui.ui.Tab.prototype = {
	add : function(widget) {
		this.element.appendChild(widget.element);
	}
}

/* EOF */