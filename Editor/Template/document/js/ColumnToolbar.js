var columnToolbar = {
	
	columnId : null,
	
	$click$cancel : function() {
		parent.Frame.EditorFrame.setUrl('Editor.php?column=0');
	},
	$click$delete : function() {
		parent.Frame.EditorFrame.setUrl('DeleteColumn.php?column='+this.columnId);
	},
	$click$save : function() {
		parent.Frame.EditorFrame.setUrl('UpdateColumn.php?width='+ui.get('width').getValue());
	},
	
	$valueChanged$width : function(value) {
		var column = parent.Frame.EditorFrame.getDocument().getElementById('column'+this.columnId);
		column.setAttribute('width',value);
	}
}

ui.listen(columnToolbar);