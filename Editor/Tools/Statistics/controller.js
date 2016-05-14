hui.ui.listen({
	continuous : true,
	$ready : function() {
		hui.ui.tellContainers('changeSelection','tool:Statistics');
	},
	$sourceIsNotBusy$listSource : function() {
		var sel = selector.getValue();
		if (sel && sel.value=='live') {			
			window.setTimeout(function() {
				listSource.refresh();
			},2000);
		}
	}
})