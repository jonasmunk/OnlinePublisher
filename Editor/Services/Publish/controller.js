hui.ui.listen({
	$ready : function() {
		hui.ui.tellContainers('changeSelection','service:publish');
	},
	$clickButton$list : function(item) {
		hui.ui.request({
			url:'PublishOne.php',
			parameters:{id:item.row.id,kind:item.row.kind},
			onSuccess : function() {
				listSource.refresh();
				hui.ui.tellContainers('pageChanged');
			}
		});
	},
	$clickIcon$list : function(info) {
		if (info.data.action=='editPage') {
			document.location='../../Template/Edit.php?id='+info.data.id;
		}
		if (info.data.action=='viewPage') {
			document.location='../../Services/Preview/?id='+info.data.id;
		}
	},
	
	$click$publishAll : function() {
		hui.ui.request({
			url:'PublishAll.php',
			onSuccess : function() {
				listSource.refresh();
				hui.ui.tellContainers('pageChanged');
			}
		});
	}
});