hui.ui.listen({
	$ready : function() {
		newPage.show();
	},
	$select$hierarchySelection : function() {
		window.setTimeout(function() {wizard.next()},300);
	},
    $click$noMenuItem : function() {
        wizard.next();
    },
    $valueChanged$newPageTitle : function(text) {
        if (newPagePath.isModified()) {
            return;
        }
        text = text.toLowerCase().trim();
        if (hui.isBlank(text)) {
            newPagePath.setValue('');
        } else {
            var path = text.replace(/[\s]+?/g, "-")
            newPagePath.setValue('/'+path+'.html');            
        }
    }
})