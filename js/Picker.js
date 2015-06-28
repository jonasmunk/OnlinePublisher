/** @constructor */
hui.ui.Picker = function(options) {
	options = this.options = hui.override({itemWidth:100,itemHeight:150,itemsVisible:null,shadow:true,valueProperty:'value'},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	this.container = hui.get.firstByClass(this.element,'hui_picker_container');
	this.content = hui.get.firstByClass(this.element,'hui_picker_content');
	this.title = hui.get.firstByClass(this.element,'hui_picker_title');
	this.pages = [];
	this.objects = [];
	this.selected = null;
	this.value = null;
	this._addBehavior();
	hui.ui.extend(this);
}

hui.ui.Picker.create = function(options) {
	options = hui.override({shadow:true},options);
	options.element = hui.build('div',{
		'class' : 'hui_picker',
		html : hui.when(options.title,'<div class="hui_picker_title">'+options.title+'</div>')+
		'<div class="hui_picker_container"><div class="hui_picker_content"></div></div>'+
		'<div class="hui_picker_pages"></div>'
	});
	if (options.shadow==true) {
		hui.cls.add(options.element,'hui_picker_shadow')
	}
	return new hui.ui.Picker(options);
}

hui.ui.Picker.prototype = {
	_addBehavior : function() {
		hui.drag.register({
			element : this.element,
			onBeforeMove : this._onBeforeMove.bind(this),
			onMove : this._onMove.bind(this),
			onAfterMove : this._onAfterMove.bind(this)
		});
		hui.listen(this.element,'click',this._onClick.bind(this));
	},
	_onClick : function(e) {
		if (this.dragging) {
			return;
		}
		e = hui.event(e);
		var page = e.findByClass('hui_picker_page');
		if (page) {
			this.goToPage(parseInt(page.getAttribute('data-index')));
		}
	},
	goToPage : function(index) {
		var pos = Math.round(this.container.clientWidth*index);
		pos = Math.min(pos,this.content.clientWidth-this.container.clientWidth);
		this._scrollTo(pos,hui.ease.fastSlow);
	},
	setObjects : function(objects) {
		this.selected = null;
		this.objects = objects || [];
		this._updateUI();
	},
	setValue : function(value) {
		this.value = value;
		this._updateSelection();
	},
	getValue : function() {
		return this.value;
	},
	reset : function() {
		this.value = null;
		this._updateSelection();
	},
	_updateUI : function() {
		var self = this,
			width;
		this.content.innerHTML = '';
		this.container.scrollLeft = 0;
		if (this.options.itemsVisible) {
			width = this.options.itemsVisible*(this.options.itemWidth+14);
		} else {
			width = this.container.clientWidth;
		}
		hui.style.set(this.container,{
			width : width+'px',
			height : (this.options.itemHeight+14)+'px'
		});
		hui.style.set(this.content,{
			width : (this.objects.length*(this.options.itemWidth+14))+'px',
			height : (this.options.itemHeight+14)+'px'
		});
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
				self._onItemClick(object[self.options.valueProperty])
			});
		});
		this._updatePages();
	},
	_updatePages : function() {
		var cw = this.container.clientWidth;
		if (!cw) {
			return;
		}
		var pageCount = Math.ceil(this.content.clientWidth / cw);
		var pages = hui.get.firstByClass(this.element,'hui_picker_pages');
		hui.dom.clear(pages);
		if (pageCount<2) {return}
		for (var i=1; i <= pageCount; i++) {
			hui.build('a',{
				parent : pages,
				text : i,
				className : 'hui_picker_page'+hui.when(i==1,' hui_picker_page_selected'),
				'data-index' : i-1
			});
		};
	},
	_updateSelection : function() {
		var children = this.content.childNodes;
		for (var i=0; i < children.length; i++) {
			hui.cls.set(children[i],'hui_picker_item_selected',this.value!=null && this.objects[i][this.options.valueProperty]==this.value);
		};
	},
	_onItemClick : function(value) {
		if (this.dragging) return;
		if (this.value==value) return;
		this.value = value;
		this._updateSelection();
		this.fire('select',value);
	},
	
	_onBeforeMove : function(e) {
		this.dragX = e.getLeft();
		this.dragScroll = this.container.scrollLeft;
		this.dragging = true;
	},
	_onMove : function(e) {
		this.container.scrollLeft = this.dragX-e.getLeft()+this.dragScroll;
	},
	_onAfterMove : function(e) {
		var size = this.options.itemWidth+14;
		var pos = Math.round(this.container.scrollLeft/size)*size;
		this._scrollTo(pos);
		this.dragging = false;
	},
	_scrollTo : function(pos,ease) {
		ease = ease || hui.ease.bounceOut;
		hui.animate(this.container,'scrollLeft',pos,500,{ease : ease,onComplete : this._updatePager.bind(this)});
	},
	_updatePager : function() {
		var page = Math.ceil(this.container.scrollLeft / this.container.clientWidth);
		hui.log(page)
		var pages = hui.get.byClass(this.element,'hui_picker_page');
		for (var i=0; i < pages.length; i++) {
			hui.cls.set(pages[i],'hui_picker_page_selected',page==i);
		};
	},
	
	$visibilityChanged : function() {
		if (!hui.dom.isVisible(this.element)) {return}
		this.container.style.display='none';
		var width;
		if (this.options.itemsVisible) {
			width = this.options.itemsVisible*(this.options.itemWidth+14);
		} else {
			width = this.container.parentNode.clientWidth;
		}
		width = Math.max(width,0);
		hui.style.set(this.container,{width:width+'px',display:'block'});
		this._updatePages();
	}
}

/* EOF */