hui.ui.listen({
	$ready : function() {
		hui.ui.tellContainers('changeSelection','tool:Issues');
	},
	
	_issueId : null,
	
	$open$list : function(row) {
		this._loadIssue(row.id);
	},
	$select$list : function() {
		var count = list.getSelectionSize();
		hui.ui.get('delete').setEnabled(count>0);
		hui.ui.get('info').setEnabled(count==1);
	},
	
	$click$addIssue : function() {
		this._issueId = null;
		issueFormula.reset();
		issueWindow.show();
		issueFormula.focus();
	},
	$valueChanged$search : function() {
		selector.setValue('all');
	},
	$click$info : function() {
		var row = list.getFirstSelection();
		if (row) {
			this._loadIssue(row.id);			
		}
	},
	
	_loadIssue : function(id) {
		hui.ui.request({
			url : 'data/LoadIssue.php',
			parameters : { id : id },
			onJSON : function(obj) {
				this._issueId = obj.id;
				issueFormula.setValues(obj);
				issueWindow.show();			
				issueFormula.focus();	
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
			url : 'actions/SaveIssue.php',
			json : {data : values},
			onSuccess : function() {
				this.clearIssue();
				this.refresh();
			}.bind(this)
		})
	},
	$click$deleteIssue : function() {
		hui.ui.request({
			url : 'actions/DeleteIssue.php',
			parameters : {id : this._issueId},
			onSuccess : function() {
				this.clearIssue();
				this.refresh();
			}.bind(this)
		})
	},
	clearIssue : function() {
		this._issueId = null;
		issueFormula.reset();
		issueWindow.hide();
	},
	refresh : function() {
		list.refresh();
		sidebarSource.refresh();
	}
});