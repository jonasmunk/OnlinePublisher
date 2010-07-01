ui.listen({
	$valueChanged$viewSelection : function(value) {
		ui.changeState(value);
	},
	$selectionChanged$selector : function(value) {
		if (value.kind=='calendar') {
			list.setSource(calendarEventsListSource);
		} else if (value.kind=='calendarsource') {
			list.setSource(sourceEventsListSource);
		} else {
			list.setSource(sourcesListSource);
		}
	}
});