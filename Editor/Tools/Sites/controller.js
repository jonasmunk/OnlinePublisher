hui.ui.listen({
	
	activePage : 0,
	dragDrop : [
		{drag:'page',drop:'language'},
		{drag:'page',drop:'subset'}
	],
	
	///////////////// List ///////////////
	
	$selectionChanged$selector : function(item) {
		hui.ui.get('newSubPage').setEnabled(item.kind=='hierarchy' || item.kind=='hierarchyItem');
	},
	$selectionChanged$list : function(item) {
		hui.ui.get('edit').setEnabled(item.kind=='page');
		hui.ui.get('info').setEnabled(true);
		hui.ui.get('delete').setEnabled(item.kind=='page');
		hui.ui.get('view').setEnabled(item.kind=='page');
	},
	$selectionReset$list : function() {
		hui.ui.get('edit').setEnabled(false);
		hui.ui.get('info').setEnabled(false);
		hui.ui.get('delete').setEnabled(false);
		hui.ui.get('view').setEnabled(false);
	},
	$click$edit : function() {
		var obj = list.getFirstSelection();
		document.location='../../Template/Edit.php?id='+obj.id;
	},
	$click$view : function() {
		var obj = list.getFirstSelection();
		document.location='../../Services/Preview/?id='+obj.id;
	},
	$click$delete : function() {
		hui.ui.confirm({
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
				hui.ui.request({url:'DeletePage.php',onSuccess:'pageDeleted',parameters:{id:obj.id}});
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
			hui.ui.request({url:'LoadPage.php',onSuccess:'pageLoaded',json:{data:obj}});
		} else if (obj.kind=='hierarchyItem') {
			this.loadHierarchyItem(obj.id);
		}
	},
	$click$newSubPage : function() {
		hui.ui.showMessage({text:'Ikke implementeret',duration:2000});
	},
	$listRowWasOpened$list : function(obj) {
		if (obj.kind=='page') {
			hui.ui.request({url:'LoadPage.php',onSuccess:'pageLoaded',json:{data:obj}});
		} else if (obj.kind=='hierarchyItem') {
			this.loadHierarchyItem(obj.id);
		}
	},
	$drop$page$language : function(dragged,dropped) {
		hui.ui.request({url:'ChangeLanguage.php',onSuccess:'languageChanged',json:{data:{id:dragged.id,language:dropped.value}}});
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
		hui.ui.request({url:'LoadHierarchyItem.php',onSuccess:'hierarchyItemLoaded',parameters:{id:id}});
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
		hui.ui.request({url:'SaveHierarchyItem.php',onSuccess:'hierarchyItemChanged',json:{data:values}});
	},
	$success$hierarchyItemChanged : function() {
		this.activeHierarchyItem = 0;
		hierarchyItemFormula.reset();
		hierarchyItemEditor.hide();
		list.refresh();
		hierarchySource.refresh();
		hui.ui.showMessage({text:'Menupunktet er opdateret!',duration:2000});
	},
	$click$deleteHierarchyItem : function() {
		hui.ui.request({url:'DeleteHierarchyItem.php',onSuccess:'hierarchyItemDeleted',parameters:{id:this.activeHierarchyItem}});
	},
	$success$hierarchyItemDeleted : function() {
		this.activeHierarchyItem = 0;
		hierarchyItemFormula.reset();
		hierarchyItemEditor.hide();
		list.refresh();
		hierarchySource.refresh();
		hui.ui.showMessage({text:'Menupunktet er slettet!',duration:2000});
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
		hui.ui.request({url:'SavePage.php',onSuccess:'pageChanged',json:{data:values}});
	},
	$click$deletePage : function() {
		hui.ui.request({url:'DeletePage.php',onSuccess:'pageChanged',parameters:{id:this.activePage}});
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
		var menuItem = menuItemSelection.getValue() || {};
		var form = newPageFormula.getValues();
		if (template===null) {
			newPageWizard.goToStep(0);
			hui.ui.showMessage({text:'Der er ikke valgt en skabelon',duration:2000});
		} else if (design===null) {
			newPageWizard.goToStep(1);
			hui.ui.showMessage({text:'Der er ikke valgt et design',duration:2000});
		} else if (frame===null) {
			newPageWizard.goToStep(2);
			hui.ui.showMessage({text:'Der er ikke valgt en grundopsætning',duration:2000});
		} else if (form.title=='') {
			newPageWizard.goToStep(4);
			hui.ui.showMessage({text:'Der er ikke udfyldt en titel',duration:2000});
			newPageTitle.focus();
		} else {
			var data = {design:design,template:template,frame:frame.value,menuItemId:menuItem.value,menuItemKind:menuItem.kind};
			hui.override(data,form);
			if (menuItem!=null) {
				if (menuItem.kind=='hierarchyItem') {
				  	data.hierarchyItem = menuItem.value;
				} else if (menuItem.kind=='hierarchy') {
					data.hierarchy = menuItem.value;
				}
			}
			hui.ui.request({url:'CreatePage.php',onSuccess:'pageCreated',parameters:data});
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