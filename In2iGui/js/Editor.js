/**
 * @constructor
 */
In2iGui.Editor = function() {
	this.name = 'In2iGuiEditor';
	this.options = {rowClass:'row',columnClass:'column',partClass:'part'};
	this.parts = [];
	this.rows = [];
	this.partControllers = [];
	this.activePart = null;
	this.active = false;
	this.dragProxy = null;
	In2iGui.extend(this);
}

In2iGui.Editor.get = function() {
	if (!In2iGui.Editor.instance) {
		In2iGui.Editor.instance = new In2iGui.Editor();
	}
	return In2iGui.Editor.instance;
}

In2iGui.Editor.prototype = {
	ignite : function() {
		this.reload();
	},
	addPartController : function(key,title,controller) {
		this.partControllers.push({key:key,'title':title,'controller':controller});
	},
	setOptions : function(options) {
		n2i.override(this.options,options);
	},
	getPartController : function(key) {
		var ctrl = null;
		this.partControllers.each(function(item) {
			if (item.key==key) ctrl=item;
		});
		return ctrl;
	},
	reload : function() {
		if (this.partControls) {
			this.partControls.hide();
		}
		var self = this;
		this.parts = [];
		var rows = $$('.'+this.options.partClass);
		rows.each(function(row,i) {
			var columns = row.select('.'+self.options.columnClass);
			self.reloadColumns(columns,i);
			columns.each(function(column,j) {
				var parts = column.select('.'+self.options.partClass);
				self.reloadParts(parts,i,j);
			});
		});
		var parts = $$('.'+this.options.partClass);
		this.parts.each(function(part) {
			var i = parts.indexOf(part.element);
			if (i!=-1) {
				delete(parts[i]);
			}
		});
		this.reloadParts(parts,-1,-1);
	},
	partExists : function(element) {
		
	},
	reloadColumns : function(columns,rowIndex) {
		var self = this;
		columns.each(function(column,columnIndex) {
			column.observe('mouseover',function() {
				self.hoverColumn(column);
			});
			column.observe('mouseout',function() {
				self.blurColumn();
			});
			column.observe('contextmenu',function(e) {
				self.contextColumn(column,rowIndex,columnIndex,e);
			});
		});
	},
	reloadParts : function(parts,row,column) {
		var self = this;
		var reg = new RegExp(this.options.partClass+"_([\\w]+)","i");
		parts.each(function(element,partIndex) {
			if (!element) return;
			var match = element.className.match(reg);
			if (match && match[1]) {
				var handler = self.getPartController(match[1]);
				if (handler) {
					var part = new handler.controller(element,row,column,partIndex);
					part.type=match[1];
					element.observe('click',function() {
						self.editPart(part);
					});
					element.observe('mouseover',function(e) {
						self.hoverPart(part);
					});
					element.observe('mouseout',function(e) {
						self.blurPart(e);
					});
					element.observe('mousedown',function(e) {
						self.startPartDrag(e);
					});
					self.parts.push(part);
				}
			}
		});
	},
	activate : function() {
		this.active = true;
	},
	deactivate : function() {
		this.active = false;
		if (this.activePart) {
			this.activePart.deactivate();
		}
		if (this.partControls) this.partControls.hide();
	},
	
	
	///////////////////////// Columns ////////////////////////
	
	hoverColumn : function(column) {
		this.hoveredColumn = column;
		if (!this.active || this.activePart) return;
		column.addClassName('in2igui_editor_column_hover');
	},
	
	blurColumn : function() {
		if (!this.active || !this.hoveredColumn) return;
		this.hoveredColumn.removeClassName('in2igui_editor_column_hover');
	},
	
	contextColumn : function(column,rowIndex,columnIndex,e) {
		if (!this.active || this.activePart) return;
		if (!this.columnMenu) {
			var menu = In2iGui.Menu.create({name:'In2iGuiEditorColumnMenu'});
			menu.addItem({title:'Rediger kolonne',value:'editColumn'});
			menu.addItem({title:'Slet kolonne',value:'removeColumn'});
			menu.addItem({title:'Tilføj kolonne',value:'addColumn'});
			menu.addDivider();
			this.partControllers.each(function(item) {
				menu.addItem({title:item.title,value:item.key});
			});
			this.columnMenu = menu;
			menu.listen(this);
		}
		this.hoveredRow=rowIndex;
		this.hoveredColumnIndex=columnIndex;
		this.columnMenu.showAtPointer(e);
	},
	$itemWasClicked$In2iGuiEditorColumnMenu : function(value) {
		if (value=='removeColumn') {
			this.fire('removeColumn',{'row':this.hoveredRow,'column':this.hoveredColumnIndex});
		} else if (value=='editColumn') {
			this.editColumn(this.hoveredRow,this.hoveredColumnIndex);
		} else if (value=='addColumn') {
			this.fire('addColumn',{'row':this.hoveredRow,'position':this.hoveredColumnIndex+1});
		} else {
			this.fire('addPart',{'row':this.hoveredRow,'column':this.hoveredColumnIndex,'position':0,type:value});
		}
	},
	
	///////////////////// Column editor //////////////////////
	
	editColumn : function(rowIndex,columnIndex) {
		this.closeColumn();
		var c = this.activeColumn = $$('.row')[rowIndex].select('.column')[columnIndex];
		c.addClassName('in2igui_editor_column_edit');
		this.showColumnWindow();
		this.columnEditorForm.setValues({width:c.getStyle('width'),paddingLeft:c.getStyle('padding-left')});
	},
	closeColumn : function() {
		if (this.activeColumn) {
			this.activeColumn.removeClassName('in2igui_editor_column_edit');
		}
	},
	showColumnWindow : function() {
		if (!this.columnEditor) {
			var w = this.columnEditor = In2iGui.Window.create({name:'columnEditor',title:'Rediger kolonne',width:200});
			var f = this.columnEditorForm = In2iGui.Formula.create();
			var g = f.createGroup();
			var width = In2iGui.Formula.Text.create({label:'Bredde',key:'width'});
			width.listen({$valueChanged:function(v) {this.changeColumnWidth(v)}.bind(this)})
			g.add(width);
			var marginLeft = In2iGui.Formula.Text.create({label:'Venstremargen',key:'left'});
			marginLeft.listen({$valueChanged:function(v) {this.changeColumnLeftMargin(v)}.bind(this)})
			g.add(marginLeft);
			var marginRight = In2iGui.Formula.Text.create({label:'Højremargen',key:'right'});
			marginRight.listen({$valueChanged:this.changeColumnRightMargin.bind(this)})
			g.add(marginRight);
			w.add(f);
			w.listen(this);
		}
		this.columnEditor.show();
	},
	$userClosedWindow$columnEditor : function() {
		this.closeColumn();
		var values = this.columnEditorForm.getValues();
		values.row=this.hoveredRow;
		values.column=this.hoveredColumnIndex;
		this.fire('updateColumn',values);
	},
	changeColumnWidth : function(width) {
		this.activeColumn.setStyle({'width':width});
	},
	changeColumnLeftMargin : function(margin) {
		this.activeColumn.setStyle({'paddingLeft':margin});
	},
	changeColumnRightMargin : function(margin) {
		this.activeColumn.setStyle({'paddingRight':margin});
	},
	///////////////////////// Parts //////////////////////////
	
	hoverPart : function(part,event) {
		if (!this.active || this.activePart) return;
		this.hoveredPart = part;
		part.element.addClassName('in2igui_editor_part_hover');
		var self = this;
		this.partControlTimer = window.setTimeout(function() {self.showPartControls()},200);
	},
	blurPart : function(e) {
		window.clearTimeout(this.partControlTimer);
		if (!this.active) return;
		if (this.partControls && !In2iGui.isWithin(e,this.partControls.element)) {
			this.hidePartControls();
			this.hoveredPart.element.removeClassName('in2igui_editor_part_hover');
		}
		if (!this.partControls && this.hoveredPart) {
			this.hoveredPart.element.removeClassName('in2igui_editor_part_hover');			
		}
	},
	showPartEditControls : function() {
		if (!this.partEditControls) {
			this.partEditControls = In2iGui.Overlay.create({name:'In2iGuiEditorPartEditActions'});
			this.partEditControls.addIcon('save','common/save');
			this.partEditControls.addIcon('cancel','common/close');
			this.partEditControls.listen(this);
		}
		this.partEditControls.showAtElement(this.activePart.element,{'horizontal':'right','vertical':'topOutside'});
	},
	showPartControls : function() {
		if (!this.partControls) {
			this.partControls = In2iGui.Overlay.create({name:'In2iGuiEditorPartActions'});
			this.partControls.addIcon('edit','common/edit');
			this.partControls.addIcon('new','common/new');
			this.partControls.addIcon('delete','common/delete');
			var self = this;
			this.partControls.getElement().observe('mouseout',function(e) {
				self.blurControls(e);
			});
			this.partControls.getElement().observe('mouseover',function(e) {
				self.hoverControls(e);
			});
			this.partControls.listen(this);
		}
		if (this.hoveredPart.column==-1) {
			this.partControls.hideIcons(['new','delete']);
		} else {
			this.partControls.showIcons(['new','delete']);
		}
		this.partControls.showAtElement(this.hoveredPart.element,{'horizontal':'right'});
	},
	hoverControls : function(e) {
		this.hoveredPart.element.addClassName('in2igui_editor_part_hover');
	},
	blurControls : function(e) {
		this.hoveredPart.element.removeClassName('in2igui_editor_part_hover');
		if (!In2iGui.isWithin(e,this.hoveredPart.element)) {
			this.hidePartControls();
		}
	},
	$iconWasClicked$In2iGuiEditorPartActions : function(key,event) {
		if (key=='delete') {
			this.deletePart(this.hoveredPart);
		} else if (key=='new') {
			this.newPart(event);
		} else if (key=='edit') {
			this.editPart(this.hoveredPart);
		}
	},
	$iconWasClicked$In2iGuiEditorPartEditActions : function(key,event) {
		if (key=='cancel') {
			this.cancelPart(this.activePart);
		} else if (key=='save') {
			this.savePart(this.activePart);
		}
	},
	hidePartControls : function() {
		if (this.partControls) {
			this.partControls.hide();
		}
	},
	hidePartEditControls : function() {
		if (this.partEditControls) {
			this.partEditControls.hide();
		}
	},
	editPart : function(part) {
		if (!this.active || this.activePart) return;
		if (this.activePart) this.activePart.deactivate();
		if (this.hoveredPart) {
			this.hoveredPart.element.removeClassName('in2igui_editor_part_hover');
		}
		this.activePart = part;
		this.showPartEditControls();
		part.element.addClassName('in2igui_editor_part_active');
		part.activate();
		window.clearTimeout(this.partControlTimer);
		this.hidePartControls();
		this.blurColumn();
		this.showPartEditor();
	},
	showPartEditor : function() {
		 // TODO: Disabled!
		/*if (!this.partEditor) {
			var w = this.partEditor = In2iGui.Window.create({padding:5,title:'Afstande',close:false,variant:'dark',width: 200});
			var f = this.partEditorForm = In2iGui.Formula.create();
			f.buildGroup({above:false},[
				{type:'Text',options:{label:'Top',key:'top'}},
				{type:'Text',options:{label:'Bottom',key:'bottom'}},
				{type:'Text',options:{label:'Left',key:'left'}},
				{type:'Text',options:{label:'Right',key:'right'}}
			]);
			w.add(f);
			f.listen({valuesChanged:this.updatePartProperties.bind(this)});
		}
		var e = this.activePart.element;
		this.partEditorForm.setValues({
			top: e.getStyle('marginTop'),
			bottom: e.getStyle('marginBottom'),
			left: e.getStyle('marginLeft'),
			right: e.getStyle('marginRight')
		});
		this.partEditor.show();*/
	},
	updatePartProperties : function(values) {
		this.activePart.element.setStyle({
			marginTop:values.top,
			marginBottom:values.bottom,
			marginLeft:values.left,
			marginRight:values.right
		});
		this.activePart.properties = values;
		n2i.log(values);
	},
	hidePartEditor : function() {
		if (this.partEditor) this.partEditor.hide();
	},
	cancelPart : function(part) {
		part.cancel();
		this.hidePartEditor();
	},
	savePart : function(part) {
		part.save();
		this.hidePartEditor();
	},
	getEditorForElement : function(element) {
		for (var i=0; i < this.parts.length; i++) {
			if (this.parts[i].element==element) {
				return this.parts[i];
			}
		};
		return null;
	},
	partDidDeacivate : function(part) {
		part.element.removeClassName('in2igui_editor_part_active');
		this.activePart = null;
		this.hidePartEditControls();
	},
	partChanged : function(part) {
		In2iGui.callDelegates(part,'partChanged');
	},
	deletePart : function(part) {
		In2iGui.callDelegates(part,'deletePart');
		this.partControls.hide();
	},
	newPart : function(e) {
		if (!this.newPartMenu) {
			var menu = In2iGui.Menu.create({name:'In2iGuiEditorNewPartMenu'});
			this.partControllers.each(function(item) {
				menu.addItem({title:item.title,value:item.key});
			});
			menu.listen(this);
			this.newPartMenu=menu;
		}
		this.newPartMenu.showAtPointer(e);
	},
	itemWasClicked$In2iGuiEditorNewPartMenu : function(value) {
		var info = {row:this.hoveredPart.row,column:this.hoveredPart.column,position:this.hoveredPart.position+1,type:value};
		In2iGui.callDelegates(this,'addPart',info);
	},
	/**** Dragging ****/
	startPartDrag : function(e) {
		return true;
		if (!this.active || this.activePart) return true;
		if (!this.dragProxy) {
			this.dragProxy = new Element('div').addClassName('in2igui_editor_dragproxy part part_header');
			document.body.appendChild(this.dragProxy);
		}
		var element = this.hoveredPart.element;
		this.dragProxy.setStyle({'width':element.getWidth()+'px'});
		this.dragProxy.innerHTML = element.innerHTML;
		In2iGui.Editor.startDrag(e,this.dragProxy);
		return;
		Element.observe(document.body,'mouseup',function() {
			self.endPartDrag();
		})
	},
	dragPart : function() {
		
	},
	endPartDrag : function() {
		In2iGui.get();
	}
}



In2iGui.Editor.startDrag = function(e,element) {
	In2iGui.Editor.dragElement = element;
	Element.observe(document.body,'mousemove',In2iGui.Editor.dragListener);
	Element.observe(document.body,'mouseup',In2iGui.Editor.dragEndListener);
	In2iGui.Editor.startDragPos = {top:e.pointerY(),left:e.pointerX()};
	e.stop();
	return false;
}

In2iGui.Editor.dragListener = function(e) {
	var element = In2iGui.Editor.dragElement;
	element.style.left = e.pointerX()+'px';
	element.style.top = e.pointerY()+'px';
	element.style.display='block';
	return false;
}

In2iGui.Editor.dragEndListener = function(event) {
	Event.stopObserving(document.body,'mousemove',In2iGui.Editor.dragListener);
	Event.stopObserving(document.body,'mouseup',In2iGui.Editor.dragEndListener);
	In2iGui.Editor.dragElement.style.display='none';
	In2iGui.Editor.dragElement=null;
}

In2iGui.Editor.getPartId = function(element) {
	var m = element.id.match(/part\-([\d]+)/i);
	if (m && m.length>0) return m[1];
}

////////////////////////////////// Header editor ////////////////////////////////

/**
 * @constructor
 */
In2iGui.Editor.Header = function(element,row,column,position) {
	this.element = $(element);
	this.row = row;
	this.column = column;
	this.position = position;
	this.id = In2iGui.Editor.getPartId(this.element);
	this.header = this.element.firstDescendant();
	this.field = null;
}

In2iGui.Editor.Header.prototype = {
	activate : function() {
		this.value = this.header.innerHTML;
		this.field = new Element('textarea').addClassName('in2igui_editor_header');
		this.field.value = this.value;
		this.header.style.visibility='hidden';
		this.updateFieldStyle();
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
		this.header.innerHTML = value;
		this.deactivate();
		if (value!=this.value) {
			this.value = value;
			In2iGui.Editor.get().partChanged(this);
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
	updateFieldStyle : function() {
		this.field.setStyle({width:this.header.getWidth()+'px',height:this.header.getHeight()+'px'});
		n2i.copyStyle(this.header,this.field,['fontSize','lineHeight','marginTop','fontWeight','fontFamily','textAlign','color','fontStyle']);
	},
	getValue : function() {
		return this.value;
	}
}

////////////////////////////////// Html editor ////////////////////////////////

/**
 * @constructor
 */
In2iGui.Editor.Html = function(element,row,column,position) {
	this.element = $(element);
	this.row = row;
	this.column = column;
	this.position = position;
	this.id = In2iGui.Editor.getPartId(this.element);
	this.field = null;
}

In2iGui.Editor.Html.prototype = {
	activate : function() {
		this.value = this.element.innerHTML;
		//if (Prototype.Browser.IE) return;
		var height = this.element.getHeight();
		this.element.update('');
		var style = this.buildStyle();
		this.editor = In2iGui.RichText.create({autoHideToolbar:false,style:style});
		this.editor.setHeight(height);
		this.element.appendChild(this.editor.getElement());
		this.editor.listen(this);
		this.editor.ignite();
		this.editor.setValue(this.value);
		this.editor.focus();
	},
	buildStyle : function() {
		return {
			'textAlign':n2i.getStyle(this.element,'text-align')
			,'fontFamily':n2i.getStyle(this.element,'font-family')
			,'fontSize':n2i.getStyle(this.element,'font-size')
			,'fontWeight':n2i.getStyle(this.element,'font-weight')
			,'color':n2i.getStyle(this.element,'color')
		}
	},
	cancel : function() {
		this.deactivate();
		this.element.innerHTML = this.value;
	},
	save : function() {
		this.deactivate();
		var value = this.editor.value;
		if (value!=this.value) {
			this.value = value;
			In2iGui.Editor.get().partChanged(this);
		}
		this.element.innerHTML = this.value;
	},
	deactivate : function() {
		if (this.editor) {
			this.editor.deactivate();
			this.element.innerHTML = this.value;
		}
		In2iGui.Editor.get().partDidDeacivate(this);
	},
	richTextDidChange : function() {
		//this.deactivate();
	},
	getValue : function() {
		return this.value;
	}
}

/* EOF */