var controller = {
	id : null,
	
	$ready : function() {
		hui.ui.request({
			url : 'data/Load.php',
			parameters : {id:this.id},
			$object : function(values) {
				formula.setValues(values);
				save.enable();
			}.bind(this)
		});
	},
	$click$save : function() {
		save.disable();
		var values = formula.getValues();
		values.id = this.id;
		hui.ui.request({
			url : 'data/Save.php',
			json : {data : values},
			$success : function(values) {
				save.enable();
				hui.ui.showMessage({text:'Ændringerne er nu gemt',icon:'common/success',duration:2000});
				window.parent.frames[0].controller.markChanged();
		}});
	}
}

hui.ui.listen(controller);