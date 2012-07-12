var partPosterController = {
	
	widget : null,
	dom : null,
	pageIndex : 0,
	
	$ready : function() {
		var form = document.forms.PartForm;
		var recipe = form.recipe.value;
		this.dom = hui.xml.parse(recipe);
		if (!this.dom) {
			
		}
		sourceFormula.setValues({
			recipe : recipe
		})
		this._setPage(0);
		posterWindow.show({avoid:form});
		pageWindow.show({avoid:posterWindow.element});
	},
	$resolveImageUrl : function(img,width,height) {
		return '../../../services/images/?id='+img.id+'&width='+width+'&height='+height+'&format=jpg';
	},
	_connectToWidget : function() {
		this.widget = hui.ui.get('part_poster_'+document.forms.PartForm.id.value);
		this.widget.setPage(this.pageIndex);
		this.widget.listen(this);
	},
	
	// part listener
	$pageChanged : function(index) {
		this._setPage(index);
	},
	
	$click$showSource : function() {
		sourceWindow.show({avoid:document.forms.PartForm});		
	},
	
	$valuesChanged$sourceFormula : function(values) {
		var dom = hui.xml.parse(values.recipe);
		if (dom) {
			this.dom = dom;
			document.forms.PartForm.recipe.value = values.recipe;
			this.preview();
			var pages = this._getPages();
			if (pages.length>0) {
				if (this.pageIndex==null) {
					this.pageIndex == 0;
				}
				this._setPage(Math.min(this.pageIndex,pages.length-1));
			} else {
				this.pageIndex = null;
				pageFormula.reset();
			}
		}
	},
	preview : function(immediate) {
		op.part.utils.updatePreview({
			node : hui.get('part_poster_container'),
			form : document.forms.PartForm,
			type : 'poster',
			delay : immediate ? 0 : 500,
			runScripts : true,
			onComplete : this._connectToWidget.bind(this)
		});
	},
	$valuesChanged$pageFormula : function(values) {
		var node = this.dom.getElementsByTagName('page')[this.pageIndex];
		var text = hui.get.firstByTag(node,'text');
		if (!text) {
			text = this.dom.createElement('text');
			node.appendChild(text);
		}
		hui.dom.setText(text,values.text);

		var title = hui.get.firstByTag(node,'title');
		if (!title) {
			title = this.dom.createElement('title');
			node.appendChild(title);
		}
		hui.dom.setText(title,values.title);

		var image = hui.get.firstByTag(node,'image');
		if (!image) {
			image = this.dom.createElement('image');
			node.appendChild(image);
		}
		if (values.image) {
			image.setAttribute('id',values.image.id);
		} else {
			image.removeAttribute('id');
		}


		this._syncDom();
		this.preview();
	},
	_setPage : function(index) {
		var pages = this._getPages();
		if (index < 0 || index > pages.length-1) {
			hui.log('Illegal index: '+index)
			return;
		}
		this.pageIndex = index;
		var values = {};
		var node = pages[index];
		var text = hui.get.firstByTag(node,'text');
		if (text) {
			values.text = hui.dom.getText(text);
		} else {
			hui.log('No text node found');
		}

		var title = hui.get.firstByTag(node,'title');
		if (title) {
			values.title = hui.dom.getText(title);
		}
		var image = hui.get.firstByTag(node,'image');
		if (image && image.getAttribute('id')) {
			var id = parseInt(image.getAttribute('id'));
			if (id) {
				values.image = {id : id};
			}
		} else {
			values.image = null;
		}

		pageFormula.setValues(values);
	},
	_syncDom : function() {
		var xml = hui.xml.serialize(this.dom);
		document.forms.PartForm.recipe.value = xml;
		sourceFormula.setValues({recipe:xml})
	},
	_getRootNode : function() {
		return this.dom.getElementsByTagName('pages')[0];
	},
	
	// Page controls ...
	
	$click$addPage : function() {
		var root = this._getRootNode();
		if (root) {
			var pages = this._getPages();
			
			var page = hui.build('page',{},this.dom);
			hui.build('title',{parent:page,text:'Vestibulum Condimentum Mollis Sit Parturient'},this.dom);
			hui.build('text',{parent:page,text:'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur blandit tempus porttitor.'},this.dom);
			hui.dom.insertAfter(pages[this.pageIndex],page);
			
			this._syncDom();
			this.preview(true);
			this._setPage(this.pageIndex+1);
		}
	},
	$click$deletePage : function() {
		var pages = this._getPages();
		if (pages) {
			var len = pages.length;
			if (len > 1 && len-1 >= this.pageIndex) {
				var page = pages[this.pageIndex];
				hui.dom.remove(page);
				this._syncDom();
				this.preview(true);
				this._setPage(Math.max(0,this.pageIndex-1));
			}
		}		
	},
	$click$moveLeft : function() {
		var pages = this._getPages();
		if (pages==null || pages.length<2) {
			return;
		}
		var page = pages[this.pageIndex];
		if (this.pageIndex==0) {
			var parent = page.parentNode;
			hui.dom.remove(page);
			parent.appendChild(page);
			this._setPage(pages.length-1);
		} else {
			hui.dom.remove(page);
			var previous = pages[this.pageIndex-1];
			hui.dom.insertBefore(previous,page);
			this._setPage(this.pageIndex-1);
		}
		this._syncDom();
		this.preview(true);
	},
	$click$moveRight : function(){
		var pages = this._getPages();
		if (pages==null || pages.length<2) {
			return;
		}
		var page = pages[this.pageIndex];
		if (this.pageIndex >= pages.length-1) {
			hui.dom.remove(page);
			hui.dom.insertBefore(pages[0],page);
			this._setPage(0);
		} else {
			var next = pages[this.pageIndex+1];
			hui.dom.remove(page);
			hui.dom.insertAfter(next,page);
			this._setPage(this.pageIndex+1);
		}
		this._syncDom();
		this.preview(true);
	},
	_getPages: function(){
		var root = this._getRootNode();
		if (root) {
			var children = [];
			var x = root.childNodes;
			for (var i=0; i < x.length; i++) {
				if (x[i].nodeName && x[i].nodeName.toLowerCase()=='page') {
					children.push(x[i]);
				}
			};
			return children;
		}
		return null;
	}
};

hui.ui.listen(partPosterController);
