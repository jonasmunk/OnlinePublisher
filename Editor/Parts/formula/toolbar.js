ui.get().listen({
	$interfaceIsReady : function() {
		var form = partToolbar.partForm;
		receiverName.setValue(form.receiverName.value);
		receiverEmail.setValue(form.receiverEmail.value);
	},
	$valueChanged$receiverName : function() {
		partToolbar.partForm.receiverName.value=receiverName.getValue();
	},
	$valueChanged$receiverEmail : function() {
		partToolbar.partForm.receiverEmail.value=receiverEmail.getValue();
	}
});