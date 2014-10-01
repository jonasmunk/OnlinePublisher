var partController = {
	$ready : function() {

	},
	preview : function(delayed) {
		op.part.utils.updatePreview({
			node : 'part_menu_container',
			form : document.forms.PartForm,
			type : 'menu',
			delay : delayed ? 300 : 0
		});
	},
	$click$cancelUpload : function() {
		fileUploadWindow.hide();
	}
}

hui.ui.listen(partController);