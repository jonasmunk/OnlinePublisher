var baseController = {
	$ready : function() {
		
	},
	changeSelection : function(key) {
		if (window['editToolbar']) {
			editToolbar.setSelection(key);
		}
		if (window['analyseToolbar']) {
			analyseToolbar.setSelection(key);
		}
		if (window['setupToolbar']) {
			setupToolbar.setSelection(key);
		}
	},
	goPublish : function() {
		dock.setUrl('Services/Publish/?close=../../Services/Start/');
	}
}
hui.ui.listen(baseController);