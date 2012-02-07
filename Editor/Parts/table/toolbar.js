hui.ui.listen({
	
	$click$clean : function() {
		partToolbar.getMainController().clean();
	},
	
	$click$editSource : function() {
		partToolbar.getMainController().editSource();
	},
	
	$click$addRow : function() {
		partToolbar.getMainController().addRow();
	}
})