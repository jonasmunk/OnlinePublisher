var controller = {
	parts : [],
	sectionId : 0,
	sectionIndex : 0,
	columnId : 0,
	columnIndex : 0,
	rowId : 0,
	rowIndex : 0,
	activeSection : 0,
	ready : false,
	selectedText : '',
	
	$interfaceIsReady : function() {
		this.sectionMenu = In2iGui.Menu.create({name:'sectionMenu'});
		this.sectionMenu.addItems([
			{title:'Rediger afsnit',value:'editSection'},
			{title:'Slet afsnit',value:'deleteSection'},
			{title:'Flyt afsnit op',value:'moveSectionUp'},
			{title:'Flyt afsnit ned',value:'moveSectionDown'},
			null,
			{title:'Kolonne',children:[
				{title:'Rediger kolonne',value:'editColumn'},
				{title:'Flyt til h&oslash;jre',value:'moveColumnRight'},
				{title:'Flyt til venstre',value:'moveColumnLeft'},
				{title:'Tilf&oslash;j kolonne',value:'addColumn'},
				{title:'Slet kolonne',value:'deleteColumn'}
			]},
			{title:'R&aelig;kke',children:[
				{title:'Flyt op',value:'moveRowUp'},
				{title:'Flyt ned',value:'moveRowDown'},
				{title:'Tilf&oslash;j r&aelig;kke',value:'addRow'},
				{title:'Slet r&aelig;kke',value:'deleteRow'}
			]}
		]);
		this.columnMenu = In2iGui.Menu.create({name:'sectionMenu'});
		this.columnMenu.addItems([
				{title:'Rediger kolonne',value:'editColumn'},
				{title:'Flyt kolonne til h&oslash;jre',value:'moveColumnRight'},
				{title:'Flyt kolonne til venstre',value:'moveColumnLeft'},
				{title:'Tilf&oslash;j kolonne',value:'addColumn'},
				{title:'Slet kolonne',value:'deleteColumn'},
				null,
				{title:'Flyt r&aelig;kke op',value:'moveRowUp'},
				{title:'Flyt r&aelig;kke ned',value:'moveRowDown'},
				{title:'Tilf&oslash;j r&aelig;kke',value:'addRow'},
				{title:'Slet r&aelig;kke',value:'deleteRow'}
		]);
		this.partMenu = In2iGui.Menu.create({name:'partMenu'});
		this.partMenu.addItems(this.parts);
		
		this.partControls = In2iGui.Overlay.create({name:'sectionActions'});
		this.partControls.addIcon('edit','common/edit');
		this.partControls.addIcon('new','common/new');
		this.partControls.addIcon('delete','common/delete');
		
		if (this.activeSection) {
			this.partEditControls = In2iGui.Overlay.create({name:'sectionEditActions'});
			this.partEditControls.addIcon('save','common/save');
			this.partEditControls.addIcon('cancel','common/stop');
			this.partEditControls.showAtElement($$('.section_selected')[0],{'horizontal':'left','vertical':'topOutside'});
			//alert($$('.sectionSelected')[0]);
		}
		this.ready = true;
		$(document.body).observe('mouseup',function() {
			this.selectedText = n2i.getSelectedText();
		}.bind(this));
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
	$select : function(value) {
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
		cell.addClassName('section_hover');
		this.sectionId=sectionId;
		document.forms.SectionAdder.column.value=columnId;
		document.forms.SectionAdder.index.value=indexVal;
		this.partControls.showAtElement(cell,{'horizontal':'right',autoHide:true});
	},
	sectionOut : function(cell,event) {
		cell.removeClassName('section_hover');
		return;
	},
	showSectionMenu : function(element,event,sectionId,sectionIndex,columnId,columnIndex,rowId,rowIndex) {
	    this.sectionId=sectionId;
	    this.sectionIndex=sectionIndex;
	    this.columnId=columnId;
	    this.columnIndex=columnIndex;
	    this.rowId=rowId;
	    this.rowIndex=rowIndex;
		this.sectionMenu.showAtPointer(event);
	},
	editSection : function() {
		document.location='Editor.php?section='+this.sectionId;
	},
	deleteSection : function() {
		if (confirm('Er du sikker p\u00e5 at du vil slette afsnittet?\nHandlingen kan ikke fortrydes')) {
			document.location='DeleteSection.php?section='+this.sectionId;
		}
	},
	showNewPartMenu : function(element,event,columnId,sectionIndex) {
		this.lastSectionMode = true;
	    this.columnId=columnId;
	    this.sectionIndex=sectionIndex;
		this.partMenu.showAtElement(element,event);
	},
	
	showColumnMenu : function(element,event,columnId,columnIndex,rowId,rowIndex) {
	    this.columnId=columnId;
	    this.columnIndex=columnIndex;
	    this.rowId=rowId;
	    this.rowIndex=rowIndex;
		this.columnMenu.showAtPointer(event);
	},
	
	columnOver : function(cell) {
		$(cell).addClassName('columnHover');
	},
	
	columnOut : function(cell) {
		$(cell).removeClassName('columnHover');
	},
	
	editColumn : function() {
		document.location='Editor.php?column='+this.columnId;
	},
	
	moveSection : function(dir) {
		document.location='MoveSection.php?section='+this.sectionId+'&dir='+dir;
	},
	
	moveColumn : function(dir) {
		document.location='MoveColumn.php?column='+this.columnId+'&dir='+dir;
	},
	
	addColumn : function() {
		document.location='AddColumn.php?row='+this.rowId+'&index='+(this.columnIndex+1);
	},
	
	deleteColumn : function() {
		if (confirm('Er du sikker p\u00e5 at du vil slette kolonnen?\nHandlingen kan ikke fortrydes')) {
			document.location='DeleteColumn.php?column='+this.columnId;
		}
	},
	
	moveRow : function(dir) {
		document.location='MoveRow.php?row='+this.rowId+'&dir='+dir;
	},
	
	addRow : function() {
		document.location='AddRow.php?index='+(this.rowIndex+1);
	},
	
	deleteRow : function() {
		if (confirm('Er du sikker p\u00e5 at du vil slette r\u00e6kken?\nHandlingen kan ikke fortrydes')) {
			document.location='DeleteRow.php?row='+this.rowId;
		}
	}
};

ui.get().listen(controller);