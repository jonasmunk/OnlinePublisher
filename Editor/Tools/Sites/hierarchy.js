hui.ui.listen({
	
	dragDrop : [
		{drag:'hierarchyItem',drop:'hierarchyItem'},
		{drag:'hierarchyItem',drop:'hierarchy'}
	],
	

	$selectionWasOpened : function(obj) {
		if (obj.kind=='hierarchyItem') {
			this.loadHierarchyItem(obj.value);
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

	/////////// Hierarchy item properties //////////
	
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
			page : data.targetType=='page' ? data.targetValue : null,
			file : data.targetType=='file' ? data.targetValue : null,
			url : data.targetType=='url' ? data.targetValue : '',
			email : data.targetType=='url' ? data.targetValue : ''
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
		hui.ui.request({
			message : {start:'Gemmer menupunkt...',delay:300},
			json : {data:values},
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