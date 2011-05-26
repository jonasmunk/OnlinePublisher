hui.ui.listen({
	$selectionWasOpened$selector : function(item) {
		if (item.kind=='calendarsource') {
			this.editSource(item.value);
			synchronizeSource.setEnabled(true);
		} else {
			synchronizeSource.setEnabled(false);
		}
	},
	$selectionChanged$selector : function(item) {
		synchronizeSource.setEnabled(item.kind=='calendarsource');
	},
	$listRowWasOpened$list : function(row) {
		if (row.kind=='calendarsource') {
			this.editSource(row.id);
		}
	},
	editSource : function(id) {
		hui.ui.request({parameters:{id:id},url:'../../Services/Model/LoadObject.php',onSuccess:'loadSource'});
	},
	
	// Source
	$success$loadSource : function(source) {
		this.sourceId = source.id;
		sourceFormula.setValues(source);
		deleteSource.setEnabled(true);
		sourceWindow.show();
	},
	$click$cancelSource : function() {
		sourceFormula.reset();
		sourceWindow.hide();
	},
	$click$saveSource : function() {
		var data = sourceFormula.getValues();
		data.id = this.sourceId;
		hui.ui.request({url:'data/SaveCalendarSource.php',onSuccess:'saveSource',json:{data:data}});
	},
	$success$saveSource : function() {
		this.sourceId = null;
		sourceFormula.reset();
		sourceWindow.hide();
		sourcesItemsSource.refresh();
		list.refresh();
	},
	$click$deleteSource : function() {
		hui.ui.request({url:'data/DeleteCalendarSource.php',onSuccess:'deleteSource',parameters:{id:this.sourceId}});
	},
	$success$deleteSource : function() {
		this.sourceId = null;
		sourceFormula.reset();
		sourceWindow.hide();
		sourcesItemsSource.refresh();
	},
	
	$click$newSource : function() {
		this.sourceId = null;
		sourceFormula.reset();
		sourceWindow.show();
		deleteSource.setEnabled(false);
	},
	
	$click$synchronizeSource : function() {
		var value = selector.getValue();
		if (value.kind=='calendarsource') {
			hui.ui.showMessage({text:'Synkroniserer kilde...'});
			hui.ui.request({url:'data/SyncCalendarSource.php',onSuccess:'synchronizeSource',onFailure:'synchronizeSource',parameters:{id:value.value}});
		}
	},
	$success$synchronizeSource : function() {
		hui.ui.hideMessage();
		list.refresh();
	},
	$failure$synchronizeSource : function() {
		hui.ui.showMessage({text:'Synkronisering fejlede!',duration:2000});
	}
});