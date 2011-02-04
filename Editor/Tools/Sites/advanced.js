ui.listen({
	
	$click$advanced : function() {
		advancedBox.show();
		advancedFrame.setUrl('http://apple.com/');
	},
	
	$click$advancedSpecialPages : function() {
		advancedSpecialPages.setSelected(true);
		advancedTemplates.setSelected(false);
		advancedFrame.setUrl('http://apple.com/');
	},
	$click$advancedTemplates : function() {
		advancedTemplates.setSelected(true);
		advancedSpecialPages.setSelected(false);
		advancedFrame.setUrl('http://microsoft.com/');
	}
	
	
});