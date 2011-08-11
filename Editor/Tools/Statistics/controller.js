hui.ui.listen({
	$ready : function() {
		if (window.parent) {
			window.parent.baseController.changeSelection('tool:Statistics');
		}
	}
})