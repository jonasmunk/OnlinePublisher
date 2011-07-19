/**
 * @constructor
 */
hui.ui.Editor = function() {
	this.name = 'huiEditor';
	this.options = {rowClass:'row',columnClass:'column',partClass:'part'};
	this.parts = [];
	this.rows = [];
	this.partControllers = [];
	this.activePart = null;
	this.active = false;
	this.dragProxy = null;
	hui.ui.extend(this);
}

hui.ui.Editor.get = function() {
	if (!hui.ui.Editor.instance) {
		hui.ui.Editor.instance = new hui.ui.Editor();
	}
	return hui.ui.Editor.instance;
}

hui.ui.Editor.prototype = {
	ignite : function() {
		this.reload();
	},
	addPartController : function(key,title,controller) {
		this.partControllers.push({key:key,'title':title,'controller':controller});
	},
	setOptions : function(options) {
		hui.override(this.options,options);
	},
	getPartController : function(key) {
		var ctrl = null;
		hui.each(this.partControllers,function(item) {
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
		var rows = hui.byClass(document.body,this.options.partClass);
		hui.each(rows,function(row,i) {
			var columns = hui.byClass(row,self.options.columnClass);
			self.reloadColumns(columns,i);
			hui.each(columns,function(column,j) {
				var parts = column.select('.'+self.options.partClass);
				self.reloadParts(parts,i,j);
			});
		});
		var parts = hui.byClass(document.body,this.options.partClass);
		hui.each(this.parts,function(part) {
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
		hui.each(columns,function(column,columnIndex) {
			hui.lsiten(column,'mouseover',function() {
				self.hoverColumn(column);
			});
			hui.listen(column,'mouseout',function() {
				self.blurColumn();
			});
			hui.listen(column,'contextmenu',function(e) {
				self.contextColumn(column,rowIndex,columnIndex,e);
			});
		});
	},
	reloadParts : function(parts,row,column) {
		var self = this;
		var reg = new RegExp(this.options.partClass+"_([\\w]+)","i");
		hui.each(parts,function(element,partIndex) {
			if (!element) return;
			var match = element.className.match(reg);
			if (match && match[1]) {
				var handler = self.getPartController(match[1]);
				if (handler) {
					var part = new handler.controller(element,row,column,partIndex);
					part.type=match[1];
					hui.listen(element,'click',function(e) {
						e = hui.event(e);
						if (!e.findByTag('a')) {
							self.editPart(part);
						}
					});
					hui.listen(element,'mouseover',function(e) {
						self.hoverPart(part);
					});
					hui.listen(element,'mouseout',function(e) {
						self.blurPart(e);
					});
					hui.listen(element,'mousedown',function(e) {
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
		hui.addClass(column,'hui_editor_column_hover');
	},
	
	blurColumn : function() {
		if (!this.active || !this.hoveredColumn) return;
		hui.removeClass(this.hoveredColumn,'hui_editor_column_hover');
	},
	
	contextColumn : function(column,rowIndex,columnIndex,e) {
		if (!this.active || this.activePart) return;
		if (!this.columnMenu) {
			var menu = hui.ui.Menu.create({name:'huiEditorColumnMenu'});
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
	$itemWasClicked$huiEditorColumnMenu : function(value) {
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
		var row = hui.byClass(document.body,'row')[rowIndex];
		var c = this.activeColumn = hui.byClass(row,'column')[columnIndex];
		hui.addClass(c,'hui_editor_column_edit');
		this.showColumnWindow();
		this.columnEditorForm.setValues({width:c.getStyle('width'),paddingLeft:c.getStyle('padding-left')});
	},
	closeColumn : function() {
		if (this.activeColumn) {
			hui.removeClass(this.activeColumn,'hui_editor_column_edit');
		}
	},
	showColumnWindow : function() {
		if (!this.columnEditor) {
			var w = this.columnEditor = hui.ui.Window.create({name:'columnEditor',title:'Rediger kolonne',width:200});
			var f = this.columnEditorForm = hui.ui.Formula.create();
			var g = f.createGroup();
			var width = hui.ui.TextField.create({label:'Bredde',key:'width'});
			width.listen({$valueChanged:function(v) {this.changeColumnWidth(v)}.bind(this)})
			g.add(width);
			var marginLeft = hui.ui.TextField.create({label:'Venstremargen',key:'left'});
			marginLeft.listen({$valueChanged:function(v) {this.changeColumnLeftMargin(v)}.bind(this)})
			g.add(marginLeft);
			var marginRight = hui.ui.TextField.create({label:'Højremargen',key:'right'});
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
		hui.addClass(part.element,'hui_editor_part_hover');
		var self = this;
		this.partControlTimer = window.setTimeout(function() {self.showPartControls()},200);
	},
	blurPart : function(e) {
		window.clearTimeout(this.partControlTimer);
		if (!this.active) return;
		if (this.partControls && !hui.ui.isWithin(e,this.partControls.element)) {
			this.hidePartControls();
			hui.removeClass(this.hoveredPart.element,'hui_editor_part_hover');
		}
		if (!this.partControls && this.hoveredPart) {
			hui.removeClass(this.hoveredPart.element,'hui_editor_part_hover');			
		}
	},
	showPartEditControls : function() {
		if (!this.partEditControls) {
			this.partEditControls = hui.ui.Overlay.create({name:'huiEditorPartEditActions'});
			this.partEditControls.addIcon('save','common/save');
			this.partEditControls.addIcon('cancel','common/close');
			this.partEditControls.listen(this);
		}
		this.partEditControls.showAtElement(this.activePart.element,{'horizontal':'right','vertical':'topOutside'});
	},
	showPartControls : function() {
		if (!this.partControls) {
			this.partControls = hui.ui.Overlay.create({name:'huiEditorPartActions'});
			this.partControls.addIcon('edit','common/edit');
			this.partControls.addIcon('new','common/new');
			this.partControls.addIcon('delete','common/delete');
			var self = this;
			hui.listen(this.partControls.getElement(),'mouseout',function(e) {
				self.blurControls(e);
			});
			hui.listen(this.partControls.getElement(),'mouseover',function(e) {
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
		hui.addClass(this.hoveredPart.element,'hui_editor_part_hover');
	},
	blurControls : function(e) {
		hui.removeClass(this.hoveredPart.element,'hui_editor_part_hover');
		if (!hui.ui.isWithin(e,this.hoveredPart.element)) {
			this.hidePartControls();
		}
	},
	$iconWasClicked$huiEditorPartActions : function(key,event) {
		if (key=='delete') {
			this.deletePart(this.hoveredPart);
		} else if (key=='new') {
			this.newPart(event);
		} else if (key=='edit') {
			this.editPart(this.hoveredPart);
		}
	},
	$iconWasClicked$huiEditorPartEditActions : function(key,event) {
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
			hui.removeClass(this.hoveredPart.element,'hui_editor_part_hover');
		}
		this.activePart = part;
		this.showPartEditControls();
		hui.addClass(part.element,'hui_editor_part_active');
		part.activate(function() {
			//hui.ui.showMessage({text:'Loaded',duration:2000});
		});
		window.clearTimeout(this.partControlTimer);
		this.hidePartControls();
		this.blurColumn();
		this.showPartEditor();
	},
	showPartEditor : function() {
		 // TODO: Disabled!
		/*if (!this.partEditor) {
			var w = this.partEditor = hui.ui.Window.create({padding:5,title:'Afstande',close:false,variant:'dark',width: 200});
			var f = this.partEditorForm = hui.ui.Formula.create();
			f.buildGroup({above:false},[
				{type:'TextField',options:{label:'Top',key:'top'}},
				{type:'TextField',options:{label:'Bottom',key:'bottom'}},
				{type:'TextField',options:{label:'Left',key:'left'}},
				{type:'TextField',options:{label:'Right',key:'right'}}
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
		hui.setStyle(this.activePart.element,{
			marginTop:values.top,
			marginBottom:values.bottom,
			marginLeft:values.left,
			marginRight:values.right
		});
		this.activePart.properties = values;
		hui.log(values);
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
		hui.removeClass(part.element,'hui_editor_part_active');
		this.activePart = null;
		this.hidePartEditControls();
	},
	partChanged : function(part) {
		hui.ui.callDelegates(part,'partChanged');
	},
	deletePart : function(part) {
		hui.ui.callDelegates(part,'deletePart');
		this.partControls.hide();
	},
	newPart : function(e) {
		if (!this.newPartMenu) {
			var menu = hui.ui.Menu.create({name:'huiEditorNewPartMenu'});
			hui.each(this.partControllers,function(item) {
				menu.addItem({title:item.title,value:item.key});
			});
			menu.listen(this);
			this.newPartMenu=menu;
		}
		this.newPartMenu.showAtPointer(e);
	},
	itemWasClicked$huiEditorNewPartMenu : function(value) {
		var info = {row:this.hoveredPart.row,column:this.hoveredPart.column,position:this.hoveredPart.position+1,type:value};
		hui.ui.callDelegates(this,'addPart',info);
	},
	/**** Dragging ****/
	startPartDrag : function(e) {
		return true;
		if (!this.active || this.activePart) return true;
		if (!this.dragProxy) {
			this.dragProxy = hui.build('div',{'class':'hui_editor_dragproxy part part_header',parent:document.body});
		}
		var element = this.hoveredPart.element;
		this.dragProxy.style.width = element.clientWidth+'px';
		this.dragProxy.innerHTML = element.innerHTML;
		hui.ui.Editor.startDrag(e,this.dragProxy);
		return;
		hui.listen(document.body,'mouseup',function() {
			self.endPartDrag();
		})
	},
	dragPart : function() {
		
	},
	endPartDrag : function() {
	}
}



hui.ui.Editor.startDrag = function(e,element) {
	hui.ui.Editor.dragElement = element;
	hui.listen(document.body,'mousemove',hui.ui.Editor.dragListener);
	hui.listen(document.body,'mouseup',hui.ui.Editor.dragEndListener);
	hui.ui.Editor.startDragPos = {top:e.pointerY(),left:e.pointerX()};
	e.stop();
	return false;
}

hui.ui.Editor.dragListener = function(e) {
	e = hui.event(e);
	var element = hui.ui.Editor.dragElement;
	element.style.left = e.getLeft()+'px';
	element.style.top = e.getTop()+'px';
	element.style.display='block';
	return false;
}

hui.ui.Editor.dragEndListener = function(event) {
	hui.unListen(document.body,'mousemove',hui.ui.Editor.dragListener);
	hui.unListen(document.body,'mouseup',hui.ui.Editor.dragEndListener);
	hui.ui.Editor.dragElement.style.display='none';
	hui.ui.Editor.dragElement=null;
}

hui.ui.Editor.getPartId = function(element) {
	var m = element.id.match(/part\-([\d]+)/i);
	if (m && m.length>0) return m[1];
}

////////////////////////////////// Header editor ////////////////////////////////

/**
 * @constructor
 */
hui.ui.Editor.Header = function(element,row,column,position) {
	this.element = hui.get(element);
	this.row = row;
	this.column = column;
	this.position = position;
	this.id = hui.ui.Editor.getPartId(this.element);
	this.header = hui.firstByTag(this.element,'*');
	this.field = null;
}

hui.ui.Editor.Header.prototype = {
	activate : function() {
		this.value = this.header.innerHTML;
		this.field = hui.build('textarea',{className:'hui_editor_header'});
		this.field.value = this.value;
		this.header.style.visibility='hidden';
		this.updateFieldStyle();
		this.element.insertBefore(this.field,this.header);
		this.field.focus();
		this.field.select();
		hui.listen(this.field,'keydown',function(e) {
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
			hui.ui.Editor.get().partChanged(this);
		}
	},
	cancel : function() {
		this.deactivate();
	},
	deactivate : function() {
		this.header.style.visibility='';
		this.element.removeChild(this.field);
		hui.ui.Editor.get().partDidDeacivate(this);
	},
	updateFieldStyle : function() {
		hui.setStyle(this.field,{width:this.header.clientWidth+'px',height:this.header.clientHeight+'px'});
		hui.copyStyle(this.header,this.field,['fontSize','lineHeight','marginTop','fontWeight','fontFamily','textAlign','color','fontStyle']);
	},
	getValue : function() {
		return this.value;
	}
}

////////////////////////////////// Html editor ////////////////////////////////

/**
 * @constructor
 */
hui.ui.Editor.Html = function(element,row,column,position) {
	this.element = hui.get(element);
	this.row = row;
	this.column = column;
	this.position = position;
	this.id = hui.ui.Editor.getPartId(this.element);
	this.field = null;
}

hui.ui.Editor.Html.prototype = {
	activate : function() {
		this.value = this.element.innerHTML;
		this.element.innerHTML='';
		var style = this.buildStyle();
		this.editor = hui.ui.MarkupEditor.create({autoHideToolbar:false,style:style});
		this.element.appendChild(this.editor.getElement());
		this.editor.listen(this);
		this.editor.setValue(this.value);
		this.editor.focus();
	},
	buildStyle : function() {
		return {
			'textAlign':hui.getStyle(this.element,'text-align')
			,'fontFamily':hui.getStyle(this.element,'font-family')
			,'fontSize':hui.getStyle(this.element,'font-size')
			,'fontWeight':hui.getStyle(this.element,'font-weight')
			,'color':hui.getStyle(this.element,'color')
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
			hui.ui.Editor.get().partChanged(this);
		}
		this.element.innerHTML = this.value;
	},
	deactivate : function() {
		if (this.editor) {
			this.editor.destroy();
			this.element.innerHTML = this.value;
		}
		hui.ui.Editor.get().partDidDeacivate(this);
	},
	richTextDidChange : function() {
		//this.deactivate();
	},
	getValue : function() {
		return this.value;
	}
}

/* EOF */