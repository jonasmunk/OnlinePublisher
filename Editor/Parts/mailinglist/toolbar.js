hui.ui.listen({
	$ready : function() {
		var ids = partToolbar.partForm.mailinglists.value.split(',');
		for (var i=0; i < ids.length; i++) {
			ids[i] = parseInt(ids[i]);
		}
		lists.setValue(ids);
	},
	$valueChanged$lists : function(value) {
		partToolbar.partForm.mailinglists.value = value.join(',');
	}
});