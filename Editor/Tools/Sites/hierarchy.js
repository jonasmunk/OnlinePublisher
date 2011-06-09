hui.ui.listen({
	
	dragDrop : [
		{drag:'hierarchyItem',drop:'hierarchyItem'},
		{drag:'hierarchyItem',drop:'hierarchy'}
	],
	

	$selectionChanged$selector : function(item) {
		newHierarchyItem.setEnabled(item.kind=='hierarchy' || item.kind=='hierarchyItem');
	},
	$selectionWasOpened : function(obj) {
		if (obj.kind=='hierarchyItem') {
			this.loadHierarchyItem(obj.value);
		} else if (obj.kind=='hierarchy') {
			this.loadHierarchy(obj.value);
		}
	},
	$clickIcon$list : function(row,data) {
		if (data.action=='pageInfo') {
			mainController.loadPage(data.id);
		} else if (data.action=='moveItem') {
			hui.ui.request({
				message : {start : 'Flytter menupunkt...',delay : 300},
				url:'MoveHierarchyItem.php',
				parameters:{id:row.id,direction:data.direction},
				onSuccess:function() {
					list.refresh();
					hierarchySource.refresh();
				}
			});
		}		
	},
	$listRowWasOpened$list : function(obj) {
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
			message : {start : 'Åbner hierarki...',delay : 300},
			url : 'LoadHierarchy.php',
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
			message : {start:'Gemmer hierarki...',delay:300},
			json : {data:values},
			url : 'SaveHierarchy.php',
			onSuccess : function() {
				list.refresh();
				hierarchySource.refresh();
				hui.ui.showMessage({text:'Hierarkiet er gemt',duration:2000,icon:'common/success'});
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
			message : {start : 'Sletter hierarki...',delay : 300},
			url : 'DeleteHierarchy.php',
			parameters : {id : this.activeHierarchy},
			onFailure : function() {
				hui.ui.showMessage({text : 'Hierarkiet kunne ikke slettes',icon : 'common/warning',duration : 2000});
			},
			onSuccess : function() {
				list.refresh();
				hierarchySource.refresh();
				hui.ui.showMessage({text : 'Hierarkiet er slettet',icon : 'common/success',duration : 2000});
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
			message : {start : 'Flytter menupunkt...',delay : 300},
			url : 'RelocateHierarchyItem.php',
			parameters : parameters,
			onJSON : function(response) {
				if (response.success) {
					list.refresh();
					hierarchySource.refresh();
					hui.ui.showMessage({text : 'Menupunktet er flyttet',icon:'common/success',duration:3000});
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
			message : {start : 'Åbner menupunkt...',delay : 300},
			url : 'LoadHierarchyItem.php',
			onSuccess : 'hierarchyItemLoaded',
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
			hui.ui.showMessage({text:'Der skal vælges et link',duration:2000,icon:'common/warning'});
			hierarchyItemPage.focus();
			return;
		}
		hui.ui.request({
			message : {start:'Gemmer menupunkt...',delay:300},
			json : {data:data},
			url : 'SaveHierarchyItem.php',
			onSuccess : function() {
				list.refresh();
				hierarchySource.refresh();
				hui.ui.showMessage({text:'Menupunktet er gemt',duration:2000,icon:'common/success'});
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
			message : {start : 'Sletter menupunkt...',delay : 300},
			url : 'DeleteHierarchyItem.php',
			parameters : {id : id},
			onFailure : function() {
				hui.ui.showMessage({text : 'Menupunktet kunne ikke slettes',icon : 'common/warning',duration : 2000});
			},
			onSuccess : function() {
				list.refresh();
				hierarchySource.refresh();
				hui.ui.showMessage({text : 'Menupunktet er slettet',icon : 'common/success',duration : 2000});
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