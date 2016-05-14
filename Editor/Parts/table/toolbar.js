hui.ui.listen({
	
	$click$clean : function() {
		partToolbar.getMainController().clean();
	},
	
	$click$editSource : function() {
		partToolbar.getMainController().editSource();
	},
	
	$click$addRow : function() {
		partToolbar.getMainController().addRow();
	},
	
	$click$addColumn : function() {
		partToolbar.getMainController().addColumn();
	},
	
	$click$showInfo : function() {
		partToolbar.getMainController().showInfo();
	}
})