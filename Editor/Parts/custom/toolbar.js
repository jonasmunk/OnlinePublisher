hui.ui.listen({
	$ready : function() {
		workflow.setValue(partToolbar.partForm.workflowId.value);
		view.setValue(partToolbar.partForm.viewId.value);
	},
	$valueChanged$workflow : function() {
		this.update();
	},
	$valueChanged$view : function() {
		this.update();
	},
	update : function() {
		partToolbar.partForm.workflowId.value = workflow.getValue();
		partToolbar.partForm.viewId.value = view.getValue();
		partToolbar.preview();
	}
});