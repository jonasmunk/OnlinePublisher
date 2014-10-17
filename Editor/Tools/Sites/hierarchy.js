hui.ui.listen({
	
	dragDrop : [
		{drag:'hierarchyItem',drop:'hierarchyItem'},
		{drag:'hierarchyItem',drop:'hierarchy'}
	],
	

	$select$selector : function(item) {
		newHierarchyItem.setEnabled(item!=null && (item.kind=='hierarchy' || item.kind=='hierarchyItem'));
	},
	$open$selector : function(obj) {
		if (obj.kind=='hierarchyItem') {
			this.loadHierarchyItem(obj.value);
		} else if (obj.kind=='hierarchy') {
			this.loadHierarchy(obj.value);
		}
	},
	$clickIcon$list : function(info) {
		
		if (info.data.action=='moveItem') {
			hui.ui.request({
				message : {start : {en:'Moving menu item...', da:'Flytter menupunkt...'},delay : 300},
				url:'actions/MoveHierarchyItem.php',
				parameters:{id:info.row.id,direction:info.data.direction},
				$success:function() {
					list.refresh();
					selectionSource.refresh();
					hui.ui.tellContainers('modelChanged');
				}
			});
		} else if (info.data.action=='visitLink') {
			window.open(info.data.url);
		}		
	},
	$open$list : function(obj) {
		if (obj.kind=='hierarchyItem') {
			this.loadHierarchyItem(obj.id);
		}
	},
	$click$delete : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='hierarchyItem') {
			this.deleteHierarchyItem(obj.id);
		}
	},
	$click$view : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='hierarchyItem' && obj.data && obj.data.page) {
			document.location='../../Services/Preview/?id='+obj.data.page;
		}
	},
	$click$edit : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='hierarchyItem' && obj.data && obj.data.page) {
			document.location='../../Template/Edit.php?id='+obj.data.page;
		}
	},
	$click$info : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='hierarchyItem') {
			this.loadHierarchyItem(obj.id);
		}
		else if (obj.kind=='hierarchy') {
			this.loadHierarchy(obj.id);
		}
	},
	
	////////////////// Hierarchy //////////////////

	$click$newHierarchy : function() {
		this.activeHierarchy = 0;
		hierarchyFormula.reset();
		hierarchyEditor.show();
		deleteHierarchy.setEnabled(false);
		hierarchyFormula.focus();
	},
	loadHierarchy : function(id) {
		hui.ui.request({
			message : {start : {en:'Loading hierarchy...',da:'Åbner hierarki...'},delay : 300},
			url : 'data/LoadHierarchy.php',
			parameters : {id:id},
			onJSON : function(data) {
				this.activeHierarchy = data.id;
				hierarchyFormula.setValues(data);
				hierarchyEditor.show();
				deleteHierarchy.setEnabled(data.canDelete);
				hierarchyFormula.focus();
			}.bind(this)
		});
	},
	$submit$hierarchyFormula : function() {
		var values = hierarchyFormula.getValues();
		values.id = this.activeHierarchy;
		hui.ui.request({
			message : {start:{en:'Saving hierarchy...',da:'Gemmer hierarki...'},delay:300},
			json : {data:values},
			url : 'actions/SaveHierarchy.php',
			$success : function() {
				list.refresh();
				selectionSource.refresh();
				hui.ui.showMessage({text:{en:'The hierarchy is saved', da:'Hierarkiet er gemt'},duration:2000,icon:'common/success'});
			}
		});
		this.activeHierarchy = 0;
		hierarchyFormula.reset();
		hierarchyEditor.hide();
	},
	$click$cancelHierarchy : function() {
		this.activeHierarchy = 0;
		hierarchyFormula.reset();
		hierarchyEditor.hide();
	},
	$click$deleteHierarchy : function() {
		hui.ui.request({
			message : {start : {en:'Deleting hierarchy...', da:'Sletter hierarki...'},delay : 300},
			url : 'actions/DeleteHierarchy.php',
			parameters : {id : this.activeHierarchy},
			onFailure : function() {
				hui.ui.showMessage({text : {en:'The hierarchy could not be deleted', da:'Hierarkiet kunne ikke slettes'},icon : 'common/warning',duration : 2000});
			},
			$success : function() {
				list.refresh();
				selectionSource.refresh();
				hui.ui.showMessage({text : {en:'The hierarchy has been deleted', da:'Hierarkiet er slettet'},icon : 'common/success',duration : 2000});
			}
		});
		this.activeHierarchy = 0;
		hierarchyFormula.reset();
		hierarchyEditor.hide();
	},
	
	/////////// Hierarchy item drag/drop //////////
	
	$drop$hierarchyItem$hierarchyItem : function(dragged,dropped) {
		this.relocateHierarchyItem({move:dragged.id,targetItem:dropped.value || dropped.id});
	},
	$drop$hierarchyItem$hierarchy : function(dragged,dropped) {
		this.relocateHierarchyItem({move:dragged.id,targetHierarchy:dropped.value});
	},
	relocateHierarchyItem : function(parameters) {
		hui.ui.request({
			message : {start : {en:'Moving menu item...', da:'Flytter menupunkt...'},delay : 300},
			url : 'actions/RelocateHierarchyItem.php',
			parameters : parameters,
			onJSON : function(response) {
				if (response.success) {
					list.refresh();
					selectionSource.refresh();
					hui.ui.showMessage({text : {en:'The menu item has been moved', da:'Menupunktet er flyttet'},icon:'common/success',duration:3000});
				} else {
					hui.ui.showMessage({text:response.message,icon:'common/warning',duration:3000});
				}
			},
		});		
	},
	
	/////////// Hierarchy item properties //////////

	$click$newHierarchyItem : function() {
		var row = list.getFirstSelection();
		if (row) {
			
		} else {
		}
		this.activeHierarchyItem = 0;
		var sel = selector.getValue();
		this.newHierarchyItemParent = {kind:sel.kind,id:sel.value};
		
		hierarchyItemFormula.reset();
		hierarchyItemEditor.show();
		hierarchyItemFormula.focus();
		deleteHierarchyItem.disable();
	},
	loadHierarchyItem : function(id) {
		hui.ui.request({
			message : {start : {en:'Loading menu item...', da:'Åbner menupunkt...'},delay : 300},
			url : 'data/LoadHierarchyItem.php',
			$success : 'hierarchyItemLoaded',
			parameters : {id:id}
		});
	},
	$success$hierarchyItemLoaded : function(data) {
		this.activeHierarchyItem = data.id;
		hierarchyItemFormula.setValues({
			title : data.title,
			hidden : data.hidden,
			page : data.targetType=='page' || data.targetType=='pageref' ? data.targetValue : null,
			reference : data.targetType=='pageref',
			file : data.targetType=='file' ? data.targetValue : null,
			url : data.targetType=='url' ? data.targetValue : '',
			email : data.targetType=='email' ? data.targetValue : ''
		});
		hierarchyItemEditor.show();
		deleteHierarchyItem.setEnabled(data.canDelete);
	},
	$click$cancelHierarchyItem : function() {
		this.activeHierarchyItem = null;
		hierarchyItemFormula.reset();
		hierarchyItemEditor.hide();
	},
	$click$saveHierarchyItem : function() {
		var values = hierarchyItemFormula.getValues();
		values.id = this.activeHierarchyItem;
		var data = {
			title : values.title,
			hidden : values.hidden
		}
		if (this.activeHierarchyItem) {
			data.id = this.activeHierarchyItem;
		} else {
			data.parent = this.newHierarchyItemParent;
		}
		if (values.page) {
			data.targetType = values.reference ? 'pageref' : 'page';
			data.targetValue = values.page;
		} else if (values.file) {
			data.targetType = 'file';
			data.targetValue = values.file;
		} else if (values.url) {
			data.targetType = 'url';
			data.targetValue = values.url;
		} else if (values.email) {
			data.targetType = 'email';
			data.targetValue = values.email;
		} else {
			hui.ui.showMessage({text:{en:'The link is required', da:'Der skal vælges et link'},duration:2000,icon:'common/warning'});
			hierarchyItemPage.focus();
			return;
		}
		hui.ui.request({
			message : {start:{en:'Saving menu item...', da:'Gemmer menupunkt...'},delay:300},
			json : {data:data},
			url : 'actions/SaveHierarchyItem.php',
			$success : function() {
				list.refresh();
				selectionSource.refresh();
				hui.ui.showMessage({text:{en:'The menu item has been saved', da:'Menupunktet er gemt'},duration:2000,icon:'common/success'});
			}
		});
		this.activeHierarchyItem = 0;
		hierarchyItemFormula.reset();
		hierarchyItemEditor.hide();
	},
	
	////////////// Item editor /////////////
	
	$valueChanged$hierarchyItemPage : function() {
		hierarchyItemFile.reset();
		hierarchyItemURL.reset();
		hierarchyItemEmail.reset();
	},
	$valueChanged$hierarchyItemFile : function() {
		hierarchyItemPage.reset();
		hierarchyItemReference.reset();
		hierarchyItemURL.reset();
		hierarchyItemEmail.reset();
	},
	$valueChanged$hierarchyItemURL : function() {
		hierarchyItemPage.reset();
		hierarchyItemReference.reset();
		hierarchyItemFile.reset();
		hierarchyItemEmail.reset();
	},
	$valueChanged$hierarchyItemEmail : function() {
		hierarchyItemPage.reset();
		hierarchyItemReference.reset();
		hierarchyItemFile.reset();
		hierarchyItemURL.reset();
	},
	
	////////////// Deletion ////////////////
	
	deleteHierarchyItem : function(id) {
		hui.ui.request({
			message : {start : {en:'Deleting menu item...', da:'Sletter menupunkt...'},delay : 300},
			url : 'actions/DeleteHierarchyItem.php',
			parameters : {id : id},
			onFailure : function() {
				hui.ui.showMessage({text : {en:'The menu item could not be deleted', da:'Menupunktet kunne ikke slettes'},icon : 'common/warning',duration : 2000});
			},
			$success : function() {
				list.refresh();
				selectionSource.refresh();
				hui.ui.showMessage({text : {en:'The menu item has been deleted', da:'Menupunktet er slettet'},icon : 'common/success',duration : 2000});
			}
		});
		if (id==this.activeHierarchyItem) {
			this.activeHierarchyItem = 0;
			hierarchyItemFormula.reset();
			hierarchyItemEditor.hide();
		}
	},
	$click$deleteHierarchyItem : function() {
		this.deleteHierarchyItem(this.activeHierarchyItem);
	}

});