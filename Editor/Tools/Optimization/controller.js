hui.ui.listen({
	$selectionChanged$selector : function(item) {
		if (item.value=='overview') {
			hui.ui.changeState('overview');
		} else {
			hui.ui.changeState('list');
			if (item.value=='warnings') {
				listDescription.setText('Viser advarsler om manglende indhold med mere');
			} else {
				listDescription.setText();
			}
		}
	}
});