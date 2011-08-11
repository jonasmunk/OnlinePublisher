var baseController = {
	$ready : function() {
		
	},
	changeSelection : function(key) {
		if (editToolbar) {
			editToolbar.setSelection(key);
		}
		if (analyseToolbar) {
			analyseToolbar.setSelection(key);
		}
		if (setupToolbar) {
			setupToolbar.setSelection(key);
		}
	},
	goPublish : function() {
		dock.setUrl('Services/Publish/?close=../../Services/Start/');
	}
}
hui.ui.listen(baseController);