hui.ui.listen({
	
	$click$showInfo : function() {
		partToolbar.getMainController().showInfo();
	},
	
	$click$showPageInfo : function() {
		partToolbar.getMainController().showPageInfo();
	},
	
	$click$showSource : function() {
		partToolbar.getMainController().showSource();
	},
	
	$click$goPrevious : function() {
		partToolbar.getMainController().goPrevious();
	},
	
	$click$goNext : function() {
		partToolbar.getMainController().goNext();
	}
})