hui.ui.listen({
	
	frameId : null,
	
	$click$newFrame : function() {
		this.frameId = null;
		frameWindow.show();
		frameFormula.reset();
		deleteFrame.setEnabled(false);
		frameFormula.focus();
	},
	$listRowWasOpened : function(row) {
		this.loadFrame(row.id);
	},
	loadFrame : function(id) {
		frameFormula.reset();
		deleteFrame.setEnabled(false);
		saveFrame.setEnabled(false);
		hui.ui.request({
			parameters : {id:id},
			url : 'data/LoadFrame.php',
			message : {start:'Åbner ramme...',delay:300},
			onJSON : function(data) {
				frameFormula.setValues(data.frame);
				topLinks.setValue(data.topLinks);
				bottomLinks.setValue(data.bottomLinks);
				frameWindow.show();
				deleteFrame.setEnabled(data.canRemove);
				saveFrame.setEnabled(true);
				this.frameId = data.frame.id;
				frameFormula.focus();
			}.bind(this)
		});
	},
	$click$saveFrame : function() {
		this._saveFrame();
	},
	$click$cancelFrame : function() {
		this.frameId = null;
		frameFormula.reset();
		frameWindow.hide();
	},
	$submit$frameFormula : function() {
		this._saveFrame();
	},
	_saveFrame : function() {
		var values = frameFormula.getValues();
		if (hui.isBlank(values.title) || values.hierarchyId==null) {
			hui.ui.showMessage({text:'Titel og hierarki skal udfyldes',icon:'common/warning',duration:2000});
			frameFormula.focus();
			return;
		}
		hui.ui.request({
			json : {
				id : this.frameId,
				frame : values,
				topLinks : topLinks.getValue(),
				bottomLinks : bottomLinks.getValue()
			},
			url : 'data/SaveFrame.php',
			message : {start:'Gemmer ramme...',delay:300},
			onSuccess : function() {
				listSource.refresh();
			}.bind(this)
		});
		this.frameId = null;
		frameFormula.reset();
		frameWindow.hide();
	},
	$click$deleteFrame : function() {
		deleteFrame.setEnabled(false);
		hui.ui.request({
			parameters : { id : this.frameId },
			url : 'data/DeleteFrame.php',
			message : {start:'Sletter ramme...',delay:300},
			onSuccess : function() {
				listSource.refresh();
			}.bind(this)
		});
		this.frameId = null;
		frameFormula.reset();
		frameWindow.hide();
	}
});