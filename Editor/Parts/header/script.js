var partController = {
	$ready : function() {
		var field = this.field = hui.get('part_header_textarea');
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