hui.ui.listen({
	$ready : function() {
		hui.ui.tellContainers('changeSelection','tool:Calendars');
	},
	$valueChanged$viewSelection : function(value) {
		hui.ui.changeState(value);
	},
	$select$selector : function(value) {
		if (value.kind=='calendar') {
			list.setSource(calendarEventsListSource);
		} else if (value.kind=='calendarsource') {
			list.setSource(sourceEventsListSource);
		} else {
			list.setSource(sourcesListSource);
		}
	}
});