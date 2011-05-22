hui.ui.listen({
	
	blueprintId : null,
	
	$click$newBlueprint : function() {
		this.blueprintId = null;
		blueprintWindow.show();
		blueprintFormula.reset();
		deleteBlueprint.setEnabled(false);
		blueprintFormula.focus();
	},
	$listRowWasOpened : function(row) {
		this.loadBlueprint(row.id);
	},
	loadBlueprint : function(id) {
		var data = {id:id};
		blueprintFormula.reset();
		deleteBlueprint.setEnabled(false);
		saveBlueprint.setEnabled(false);
		hui.ui.request({
			json : {data:data},
			url : '../../Services/Model/LoadObject.php',
			message : {start:'Åbner skabelon...',delay:300},
			onJSON : function(data) {
				blueprintFormula.setValues(data);
				blueprintWindow.show();
				deleteBlueprint.setEnabled(true);
				saveBlueprint.setEnabled(true);
				this.blueprintId = data.id;
				blueprintFormula.focus();
			}.bind(this)
		});
	},
	$click$cancelBlueprint : function() {
		this.blueprintId = null;
		blueprintFormula.reset();
		blueprintWindow.hide();
	},
	$submit$blueprintFormula : function() {
		var values = blueprintFormula.getValues();
		if (hui.isBlank(values.title) || !values.designId || !values.frameId || !values.templateId) {
			hui.ui.showMessage({text:'Udfyld venligst alle felter',icon:'common/warning',duration:2000});
			blueprintFormula.focus();
			return;
		}
		values.id = this.blueprintId;
		hui.ui.request({
			json : {data:values},
			url : 'SaveBlueprint.php',
			message : {start:'Gemmer skabelon...',delay:300},
			onSuccess : function() {
				blueprintFormula.reset();
				blueprintWindow.hide();
				listSource.refresh();
			}.bind(this)
		});
	},
	$click$deleteBlueprint : function() {
		deleteBlueprint.setEnabled(false);
		hui.ui.request({
			json : {data:{id:this.blueprintId}},
			url : '../../Services/Model/DeleteObject.php',
			message : {start:'Sletter skabelon...',delay:300},
			onSuccess : function() {
				this.blueprintId = null;
				blueprintFormula.reset();
				blueprintWindow.hide();
				listSource.refresh();
			}.bind(this)
		});
	}

	
	
});