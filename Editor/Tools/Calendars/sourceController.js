hui.ui.listen({
	$open$selector : function(item) {
		if (item.kind=='calendarsource') {
			this.editSource(item.value);
			synchronizeSource.setEnabled(true);
		} else {
			synchronizeSource.setEnabled(false);
		}
	},
	$select$selector : function(item) {
		synchronizeSource.setEnabled(item.kind=='calendarsource');
	},
	$open$list : function(row) {
		if (row.kind=='calendarsource') {
			this.editSource(row.id);
		}
	},
	editSource : function(id) {
		sourceFormula.reset();
		hui.ui.request({
			message : {start:{en:'Loading source...',da:'Henter kilde...'},delay:300},
			parameters : {id:id},
			url : '../../Services/Model/LoadObject.php',
			$success : 'loadSource'
		});
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
		hui.ui.request({url:'actions/SaveCalendarSource.php',$success:'saveSource',json:{data:data}});
	},
	$success$saveSource : function() {
		this.sourceId = null;
		sourceFormula.reset();
		sourceWindow.hide();
		sourcesItemsSource.refresh();
		list.refresh();
	},
	$click$deleteSource : function() {
		hui.ui.request({url:'actions/DeleteCalendarSource.php',$success:'deleteSource',parameters:{id:this.sourceId}});
	},
	$success$deleteSource : function() {
		this.sourceId = null;
		sourceFormula.reset();
		sourceWindow.hide();
		sourcesItemsSource.refresh();
		list.refresh();
	},
	
	$click$newSource : function() {
		this.sourceId = null;
		sourceFormula.reset();
		sourceWindow.show();
		deleteSource.setEnabled(false);
		sourceFormula.focus();
	},
	
	$click$synchronizeSource : function() {
		var value = selector.getValue();
		if (value.kind=='calendarsource') {
			hui.ui.showMessage({text:{en:'Synchronizing source...',da:'Synkroniserer kilde...'}});
			hui.ui.request({url:'actions/SyncCalendarSource.php',$success:'synchronizeSource',onFailure:'synchronizeSource',parameters:{id:value.value}});
		}
	},
	$success$synchronizeSource : function() {
		hui.ui.hideMessage();
		list.refresh();
	},
	$failure$synchronizeSource : function() {
		hui.ui.showMessage({text:{en:'Synchronization failed',da:'Synkronisering fejlede'},duration:2000});
	}
});