hui.ui.listen({
	$ready : function() {
		if (window.parent!=window) {
			window.parent.baseController.changeSelection('service:start');
		}
	},
	$clickButton : function(info) {
		window.open(info.button.getData().url);
	}
})