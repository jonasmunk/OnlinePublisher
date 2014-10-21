var controller = {
	id : null,
	
	$ready : function() {
		hui.ui.request({
			url : 'data/Load.php',
			parameters : {id:this.id},
			$object : function(values) {
				formula.setValues(values);
				formula.focus();
			}.bind(this)
		});
	},
	$valuesChanged$formula : function() {
		save.enable();
	},
	$submit$formula : function() {
		save.disable();
		var values = formula.getValues();
		values.id = this.id;
		hui.ui.request({
			url : 'data/Save.php',
			json : {data:values},
			$success : function() {
				window.parent.frames[0].controller.markChanged();
				hui.ui.showMessage({text:'Ændringerne er nu gemt',icon:'common/success',duration:2000});
			}
		});
	}
};

hui.ui.listen(controller);