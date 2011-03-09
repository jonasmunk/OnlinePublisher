/**
 * Simple container
 * @param {Object} The options
 * @constructor
 */
In2iGui.Links = function(options) {
	this.options = options;
	this.element = n2i.get(options.element);
	this.name = options.name;
	In2iGui.extend(this);
	this.items = [];
	this.addBehavior();
	this.selectedIndex = null;
	this.inputs = {};
}

In2iGui.Links.prototype = {
	addBehavior : function() {
		n2i.listen(this.element,'click',this.onClick.bind(this));
		n2i.listen(this.element,'dblclick',this.onDblClick.bind(this));
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
		e = new n2i.Event(e);
		n2i.selection.clear();
		e.stop(e);
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
		e = new n2i.Event(e);
		e.stop();
		var element = e.getElement();
		if (n2i.hasClass(element,'in2igui_links_remove')) {
			var row = e.findByClass('in2igui_links_row');
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
		var row = event.findByClass('in2igui_links_row');
		if (row) {
			var idx = row.in2igui_index;
			if (this.selectedIndex!==null) {
				var x = n2i.byClass(this.element,'in2igui_links_row')[this.selectedIndex];
				n2i.removeClass(x,'in2igui_links_row_selected')
			}
			this.selectedIndex = idx;
			n2i.addClass(row,'in2igui_links_row_selected');
			return this.items[idx];
		}
	},
	build : function() {
		var list = this.list || n2i.firstByClass(this.element,'in2igui_links_list'),
			i,item,row,infoNode,text,remove;
		list.innerHTML='';
		for (i=0; i < this.items.length; i++) {
			item = this.items[i];
			row = n2i.build('div',{'class':'in2igui_links_row'});
			row.in2igui_index = i;
			
			row.appendChild(In2iGui.createIcon(item.icon,1));
			text = n2i.build('div',{'class':'in2igui_links_text',text:item.text});
			row.appendChild(text);

			infoNode = n2i.build('div',{'class':'in2igui_links_info',text:n2i.wrap(item.info)});
			row.appendChild(infoNode);
			remove = In2iGui.createIcon('monochrome/round_x',1);
			n2i.addClass(remove,'in2igui_links_remove');
			row.appendChild(remove);

			list.appendChild(row);
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
			this.inputs['url']=url;
			
			var email = In2iGui.Formula.Text.create({label:'E-mail',key:'email'});
			g.add(email);
			this.inputs['email']=email;
			
			page = In2iGui.Formula.DropDown.create({label:'Side',key:'page',source:this.options.pageSource});
			g.add(page);
			this.inputs['page']=page;
			
			file = In2iGui.Formula.DropDown.create({label:'Fil',key:'file',source:this.options.fileSource});
			g.add(file);
			this.inputs['file']=file;
			
			var self = this;
			n2i.each(this.inputs,function(key,value) {
				value.listen({$valueChanged:function(){self.changeType(key)}});
			});
			
			g.createButtons().add(In2iGui.Button.create({text:'Gem',submit:true,highlighted:true}));
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
			link.info=this.inputs['page'].getItem().title;
			link.icon='common/page';
		} else if (n2i.isDefined(values.file)) {
			link.kind='file';
			link.value=values.file;
			link.info=this.inputs['file'].getItem().title;
			link.icon='monochrome/file';
		}
		return link;
	},
	changeType : function(type) {
		n2i.each(this.inputs,function(key,value) {
			if (key!=type) {
				value.setValue();
			}
		});
	}
}

/* EOF */