var controller = {
	parts : [],
	pageId : 0,
	sectionId : 0,
	sectionIndex : 0,
	columnId : 0,
	columnIndex : 0,
	rowId : 0,
	rowIndex : 0,
	activeSection : 0,
	ready : false,
	selectedText : '',
	changed : false,
	strings : new hui.ui.Bundle({
			edit_section : {da:'Rediger afsnit',en:'Edit section'},
			delete_section : {da:'Slet afsnit',en:'Delete section'},
			move_section_up : {da:'Flyt afsnit op',en:'Move section up'},
			move_section_down : {da:'Flyt afsnit ned',en:'Move section down'},
			add_column : {da:'Tilf\u00f8j kolonne',en:'Add column'},
			edit_column : {da:'Indstil kolonne',en:'Column setup'},
			move_right : {da:'Flyt til h\u00f8jre',en:'Move right'},
			move_left : {da:'Flyt til venstre',en:'Move left'},
			move_column_right : {da:'Flyt kolonne til h\u00f8jre',en:'Move column right'},
			move_column_left : {da:'Flyt kolonne til venstre',en:'Move column left'},
			delete_column : {da:'Slet kolonne',en:'Delete column'},
			move_up : {da:'Flyt op',en:'Move up'},
			move_down : {da:'Flyt ned',en:'Move down'},
			move_row_up : {da:'Flyt r\u00e6kke op',en:'Move row up'},
			move_row_down : {da:'Flyt r\u00e6kke ned',en:'Move row down'},
			add_row : {da:'Tilf\u00f8j r\u00e6kke',en:'Add row'},
			delete_row : {da:'Slet r\u00e6kke',en:'Delete row'},
			column : {da:'Kolonne',en:'Column'},
			row : {da:'R\u00e6kke',en:'Row'},
			
			confirm_delete_section : {
				da:'Er du sikker p\u00e5 at du vil slette afsnittet?\n\nHandlingen kan ikke fortrydes.\n',
				en:'Are you sure you want to delete the section?\n\nThe action cannot be undone.\n'
			},
			confirm_delete_column : {
				da:'Er du sikker p\u00e5 at du vil slette kolonnen?\n\nHandlingen kan ikke fortrydes.\n',
				en:'Are you sure you want to delete the column?\n\nThe action cannot be undone.\n'
			},
			confirm_delete_row : {
				da:'Er du sikker p\u00e5 at du vil slette r\u00e6kken?\n\nHandlingen kan ikke fortrydes.\n',
				en:'Are you sure you want to delete the row?\n\nThe action cannot be undone.\n'
			}
	}),
	$ready : function() {
		var strings = this.strings;
		this.sectionMenu = hui.ui.Menu.create({name:'sectionMenu'});
		this.sectionMenu.addItems([
			{title:strings.get('edit_section'),value:'editSection'},
			{title:strings.get('delete_section'),value:'deleteSection'},
			{title:strings.get('move_section_up'),value:'moveSectionUp'},
			{title:strings.get('move_section_down'),value:'moveSectionDown'},
			null,
			{title:strings.get('column'),children:[
				{title:strings.get('add_column'),value:'addColumn'},
				{title:strings.get('edit_column'),value:'editColumn'},
				{title:strings.get('move_right'),value:'moveColumnRight'},
				{title:strings.get('move_left'),value:'moveColumnLeft'},
				{title:strings.get('delete_column'),value:'deleteColumn'}
			]},
			{title:strings.get('row'),children:[
				{title:strings.get('move_up'),value:'moveRowUp'},
				{title:strings.get('move_down'),value:'moveRowDown'},
				{title:strings.get('add_row'),value:'addRow'},
				{title:strings.get('delete_row'),value:'deleteRow'}
			]}
		]);
		this.columnMenu = hui.ui.Menu.create({name:'sectionMenu'});
		this.columnMenu.addItems([
				{title:strings.get('add_column'),value:'addColumn'},
				{title:strings.get('edit_column'),value:'editColumn'},
				{title:strings.get('move_column_right'),value:'moveColumnRight'},
				{title:strings.get('move_column_left'),value:'moveColumnLeft'},
				{title:strings.get('delete_column'),value:'deleteColumn'},
				null,
				{title:strings.get('move_row_up'),value:'moveRowUp'},
				{title:strings.get('move_row_down'),value:'moveRowDown'},
				{title:strings.get('add_row'),value:'addRow'},
				{title:strings.get('delete_row'),value:'deleteRow'}
		]);
		this.partMenu = hui.ui.Menu.create({name:'partMenu'});
		this.partMenu.addItems(this.parts);
		
		this.partControls = hui.ui.Overlay.create({name:'sectionActions'});
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
		}
		this.ready = true;
		hui.listen(document.body,'mouseup',function(e) {
			e = hui.event(e);
			var section = e.findByClass('editor_section');
			if (section) {
				this.selectedTextInfo = hui.string.fromJSON(section.getAttribute('data'));
			} else {
				this.selectedTextInfo = null;
			}
			this.selectedText = hui.selection.getText();
		}.bind(this));
		
		if (this.changed) {
			this._markToolbarChanged();
		}
		window.onscroll = this.saveScroll;
		var editLink = hui.location.getInt('editLink');
		if (editLink) {
			this._loadLink(editLink);
		} else {
			var scroll = hui.cookie.get('document.scroll');
			if (scroll) {
				window.scrollTo(0,parseInt(scroll,10));
			}
		}
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
			this.editSection();
		} else if (value=='new') {
			this.lastSectionMode = false;
			this.partMenu.showAtPointer(event);
		} else if (value=='delete') {
			this.deleteSection();
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
		switch (value) {
			case 'editSection' : this.editSection(); break;
			case 'deleteSection' : this.deleteSection(); break;
			case 'moveSectionUp' : this.moveSection(-1); break;
			case 'moveSectionDown' : this.moveSection(1); break;
			
			case 'editColumn' : this.editColumn(); break;
			case 'addColumn' : this.addColumn(); break;
			case 'deleteColumn' : this.deleteColumn(); break;
			case 'moveColumnLeft' : this.moveColumn(-1); break;
			case 'moveColumnRight' : this.moveColumn(1); break;
			
			case 'addRow' : this.addRow(); break;
			case 'deleteRow' : this.deleteRow(); break;
			case 'moveRowUp' : this.moveRow(-1); break;
			case 'moveRowDown' : this.moveRow(1); break;
		}
	},
	$select$partMenu : function(value) {
		document.forms.SectionAdder.type.value='part';
		document.forms.SectionAdder.part.value=value;
		if (this.lastSectionMode) {
			document.forms.SectionAdder.column.value=this.columnId;
			document.forms.SectionAdder.index.value=this.sectionIndex;
		}
		document.forms.SectionAdder.submit();
	},
	
	//////////////////// Sections ///////////////////
	
	sectionOver : function(cell,sectionId,columnId,indexVal) {
		if (this.activeSection || !this.ready) return;
		hui.cls.add(cell,'section_hover');
		this.sectionId=sectionId;
		document.forms.SectionAdder.column.value=columnId;
		document.forms.SectionAdder.index.value=indexVal;
		this.partControls.showAtElement(cell,{'horizontal':'right',autoHide:true});
	},
	sectionOut : function(cell,event) {
		hui.cls.remove(cell,'section_hover');
		return;
	},
	showSectionMenu : function(element,event,sectionId,sectionIndex,columnId,columnIndex,rowId,rowIndex) {
		if (this.activeSection || this.selectedText) {
			return true;
		}
	    this.sectionId = sectionId;
	    this.sectionIndex = sectionIndex;
	    this.columnId = columnId;
	    this.columnIndex = columnIndex;
	    this.rowId = rowId;
	    this.rowIndex = rowIndex;
		this.sectionMenu.showAtPointer(event);
		return false;
	},
	clickSection : function(info) {
		if (info.event.altKey) {
			document.location='Editor.php?section='+info.id;
		}
	},
	editSection : function() {
		document.location='Editor.php?section='+this.sectionId;
	},
	deleteSection : function() {
		if (confirm(this.strings.get('confirm_delete_section'))) {
			document.location='data/DeleteSection.php?section='+this.sectionId;
		}
	},
	showNewPartMenu : function(element,event,columnId,sectionIndex) {
		this.lastSectionMode = true;
	    this.columnId=columnId;
	    this.sectionIndex=sectionIndex;
		this.partMenu.showAtElement(element,event);
	},
	
	showColumnMenu : function(element,event,columnId,columnIndex,rowId,rowIndex) {
		if (this.activeSection || this.selectedText) {
			return true;
		}
	    this.columnId=columnId;
	    this.columnIndex=columnIndex;
	    this.rowId=rowId;
	    this.rowIndex=rowIndex;
		this.columnMenu.showAtPointer(event);
		return false;
	},
	
	columnOver : function(cell) {
		hui.cls.add(cell,'editor_column_hover');
	},
	
	columnOut : function(cell) {
		hui.cls.remove(cell,'editor_column_hover');
	},
	
	moveSection : function(dir) {
		document.location='data/MoveSection.php?section='+this.sectionId+'&dir='+dir;
	},
	
	moveColumn : function(dir) {
		document.location='data/MoveColumn.php?column='+this.columnId+'&dir='+dir;
	},
	
	addColumn : function() {
		document.location='data/AddColumn.php?row='+this.rowId+'&index='+(this.columnIndex+1);
	},
	
	deleteColumn : function() {
		if (confirm(this.strings.get('confirm_delete_column'))) {
			document.location='data/DeleteColumn.php?column='+this.columnId;
		}
	},
	
	moveRow : function(dir) {
		document.location='data/MoveRow.php?row='+this.rowId+'&dir='+dir;
	},
	
	addRow : function() {
		document.location='data/AddRow.php?index='+(this.rowIndex+1)+'&pageId='+this.pageId;
	},
	
	deleteRow : function() {
		if (confirm(this.strings.get('confirm_delete_row'))) {
			document.location='data/DeleteRow.php?row='+this.rowId;
		}
	},
	
	///////////////////////////////// Columns /////////////////////////
	
	
	editColumn : function() {
		if (this.editedColumn) {
			this._resetColumn();
		}
		
		var node = hui.get('column'+this.columnId);
		hui.cls.add(node,'editor_column_highlighted');
		this.editedColumn = {
			id : this.columnId,
			initialWidth : node.style.width,
			node : node
		}
		hui.ui.request({
			message : {start : 'Åbner kolonne...',delay:300},
			url : 'data/LoadColumn.php',
			parameters : { id : this.editedColumn.id },
			onJSON : function(obj) {
				var values = {preset:'dynamic',width:''};
				if (obj.width=='min') {
					values.preset='min';
				} else if (obj.width=='max') {
					values.preset='max';
				} else if (!hui.isBlank(obj.width)) {
					values.preset='specific';
					values.width = obj.width;
				}
				columnWindow.show();
				columnFormula.setValues(values);
			}
		})
	},
	$valueChanged$columnWidth : function() {
		columnPreset.setValue('specific');
	},
	$valueChanged$columnPreset : function(value) {
		if (columnPreset.getValue()=='specific') {
			columnWidth.focus();
		} else {
			columnWidth.reset();
		}
	},
	$valuesChanged$columnFormula : function(values) {
		var node = hui.get('column'+this.columnId);
		if (node) {
			if (values.preset=='min') {
				node.style.width = '1%';
			} else if (values.preset=='max') {
				node.style.width = '100%';
			} else if (values.preset=='dynamic') {
				node.style.width = 'auto';
			} else {
				node.style.width=values.width || 'auto';
			}
		} else {
			hui.log('Column node not found');
		}
	},
	_resetColumn : function() {
		this.editedColumn.node.style.width = this.editedColumn.initialWidth;
		hui.cls.remove(this.editedColumn.node,'editor_column_highlighted');
		this.editedColumn = null;
		columnFormula.reset();
		columnWindow.hide();
	},
	$userClosedWindow$columnWindow : function() {
		this._resetColumn();
	},
	$click$cancelColumn : function() {
		this._resetColumn();
	},
	$click$deleteColumn : function() {
		document.location='data/DeleteColumn.php?column='+this.editedColumn.id;
	},
	$submit$columnFormula : function(form) {
		var values = form.getValues();
		var p = {
			id : this.editedColumn.id
		};
		if (values.preset=='min' || values.preset=='max') {
			p.width = values.preset;
		} else if (values.preset='specific') {
			p.width = values.width;
		}
		hui.ui.request({
			url : 'data/UpdateColumn.php',
			parameters : p,
			message : {start : 'Gemmer kolonne...',delay:300},
			onSuccess : function() {
				hui.ui.showMessage({text:'Kolonnen er gemt',duration:2000,icon:'common/success'});
				this._markToolbarChanged();
			}.bind(this)
		});
		hui.cls.remove(this.editedColumn.node,'editor_column_highlighted');
		this.editedColumn = null;
		columnFormula.reset();
		columnWindow.hide();
	},
	
	////////////////////////////////// Links //////////////////////////
	
	linkId : null,
	
	newLink : function() {
		this.linkId = null;
		if (this.selectedTextInfo) {
			this.linkPartId = this.selectedTextInfo.part;
			this._highlightPart(this.linkPartId);
		} else {
			this.linkPartId = null;
		}
		linkScope.setEnabled(this.selectedTextInfo!=null);
		linkFormula.reset();
		linkFormula.setValues({
			text : this.selectedText,
			scope : this.linkPartId ? 'part' : 'page'
		});
		linkWindow.show();
		deleteLink.disable();
		saveLink.setText('Opret');
		linkFormula.focus();
	},
	$click$cancelLink : function() {
		linkFormula.reset();
		linkWindow.hide();
		this._clearPartHighlight();
	},
	_highlightPart : function(id) {
		this._clearPartHighlight();
		var node = hui.get('part'+id);
		if (node) {
			hui.window.scrollTo({element:node});
			hui.cls.add(node,'editor_part_highlighted');
			this._highlightedPart = node;
		}
	},
	_clearPartHighlight : function() {
		if (this._highlightedPart) {
			hui.cls.remove(this._highlightedPart,'editor_part_highlighted');
		}
		this._highlightedPart = null;
	},
	$userClosedWindow$linkWindow : function() {
		this._clearPartHighlight();
	},
	$valueChanged$linkPage : function() {
		linkUrl.reset();
		linkFile.reset();
		linkEmail.reset()
	},
	$valueChanged$linkFile : function() {
		linkUrl.reset();
		linkPage.reset();
		linkEmail.reset()
	},
	$valueChanged$linkUrl : function() {
		linkFile.reset();
		linkPage.reset();
		linkEmail.reset()
	},
	$valueChanged$linkEmail : function() {
		linkFile.reset();
		linkPage.reset();
		linkUrl.reset()
	},
	$submit$linkFormula : function(form) {
		var values = form.getValues();
		if (hui.isBlank(values.text)) {
			hui.ui.showMessage({text:'Skriv den tekst hvor der skal linkes fra',duration:2000});
			form.focus();
			return;
		}
		if (hui.isBlank(values.email) && hui.isBlank(values.url) && values.page==null && values.file==null) {
			hui.ui.showMessage({text:'Du skal vælge et mål for linket',duration:2000});
			form.focus();
			return;
		}
		var p = {text : values.text, description : values.description, id : this.linkId};
		if (values.page) {
			p.type = 'page';
			p.value = values.page;
		} else if (values.file) {
			p.type = 'file';
			p.value = values.file;
		} else if (!hui.isBlank(values.url)) {
			p.type = 'url';
			p.value = values.url;
		} else if (!hui.isBlank(values.email)) {
			p.type = 'email';
			p.value = values.email;
		}
		if (values.scope=='part' && this.linkPartId) {
			p.partId = this.linkPartId;
		}
		hui.ui.request({
			url : 'data/SaveLink.php',
			parameters : p,
			message : {start:'Indsætter link',delay:300},
			onSuccess : function() {
				document.location.reload();
			}
		});
		linkFormula.reset();
		linkWindow.hide();
	},
	_loadLink : function(id) {
		this.linkId = null;
		linkFormula.reset();
		hui.ui.request({
			url : 'data/LoadLink.php',
			parameters : {id:id},
			message : {start:'Henter link',delay:300},
			onJSON : function(obj) {
				this.linkId = obj.id;
				if (obj.partId) {
					this.linkPartId = obj.partId;
					hui.log('Using partId from data');
				} else if (this.selectedTextInfo) {
					this.linkPartId = this.selectedTextInfo.part;
					hui.log('Using partId from selected text');
				} else {
					hui.log('Part id is undefined');
				}
				if (this.linkPartId) {
					this._highlightPart(this.linkPartId);
				}
				hui.log('load link: Part id is: '+this.linkPartId);
				linkFormula.setValues(obj);
				linkWindow.show();
				deleteLink.enable();
				saveLink.setText('Opdater');
			}.bind(this)
		});
	},
	
	clickedLink : null,
	panelLinkInfo : null,
	
	_clearLinkFocus : function() {
		if (this.clickedLinkInfo) {
			hui.cls.remove(this.clickedLinkInfo.node,'editor_link_highlighted');
		}
	},
	
	linkWasClicked : function(info) {
		this._clearLinkFocus();
		this.clickedLinkInfo = info;
		var section = hui.get.firstAncestorByClass(info.node,'editor_section');
		if (section) {
			this.selectedTextInfo = hui.string.fromJSON(section.getAttribute('data'));
		} else {
			hui.log('Section not found');
			this.selectedTextInfo = null;
		}
		if (this.selectedTextInfo) {
			this.linkPartId = this.selectedTextInfo.part;
			hui.log('link click: Part id is: '+this.linkPartId);
		}
		hui.cls.add(info.node,'editor_link_highlighted');
		hui.ui.request({
			url : 'data/LoadLinkInfo.php',
			parameters : {id:info.id},
			onJSON : function(obj) {
				this.panelLinkInfo = obj;
				linkInfo.setContent(obj.rendering);
				linkPanel.position(info.node);
				visitLink.setEnabled(obj.type=='page' || obj.type=='url');
				linkPanel.show();
			}.bind(this)
		})
	},
	linkMenu : function(info) {
		if (!this.linkContextMenu) {
			this.linkContextMenu = hui.ui.Menu.create({name:'linkMenu'});
			this.linkContextMenu.addItems([
				{title:'Slet',value:'delete'}
			]);
		}
		this.linkContextMenu.showAtPointer(info.event);
	},
	$click$cancelLinkPanel : function() {
		this._clearLinkFocus();
		linkPanel.hide();
		this.clickedLinkInfo = null;
	},
	$click$editLink : function() {
		this._clearLinkFocus();
		linkPanel.hide();
		this._loadLink(this.clickedLinkInfo.id);
	},
	$click$visitLink : function() {
		if (this.panelLinkInfo.type=='page') {
			parent.location='../Edit.php?id='+this.panelLinkInfo.targetId;
		}
		if (this.panelLinkInfo.type=='url') {
			alert(this.panelLinkInfo.targetValue)
			window.open(this.panelLinkInfo.targetValue);
		}
	},
	$click$limitLinkToPart : function() {
		hui.ui.request({
			url : 'data/BindLinkToPart.php',
			parameters : {linkId:this.clickedLinkInfo.id,partId:this.clickedLinkInfo.part},
			message : {start:'Gemmer link',delay:300},
			onSuccess : function() {
				document.location.reload();
			}
		});
	},
	$click$deleteLinkPanel : function() {
		linkPanel.hide();
		this._deleteLink(this.clickedLinkInfo.id);
	},
	$click$deleteLink : function() {
		this._deleteLink(this.linkId);
		this.linkId = null;
		linkFormula.reset();
		linkWindow.hide();
	},
	_deleteLink : function(id) {
		hui.ui.request({
			url : 'data/DeleteLink.php',
			parameters : {id:id},
			message : {start:'Sletter link',delay:300},
			onSuccess : function() {
				document.location.reload();
			}
		});
	}
};

hui.ui.listen(controller);

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