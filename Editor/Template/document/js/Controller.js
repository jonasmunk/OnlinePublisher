var controller = {
	pageId : 0,
	activeSection : null,


	ready : false,
	selectedText : '',
	changed : false,
		
	$ready : function() {
		var strings = this.strings;
		
		if (!this.activeSection) {
			this._buildSectionAdders();
		}
		
		this.partControls = hui.ui.Overlay.create({name:'sectionActions',variant:'light'});
		this.partControls.addIcon('edit','common/edit');
		this.partControls.addIcon('new','common/new');
		this.partControls.addIcon('delete','common/delete');
		
		if (this.activeSection) {
			this.partEditControls = hui.ui.Overlay.create({name:'sectionEditActions'});
			this.partEditControls.addIcon('save','common/save');
			this.partEditControls.addIcon('cancel','common/stop');
			this.partEditControls.showAtElement(hui.get.firstByClass(document.body,'section_selected'),{'horizontal':'left','vertical':'topOutside'});
		} else {
			hui.listen(document.body,'keydown',function(e) {
				e = hui.event(e);
				if (e.shiftKey) {
					hui.cls.add(document.body,'editor_details');
					controller.detailsMode = true;
				}
			});
			hui.listen(document.body,'keyup',function(e) {
				if (controller.detailsMode) {
					hui.cls.remove(document.body,'editor_details');
					controller.detailsMode = false;
				}
			});
			hui.listen(document.body,'mouseup',function(e) {
				this.selectedText = hui.selection.getText();
			}.bind(this));
			
			hui.ui.tellContainers('pageChanged',this.pageId);		
		}
		this.ready = true;		
		if (this.changed) {
			this._markToolbarChanged();
		}
		window.onscroll = this.saveScroll;
		var scroll = hui.cookie.get('document.scroll');
		if (scroll) {
			window.scrollTo(0,parseInt(scroll,10));
		}
	},
	
	$$afterResize : function() {
		this._buildSectionAdders();
	},
	_buildSectionAdders : function() {
		return;
		if (!this.adders) {
			this.adders = hui.build('div',{parent:document.body});
			hui.listen(this.adders,'click',function(e) {
				if (this.stickyAdder) {
					hui.cls.remove(this.stickyAdder,'editor_section_adder_sticky');
				}
				e = hui.event(e);
				var adder = e.findByClass('editor_section_adder');
				this.menuInfo = {
					columnId : adder.columnId,
					sectionIndex : adder.sectionIndex+1
				};
				this.stickyAdder = adder;
				hui.cls.add(adder,'editor_section_adder_sticky');
				partMenu.showAtPointer(e);
			}.bind(this))
			hui.listen(this.adders,'mouseover',function(e) {
				e = hui.event(e);
				var adder = e.findByClass('editor_section_adder');
				//hui.cls.add(hui.get('column'+adder.columnId),'editor_column_hover');
				this.partControls.hide();
			}.bind(this))
		} else {
			this.adders.innerHTML = '';
		}
		
		
		var columns = hui.get.byClass(document.body,'editor_column');
		for (var i=0; i < columns.length; i++) {
			var column = columns[i],
				columnId = parseInt(column.getAttribute('data-id')),
				pos = hui.position.get(column);
			var sections = hui.get.byClass(column,'editor_section');
			var adder = hui.build('div',{style:'left:'+pos.left+'px;top:'+pos.top+'px; width:'+column.clientWidth+'px;',className:'editor_section_adder',html:'<div><span><em></em><strong></strong></span></div>',parent:this.adders});
			adder.columnId = columnId;
			adder.sectionIndex = 0;
			for (var j=0; j < sections.length; j++) {
				var section = sections[j];
				var pos = hui.position.get(section);
				var adder = hui.build('div',{style:'left:'+pos.left+'px;top:'+(pos.top+section.clientHeight)+'px; width:'+column.clientWidth+'px;',html:'<div><span><em></em><strong></strong></span></div>',className:'editor_section_adder',parent:this.adders});
				adder.columnId = columnId;
				adder.sectionIndex = j+1;
			};
		};
	},

	showAdderMenu : function(options) {
		this._hideMenus();
		this.partControls.hide();
		this.menuInfo = {
			columnId : options.columnId,
			sectionIndex : options.sectionIndex
		};
		this.menuInfo = hui.string.fromJSON(options.element.getAttribute('data'));
		hui.log(this.menuInfo)
		this.stickyAdder = options.element;
		hui.cls.add(options.element,'editor_section_adder_sticky');
		partMenu.showAtPointer(options.event);
		this.menuMode = true;
	},
	$hide$sectionMenu : function() {
		if (this.menuInfo) {
			var section = hui.get('section'+this.menuInfo.sectionId);
			hui.cls.remove(section,'editor_part_highlighted');
		}
	},
	$hide$partMenu : function() {
		if (this.stickyAdder) {
			hui.cls.remove(this.stickyAdder,'editor_section_adder_sticky');
		}
		this.menuMode = false;
	},
	
	_onClickAdder : function() {
		
	},
	
	_markToolbarChanged : function() {
		try {
			parent.frames[0].controller.markChanged();
		} catch (e) {
			hui.log('Unable to mark toolbar changed...');
			hui.log(e);
		}
	},
	saveScroll : function() {
		hui.cookie.set('document.scroll',hui.window.getScrollTop());
	},
	setMainToolbar : function() {
		try {
			if (parent.frames[0].location.href.indexOf('/Toolbar.php')==-1) {
				parent.frames[0].location='Toolbar.php?'+Math.random();
			} else {
				hui.log('Toolbar already correct!');
			}
		} catch (e) {
			hui.log('Unable to set main controller');
		}
	},
	$iconWasClicked$sectionActions : function(value,event) {
		if (value=='edit') {
			this.editSection(this.hoverInfo.sectionId);
		} else if (value=='new') {
			this.lastSectionMode = false;
			this.menuInfo = this.hoverInfo;
			this._hideMenus();
			partMenu.showAtPointer(event);
		} else if (value=='delete') {
			this.deleteSection(this.hoverInfo.sectionId);
		}
	},
	$iconWasClicked$sectionEditActions : function(value) {
		if (value=='cancel') {
			document.location='Editor.php?section=';
		} else if (value=='save') {
			document.forms.PartForm.submit();
		}
	},
	$select$sectionMenu : function(value) {
		this._onMenu(value);
	},
	$select$columnMenu : function(value) {
		this._onMenu(value);
	},
	_onMenu : function(value) {
		switch (value) {
			case 'cutSection' : this.cutSection(this.menuInfo.sectionId); break;
			case 'copySection' : this.copySection(this.menuInfo.sectionId); break;
			case 'editSection' : this.editSection(this.menuInfo.sectionId); break;
			case 'deleteSection' : this.deleteSection(this.menuInfo.sectionId); break;
			case 'moveSectionUp' : this.moveSection(this.menuInfo.sectionId,-1); break;
			case 'moveSectionDown' : this.moveSection(this.menuInfo.sectionId,1); break;
			
			case 'editColumn' : columnsController.editColumn(this.menuInfo.columnId); break;
			case 'addColumn' : this.addColumn(this.menuInfo.rowId,this.menuInfo.columnIndex+1); break;
			case 'deleteColumn' : columnsController.deleteColumn(this.menuInfo.columnId); break;
			case 'moveColumnLeft' : columnsController.moveColumn(this.menuInfo.columnId,-1); break;
			case 'moveColumnRight' : columnsController.moveColumn(this.menuInfo.columnId,1); break;
			
			case 'addRow' : this.addRow(this.menuInfo.rowIndex+1); break;
			case 'deleteRow' : this.deleteRow(this.menuInfo.rowId); break;
			case 'moveRowUp' : this.moveRow(this.menuInfo.rowId,-1); break;
			case 'moveRowDown' : this.moveRow(this.menuInfo.rowId,1); break;
		}
	},
	$select$partMenu : function(value) {
		if (value=='paste') {
			hui.ui.request({
				url : 'actions/Paste.php',
				message : {start:{en:'Pasting',da:'Indsætter'},delay:300},
				parameters : {
					column : this.menuInfo.columnId,
					index : this.menuInfo.sectionIndex
				},
				onJSON : function(obj) {
					document.location = 'Editor.php';//'Editor.php?section='+obj.sectionId;
				},
				$failure : function() {
					hui.ui.showMessage({icon:'common/warning',text:{en:'Pasting failed',da:'Kunne ikke indsætte'},duration:2000})
				}
			})			
			return;
		}
		hui.ui.request({
			url : 'data/AddSection.php',
			message : {start:{en:'Adding section',da:'Tilføjer sektion'},delay:300},
			parameters : {
				type: 'part',
				part : value,
				column : this.menuInfo.columnId,
				index : this.menuInfo.sectionIndex
			},
			onJSON : function(obj) {
				document.location = 'Editor.php?section='+obj.sectionId;
			}
		})
	},
	
	//////////////////// Sections ///////////////////
	
	_hideMenus : function() {
		if (this.menuInfo) {
			var old = hui.get('section'+this.menuInfo.sectionId);
			if (old) {
				hui.cls.remove(old,'editor_part_highlighted');
			}
		}
		sectionMenu.hide({immediate:true});
		partMenu.hide({immediate:true});
		columnMenu.hide({immediate:true});
		if (this.stickyAdder) {
			hui.cls.remove(this.stickyAdder,'editor_section_adder_sticky');
		}
	},
	sectionOver : function(element,sectionId,columnId,sectionIndex) {
		if (this.activeSection || this.menuMode || !this.ready) return;
		this.hoverInfo = {
			sectionId : sectionId,
			sectionIndex : sectionIndex,
			columnId : columnId
		}
		this.partControls.showAtElement(element,{'horizontal':'right',autoHide:true,top:2});
		addHoverClass(element,'editor_section_hover');
	},
	sectionOut : function(cell,event) {
		return;
	},
	showSectionMenu : function(element,event,sectionId,sectionIndex,columnId,columnIndex,rowId,rowIndex) {
		if (this.activeSection || this.selectedText) {
			return true;
		}
		this._hideMenus();
		var section = hui.get('section'+sectionId);
		hui.cls.add(section,'editor_part_highlighted');
		this.menuInfo = {
		    sectionId : sectionId,
		    sectionIndex : sectionIndex,
		    columnId : columnId,
		    columnIndex : columnIndex,
		    rowId : rowId,
		    rowIndex : rowIndex
		}
		sectionMenu.showAtPointer(event);
		hui.selection.clear();
		return false;
	},
	clickSection : function(info) {
		if (info.event.altKey) {
			document.location = 'Editor.php?section='+info.id;
		}
	},
	copySection : function(id) {
		hui.ui.request({
			url : '../../Services/Clipboard/Copy.php',
			parameters : {sectionId : id},
			$success : function() {
				hui.ui.showMessage({icon:'common/success',text:{en:'Copied',da:'Kopieret'},duration:2000});
			},
			$failure : function() {
				hui.ui.showMessage({icon:'common/warning',text:{en:'Unable to copy',da:'Kopieringen mislykkedes'},duration:2000});
			}
		})
	},
	cutSection : function(id) {
		hui.ui.request({
			url : '../../Services/Clipboard/Cut.php',
			parameters : {sectionId : id},
			$success : function() {
				hui.ui.showMessage({icon:'common/success',text:{en:'Cutted',da:'Klippet'},duration:2000});
			},
			$failure : function() {
				hui.ui.showMessage({icon:'common/warning',text:{en:'Unable to cut',da:'Klipningen mislykkedes'},duration:2000});
			}
		})
	},
	editSection : function(id) {
		document.location = 'Editor.php?section='+id;
	},
	deleteSection : function(id) {
		this.partControls.hide();
		var element = hui.get('section'+id);
		hui.log(element)
		hui.cls.add(element,'editor_part_highlighted');
		hui.ui.confirmOverlay({
			element : element,
			text : {da:'Vil du slette afsnittet? Det kan ikke fortrydes.',en:'Delete the section? It cannot be undone.'},
			okText : {da : 'Ja, slet',en : 'Yes, delete'},
			cancelText : { da : 'Annuller', en : 'Cancel' },
			onOk : function() {
				document.location = 'data/DeleteSection.php?section='+id;
			}.bind(this),
			onCancel : function() {
				hui.cls.remove(element,'editor_part_highlighted');
			}
		})
		this.menuInfo = null;
	},
	showNewPartMenu : function(info) {
		this._hideMenus();
		this.lastSectionMode = true;
		this.menuInfo = {
	    	columnId : info.columnId,
	    	sectionIndex : info.sectionIndex
		}
		partMenu.showAtElement(info.element,info.event);
	},
	
	showColumnMenu : function(element,event,columnId,columnIndex,rowId,rowIndex) {
		if (this.activeSection || this.selectedText) {
			return true;
		}
		this._hideMenus();
		this.menuInfo = {
	    	columnId : columnId,
	    	columnIndex : columnIndex,
	    	rowId : rowId,
	    	rowIndex : rowIndex
		};
		columnMenu.showAtPointer(event);
		return false;
	},
	
	columnOver : function(cell) {
		addHoverClass(cell,'editor_column_hover');
	},
	
	columnOut : function(cell) {
	},
	
	moveSection : function(id,dir) {
		document.location='data/MoveSection.php?section='+id+'&dir='+dir;
	},
		
	addColumn : function(row,index) {
		document.location='data/AddColumn.php?row='+row+'&index='+index;
	},
	
	moveRow : function(id,dir) {
		document.location='data/MoveRow.php?row='+id+'&dir='+dir;
	},
	
	addRow : function(index) {
		document.location='data/AddRow.php?index='+index+'&pageId='+this.pageId;
	},
	
	deleteRow : function(id) {
		
		controller.partControls.hide();
		var node = hui.get('row'+id);
		hui.cls.add(node,'editor_row_highlighted');
		hui.ui.confirmOverlay({
			element : node,
			text : {da:'Vil du slette r\u00e6kken? Det kan ikke fortrydes.',en:'Delete the row? It cannot be undone.'},
			okText : {da : 'Ja, slet',en : 'Yes, delete'},
			cancelText : { da : 'Annuller', en : 'Cancel' },
			onOk : function() {
				document.location='data/DeleteRow.php?row='+id;
			},
			onCancel : function() {
				hui.cls.remove(node,'editor_row_highlighted');
			}
		})
	}

};

hui.ui.listen(controller);


function addHoverClass(cell,className) {
			hui.cls.add(cell,className);
	var hider = null;
	hider = function(e) {
		if (!hui.ui.isWithin(e,cell)) {
			hui.cls.remove(cell,className);
			try {
				hui.unListen(document.body,'mousemove',hider);
			} catch (e) {
				hui.log('unable to stop listening: document='+document);
			}
		}
	}
	hui.listen(document.body,'mousemove',hider);
}

if (!op) {var op={}}

op.FieldResizer = function(options) {
	this.options = options;
	this.options.field.style.overflow='hidden';
	this.dummy = document.createElement('div');
	document.body.appendChild(this.dummy);
	this.dummy.style.position='absolute';
	this.dummy.style.left='-999999px';
	this.dummy.style.top='-999999px';
	this.dummy.style.visibility='hidden';
	var self = this;
	hui.listen(options.field,'keyup',function(){self.resize(false,true)});
	hui.listen(options.field,'keydown',function(){self.options.field.scrollTop=0;});
}

op.FieldResizer.prototype = {
	resize : function(instantly,focused) {
				
		var field = this.options.field;
		hui.style.copy(field,this.dummy,[
			'font-size','line-height','font-weight','letter-spacing','word-spacing','font-family','text-transform','font-variant','text-indent'
		]);
		var html = field.value;
		if (html[html.length-1]==='\n') {
			html+='x';
		}
		// Force webkit redraw
		if (!focused) {
			field.style.display='none';
			field.offsetHeight; // no need to store this anywhere, the reference is enough
			field.style.display='block';
		}
		this.dummy.innerHTML = html.replace(/\n/g,'<br/>');
		this.options.field.style.webkitTransform = 'scale(1)';
		this.dummy.style.width=this.options.field.clientWidth+'px';
		var height = Math.max(50,this.dummy.clientHeight)+'px';
		hui.log(height)
		if (instantly) {
			this.options.field.style.height=height;
		} else {
			//this.options.field.scrollTop=0;
			hui.animate(this.options.field,'height',height,200,{ease:hui.ease.slowFastSlow});
		}
	}
}