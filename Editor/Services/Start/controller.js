hui.ui.listen({
	$ready : function() {
		if (window.parent) {
			window.parent.baseController.changeSelection('service:start');
		}
	},
	$clickButton : function(info) {
		window.open(info.button.getData().link);
	}
})