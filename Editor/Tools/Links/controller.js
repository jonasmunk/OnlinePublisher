hui.ui.listen({
	$ready : function() {
		hui.ui.tellContainers('changeSelection','tool:Links');
	},
	$valueChanged$view : function(value) {
		hui.ui.changeState(value);
	},
	$clickIcon$list : function(info) {
		if (info.data.action=='editPage') {
			document.location='../../Template/Edit.php?id='+info.data.id;
		}
		else if (info.data.action=='viewPage') {
			document.location='../../Services/Preview/?id='+info.data.id;
		}
		else if (info.data.action=='newsInfo') {
			document.location='../../Tools/News/?newsInfo='+info.data.id;
		}
		else if (info.data.action=='fileInfo') {
			document.location='../../Tools/Files/?fileInfo='+info.data.id;
		}
		else if (info.data.action=='viewUrl') {
			window.open(info.data.url);
		}
	}
});