hui.ui.listen({
	//dragDrop : [
	//	{drag:'event',drop:'calendar'}
	//],
	
	//////// List ////////
	
	$listRowWasOpened$list : function(item) {
		if (item.kind=='event') {
			this.loadEvent(item.id);
		}
	},
	$selectionChanged$list : function(value) {
		if (value.kind=='event') {
			deleteItem.setEnabled(true);
			editItem.setEnabled(true);
		}
	},
	$selectionReset$list : function() {
		deleteItem.setEnabled(false);
		editItem.setEnabled(false);
	},
	$click$editItem : function() {
		var obj  = list.getFirstSelection();
		if (obj && obj.kind=='event') {
			this.loadEvent(obj.id);
		}
	},
	$click$deleteItem : function() {
		var obj  = list.getFirstSelection();
		if (obj && obj.kind=='event') {
			this.deleteEvent(obj.id);
		}
	},
	
	///////////// Other ///////////
	
	loadEvent : function(id) {
		hui.ui.request({parameters:{id:id},url:'data/LoadEvent.php',onSuccess:'loadEvent'});
	},
	
	$drop$event$calendar : function(dragged,dropped) {
		//alert(Object.toJSON(dragged));
		//alert(Object.toJSON(dropped));
	},
	$success$loadEvent : function(data) {
		this.eventId = data.event.id;
		eventFormula.setValues(data.event);
		eventCalendars.setValue(data.calendars);
		deleteEvent.setEnabled(true);
		eventWindow.show();
		eventFormula.focus();
	},
	
	$click$cancelEvent : function() {
		this.eventId = null;
		eventFormula.reset();
		eventWindow.hide();
	},
	$submit$eventFormula : function() {
		var data = eventFormula.getValues();
		data.id = this.eventId;
		if (data.startdate) {
			data.startdate=Math.round(data.startdate.getTime()/1000);
		} else {
			ui.showMessage({text:'Startdato skal udfyldes',duration:2000});
			eventFormula.focus();
			return;
		}
		if (data.enddate) {
			data.enddate=Math.round(data.enddate.getTime()/1000);
		} else {
			ui.showMessage({text:'Slutdato skal udfyldes',duration:2000});
			eventFormula.focus();
			return;
		}
		if (data.calendars.length<1) {
			ui.showMessage({text:'Der skal vælges mindst een kalender',duration:2000});
			eventFormula.focus();
			return;
		}
		hui.ui.request({url:'data/SaveEvent.php',onSuccess:'saveEvent',json:{data:data}});
	},
	$success$saveEvent : function() {
		this.eventId = null;
		eventFormula.reset();
		eventWindow.hide();
		list.refresh();
	},
	
	$click$newEvent : function() {
		this.eventId = null;
		eventFormula.reset();
		var selection = selector.getValue();
		if (selection.kind=='calendar') {
			eventCalendars.setValue([selection.value]);
		}
		eventWindow.show();
		deleteEvent.setEnabled(false);
		eventFormula.focus();
	},
	$click$deleteEvent : function() {
		this.deleteEvent(this.eventId);
	},
	deleteEvent : function(id) {
		hui.ui.request({url:'data/DeleteEvent.php',onSuccess:'deleteEvent',parameters:{id:id}});
	},
	$success$deleteEvent : function() {
		this.eventId = null;
		eventFormula.reset();
		eventWindow.hide();
		list.refresh();
	}
	
});