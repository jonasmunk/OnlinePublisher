var rowsController = {
	
	rowId : null,
	editedRow : null,
	
	editRow : function(rowId) {
		columnsController.reset();
		this.reset();
		this.rowId = rowId;
		var node = hui.get('row'+rowId);
		hui.cls.add(node,'editor_row_highlighted');
		this.editedRow = {
			id : this.columnId,
			style : node.getAttribute('style'),
			node : node
		}
		hui.ui.request({
			message : {start : {en:'Loading row...',da:'Åbner række...'},delay:300},
			url : 'data/LoadRow.php',
			parameters : { id : rowId },
			onJSON : function(obj) {
				rowWindow.show();
				rowFormula.setValues(obj);				
				return;
			}
		})
	},
	reset : function() {
		if (!this.editedRow) {
			return;
		}
		this.editedRow.node.setAttribute('style',this.editedRow.style);
		hui.cls.remove(this.editedRow.node,'editor_row_highlighted');
		this.editedRow = null;
		rowFormula.reset();
		rowWindow.hide();
	},
	$valuesChanged$rowFormula : function(values) {

		var node = this.editedRow.node;
		if (node) {
			node.style.marginTop = values.top;
			node.style.marginBottom = values.bottom;
		} else {
			hui.log('Row node not found');
		}
	},
	$click$cancelRow : function() {
		this.reset();
	},
	$submit$rowFormula : function(form) {
		var values = form.getValues();
		values.id = this.editedRow.id;
		hui.ui.request({
			url : 'data/UpdateRow.php',
			parameters : values,
			message : {start : {en:'Saving row...',da:'Gemmer række...'},delay:300},
			onSuccess : function() {
				hui.ui.showMessage({text:{en:'The row is saved',da:'Rækken er gemt'},duration:2000,icon:'common/success'});
				controller._markToolbarChanged();
			}.bind(this)
		});
		this.reset();
	}
	
}
hui.ui.listen(rowsController);