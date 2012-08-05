hui.ui.listen({
	
	sourceId : null,
	
	$select$selector : function(item) {
		if (item.kind=='newssource') {
			sourceHeader.setText(item.title);
			this.updateSourceInfo(item.value);
		}
	},
	updateSourceInfo : function(id) {
		sourceText.setText();
		hui.ui.request({
			parameters : {id:id},
			url : '../../Services/Model/LoadObject.php',
			onJSON : function(obj) {
				sourceText.setText((hui.ui.language=='da' ? ' - synkroniseret: ' : ' - synchronized: ')+new Date(obj.synchronized*1000));
			}
		});
	},
	$click$synchronize : function() {
		var item = selector.getValue();
		synchronize.disable();
		hui.ui.request({
			message : {start:{en:'Synchronizing source',da:'Synkroniserer kilde...'},success:{en:'The source has been synchronized',da:'Kilden er nu synkroniseret'}},
			url : 'actions/SyncSource.php',
			parameters : {id:item.value},
			onSuccess : function() {
				list.refresh();
				synchronize.enable();
				this.updateSourceInfo(item.value);
			}.bind(this)
		});
		
	},
	
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
			hui.ui.showMessage({text:{en:'The title is required',da:'Titlen er krævet'},duration:2000});
			sourceFormula.focus();
		} else {
			hui.ui.request({
				json : {data:values},
				url : 'actions/SaveSource.php',
				message : {start:{en:'Saving source...',da:'Gemmer kilde...'},delay:300},
				onSuccess : function() {
					sourcesSource.refresh();
				}
			});
			sourceFormula.reset();
			sourceWindow.hide();
		}
	},
	$click$sourceInfo : function() {
		var item = selector.getValue();
		this.loadSource(item.value);
	},
	$selectionWasOpened$selector : function(item) {
		if (item.kind=='newssource') {
			this.loadSource(item.value);
		}
	},
	loadSource : function(id) {
		hui.ui.request({
			parameters : {id:id},
			url : '../../Services/Model/LoadObject.php',
			onSuccess : 'sourceLoaded',
			message : {start:{en:'Loading source...',da:'Åbner kilde...'},delay:300}
		});
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
			message : {start:{en:'Deleting source...',da:'Sletter kilde...'},delay:300}
		});
	},
	$success$deleteSource : function() {
		sourcesSource.refresh();
		this.sourceId = null;
		sourceFormula.reset();
		sourceWindow.hide();
	}
	
});