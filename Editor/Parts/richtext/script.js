hui.ui.listen({
	$ready : function() {
		var container = hui.get('part_richtext');
		var node = hui.get.firstByClass(container,'part_richtext') || container;
		this.editor = new hui.ui.MarkupEditor({replace:node});
		this.editor.listen(this);
		this.editor.focus();
	},
	$blur : function() {
		document.forms.PartForm.html.value = this.editor.getValue();
	}
})