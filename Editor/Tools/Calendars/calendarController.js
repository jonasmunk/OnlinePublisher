hui.ui.listen({
	$open$selector : function(item) {
		if (item.kind=='calendar') {
			hui.ui.request({parameters:{id:item.value},url:'../../Services/Model/LoadObject.php',onSuccess:'loadCalendar'});
		}
	},
	
	$success$loadCalendar : function(source) {
		this.calendarId = source.id;
		calendarFormula.setValues(source);
		deleteCalendar.setEnabled(true);
		calendarWindow.show();
		calendarFormula.focus();
	},
	
	$click$cancelCalendar : function() {
		this.calendarId = null;
		calendarFormula.reset();
		calendarWindow.hide();
	},
	$submit$calendarFormula : function() {
		var data = calendarFormula.getValues();
		data.id = this.calendarId;
		hui.ui.request({url:'data/SaveCalendar.php',onSuccess:'saveCalendar',json:{data:data}});
	},
	$success$saveCalendar : function() {
		this.sourceId = null;
		calendarFormula.reset();
		calendarWindow.hide();
		calendarItemsSource.refresh();
	},
	
	$click$newCalendar : function() {
		this.calendarId = null;
		calendarFormula.reset();
		calendarWindow.show();
		deleteCalendar.setEnabled(false);
		calendarFormula.focus();
	},
	$click$deleteCalendar : function() {
		hui.ui.request({url:'data/DeleteCalendar.php',onSuccess:'deleteCalendar',parameters:{id:this.calendarId}});
	},
	$success$deleteCalendar : function() {
		this.calendarId = null;
		calendarFormula.reset();
		calendarWindow.hide();
		calendarItemsSource.refresh();
	}
	
});