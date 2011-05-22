var controller = {
	$ready : function() {
		window.setTimeout(this.refresh.bind(this),3000);
	},
	refresh : function() {
		var url = '../graphviz/'+algorithm.getValue().value+'/'+file.getValue().value;
		graph.load(url);
		
	},
	$selectionChanged : function() {
		this.refresh();
	},
	$click$smaller : function() {
		graph.zoom(.9);
	},
	$click$larger : function() {
		graph.zoom(1/.9);
	}
}