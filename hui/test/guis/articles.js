hui.ui.listen({
	$ready : function() {
		search.setValue(hui.ui.storage.get('search'));
		newPage.show();
	},
	$select$hierarchySelection : function() {
		wizard.next();
	},
	$valueChanged$search : function(value) {
		hui.ui.storage.set('search',value);
	}
})

hui.ui.storage = {
	
	isSupported : function() {
	  try {
	    return 'localStorage' in window && window['localStorage'] !== null;
	  } catch (e) {
	    return false;
	  }
	},
	
	set : function(key,value) {
		if (this.isSupported) {
			localStorage.setItem(key, value);
		}
	},
	get : function(key) {
		if (this.isSupported) {
			return localStorage.getItem(key);
		}
		return null;
	}
	
}