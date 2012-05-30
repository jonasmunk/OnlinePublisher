var controller = {
	id : null,
	
	$ready : function() {
		hui.ui.request({
			url : 'Load.php',
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
		hui.ui.request({url:'Save.php',json:{data:values},onSuccess:function(values) {
			window.parent.Toolbar.publish.enable();
			hui.ui.showMessage({text:'Ændringerne er nu gemt',duration:2000});
		}});
	}
};

hui.ui.listen(controller);