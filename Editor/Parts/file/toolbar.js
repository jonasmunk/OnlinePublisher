hui.ui.listen({
	$ready : function() {
		this.form = partToolbar.partForm;
		text.setValue(this.form.text.value);
	},
	$click$chooseFile : function() {
		partToolbar.getMainController().showFinder();
	},
	$click$addFile : function() {
		partToolbar.getMainController().addFile();
	},
	$valueChanged$text : function(str) {
		partToolbar.partForm.text.value = str;
		partToolbar.getMainController().preview(true);
	}
});