ui.listen({
	$ready : function() {
		this.editor = new ui.MarkupEditor({replace:'part_richtext'});
		this.editor.listen(this);
		this.editor.focus();
	},
	$blur : function() {
		document.forms.PartForm.html.value = this.editor.getValue();
	}
})