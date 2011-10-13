hui.ui.listen({
	$ready : function() {
		if (window.parent!=window) {
			window.parent.baseController.changeSelection('tool:Links');
		}
	},
	$valueChanged$view : function(value) {
		hui.ui.changeState(value);
	}
});