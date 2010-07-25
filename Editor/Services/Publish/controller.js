ui.listen({
	$listRowWasOpened$list : function(item) {
		ui.request({
			url:'PublishOne.php',
			parameters:{id:item.id,kind:item.kind},
			onSuccess : function() {
				listSource.refresh();
			}
		});
	},
	$click$publishAll : function() {
		ui.request({
			url:'PublishAll.php',
			onSuccess : function() {
				listSource.refresh();
			}
		});
	}
});