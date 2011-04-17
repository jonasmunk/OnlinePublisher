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
		n2i.each(this.partControllers,function(item) {
			if (item.key==key) {ctrl=item};
		});
		return ctrl;
	},
	reload : function() {
		if (this.partControls) {
			this.partControls.hide();
		}
		var self = this;
		this.parts = [];
		var rows = n2i.byClass(document.body,this.options.partClass);
		n2i.each(rows,function(row,i) {
			var columns = n2i.byClass(row,self.options.columnClass);
			self.reloadColumns(columns,i);
			n2i.each(columns,function(column,j) {
				var parts = column.select('.'+self.options.partClass);
				self.reloadParts(parts,i,j);
			});
		});
		var parts = n2i.byClass(document.body,this.options.partClass);
		n2i.each(this.parts,function(part) {
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
		n2i.each(columns,function(column,columnIndex) {
			n2i.lsiten(column,'mouseover',function() {
				self.hoverColumn(column);
			});
			n2i.listen(column,'mouseout',function() {
				self.blurColumn();
			});
			n2i.listen(column,'contextmenu',function(e) {
				self.contextColumn(column,rowIndex,columnIndex,e);
			});
		});
	},
	reloadParts : function(parts,row,column) {
		var self = this;
		var reg = new RegExp(this.options.partClass+"_([\\w]+)","i");
		n2i.each(parts,function(element,partIndex) {
			if (!element) return;
			var match = element.className.match(reg);
			if (match && match[1]) {
				var handler = self.getPartController(match[1]);
				if (handler) {
					var part = new handler.controller(element,row,column,partIndex);
					part.type=match[1];
					n2i.listen(element,'click',function() {
						self.editPart(part);
					});
					n2i.listen(element,'mouseover',function(e) {
						self.hoverPart(part);
					});
					n2i.listen(element,'mouseout',function(e) {
						self.blurPart(e);
					});
					n2i.listen(element,'mousedown',function(e) {
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
		n2i.addClass(column,'in2igui_editor_column_hover');
	},
	
	blurColumn : function() {
		if (!this.active || !this.hoveredColumn) return;
		n2i.removeClass(this.hoveredColumn,'in2igui_editor_column_hover');
	},
	
	contextColumn : function(column,rowIndex,columnIndex,e) {
		if (!this.active || this.activePart) return;
		if (!this.columnMenu) {
			var menu = In2iGui.Menu.create({name:'In2iGuiEditorColumnMenu'});
			menu.addItem({title:'Rediger kolonne',value:'editColumn'});
			menu.addItem({title:'Slet kolonne',value:'removeColumn'});
			menu.addItem({title:'Tilføj kolonne',value:'addColumn'});
			menu.addDivider();
			for (var i=0; i < this.partControllers.length; i++) {
				var ctrl = this.partControllers[i];
				menu.addItem({title:ctrl.title,value:ctrl.key});
			};
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
		var row = n2i.byClass(document.body,'row')[rowIndex];
		var c = this.activeColumn = n2i.byClass(row,'column')[columnIndex];
		n2i.addClass(c,'in2igui_editor_column_edit');
		this.showColumnWindow();
		this.columnEditorForm.setValues({width:c.getStyle('width'),paddingLeft:c.getStyle('padding-left')});
	},
	closeColumn : function() {
		if (this.activeColumn) {
			n2i.removeClass(this.activeColumn,'in2igui_editor_column_edit');
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
		this.activeColumn.style.width=width;
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
		n2i.addClass(part.element,'in2igui_editor_part_hover');
		var self = this;
		this.partControlTimer = window.setTimeout(function() {self.showPartControls()},200);
	},
	blurPart : function(e) {
		window.clearTimeout(this.partControlTimer);
		if (!this.active) return;
		if (this.partControls && !In2iGui.isWithin(e,this.partControls.element)) {
			this.hidePartControls();
			n2i.removeClass(this.hoveredPart.element,'in2igui_editor_part_hover');
		}
		if (!this.partControls && this.hoveredPart) {
			n2i.removeClass(this.hoveredPart.element,'in2igui_editor_part_hover');			
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
			n2i.listen(this.partControls.getElement(),'mouseout',function(e) {
				self.blurControls(e);
			});
			n2i.listen(this.partControls.getElement(),'mouseover',function(e) {
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
		n2i.addClass(this.hoveredPart.element,'in2igui_editor_part_hover');
	},
	blurControls : function(e) {
		n2i.removeClass(this.hoveredPart.element,'in2igui_editor_part_hover');
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
			n2i.removeClass(this.hoveredPart.element,'in2igui_editor_part_hover');
		}
		this.activePart = part;
		this.showPartEditControls();
		n2i.addClass(part.element,'in2igui_editor_part_active');
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
		n2i.setStyle(this.activePart.element,{
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
		n2i.removeClass(part.element,'in2igui_editor_part_active');
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
			n2i.each(this.partControllers,function(item) {
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
			this.dragProxy = n2i.build('div',{'class':'in2igui_editor_dragproxy part part_header',parent:document.body});
		}
		var element = this.hoveredPart.element;
		this.dragProxy.style.width = element.clientWidth+'px';
		this.dragProxy.innerHTML = element.innerHTML;
		In2iGui.Editor.startDrag(e,this.dragProxy);
		return;
		n2i.listen(document.body,'mouseup',function() {
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
	n2i.listen(document.body,'mousemove',In2iGui.Editor.dragListener);
	n2i.listen(document.body,'mouseup',In2iGui.Editor.dragEndListener);
	In2iGui.Editor.startDragPos = {top:e.pointerY(),left:e.pointerX()};
	e.stop();
	return false;
}

In2iGui.Editor.dragListener = function(e) {
	e = n2i.event(e);
	var element = In2iGui.Editor.dragElement;
	element.style.left = e.getLeft()+'px';
	element.style.top = e.getTop()+'px';
	element.style.display='block';
	return false;
}

In2iGui.Editor.dragEndListener = function(event) {
	n2i.unListen(document.body,'mousemove',In2iGui.Editor.dragListener);
	n2i.unListen(document.body,'mouseup',In2iGui.Editor.dragEndListener);
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
	this.element = n2i.get(element);
	this.row = row;
	this.column = column;
	this.position = position;
	this.id = In2iGui.Editor.getPartId(this.element);
	this.header = n2i.firstByTag(this.element,'*');
	this.field = null;
}

In2iGui.Editor.Header.prototype = {
	activate : function() {
		this.value = this.header.innerHTML;
		this.field = n2i.build('textarea',{className:'in2igui_editor_header'});
		this.field.value = this.value;
		this.header.style.visibility='hidden';
		this.updateFieldStyle();
		this.element.insertBefore(this.field,this.header);
		this.field.focus();
		this.field.select();
		n2i.listen(this.field,'keydown',function(e) {
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
		n2i.setStyle(this.field,{width:this.header.clientWidth+'px',height:this.header.clientHeight+'px'});
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
	this.element = n2i.get(element);
	this.row = row;
	this.column = column;
	this.position = position;
	this.id = In2iGui.Editor.getPartId(this.element);
	this.field = null;
}

In2iGui.Editor.Html.prototype = {
	activate : function() {
		this.value = this.element.innerHTML;
		this.element.innerHTML='';
		var style = this.buildStyle();
		this.editor = In2iGui.MarkupEditor.create({autoHideToolbar:false,style:style});
		this.element.appendChild(this.editor.getElement());
		this.editor.listen(this);
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
		var value = this.editor.getValue();
		if (value!=this.value) {
			this.value = value;
			In2iGui.Editor.get().partChanged(this);
		}
		this.element.innerHTML = this.value;
	},
	deactivate : function() {
		if (this.editor) {
			this.editor.destroy();
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