var columnToolbar = {
	
	columnId : null,
	
	$click$cancel : function() {
		parent.Frame.EditorFrame.setUrl('Editor.php?column=0');
	},
	$click$delete : function() {
		parent.Frame.EditorFrame.setUrl('DeleteColumn.php?column='+this.columnId);
	},
	$click$save : function() {
		var width = ui.get('width').getValue();
		parent.Frame.EditorFrame.setUrl('UpdateColumn.php?width='+width);
	},
	
	$valueChanged$width : function(value) {
		if (value==='min') {
			value='1%';
		} else if (value==='max') {
			value='100%';
		}
		var column = parent.Frame.EditorFrame.getDocument().getElementById('column'+this.columnId);
		column.setAttribute('width',value);
	}
}

ui.listen(columnToolbar);