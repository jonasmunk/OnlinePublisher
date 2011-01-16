op.SearchTemplate = function() {
	var container = n2i.firstByClass(document.body,'search','div');
	n2i.firstByClass(container,'text','input').focus();
}

In2iGui.onDomReady(function() {
	new op.SearchTemplate();
});