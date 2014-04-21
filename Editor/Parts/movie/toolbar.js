hui.ui.listen({
	$ready : function() {
		this.form = partToolbar.partForm;
	},
	$click$chooseFile : function() {
		partToolbar.getMainController().showFinder();
	},
	$click$addFile : function() {
		partToolbar.getMainController().addFile();
	},
	$click$info : function() {
		partToolbar.getMainController().showInfo();
	}/*,
	$valueChanged$text : function(str) {
		partToolbar.partForm.text.value = str;
		partToolbar.getMainController().preview(true);
	}*/
});