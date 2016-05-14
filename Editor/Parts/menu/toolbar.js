hui.ui.listen({
	$ready : function() {
		this.form = partToolbar.partForm;
		variant.setValue(this.form.variant.value);
		depth.setValue(this.form.depth.value);
	},
	$valueChanged$depth : function(value) {
		partToolbar.partForm.depth.value = value;
		partToolbar.getMainController().preview(true);
	},
	$valueChanged$variant : function(value) {
		partToolbar.partForm.variant.value = value;
		partToolbar.getMainController().preview(true);
	}
});