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
		In2iGui.json({data:data},'../../Services/Model/LoadObject.php','loadUsage');
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
		In2iGui.json({data:{id:this.usageId}},'../../Services/Model/DeleteObject.php','deleteUsage');
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