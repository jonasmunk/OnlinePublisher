hui.ui.listen({
	$ready : function() {
		if (window.parent!=window) {
			window.parent.baseController.changeSelection('service:start');
		}
	},
	$clickIcon$newsList : function(info) {
		window.open(info.data.url);
	},
	$clickIcon$taskList : function(info) {
		if (info.data.action=='edit') {
			document.location='../../Template/Edit.php?id='+info.data.id;
		}
	}
})