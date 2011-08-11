hui.ui.listen({
	$ready : function() {
		if (window.parent) {
			window.parent.baseController.changeSelection('service:publish');
		}
	},
	$clickButton$list : function(item) {
		hui.ui.request({
			url:'PublishOne.php',
			parameters:{id:item.row.id,kind:item.row.kind},
			onSuccess : function() {
				listSource.refresh();
			}
		});
	},
	$click$publishAll : function() {
		hui.ui.request({
			url:'PublishAll.php',
			onSuccess : function() {
				listSource.refresh();
			}
		});
	}
});