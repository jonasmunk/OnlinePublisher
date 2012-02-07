hui.ui.listen({
	$ready : function() {
	},
	
	_issueId : null,
	
	$listRowWasOpened$list : function(row) {
		hui.ui.request({
			url : 'data/LoadIssue.php',
			parameters : { id : row.id },
			onJSON : function(obj) {
				this._issueId = obj.id;
				issueFormula.setValues(obj);
				issueWindow.show();				
			}.bind(this)
		})
	},
	$click$cancelIssue : function() {
		this.clearIssue();
	},
	$submit$issueFormula : function() {
		var values = issueFormula.getValues();
		values.id = this._issueId;
		hui.ui.request({
			url : 'data/SaveIssue.php',
			json : {data : values},
			onSuccess : function() {
				list.refresh();
				this.clearIssue();
			}.bind(this)
		})
	},
	$click$deleteIssue : function() {
		hui.ui.request({
			url : 'data/DeleteIssue.php',
			parameters : {id : this._issueId},
			onSuccess : function() {
				list.refresh();
				this.clearIssue();
			}.bind(this)
		})
	},
	clearIssue : function() {
		this._issueId = null;
		issueFormula.reset();
		issueWindow.hide();
	}
});