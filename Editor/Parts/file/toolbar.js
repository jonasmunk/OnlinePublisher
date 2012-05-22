hui.ui.listen({
	$click$chooseFile : function() {
		partToolbar.getMainController().showFinder();
	},
	$click$addFile : function() {
		partToolbar.getMainController().addFile();
	}
});