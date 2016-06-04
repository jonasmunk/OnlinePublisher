hui.ui.listen({
	$ready : function() {
		search.setValue(hui.store.get('search'));
		newPage.show();
	},
	$select$hierarchySelection : function() {
		wizard.next();
	},
	$valueChanged$search : function(value) {
		hui.store.set('search',value);
	}
})

