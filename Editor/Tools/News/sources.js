ui.listen({
	
	sourceId : null,
	
	$click$newSource : function() {
		this.sourceId = null;
		sourceFormula.reset();
		deleteSource.setEnabled(false);
		sourceWindow.show();
		sourceFormula.focus();
	},
	$click$cancelSource : function() {
		this.sourceId = null;
		sourceFormula.reset();
		sourceWindow.hide();
	},
	$submit$sourceFormula : function() {
		var values = sourceFormula.getValues();
		values.id = this.sourceId;
		if (n2i.isBlank(values.title)) {
			ui.showMessage({text:'Du skal angive en titel',duration:2000});
			sourceFormula.focus();
		} else {
			ui.request({json:{data:values},url:'SaveSource.php',onSuccess:'sourceSaved'});
		}
	},
	$success$sourceSaved : function() {
		sourcesSource.refresh();
		sourceFormula.reset();
		sourceWindow.hide();
	},
	$selectionWasOpened$selector : function(item) {
		if (item.kind=='newssource') {
			ui.request({parameters:{id:item.value},url:'../../Services/Model/LoadObject.php',onSuccess:'sourceLoaded'});
		}
	},
	$success$sourceLoaded : function(data) {
		this.sourceId = data.id;
		sourceFormula.setValues(data);
		deleteSource.setEnabled(true);
		sourceWindow.show();
		sourceFormula.focus();
	},
	$click$deleteSource : function() {
		ui.request({json:{data:{id:this.sourceId}},url:'../../Services/Model/DeleteObject.php',onSuccess:'deleteSource'});
	},
	$success$deleteSource : function() {
		sourcesSource.refresh();
		this.sourceId = null;
		sourceFormula.reset();
		sourceWindow.hide();
	}
	
});