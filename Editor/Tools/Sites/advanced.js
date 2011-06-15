hui.ui.listen({
	
	$click$advanced : function() {
		advancedBox.show();
		advancedFrame.setUrl('specialpages.php');
	},
	
	$click$advancedSpecialPages : function() {
		advancedSpecialPages.setSelected(true);
		advancedTemplates.setSelected(false);
		advancedFrame.setUrl('specialpages.php');
	},
	$click$advancedTemplates : function() {
		advancedTemplates.setSelected(true);
		advancedSpecialPages.setSelected(false);
		advancedFrame.setUrl('blueprints.php');
	}
});