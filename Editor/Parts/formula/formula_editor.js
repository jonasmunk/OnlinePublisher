var partController = {
	$ready : function() {
		this.showSource();
		sourceFormula.setValues({
			recipe : document.forms.PartForm.recipe.value
		})
	},
	showSource : function() {
		sourceWindow.show();
	},
	
	$valuesChanged$sourceFormula : function(values) {
		var dom = hui.xml.parse(values.recipe);
		if (dom) {
			document.forms.PartForm.recipe.value = values.recipe;
			this.preview();
		}
	},
	preview : function(immediate) {
		op.part.utils.updatePreview({
			node : hui.get('part_formula_container'),
			form : document.forms.PartForm,
			type : 'formula',
			delay : immediate ? 0 : 500,
			runScripts : true
		});
	},
}
hui.ui.listen(partController);