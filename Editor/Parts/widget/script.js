var partController = {
	$ready : function() {
        widgetDataWindow.show();
        var form = document.forms.PartForm;
        widgetDataFormula.setValues({
            data : form.data.value,
            key : form.key.value
        })
	},
	preview : function(delayed) {
		op.part.utils.updatePreview({
			node : 'part_widget_container',
			form : document.forms.PartForm,
			type : 'widget',
			delay : delayed ? 300 : 0
		});
	},
    info : function() {
        widgetDataWindow.show();
    },
    $valuesChanged$widgetDataFormula : function(values) {
        var form = document.forms.PartForm;
        form.data.value = values.data;
        form.key.value = values.key;
        this.preview(true);
    }
}

hui.ui.listen(partController);