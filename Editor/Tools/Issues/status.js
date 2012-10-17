hui.ui.listen({
	
	$ready : function() {
		pages.next();
	},
	_statusId : null,
	
	$click$newStatus : function() {
		this._statusId = null;
		statusFormula.reset();
		statusWindow.show();
		statusFormula.focus();
	},
	$open$selector : function(obj) {
		if (obj.kind=='issuestatus') {
			this._loadStatus(obj.value);
		}
	},
	
	_loadStatus : function(id) {
		hui.ui.request({
			url : '../../Services/Model/LoadObject.php',
			parameters : { id : id },
			onJSON : function(obj) {
				this._statusId = obj.id;
				statusFormula.setValues(obj);
				statusWindow.show();
				statusFormula.focus();
			}.bind(this)
		})		
	},
	
	$click$cancelStatus : function() {
		this.clearStatus();
	},
	$submit$statusFormula : function() {
		var values = statusFormula.getValues();
		values.id = this._statusId;
		hui.ui.request({
			url : 'actions/SaveStatus.php',
			json : {data : values},
			onSuccess : function() {
				this.clearStatus();
				this.refresh();
			}.bind(this)
		})
	},
	$click$deleteStatus : function() {
		hui.ui.request({
			url : 'actions/DeleteStatus.php',
			parameters : {id : this._statusId},
			onSuccess : function() {
				this.clearStatus();
				this.refresh();
			}.bind(this)
		})
	},
	clearStatus : function() {
		this._statusId = null;
		statusFormula.reset();
		statusWindow.hide();
	},
	refresh : function() {
		list.refresh();
		sidebarSource.refresh();
	}
});
