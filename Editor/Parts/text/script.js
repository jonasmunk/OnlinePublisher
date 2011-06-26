var partController = {
	$ready : function() {
		var field = hui.get('PartTextTextarea');
		this.resizer = new op.FieldResizer({field:field});
		this.resizer.resize(true);
		field.focus();
		field.select();
	},
	syncSize : function() {
		this.resizer.resize();
	}
}

hui.ui.listen(partController);