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
		hui.log('Selection size: '+count);
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
	$click$delete : function() {
		var ids = list.getSelectionIds();
		this._deleteObjects(ids);
	},
	$valueChanged$changeKind : function(value) {
		var ids = list.getSelectionIds();
		if (ids) {
			hui.ui.request({
				url : 'actions/ChangeKind.php',
				json : { ids : ids , kind:value},
				$success : function(obj) {
					list.refresh();
					sidebarSource.refresh();
				}.bind(this)
			})		
		}
		changeKind.reset();
	},
	
	_loadIssue : function(id) {
		hui.ui.request({
			url : 'data/LoadIssue.php',
			parameters : { id : id },
			$object : function(obj) {
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
			$success : function() {
				this.clearIssue();
				this.refresh();
			}.bind(this)
		})
	},
	$click$deleteIssue : function() {
		this._deleteObjects([this._issueId]);
	},
	_deleteObjects : function(ids) {
		hui.ui.request({
			url : '../../Services/Model/DeleteObjects.php',
			json : {ids : ids},
			$success : function() {
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