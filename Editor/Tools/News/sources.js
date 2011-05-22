hui.ui.listen({
	
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
		if (hui.isBlank(values.title)) {
			hui.ui.showMessage({text:'Du skal angive en titel',duration:2000});
			sourceFormula.focus();
		} else {
			hui.ui.request({
				json:{data:values},
				url:'SaveSource.php',
				onSuccess:'sourceSaved',
				message:{start:'Gemmer kilde...',delay:300}
			});
		}
	},
	$success$sourceSaved : function() {
		sourcesSource.refresh();
		sourceFormula.reset();
		sourceWindow.hide();
	},
	$selectionWasOpened$selector : function(item) {
		if (item.kind=='newssource') {
			hui.ui.request(
				{parameters:{id:item.value},
				url:'../../Services/Model/LoadObject.php',
				onSuccess:'sourceLoaded',
				message:{start:'Åbner kilde...',delay:300}
			});
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
		hui.ui.request({
			json : {data:{id:this.sourceId}},
			url : '../../Services/Model/DeleteObject.php',
			onSuccess : 'deleteSource',
			message : {start:'Sletter kilde...',delay:300}
		});
	},
	$success$deleteSource : function() {
		sourcesSource.refresh();
		this.sourceId = null;
		sourceFormula.reset();
		sourceWindow.hide();
	}
	
});