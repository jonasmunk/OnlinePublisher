ui.listen({
	$ready : function() {
		var container = n2i.get('part_richtext');
		var node = n2i.firstByClass(container,'part_richtext') || container;
		this.editor = new ui.MarkupEditor({replace:node});
		this.editor.listen(this);
		this.editor.focus();
	},
	$blur : function() {
		document.forms.PartForm.html.value = this.editor.getValue();
	}
})