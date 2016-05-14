var partController = {
	preview : function(delayed) {
		op.part.utils.updatePreview({
			node : 'part_widget_container',
			form : document.forms.PartForm,
			type : 'authentication',
			delay : delayed ? 300 : 0
		});
	}
}

hui.ui.listen(partController);