hui.ui.listen({
	id:null,
	
	$ready : function() {
		hui.ui.request({url:'Load.php',onJSON:function(values) {
			this.id = values.id;
			formula.setValues(values);
			save.enable();
		}.bind(this)});
	},
	$click$save : function() {
		save.disable();
		var values = formula.getValues();
		values.id = this.id;
		hui.ui.request({url:'Save.php',json:{data:values},onSuccess:function(values) {
			save.enable();
			window.parent.Toolbar.publish.enable();
			hui.ui.showMessage({text:'Ændringerne er nu gemt',duration:2000});
		}});
	}
});