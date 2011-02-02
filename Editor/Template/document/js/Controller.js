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
	strings : new ui.Bundle({
			edit_section : {da:'Rediger afsnit',en:'Edit section'},
			delete_section : {da:'Slet afsnit',en:'Delete section'},
			move_section_up : {da:'Flyt afsnit op',en:'Move section up'},
			move_section_down : {da:'Flyt afsnit ned',en:'Move section down'},
			edit_column : {da:'Rediger kolonne',en:'Edit column'},
			move_right : {da:'Flyt til h\u00f8jre',en:'Move right'},
			move_left : {da:'Flyt til venstre',en:'Move left'},
			move_column_right : {da:'Flyt kolonne til h\u00f8jre',en:'Move column right'},
			move_column_left : {da:'Flyt kolonne til venstre',en:'Move column left'},
			add_column : {da:'Tilf\u00f8j kolonne',en:'Add column'},
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
		this.sectionMenu = In2iGui.Menu.create({name:'sectionMenu'});
		this.sectionMenu.addItems([
			{title:strings.get('edit_section'),value:'editSection'},
			{title:strings.get('delete_section'),value:'deleteSection'},
			{title:strings.get('move_section_up'),value:'moveSectionUp'},
			{title:strings.get('move_section_down'),value:'moveSectionDown'},
			null,
			{title:strings.get('column'),children:[
				{title:strings.get('edit_column'),value:'editColumn'},
				{title:strings.get('move_right'),value:'moveColumnRight'},
				{title:strings.get('move_left'),value:'moveColumnLeft'},
				{title:strings.get('add_column'),value:'addColumn'},
				{title:strings.get('delete_column'),value:'deleteColumn'}
			]},
			{title:strings.get('row'),children:[
				{title:strings.get('move_up'),value:'moveRowUp'},
				{title:strings.get('move_down'),value:'moveRowDown'},
				{title:strings.get('add_row'),value:'addRow'},
				{title:strings.get('delete_row'),value:'deleteRow'}
			]}
		]);
		this.columnMenu = In2iGui.Menu.create({name:'sectionMenu'});
		this.columnMenu.addItems([
				{title:strings.get('edit_column'),value:'editColumn'},
				{title:strings.get('move_column_right'),value:'moveColumnRight'},
				{title:strings.get('move_column_left'),value:'moveColumnLeft'},
				{title:strings.get('add_column'),value:'addColumn'},
				{title:strings.get('delete_column'),value:'deleteColumn'},
				null,
				{title:strings.get('move_row_up'),value:'moveRowUp'},
				{title:strings.get('move_row_down'),value:'moveRowDown'},
				{title:strings.get('add_row'),value:'addRow'},
				{title:strings.get('delete_row'),value:'deleteRow'}
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
			this.partEditControls.showAtElement(n2i.firstByClass(document.body,'section_selected'),{'horizontal':'left','vertical':'topOutside'});
		}
		this.ready = true;
		n2i.listen(document.body,'mouseup',function() {
			this.selectedText = n2i.getSelectedText();
		}.bind(this));
		window.onscroll=this.saveScroll;
		var scroll = n2i.cookie.get('document.scroll');
		if (scroll) {
			window.scrollTo(0,parseInt(scroll,10));
		}
	},
	saveScroll : function() {
		n2i.cookie.set('document.scroll',n2i.getScrollTop());
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
		n2i.addClass(cell,'section_hover');
		this.sectionId=sectionId;
		document.forms.SectionAdder.column.value=columnId;
		document.forms.SectionAdder.index.value=indexVal;
		this.partControls.showAtElement(cell,{'horizontal':'right',autoHide:true});
	},
	sectionOut : function(cell,event) {
		n2i.removeClass(cell,'section_hover');
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
		if (confirm(this.strings.get('confirm_delete_section'))) {
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
		n2i.addClass(cell,'columnHover');
	},
	
	columnOut : function(cell) {
		n2i.removeClass(cell,'columnHover');
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
		if (confirm(this.strings.get('confirm_delete_column'))) {
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
		if (confirm(this.strings.get('confirm_delete_row'))) {
			document.location='DeleteRow.php?row='+this.rowId;
		}
	},
	
	////////////////////////////////// Links //////////////////////////
	
	linkWasClicked : function(id) {
		parent.parent.Toolbar.location='Toolbar.php?link=true&id='+id+'&'+Math.random();
	}
};

ui.listen(controller);

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
	n2i.listen(options.field,'keyup',function(){self.resize(false,true)});
	n2i.listen(options.field,'keydown',function(){self.options.field.scrollTop=0;});
}

op.FieldResizer.prototype = {
	resize : function(instantly,focused) {
				
		var field = this.options.field;
		n2i.copyStyle(field,this.dummy,[
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
		if (instantly) {
			this.options.field.style.height=height;
		} else {
			//this.options.field.scrollTop=0;
			n2i.animate(this.options.field,'height',height,200,{ease:n2i.ease.slowFastSlow});
		}
	}
}