hui.ui.listen({
	
	specialPageId : null,
	
	$click$newSpecialPage : function() {
		this.specialPageId = null;
		specialPageWindow.show();
		specialPageFormula.reset();
		deleteSpecialPage.setEnabled(false);
		specialPageFormula.focus();
	},
	$open : function(row) {
		this.loadSpecialPage(row.id);
	},
	loadSpecialPage : function(id) {
		specialPageFormula.reset();
		deleteSpecialPage.setEnabled(false);
		saveSpecialPage.setEnabled(false);
		hui.ui.request({
			parameters : {id:id},
			url : 'data/LoadSpecialPage.php',
			message : {start:{en:'Loading special page...', da:'Åbner speciel side...'},delay:300},
			$object : function(data) {
				specialPageFormula.setValues(data);
				specialPageWindow.show();
				deleteSpecialPage.setEnabled(true);
				saveSpecialPage.setEnabled(true);
				this.specialPageId = data.id;
				specialPageFormula.focus();
			}.bind(this)
		});
	},
	$click$cancelSpecialPage : function() {
		this.specialPageId = null;
		specialPageFormula.reset();
		specialPageWindow.hide();
	},
	$submit$specialPageFormula : function() {
		var values = specialPageFormula.getValues();
		if (!values.pageId || !values.type) {
			hui.ui.showMessage({text:{en:'Page and type is requied', da:'Side og type skal udfyldes'},icon:'common/warning',duration:2000});
			specialPageFormula.focus();
			return;
		}
		values.id = this.specialPageId;
		hui.ui.request({
			json : {data:values},
			url : 'actions/SaveSpecialPage.php',
			message : {start:{en:'Saving special page...', da:'Gemmer speciel side...'},delay:300},
			$success : function() {
				listSource.refresh();
			}.bind(this)
		});
		this.specialPageId = null;
		specialPageFormula.reset();
		specialPageWindow.hide();
	},
	$click$deleteSpecialPage : function() {
		deleteSpecialPage.setEnabled(false);
		hui.ui.request({
			parameters : { id : this.specialPageId },
			url : 'actions/DeleteSpecialPage.php',
			message : {start:{en:'Deleting special page...', da:'Sletter speciel side...'},delay:300},
			$success : function() {
				listSource.refresh();
			}.bind(this)
		});
		this.specialPageId = null;
		specialPageFormula.reset();
		specialPageWindow.hide();
	}

	
	
});