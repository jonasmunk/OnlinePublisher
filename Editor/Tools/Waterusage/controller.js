ui.listen({
	usageId : null,
	
	$valueChanged$search : function(value) {
		list.resetState();
	},
	
	$click$cancelUsage : function() {
		this.usageId = null;
		usageFormula.reset();
		usageWindow.hide();
	},
	
	$click$newUsage : function() {
		this.usageId = null;
		usageWindow.show();
		usageFormula.reset();
		deleteUsage.setEnabled(false);
		saveUsage.setEnabled(true);
	},
	
	$click$saveUsage : function() {
		var data = usageFormula.getValues();
		data.id = this.usageId;
		ui.request({url:'SaveUsage.php',onSuccess:'usageUpdated',json:{data:data}});
	},
	$success$usageUpdated : function() {
		list.refresh();
		this.usageId = null;
		usageFormula.reset();
		usageWindow.hide();
	},
	
	$listRowWasOpened$list : function(obj) {
		var data = {id:obj.id};
		usageFormula.reset();
		deleteUsage.setEnabled(false);
		saveUsage.setEnabled(false);
		ui.request({json:{data:data},url:'../../Services/Model/LoadObject.php',onSuccess:'loadUsage'});
	},
	
	$success$loadUsage : function(data) {
		this.usageId = data.id;
		usageFormula.setValues(data);
		usageWindow.show();
		saveUsage.setEnabled(true);
		deleteUsage.setEnabled(true);
		usageFormula.focus();
	},
	$click$deleteUsage : function() {
		ui.request({json:{data:{id:this.usageId}},url:'../../Services/Model/DeleteObject.php',onSuccess:'deleteUsage'});
	},
	$success$deleteUsage : function() {
		usageFormula.reset();
		usageWindow.hide();
		list.refresh();
	},
	$uploadDidCompleteQueue : function() {
		list.refresh();
	},
});