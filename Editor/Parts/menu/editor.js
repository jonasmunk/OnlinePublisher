var partController = {
	$ready : function() {
        partMenuWindow.show();
        var form = document.forms.PartForm;
        partMenuFormula.setValues({
            hierarchyId : form.hierarchyId.value,
            header : form.header.value
        })
	},
	preview : function(delayed) {
		op.part.utils.updatePreview({
			node : 'part_menu_container',
			form : document.forms.PartForm,
			type : 'menu',
			delay : delayed ? 300 : 0
		});
	},
    info : function() {
        partMenuWindow.show();
    },
    $valuesChanged$partMenuFormula : function(values) {
        var form = document.forms.PartForm;
        form.hierarchyId.value = values.hierarchyId;
        form.header.value = values.header;
        this.preview(true);
    }
}

hui.ui.listen(partController);