op.SearchTemplate = function() {
	var container = hui.firstByClass(document.body,'search','div');
	hui.firstByClass(container,'text','input').focus();
}

hui.ui.onReady(function() {
	new op.SearchTemplate();
});