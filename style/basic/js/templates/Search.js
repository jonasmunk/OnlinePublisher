op.SearchTemplate = function() {
	$$('div.search input.text').first().focus();
}

In2iGui.onDomReady(function() {
	new op.SearchTemplate();
});