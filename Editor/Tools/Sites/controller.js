ui.listen({
	
	activePage : 0,
	dragDrop : [
		{drag:'page',drop:'language'},
		{drag:'page',drop:'subset'}
	],
	
	$interfaceIsReady : function() {
		
	},
	
	///////////////// List ///////////////
	
	$selectionChanged$selector : function(item) {
		In2iGui.get('newSubPage').setEnabled(item.kind=='hierarchy' || item.kind=='hierarchyItem');
	},
	$selectionChanged$list : function(item) {
		In2iGui.get('edit').setEnabled(item.kind=='page');
		In2iGui.get('info').setEnabled(true);
		In2iGui.get('delete').setEnabled(item.kind=='page');
	},
	$selectionReset$list : function() {
		In2iGui.get('edit').setEnabled(false);
		In2iGui.get('info').setEnabled(false);
		In2iGui.get('delete').setEnabled(false);
	},
	$click$edit : function() {
		var obj = list.getFirstSelection();
		document.location='../../Template/Edit.php?id='+obj.id;
	},
	$click$delete : function() {
		In2iGui.get().confirm({
			emotion:'gasp',
			title:'Er du sikker på at du vil slette siden?',
			text:'Alle data fjernes og handlingen kan ikke fortrydes!',
			cancel:'Nej',
			ok:'Ja, slet siden',
			onOK : function() {
				var obj = list.getFirstSelection();
				if (this.activePage==obj.id) {
					pageFormula.reset();
					pageEditor.hide();
					this.activePage = 0;
				}
				In2iGui.request({url:'DeletePage.php',onSuccess:'pageDeleted',parameters:{id:obj.id}});
			}.bind(this)
		});
	},
	$ok$deleteConfirmation : function() {
		alert(0)
	},
	$success$pageDeleted : function() {
		list.refresh();
		languageSource.refresh();
		hierarchySource.refresh();
	},
	$click$info : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='page') {
			ui.request({url:'LoadPage.php',onSuccess:'pageLoaded',json:{data:obj}});
		} else if (obj.kind=='hierarchyItem') {
			this.loadHierarchyItem(obj.id);
		}
	},
	$click$newSubPage : function() {
		In2iGui.showMessage({text:'Ikke implementeret',duration:2000});
	},
	$listRowWasOpened$list : function(obj) {
		if (obj.kind=='page') {
			In2iGui.request({url:'LoadPage.php',onSuccess:'pageLoaded',json:{data:obj}});
		} else if (obj.kind=='hierarchyItem') {
			this.loadHierarchyItem(obj.id);
		}
	},
	$drop$page$language : function(dragged,dropped) {
		In2iGui.request({url:'ChangeLanguage.php',onSuccess:'languageChanged',json:{data:{id:dragged.id,language:dropped.value}}});
	},
	$success$languageChanged : function(data) {
		list.refresh();
		languageSource.refresh();
	},
	$selectionWasOpened : function(obj) {
		if (obj.kind=='hierarchyItem') {
			this.loadHierarchyItem(obj.value);
		}
	},
		
	/////////// Hierarchy item properties //////////
	
	loadHierarchyItem : function(id) {
		In2iGui.request({url:'LoadHierarchyItem.php',onSuccess:'hierarchyItemLoaded',parameters:{id:id}});
	},
	$success$hierarchyItemLoaded : function(data) {
		this.activeHierarchyItem = data.id;
		hierarchyItemFormula.setValues(data);
		hierarchyItemEditor.show();
	},
	$click$cancelHierarchyItem : function() {
		this.activeHierarchyItem = null;
		hierarchyItemFormula.reset();
		hierarchyItemEditor.hide();
	},
	$click$saveHierarchyItem : function() {
		var values = hierarchyItemFormula.getValues();
		values.id = this.activeHierarchyItem;
		In2iGui.request({url:'SaveHierarchyItem.php',onSuccess:'hierarchyItemChanged',json:{data:values}});
	},
	$success$hierarchyItemChanged : function() {
		this.activeHierarchyItem = 0;
		hierarchyItemFormula.reset();
		hierarchyItemEditor.hide();
		list.refresh();
		hierarchySource.refresh();
		In2iGui.showMessage({text:'Menupunktet er opdateret!',duration:2000});
	},
	$click$deleteHierarchyItem : function() {
		In2iGui.request({url:'DeleteHierarchyItem.php',onSuccess:'hierarchyItemDeleted',parameters:{id:this.activeHierarchyItem}});
	},
	$success$hierarchyItemDeleted : function() {
		this.activeHierarchyItem = 0;
		hierarchyItemFormula.reset();
		hierarchyItemEditor.hide();
		list.refresh();
		hierarchySource.refresh();
		In2iGui.showMessage({text:'Menupunktet er slettet!',duration:2000});
	},
	
	/////////// Page properties //////////
	
	$success$pageLoaded : function(data) {
		this.activePage = data.id;
		pageFormula.setValues(data);
		pageEditor.show();
	},
	$click$cancelPage : function() {
		this.activePage = 0;
		pageFormula.reset();
		pageEditor.hide();
	},
	$click$savePage : function() {
		var values = pageFormula.getValues();
		values.id = this.activePage;
		In2iGui.request({url:'SavePage.php',onSuccess:'pageChanged',json:{data:values}});
	},
	$click$deletePage : function() {
		In2iGui.request({url:'DeletePage.php',onSuccess:'pageChanged',parameters:{id:this.activePage}});
	},
	$success$pageChanged : function(data) {
		this.activePage = 0;
		pageFormula.reset();
		pageEditor.hide();
		list.refresh();
		languageSource.refresh();
		hierarchySource.refresh();
	},
	
	////////////// New page /////////////
	
	$click$newPage : function() {
		templatePicker.reset();
		designPicker.reset();
		menuItemSelection.reset();
		frameSelection.reset();
		newPageFormula.reset();
		newPageWizard.goToStep(0);
		createPage.setEnabled(false);
		newPageBox.show();
	},
	
	$click$createPage : function() {
		var design = designPicker.getValue();
		var template = templatePicker.getValue();
		var frame = frameSelection.getValue();
		var menuItem = menuItemSelection.getValue();
		var form = newPageFormula.getValues();
		if (template===null) {
			newPageWizard.goToStep(0);
			In2iGui.showMessage({text:'Der er ikke valgt en skabelon',duration:2000});
		} else if (design===null) {
			newPageWizard.goToStep(1);
			In2iGui.showMessage({text:'Der er ikke valgt et design',duration:2000});
		} else if (frame===null) {
			newPageWizard.goToStep(2);
			In2iGui.showMessage({text:'Der er ikke valgt en grundopsætning',duration:2000});
		} else if (form.title=='') {
			newPageWizard.goToStep(4);
			In2iGui.showMessage({text:'Der er ikke udfyldt en titel',duration:2000});
			newPageTitle.focus();
		} else {
			var data = {design:design,template:template,frame:frame.value,menuItemId:menuItem.value,menuItemKind:menuItem.kind};
			n2i.override(data,form);
			if (menuItem!=null) {
				if (menuItem.kind=='hierarchyItem') {
				  	data.hierarchyItem = menuItem.value;
				} else if (menuItem.kind=='hierarchy') {
					data.hierarchy = menuItem.value;
				}
			}
			In2iGui.request({url:'CreatePage.php',onSuccess:'pageCreated',parameters:data});
		}
	},
	$success$pageCreated : function() {
		newPageBox.hide();
		list.refresh();
		languageSource.refresh();
		hierarchySource.refresh();
	},
	
	$selectionChanged$templatePicker : function() {
		newPageWizard.goToStep(1);
	},
	$selectionChanged$designPicker : function() {
		newPageWizard.goToStep(2);
	},
	$selectionChanged$frameSelection : function() {
		newPageWizard.goToStep(3);
	},
	$selectionChanged$menuItemSelection : function() {
		newPageWizard.goToStep(4);
	},
	$valueChanged$newPageTitle : function(value) {
		this.checkNewPage(value);
	},
	$click$noMenuItem : function() {
		menuItemSelection.reset();
		newPageWizard.goToStep(4);
	},
	checkNewPage : function(value) {
		createPage.setEnabled(value.length>0);
	}
});