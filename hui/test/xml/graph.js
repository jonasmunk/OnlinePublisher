hui.ui.listen({
	$nodeWasClicked : function(node) {
		hui.ui.showMessage({text:node.label,duration:2000});
	}
})