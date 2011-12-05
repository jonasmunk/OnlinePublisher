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
	this.live = true;
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
		hui.listen(window,'keydown',this._onKeyDown.bind(this));
		hui.listen(window,'keyup',this._onKeyUp.bind(this));
		this.reload();
	},
	_onKeyDown : function(e) {
		e = hui.event(e);
		//this.live = e.altKey;
	},
	_onKeyUp : function(e) {
		//this.live = false;
	},
	
	addPartController : function(key,title,controller) {
		this.partControllers.push({key:key,'title':title,'controller':controller});
	},
	setOptions : function(options) {
		hui.override(this.options,options);
	},
	_getPartController : function(key) {
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
		var rows = hui.get.byClass(document.body,this.options.rowClass);
		hui.each(rows,function(row,i) {
			var columns = hui.get.byClass(row,self.options.columnClass);
			self._reloadColumns(columns,i);
			hui.each(columns,function(column,j) {
				var parts = hui.get.byClass(column,self.options.partClass);
				self._reloadParts(parts,i,j);
			});
		});
		var parts = hui.get.byClass(document.body,this.options.partClass);
		hui.each(this.parts,function(part) {
			var i = parts.indexOf(part.element);
			if (i!=-1) {
				delete(parts[i]);
			}
		});
		this._reloadParts(parts,-1,-1);
	},
	_reloadColumns : function(columns,rowIndex) {
		var self = this;
		hui.each(columns,function(column,columnIndex) {
			hui.listen(column,'mouseover',function() {
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
	_reloadParts : function(parts,row,column) {
		var self = this;
		var reg = new RegExp(this.options.partClass+"_([\\w]+)","i");
		hui.each(parts,function(element,partIndex) {
			if (!element) return;
			var match = element.className.match(reg);
			if (match && match[1]) {
				var handler = self._getPartController(match[1]);
				if (handler) {
					var part = new handler.controller(element,row,column,partIndex);
					part.type=match[1];
					hui.listen(element,'click',function(e) {
						e = hui.event(e);
						if (!e.findByTag('a') && e.altKey) {
							self.editPart(part);
						}
					});
					hui.listen(element,'mouseover',function(e) {
						self.hoverPart(part);
					});
					hui.listen(element,'mouseout',function(e) {
						self.blurPart(e);
					});
					self.parts.push(part);
				}	
					hui.listen(element,'mousedown',function(e) {
						self._startPartDrag(element,e);
					});
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
		hui.cls.add(column,'hui_editor_column_hover');
	},
	
	blurColumn : function() {
		if (!this.active || !this.hoveredColumn) return;
		hui.cls.remove(this.hoveredColumn,'hui_editor_column_hover');
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
		var row = hui.get.byClass(document.body,'row')[rowIndex];
		var c = this.activeColumn = hui.get.byClass(row,'column')[columnIndex];
		hui.cls.add(c,'hui_editor_column_edit');
		this.showColumnWindow();
		this.columnEditorForm.setValues({width:c.getStyle('width'),paddingLeft:c.getStyle('padding-left')});
	},
	closeColumn : function() {
		if (this.activeColumn) {
			hui.cls.remove(this.activeColumn,'hui_editor_column_edit');
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
		if (!this.active || this.activePart || !this.live || this.dragging) {
			hui.log(!this.live)
			return;
		}
		this.hoveredPart = part;
		hui.cls.add(part.element,'hui_editor_part_hover');
		var self = this;
		this.partControlTimer = window.setTimeout(function() {self.showPartControls()},200);
	},
	blurPart : function(e) {
		window.clearTimeout(this.partControlTimer);
		if (!this.active) return;
		if (this.partControls && !hui.ui.isWithin(e,this.partControls.element)) {
			this.hidePartControls();
			hui.cls.remove(this.hoveredPart.element,'hui_editor_part_hover');
		}
		if (!this.partControls && this.hoveredPart) {
			hui.cls.remove(this.hoveredPart.element,'hui_editor_part_hover');			
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
		hui.cls.add(this.hoveredPart.element,'hui_editor_part_hover');
	},
	blurControls : function(e) {
		hui.cls.remove(this.hoveredPart.element,'hui_editor_part_hover');
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
			hui.cls.remove(this.hoveredPart.element,'hui_editor_part_hover');
		}
		this.activePart = part;
		this.showPartEditControls();
		hui.cls.add(part.element,'hui_editor_part_active');
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
		hui.style.set(this.activePart.element,{
			marginTop:values.top,
			marginBottom:values.bottom,
			marginLeft:values.left,
			marginRight:values.right
		});
		this.activePart.properties = values;
	},
	hidePartEditor : function() {
		if (this.partEditor) this.partEditor.hide();
	},
	cancelPart : function(part) {
		part.cancel();
		this.hidePartEditor();
		this.activePart = null;
	},
	savePart : function(part) {
		hui.log('savePart')
		part.save();
		this.hidePartEditor();
		this.activePart = null;
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
		hui.cls.remove(part.element,'hui_editor_part_active');
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
	
	_dragInfo : null,
	
	_dropInfo : null,
	
	dragProxy : null,
	
	_startPartDrag : function(node,e) {
		if (!this.active || this.activePart || !this.live) {
			return true;
		}
		if (!this.dragProxy) {
			this.dragProxy = hui.build('div',{'class':'hui_editor_dragproxy part part_header',parent:document.body,style:'display:none;'});
		}
		e = hui.event(e);
		e.stop();
		var element = node;
		var proxy = this.dragProxy;
		
		this._dragInfo = {
			diffLeft : e.getLeft() - hui.position.getLeft(element),
			diffTop : e.getTop() - hui.position.getTop(element),
			draggedElement : element
		}
		proxy.innerHTML = element.innerHTML;
		hui.drag.start({
			element : proxy,
			onBeforeMove : this._onBeforeDrag.bind(this),
			onMove : this._onDrag.bind(this),
			onAfterMove : this._onAfterDrag.bind(this),
			onEnd : function() {
				
			}
		})
	},
	_onDrag : function(e) {
		var left = e.getLeft();
		var top = e.getTop();
		this.dragProxy.style.left = (left - this._dragInfo.diffLeft) + 'px';
		this.dragProxy.style.top = (top - this._dragInfo.diffTop) + 'px';
		for (var i=0; i < this.dropTargets.length; i++) {
			var info = this.dropTargets[i];
			if (info.left<left && info.right>left && info.top<top && info.bottom>top) {
				if (info.placeholder!=this._activeDragPlaceholder) {
					if (this._activeDragPlaceholder) {
						var n = this._activeDragPlaceholder;
						hui.animate({node:n,css:{height:'0px'},duration:500,ease:hui.ease.slowFastSlow});
					}
					var h = this._dragColumnHeights[info.rowIndex+'-'+info.columnIndex];
					hui.log(info.columnIndex+': '+h)
					hui.animate({node:info.placeholder,css:{height : h+'px'},duration:500,ease:hui.ease.slowFastSlow});
					//info.placeholder.style.height='100px';
					hui.animate({node:this.dragProxy,css:{width:(info.right-info.left)+'px'},duration:300,ease:hui.ease.slowFastSlow});
					this._activeDragPlaceholder = info.placeholder;
					this._dropInfo = info;
					break;
				}
			}
		};
	},
	_onBeforeDrag : function() {
		var dragged = this._dragInfo.draggedElement;
		this._insertDropPlaceholders();
		hui.style.set(this.dragProxy,{
			display : 'block',
			visibility : 'visible',
			height  : dragged.clientHeight+'px',
			width  : dragged.clientWidth+'px'
		});
		dragged.style.display='none';
		this._dragging = true;
	},
	_onAfterDrag : function(e) {
		this.dragProxy.style.display = 'none';
		if (this._dropInfo) {
			if (this._dragInfo.draggedElement!==this._dropInfo.part) {
				hui.dom.remove(this._dragInfo.draggedElement);
				hui.dom.insertBefore(this._dropInfo.part,this._dragInfo.draggedElement);
			}
		}
		this._dragInfo.draggedElement.style.display='';
		var p = hui.get.byClass(document.body,'hui_editor_drop_placeholder');
		for (var i=0; i < p.length; i++) {
			hui.dom.remove(p[i]);
		};
		this.dropTargets = [];
		this._dragging = false;
	},
	
	
	
	_activeDragPlaceholder : null,
	
	_dragInfo : null,
	
	_dragColumnHeights : null,
	
	_insertDropPlaceholders : function() {
		var infos = this.dropTargets = [];
		var colHeights = this._dragColumnHeights = {}
		var proxy = this.dragProxy;
		var draggedPart = this._dragInfo.draggedElement;
		var rows = hui.get.byClass(document.body,this.options.rowClass);
		for (var i=0; i < rows.length; i++) {
			var row = rows[i]
			var columns = hui.get.byClass(row,this.options.columnClass);
			for (var j=0; j < columns.length; j++) {
				var column = columns[j];
				hui.style.set(proxy,{
					width : column.clientWidth+'px',
					height : '',
					visibility : 'hidden',
					display : 'block'
				});
				var height = colHeights[i+'-'+j] = proxy.clientHeight;
				var parts = hui.get.byClass(column,this.options.partClass);
				var len = hui.position.getTop(column);
				for (var k=0; k < parts.length; k++) {
					var part = parts[k];
					if (part==draggedPart) {
						continue;
					}
					var placeholder = hui.build('div',{className:'hui_editor_drop_placeholder',html:'<div></div>'});
					hui.dom.insertBefore(part,placeholder);
					var info = {
						rowIndex : i,
						columnIndex : j,
						partIndex : k,
						part : part,
						left : hui.position.getLeft(part),
						right : hui.position.getLeft(part)+part.clientWidth,
						top : len, //hui.position.getTop(part),
						bottom : len+part.clientHeight/2, //hui.position.getTop(part)+part.clientHeight,
						placeholder : placeholder
					}
					len+=part.clientHeight;
					hui.log(info)
					infos.push(info);
				};
				
			}
		}
	}
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
	this.header = hui.get.firstByTag(this.element,'*');
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
		hui.style.set(this.field,{width:this.header.clientWidth+'px',height:this.header.clientHeight+'px'});
		hui.style.copy(this.header,this.field,['fontSize','lineHeight','marginTop','fontWeight','fontFamily','textAlign','color','fontStyle']);
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
			'textAlign':hui.style.get(this.element,'text-align')
			,'fontFamily':hui.style.get(this.element,'font-family')
			,'fontSize':hui.style.get(this.element,'font-size')
			,'fontWeight':hui.style.get(this.element,'font-weight')
			,'color':hui.style.get(this.element,'color')
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