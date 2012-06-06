hui.ui.listen({
	$ready : function() {
		mapWindow.show();
		var form = document.forms.PartForm;
		mapFormula.setValues({
			maptype : form.maptype.value
		})
	},
	$valuesChanged$mapFormula : function(values) {
		var form = document.forms.PartForm;
		form.maptype.value = values.maptype;
		this.preview();
	},
	preview : function() {
		op.part.utils.updatePreview({
			node : hui.get('part_map_container'),
			form : document.forms.PartForm,
			type : 'map',
			delay : 500
		});
	},
})