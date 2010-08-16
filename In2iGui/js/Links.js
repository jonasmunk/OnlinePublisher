/**
 * Simple container
 * @param {Object} The options
 * @constructor
 */
In2iGui.Links = function(options) {
	this.options = options;
	this.element = $(options.element);
	this.name = options.name;
	In2iGui.extend(this);
	this.items = [];
	this.addBehavior();
	this.selectedIndex = null;
	this.inputs = new Hash();
}

In2iGui.Links.prototype = {
	addBehavior : function() {
		this.element.observe('click',this.onClick.bind(this));
		this.element.observe('dblclick',this.onDblClick.bind(this));
	},
	reset : function() {
		this.setValue([]);
	},
	setValue : function(items) {
		this.items = items;
		this.selectedIndex = null;
		this.build();
	},
	getValue : function() {
		return this.items;
	},
	onDblClick : function(e) {
		n2i.selection.clear();
		e.stop();
		e.preventDefault();
		var link = this.selectAndGetRow(e);
		var values = {text:link.text};
		values[link.kind]=link.value;
		this.editedLink = link;
		var win = this.getEditWindow();
		this.editForm.reset();
		this.editForm.setValues(values);
		win.show();
	},
	onClick : function(e) {
		e.stop();
		e.preventDefault();
		var element = e.element();
		if (element.hasClassName('in2igui_links_remove')) {
			var row = e.findElement('div.in2igui_links_row');
			In2iGui.confirmOverlay({element:element,text:'Vil du fjerne linket?',okText:'Ja, fjern',cancelText:'Annuller',onOk:function() {
				this.items.splice(row.in2igui_index,1);
				if (this.selectedIndex===row.in2igui_index) {
					this.selectedIndex=null;
				}
				this.build();				
			}.bind(this)});
		} else {
			this.selectAndGetRow(e);
		}
	},
	selectAndGetRow : function(event) {
		var row = event.findElement('div.in2igui_links_row');
		if (row) {
			var idx = row.in2igui_index;
			if (this.selectedIndex!==null) {
				this.element.select('.in2igui_links_row')[this.selectedIndex].removeClassName('in2igui_links_row_selected');
			}
			this.selectedIndex = idx;
			row.addClassName('in2igui_links_row_selected');
			return this.items[idx];
		}
	},
	build : function() {
		var list = this.list || this.element.select('.in2igui_links_list')[0];
		list.update();
		for (var i=0; i < this.items.length; i++) {
			var item = this.items[i];
			var row = new Element('div',{'class':'in2igui_links_row'});
			row.in2igui_index = i;
			
			row.appendChild(In2iGui.createIcon(item.icon,1));
			var text = new Element('div',{'class':'in2igui_links_text'});
			n2i.dom.addText(text,item.text);
			row.insert(text);

			var infoNode = new Element('div',{'class':'in2igui_links_info'});
			n2i.dom.addText(infoNode,n2i.wrap(item.info));
			row.insert(infoNode);
			var remove = In2iGui.createIcon('monochrome/round_x',1);
			remove.addClassName('in2igui_links_remove');
			row.insert(remove);

			list.insert(row);
		};
	},
	addLink : function() {
		this.editedLink = null;
		this.getEditWindow().show();
		this.editForm.reset();
		this.editForm.focus();
	},
	getEditWindow : function() {
		if (!this.editWindow) {
			var win = this.editWindow = In2iGui.Window.create({title:'Link',width:300,padding:5});
			var form = this.editForm = In2iGui.Formula.create();
			var g = form.buildGroup({above:false},[
				{type:'Text',options:{label:'Tekst',key:'text'}}
			]);
			
			var url = In2iGui.Formula.Text.create({label:'URL',key:'url'});
			g.add(url);
			this.inputs.set('url',url);
			
			var email = In2iGui.Formula.Text.create({label:'E-mail',key:'email'});
			g.add(email);
			this.inputs.set('email',email);
			
			page = In2iGui.Formula.DropDown.create({label:'Side',key:'page',source:this.options.pageSource});
			g.add(page);
			this.inputs.set('page',page);
			
			file = In2iGui.Formula.DropDown.create({label:'Fil',key:'file',source:this.options.fileSource});
			g.add(file);
			this.inputs.set('file',file);
			
			var self = this;
			this.inputs.each(function(pair) {
				pair.value.listen({$valueChanged:function(){self.changeType(pair.key)}});
			});
			
			var b = g.createButtons().add(In2iGui.Button.create({text:'Gem',submit:true,highlighted:true}));
			this.editForm.listen({$submit:this.saveLink.bind(this)});
			win.add(form);
			if (this.options.pageSource) {
				this.options.pageSource.refresh();
			}
			if (this.options.fileSource) {
				this.options.fileSource.refresh();
			}
		}
		return this.editWindow;
	},
	saveLink : function() {
		var v = this.editForm.getValues();
		var link = this.valuesToLink(v);
		var edited = this.editedLink;
		if (edited) {
			n2i.override(edited,link);
		} else {
			this.items.push(link);
		}
		this.build();
		this.editForm.reset();
		this.editWindow.hide();
		this.editedLink = null;
	},
	valuesToLink : function(values) {
		var link = {};
		link.text = values.text;
		if (values.email!='') {
			link.kind='email';
			link.value=values.email;
			link.info=values.email;
			link.icon='monochrome/email';
		} else if (values.url!='') {
			link.kind='url';
			link.value=values.url;
			link.info=values.url;
			link.icon='monochrome/globe';
		} else if (n2i.isDefined(values.page)) {
			link.kind='page';
			link.value=values.page;
			link.info=this.inputs.get('page').getItem().title;
			link.icon='common/page';
		} else if (n2i.isDefined(values.file)) {
			link.kind='file';
			link.value=values.file;
			link.info=this.inputs.get('file').getItem().title;
			link.icon='monochrome/file';
		}
		return link;
	},
	changeType : function(type) {
		this.inputs.each(function(pair) {
			if (pair.key!=type) {
				pair.value.setValue();
			}
		});
	}
}

/* EOF */