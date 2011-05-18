var partController = {
	$ready : function() {
		var field = this.field = hui.get('part_header_textarea');
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