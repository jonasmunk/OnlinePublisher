hui.ui.listen({
	
	specialPageId : null,
	
	$click$newSpecialPage : function() {
		this.specialPageId = null;
		specialPageWindow.show();
		specialPageFormula.reset();
		deleteSpecialPage.setEnabled(false);
		specialPageFormula.focus();
	},
	$listRowWasOpened : function(row) {
		this.loadSpecialPage(row.id);
	},
	loadSpecialPage : function(id) {
		specialPageFormula.reset();
		deleteSpecialPage.setEnabled(false);
		saveSpecialPage.setEnabled(false);
		hui.ui.request({
			parameters : {id:id},
			url : 'data/LoadSpecialPage.php',
			message : {start:'Åbner speciel side...',delay:300},
			onJSON : function(data) {
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
			hui.ui.showMessage({text:'Side og type skal udfyldes',icon:'common/warning',duration:2000});
			specialPageFormula.focus();
			return;
		}
		values.id = this.specialPageId;
		hui.ui.request({
			json : {data:values},
			url : 'data/SaveSpecialPage.php',
			message : {start:'Gemmer speciel side...',delay:300},
			onSuccess : function() {
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
			url : 'data/DeleteSpecialPage.php',
			message : {start:'Sletter speciel side...',delay:300},
			onSuccess : function() {
				listSource.refresh();
			}.bind(this)
		});
		this.specialPageId = null;
		specialPageFormula.reset();
		specialPageWindow.hide();
	}

	
	
});