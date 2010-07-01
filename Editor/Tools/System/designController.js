ui.listen({
	id : null,
	
	$listRowWasOpened$list : function(obj) {
		if (obj.kind=='design') {
			designFormula.reset();
			designEditor.show();
			ui.request({parameters:{id:obj.id},url:'../../Services/Model/LoadObject.php',onSuccess:'loadDesign'});
		}
	},
	$success$loadDesign : function(data) {
		this.id = data.id;
		designFormula.setValues(data);
		deleteDesign.setEnabled(true);
	},
	$click$newDesign : function() {
		this.id = null;
		designEditor.show();
		deleteDesign.setEnabled(false);
	},
	$click$cancelDesign : function() {
		designFormula.reset();
		designEditor.hide();
	},
	$click$deleteDesign : function() {
		ui.request({json:{data:{id:this.id}},url:'../../Services/Model/DeleteObject.php',onSuccess:'deleteDesign'});
	},
	$success$deleteDesign : function() {
		list.refresh();
		designFormula.reset();
		designEditor.hide();
	},
	$click$saveDesign : function() {
		var values = designFormula.getValues();
		if (n2i.isBlank(values.title)) {
			ui.showMessage({text:'Du skal angive en titel!',duration:2000});
			designFormula.focus();
		} else if (values.unique===null) {
			ui.showMessage({text:'Du skal vælge et design!',duration:2000});
			designFormula.focus();
		} else {
			values.id = this.id;
			ui.request({json:{data:values},url:'SaveDesign.php',onSuccess:'designSaved'});
		}
	},
	$success$designSaved : function() {
		list.refresh();
		designFormula.reset();
		designEditor.hide();
	}
});