hui.ui.listen({
	
	blueprintId : null,
	
	$click$newBlueprint : function() {
		this.blueprintId = null;
		blueprintWindow.show();
		blueprintFormula.reset();
		deleteBlueprint.setEnabled(false);
		blueprintFormula.focus();
	},
	$open : function(row) {
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
			message : {start:{en:'Loading template...', da:'Åbner skabelon...'},delay:300},
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
			hui.ui.showMessage({text:{en:'Please fill in all fields', da:'Udfyld venligst alle felter'},icon:'common/warning',duration:2000});
			blueprintFormula.focus();
			return;
		}
		values.id = this.blueprintId;
		hui.ui.request({
			json : {data:values},
			url : 'actions/SaveBlueprint.php',
			message : {start:{en:'Saving template...', da:'Gemmer skabelon...'},delay:300},
			onSuccess : function() {
				listSource.refresh();
			}.bind(this)
		});
		this.blueprintId = null;
		blueprintFormula.reset();
		blueprintWindow.hide();
	},
	$click$deleteBlueprint : function() {
		deleteBlueprint.setEnabled(false);
		hui.ui.request({
			json : {data:{id:this.blueprintId}},
			url : '../../Services/Model/DeleteObject.php',
			message : {start:{en:'Deleting template...', da:'Sletter skabelon...'},delay:300},
			onSuccess : function() {
				listSource.refresh();
			}.bind(this)
		});
		this.blueprintId = null;
		blueprintFormula.reset();
		blueprintWindow.hide();
	}

	
	
});