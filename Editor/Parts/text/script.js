var partController = {
	$ready : function() {
		var field = hui.get('PartTextTextarea');
		field.focus();
		field.select();
		this.resizer = new op.FieldResizer({field:field});
		this.resizer.resize(true);
	},
	syncSize : function() {
		this.resizer.resize();
	}
}

hui.ui.listen(partController);