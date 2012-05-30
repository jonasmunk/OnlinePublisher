var controller = {
	id : null,
	
	$ready : function() {
		hui.ui.request({
			url : 'data/Load.php',
			parameters : {id:this.id},
			onJSON : function(values) {
				formula.setValues(values);
			}.bind(this)
		});
	},
	$valuesChanged$formula : function() {
		save.enable();
		//hui.ui.stress(save);
	},
	$click$save : function() {
		save.disable();
		var values = formula.getValues();
		values.id = this.id;
		hui.ui.request({
			url : 'data/Save.php',
			parameters : values,
			onJSON : function(obj) {
				window.parent.Toolbar.publish.enable();
				hui.ui.showMessage({text:'Ændringerne er nu gemt'+(obj.valid ? ' (valid)' : ' (invalid)'),duration:2000});
			}
		});
	}
};

hui.ui.listen(controller);