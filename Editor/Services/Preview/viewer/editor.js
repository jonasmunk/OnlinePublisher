op.Editor = {
	$interfaceIsReady : function() {
		var editor = ui.Editor.get();
		editor.setOptions({
			partClass:'part_section'
		});
		editor.addPartController('header','Overskrift',op.Editor.Header);
		editor.addPartController('text','Text',op.Editor.Text);
		//editor.listen(this);
		editor.ignite();
		editor.activate();
	},
	editProperties : function() {
		if (!this.propertiesWindow) {
			var win = this.propertiesWindow = ui.Window.create({width:300,title:'Egenskaber',icon:'common/info',padding:10,variant:'dark'});
			var form = ui.Formula.create();
			form.buildGroup({above:true},[
				{type:'Text',options:{label:'Titel:',key:'title'}},
				{type:'Text',options:{label:'Beskrivelse:',key:'title',multiline:true}}
			]);
			win.add(form);
		}
		this.propertiesWindow.show();
	}
}

ui.listen(op.Editor);

/**
 * @constructor
 */
op.Editor.Header = function(element,row,column,position) {
	this.element = $(element);
	this.row = row;
	this.column = column;
	this.position = position;
	this.id = ui.Editor.getPartId(this.element);
	this.header = this.element.firstDescendant();
	this.field = null;
}

op.Editor.Header.prototype = {
	activate : function() {
		this._load();
	},
	_load : function() {
		ui.request({url:'parts/load.php',parameters:{type:'header',id:this.id},onJSON:function(part) {
			this.part = part;
			this._edit();
		}.bind(this)});
	},
	_edit : function() {
		this.field = new Element('textarea').addClassName('in2igui_editor_header');
		this.field.value = this.part.text;
		this.header.style.visibility='hidden';
		this._updateFieldStyle();
		this.element.insertBefore(this.field,this.header);
		this.field.focus();
		this.field.select();
		this.field.observe('keydown',function(e) {
			if (e.keyCode==Event.KEY_RETURN) {
				this.save();
			}
		}.bind(this));
	},
	save : function() {
		var value = this.field.value;
		this.deactivate();
		if (value!=this.value) {
			this.value = value;
			this.header.innerHTML = value;
			In2iGui.Editor.get().partChanged(this);
			ui.request({url:'parts/update.php',parameters:{id:this.id,pageId:op.page.id,text:this.value,type:'header'},onText:function(html) {
				this.element.update(html);
				this.header = this.element.firstDescendant();
			}.bind(this)});
		}
	},
	cancel : function() {
		this.deactivate();
	},
	deactivate : function() {
		this.header.style.visibility='';
		this.element.removeChild(this.field);
		In2iGui.Editor.get().partDidDeacivate(this);
	},
	_updateFieldStyle : function() {
		this.field.setStyle({width:this.header.getWidth()+'px',height:this.header.getHeight()+'px'});
		n2i.copyStyle(this.header,this.field,['fontSize','lineHeight','marginTop','fontWeight','fontFamily','textAlign','color','fontStyle']);
	},
	getValue : function() {
		return this.value;
	}
}

/**
 * @constructor
 */
op.Editor.Text = function(element,row,column,position) {
	this.element = $(element);
	this.row = row;
	this.column = column;
	this.position = position;
	this.id = ui.Editor.getPartId(this.element);
	this.header = this.element.firstDescendant();
	this.field = null;
}

op.Editor.Text.prototype = {
	activate : function() {
		this._load();
	},
	_load : function() {
		ui.request({url:'parts/load.php',parameters:{type:'text',id:this.id},onJSON:function(part) {
			this.part = part;
			this._edit();
		}.bind(this)});
	},
	_edit : function() {
		this.field = new Element('textarea').addClassName('in2igui_editor_header');
		this.field.value = this.part.text;
		this.header.style.visibility='hidden';
		this._updateFieldStyle();
		this.element.insertBefore(this.field,this.header);
		this.field.focus();
		this.field.select();
		/*
		this.field.observe('keydown',function(e) {
			if (e.keyCode==Event.KEY_RETURN) {
				this.save();
			}
		}.bind(this));*/
	},
	save : function() {
		var value = this.field.value;
		this.deactivate();
		if (value!=this.value) {
			this.value = value;
			this.header.innerHTML = value;
			In2iGui.Editor.get().partChanged(this);
			ui.request({url:'parts/update.php',parameters:{id:this.id,pageId:op.page.id,text:this.value,type:'text'},onText:function(html) {
				this.element.update(html);
				this.header = this.element.firstDescendant();
			}.bind(this)});
		}
	},
	cancel : function() {
		this.deactivate();
	},
	deactivate : function() {
		this.header.style.visibility='';
		this.element.removeChild(this.field);
		In2iGui.Editor.get().partDidDeacivate(this);
	},
	_updateFieldStyle : function() {
		this.field.setStyle({width:this.header.getWidth()+'px',height:this.header.getHeight()+'px'});
		n2i.copyStyle(this.header,this.field,['fontSize','lineHeight','marginTop','fontWeight','fontFamily','textAlign','color','fontStyle']);
	},
	getValue : function() {
		return this.value;
	}
}