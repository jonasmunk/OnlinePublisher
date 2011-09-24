var mainController = {
	
	activePage : 0,
	dragDrop : [
		{drag:'page',drop:'language'},
		{drag:'page',drop:'subset'}
	],
	
	$ready : function() {
		if (window.parent!=window) {
			window.parent.baseController.changeSelection('tool:Sites');
		}
		var pageInfo = hui.location.getInt('pageInfo');
		if (pageInfo) {
			this.loadPage(pageInfo);
		}
	},
	
	//////////////// Search //////////////
	
	$valueChanged$search : function() {
		var value = selector.getValue();
		if (value.kind=='hierarchy' || value.kind=='hierarchyItem') {
			selector.setValue('all');
		}
	},
	
	///////////////// List ///////////////
	
	$selectionChanged$list : function(item) {
		if (item.kind=='page') {
			hui.ui.get('edit').setEnabled(true);
			hui.ui.get('info').enable();
			hui.ui.get('delete').enable();
			hui.ui.get('view').enable();			
		} else if (item.kind=='hierarchyItem') {
			var page = item.data && item.data.page;
			hui.ui.get('edit').setEnabled(page);
			hui.ui.get('info').enable();
			hui.ui.get('delete').enable();
			hui.ui.get('view').setEnabled(page);
		} else {
			hui.ui.get('edit').disable()
			hui.ui.get('info').disable()
			hui.ui.get('delete').disable()
			hui.ui.get('view').disable()
		}
	},
	$selectionReset$list : function() {
		hui.ui.get('edit').setEnabled(false);
		hui.ui.get('info').setEnabled(false);
		hui.ui.get('delete').setEnabled(false);
		hui.ui.get('view').setEnabled(false);
	},
	$clickIcon$list : function(info) {
		if (info.data.action=='previewPage') {
			previewer.position(info.node);
			previewer.show();
			previewFrame.setUrl('../../Services/Preview/MiniPreview.php?id='+info.data.id+'&mini=true');
		} else if (info.data.action=='viewPage') {
			document.location='../../Services/Preview/?id='+info.data.id;
		} else if (info.data.action=='editPage') {
			document.location='../../Template/Edit.php?id='+info.data.id;
		} else if (info.data.action=='pageInfo') {
			this.loadPage(info.data.id);
		}
	},
	
	/////////////// Toolbar //////////////
	
	$click$edit : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='page') {
			document.location='../../Template/Edit.php?id='+obj.id;
		}
	},
	$click$view : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='page') {
			document.location='../../Services/Preview/?id='+obj.id;
		}
	},
	$click$delete : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='page') {
			this.deletePage(obj.id);
		}
	},
	deletePage : function(id) {
		if (this.activePage==id) {
			pageFormula.reset();
			pageEditor.hide();
			pageFinder.hide();
			this.activePage = 0;
		}
		hui.ui.request({
			message : {start:'Sletter side...',delay:300,success:'Siden er nu slettet'},
			url : 'DeletePage.php',
			parameters : { id : id },
			onSuccess : function() {
				list.refresh();
				languageSource.refresh();
				hierarchySource.refresh();				
			}
		});
	},
	$click$info : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='page') {
			this.loadPage(obj.id);
		}
	},
	$listRowWasOpened$list : function(obj) {
		if (obj.kind=='page') {
			this.loadPage(obj.id);
		}
	},
	$drop$page$language : function(dragged,dropped) {
		hui.ui.request({
			message : {start:'Ændrer sprog...',delay:300},
			url : 'ChangeLanguage.php',
			json : {data : {id:dragged.id,language:dropped.value}},
			onSuccess : function() {
				hui.ui.showMessage({text:'Sproget er ændret',icon:'common/success',duration:2000});
				list.refresh();
				languageSource.refresh();
			}
		});
	},
	
	/////////// Page properties //////////
	
	loadPage : function(id) {
		hui.ui.request({
			message : {start : 'Åbner side...',delay:300},
			url : 'data/LoadPage.php',
			onSuccess : 'pageLoaded',
			parameters : {id:id}
		});
	},
	$success$pageLoaded : function(data) {
		var page = data.page;
		this.activePage = page.id;
		pageFormula.setValues(page);
		publishPage.setEnabled(page.changed>page.published);
		pageEditor.show();
		pageTranslationList.setUrl('data/PageTranslationList.php?page='+data.page.id);
	},
	$click$editPage : function() {
		document.location='../../Template/Edit.php?id='+this.activePage;
	},
	$click$viewPage : function() {
		document.location='../../Services/Preview/?id='+this.activePage;
	},
	$click$cancelPage : function() {
		this.activePage = 0;
		pageFormula.reset();
		pageEditor.hide();
		pageFinder.hide();
	},
	$click$savePage : function() {
		var values = pageFormula.getValues();
		values.id = this.activePage;
		hui.ui.request({
			message : {start : 'Gemmer side...',delay:300},
			url : 'SavePage.php',
			json : {data:values},
			onSuccess : function() {
				list.refresh();
				languageSource.refresh();
				hierarchySource.refresh();				
			}
		});
		this.activePage = 0;
		pageFormula.reset();
		pageEditor.hide();
		pageFinder.hide();
	},
	$click$deletePage : function() {
		this.deletePage(this.activePage);
	},
	
	$click$pageInfo : function() {
		pageInfoFragment.show();
		pageInfo.setSelected(true);
		pageInfo.element.blur();
		pageTranslationFragment.hide();
		pageTranslation.setSelected(false);
	},
	$userClosedWindow$pageEditor : function() {
		pageFinder.hide();
	},
	
	$click$pageTranslation : function() {
		pageInfoFragment.hide();
		pageInfo.setSelected(false);
		pageTranslationFragment.show();
		pageTranslation.setSelected(true);
		pageTranslation.element.blur();
	},
	$clickIcon$pageTranslationList : function(info) {
		hui.ui.confirmOverlay({element:info.node,text:'Er du sikker?',okText:'Ja, fjern',cancelText:'Nej',onOk : function() {
			hui.ui.request({
				message : {start:'Sletter oversættelse...',delay:300},
				url : 'data/DeletePageTranslation.php',
				parameters : {id:info.row.id},
				onSuccess : function() {
					pageTranslationList.refresh();
					publishPage.enable();
					list.refresh();
				}
			})
		}});
	},
	$click$addTranslation : function() {
		pageFinder.show({avoid:pageEditor.getElement()});
	},
	$listRowWasOpened$pageFinderList : function(row) {
		if (!this.activePage) {
			return;
		}
		hui.ui.request({
			message : {start:'Tilføjer oversættelse...',delay:300},
			url : 'data/AddPageTranslation.php',
			parameters : {page:this.activePage,translation:row.id},
			onSuccess : function() {
				pageTranslationList.refresh();
				publishPage.enable();
				list.refresh();
			}
		})
		pageFinder.hide();
	},
	$click$publishPage : function() {
		hui.ui.request({
			message : {start:'Udgiver side...',delay:300},
			url : '../../Services/Model/PublishPage.php',
			parameters : {id:this.activePage},
			onSuccess : function() {
				publishPage.disable();
				list.refresh();
			}
		})

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
		this._checkNewPage();
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
			hui.ui.request({
				message : {start:'Opretter side...',delay:300},
				url : 'data/CreatePage.php',
				onSuccess : 'pageCreated',
				parameters : data
			});
		}
	},
	$success$pageCreated : function(info) {
		newPageBox.hide();
		list.refresh();
		languageSource.refresh();
		hierarchySource.refresh();
		this.loadPage(info.id);
	},
	
	$selectionChanged$templatePicker : function() {
		newPageWizard.next();
		this._checkNewPage();
	},
	$selectionChanged$designPicker : function() {
		newPageWizard.next();
		this._checkNewPage();
	},
	$selectionChanged$frameSelection : function() {
		newPageWizard.next();
		this._checkNewPage();
	},
	$selectionChanged$menuItemSelection : function() {
		newPageWizard.next();
		this._checkNewPage();
		newPageTitle.focus();
	},
	$valueChanged$newPageTitle : function(value) {
		this._checkNewPage();
	},
	$click$noMenuItem : function() {
		menuItemSelection.reset();
		newPageWizard.next();
		newPageTitle.focus();
	},
	$stepChanged$newPageWizard : function() {
		this._checkNewPage();
	},
	_checkNewPage : function(value) {
		var templateSelected = templatePicker.getValue()!=null;
		var designSelected = designPicker.getValue()!=null;
		var frameSelected = frameSelection.getValue()!=null;
		var titleFilled = !newPageTitle.isBlank();
		//hui.log(hui.toJSON({templateSelected:templateSelected,designSelected:designSelected,frameSelected:frameSelected,titleFilled:titleFilled}));
		createPage.setEnabled(templateSelected && designSelected && frameSelected && titleFilled);
		newPagePrevious.setEnabled(!newPageWizard.isFirst());
		newPageNext.setEnabled(!newPageWizard.isLast());
	},
	$click$newPageCancel : function() {
		newPageBox.hide()
	},
	$click$newPagePrevious : function() {
		newPageWizard.previous()
		this._checkNewPage();
	},
	$click$newPageNext : function() {
		newPageWizard.next()
		this._checkNewPage();
	}
}

hui.ui.listen(mainController);