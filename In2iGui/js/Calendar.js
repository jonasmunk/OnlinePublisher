/**
 * @constructor
 */
In2iGui.Calendar = function(o) {
	this.name = o.name;
	this.options = n2i.override({startHour:7,endHour:24},o);
	this.element = $(o.element);
	this.head = $(this.element.getElementsByTagName('thead')[0]);
	this.body = $(this.element.getElementsByTagName('tbody')[0]);
	this.date = new Date();
	In2iGui.extend(this);
	this.buildUI();
	this.updateUI();
	if (this.options.source) {
		this.options.source.listen(this);
	}
}

In2iGui.Calendar.prototype = {
	show : function() {
		this.element.style.display='block';
		if (this.options.source) {
			this.options.source.refresh();
		}
	},
	hide : function() {
		this.element.style.display='none';
	},
	/** @private */
	getFirstDay : function() {
		var date = new Date(this.date.getTime());
		date.setDate(date.getDate()-date.getDay()+1);
		date.setHours(0);
		date.setMinutes(0);
		date.setSeconds(0);
		return date;
	},
	/** @private */
	getLastDay : function() {
		var date = new Date(this.date.getTime());
		date.setDate(date.getDate()-date.getDay()+7);
		date.setHours(23);
		date.setMinutes(59);
		date.setSeconds(59);
		return date;
	},
	clearEvents : function() {
		this.events = [];
		var nodes = this.element.select('.in2igui_calendar_event');
		nodes.each(function(node) {
			node.remove();
		});
		this.hideEventViewer();
	},
	$objectsLoaded : function(data) {
		try {
			this.setEvents(data);
		} catch (e) {
			n2i.log(e);
		}
	},
	$sourceIsBusy : function() {
		this.setBusy(true);
	},
	$sourceShouldRefresh : function() {
		return this.element.style.display!='none';
	},
	setEvents : function(events) {
		events = events || [];
		for (var i=0; i < events.length; i++) {
			var e = events[i];
			if (typeof(e.startTime)!='object') {
				e.startTime = new Date(parseInt(e.startTime)*1000);
			}
			if (typeof(e.endTime)!='object') {
				e.endTime = new Date(parseInt(e.endTime)*1000);
			}
		};
		this.setBusy(false);
		this.clearEvents();
		this.events = events;
		var self = this;
		var pixels = (this.options.endHour-this.options.startHour)*40;
		var week = this.getFirstDay().getWeekOfYear();
		var year = this.getFirstDay().getYear();
		this.events.each(function(event) {
			var day = self.body.select('.in2igui_calendar_day')[event.startTime.getDay()-1];
			if (!day) {
				return;
			}
			if (event.startTime.getWeekOfYear()!=week || event.startTime.getYear()!=year) {
				return;
			}
			var node = new Element('div',{'class':'in2igui_calendar_event'});
			var top = ((event.startTime.getHours()*60+event.startTime.getMinutes())/60-self.options.startHour)*40-1;
			var height = (event.endTime.getTime()-event.startTime.getTime())/1000/60/60*40+1;
			var height = Math.min(pixels-top,height);
			node.setStyle({'marginTop':top+'px','height':height+'px',visibility:'hidden'});
			var content = new Element('div');
			content.insert(new Element('p',{'class':'in2igui_calendar_event_time'}).update(event.startTime.dateFormat('H:i')));
			content.insert(new Element('p',{'class':'in2igui_calendar_event_text'}).update(event.text));
			if (event.location) {
				content.insert(new Element('p',{'class':'in2igui_calendar_event_location'}).update(event.location));
			}
			
			day.insert(node.insert(content));
			window.setTimeout(function() {
				In2iGui.bounceIn(node);
			},Math.random()*200)
			node.observe('click',function() {
				self.eventWasClicked(event,this);
			});
		});
	},
	/** @private */
	eventWasClicked : function(event,node) {
		this.showEvent(event,node);
	},
	setBusy : function(busy) {
		if (busy) {
			this.element.addClassName('in2igui_calendar_busy');
		} else {
			this.element.removeClassName('in2igui_calendar_busy');
		}
	},
	/** @private */
	updateUI : function() {
		var first = this.getFirstDay();
		//var x = this.head.select('.time')[0];
		//x.update('<div>Uge '+this.date.getWeekOfYear()+' '+this.date.getFullYear()+'</div>');
		
		var days = this.head.select('.day');
		for (var i=0; i < days.length; i++) {
			var date = new Date(first.getTime());
			date.setDate(date.getDate()+i);
			days[i].update(date.dateFormat('l \\d. d M'))
		};
	},
	/** @private */
	buildUI : function() {
		var bar = this.element.select('.in2igui_calendar_bar')[0];
		this.toolbar = In2iGui.Toolbar.create({labels:false});
		bar.insert(this.toolbar.getElement());
		var previous = In2iGui.Button.create({name:'in2iguiCalendarPrevious',text:'',icon:'monochrome/previous'});
		previous.listen(this);
		this.toolbar.add(previous);
		var today = In2iGui.Button.create({name:'in2iguiCalendarToday',text:'Idag'});
		today.click(function() {this.setDate(new Date())}.bind(this));
		this.toolbar.add(today);
		var next = In2iGui.Button.create({name:'in2iguiCalendarNext',text:'',icon:'monochrome/next'});
		next.listen(this);
		this.toolbar.add(next);
		this.datePickerButton = In2iGui.Button.create({name:'in2iguiCalendarDatePicker',text:'Vælg dato...'});
		this.datePickerButton.listen(this);
		this.toolbar.add(this.datePickerButton);
		
		var time = this.body.select('.in2igui_calendar_day')[0];
		for (var i=this.options.startHour; i <= this.options.endHour; i++) {
			var node = new Element('div',{'class':'in2igui_calendar_time'}).update('<span><em>'+i+':00</em></span>');
			if (i==this.options.startHour) {
				node.addClassName('in2igui_calendar_time_first');
			}
			if (i==this.options.endHour) {
				node.addClassName('in2igui_calendar_time_last');
			}
			time.insert(node);
		};
	},
	$click$in2iguiCalendarPrevious : function() {
		var date = new Date(this.date.getTime());
		date.setDate(this.date.getDate()-7);
		this.setDate(date);
	},
	$click$in2iguiCalendarNext : function() {
		var date = new Date(this.date.getTime());
		date.setDate(this.date.getDate()+7);
		this.setDate(date);
	},
	setDate: function(date) {
		this.date = new Date(date.getTime());
		this.updateUI();
		this.refresh();
		if (this.datePicker) {
			this.datePicker.setValue(this.date);
		}
	},
	$click$in2iguiCalendarDatePicker : function() {
		this.showDatePicker();
	},
	refresh : function() {
		this.clearEvents();
		this.setBusy(true);
		var info = {'startTime':this.getFirstDay(),'endTime':this.getLastDay()};
		this.fire('calendarSpanChanged',info);
		In2iGui.firePropertyChange(this,'startTime',this.getFirstDay());
		In2iGui.firePropertyChange(this,'endTime',this.getLastDay());
	},
	/** @private */
	valueForProperty : function(p) {
		if (p=='startTime') {
			return this.getFirstDay();
		}
		if (p=='endTime') {
			return this.getLastDay();
		}
		return this[p];
	},
	
	////////////////////////////////// Date picker ///////////////////////////
	showDatePicker : function() {
		if (!this.datePickerPanel) {
			this.datePickerPanel = In2iGui.BoundPanel.create();
			this.datePicker = In2iGui.DatePicker.create({name:'in2iguiCalendarDatePicker',value:this.date});
			this.datePicker.listen(this);
			this.datePickerPanel.add(this.datePicker);
			this.datePickerPanel.addSpace(3);
			var button = In2iGui.Button.create({name:'in2iguiCalendarDatePickerClose',text:'Luk',small:true,rounded:true});
			button.listen(this);
			this.datePickerPanel.add(button);
		}
		this.datePickerPanel.position(this.datePickerButton.getElement());
		this.datePickerPanel.show();
	},
	$click$in2iguiCalendarDatePickerClose : function() {
		this.datePickerPanel.hide();
	},
	$dateChanged$in2iguiCalendarDatePicker : function(date) {
		this.setDate(date);
	},
	
	//////////////////////////////// Event viewer //////////////////////////////
	
	showEvent : function(event,node) {
		if (!this.eventViewerPanel) {
			this.eventViewerPanel = In2iGui.BoundPanel.create({width:270,padding: 3});
			this.eventInfo = In2iGui.InfoView.create(null,{height:240,clickObjects:true});
			this.eventViewerPanel.add(this.eventInfo);
			this.eventViewerPanel.addSpace(5);
			var button = In2iGui.Button.create({name:'in2iguiCalendarEventClose',text:'Luk'});
			button.listen(this);
			this.eventViewerPanel.add(button);
		}
		this.eventInfo.clear();
		this.eventInfo.setBusy(true);
		this.eventViewerPanel.position(node);
		this.eventViewerPanel.show();
		In2iGui.callDelegates(this,'requestEventInfo',event);
		return;
	},
	updateEventInfo : function(event,data) {
		this.eventInfo.setBusy(false);
		this.eventInfo.update(data);
	},
	click$in2iguiCalendarEventClose : function() {
		this.hideEventViewer();
	},
	hideEventViewer : function() {
		if (this.eventViewerPanel) {
			this.eventViewerPanel.hide();
		}
	}
}


////////////////////////// Date picker ////////////////////////

/** @constructor */
In2iGui.DatePicker = function(options) {
	this.name = options.name;
	this.element = $(options.element);
	this.options = {};
	n2i.override(this.options,options);
	this.cells = [];
	this.title = this.element.select('strong')[0];
	this.today = new Date();
	this.value = this.options.value ? new Date(this.options.value.getTime()) : new Date();
	this.viewDate = new Date(this.value.getTime());
	this.viewDate.setDate(1);
	In2iGui.extend(this);
	this.addBehavior();
	this.updateUI();
}

In2iGui.DatePicker.create = function(options) {
	var element = options.element = new Element('div',{'class':'in2igui_datepicker'});
	element.insert('<div class="in2igui_datepicker_header"><a class="in2igui_datepicker_next"></a><a class="in2igui_datepicker_previous"></a><strong></strong></div>');
	var table = new Element('table');
	var head = new Element('tr');
	table.insert(new Element('thead').insert(head));
	for (var i=0;i<7;i++) {
		head.insert(new Element('th').update(Date.dayNames[i].substring(0,3)));
	}
	var body = new Element('tbody');
	table.insert(body);
	for (var i=0;i<6;i++) {
		var row = new Element('tr');
		for (var j=0;j<7;j++) {
			var cell = new Element('td');
			row.insert(cell);
		}
		body.insert(row);
	}
	element.insert(table);
	return new In2iGui.DatePicker(options);
}

In2iGui.DatePicker.prototype = {
	addBehavior : function() {
		var self = this;
		this.cells = this.element.select('td');
		this.cells.each(function(cell,index) {
			cell.observe('mousedown',function() {self.selectCell(index)});
		})
		this.element.select('.in2igui_datepicker_next')[0].observe('mousedown',function() {self.next()});
		this.element.select('.in2igui_datepicker_previous')[0].observe('mousedown',function() {self.previous()});
	},
	setValue : function(date) {
		this.value = new Date(date.getTime());
		this.viewDate = new Date(date.getTime());
		this.viewDate.setDate(1);
		this.updateUI();
	},
	updateUI : function() {
		this.title.update(this.viewDate.dateFormat('F Y'));
		var isSelectedYear =  this.value.getFullYear()==this.viewDate.getFullYear();
		var month = this.viewDate.getMonth();
		for (var i=0; i < this.cells.length; i++) {
			var date = this.indexToDate(i);
			var cell = this.cells[i];
			if (date.getMonth()<month) {
				cell.className = 'in2igui_datepicker_dimmed';
			} else if (date.getMonth()>month) {
				cell.className = 'in2igui_datepicker_dimmed';
			} else {
				cell.className = '';
			}
			if (date.getDate()==this.value.getDate() && date.getMonth()==this.value.getMonth() && isSelectedYear) {
				cell.addClassName('in2igui_datepicker_selected');
			}
			if (date.getDate()==this.today.getDate() && date.getMonth()==this.today.getMonth() && date.getFullYear()==this.today.getFullYear()) {
				cell.addClassName('in2igui_datepicker_today');
			}
			cell.update(date.getDate());
		};
	},
	getPreviousMonth : function() {
		var previous = new Date(this.viewDate.getTime());
		previous.setMonth(previous.getMonth()-1);
		return previous;
	},
	getNextMonth : function() {
		var previous = new Date(this.viewDate.getTime());
		previous.setMonth(previous.getMonth()+1);
		return previous;
	},

	////////////////// Events ///////////////

	previous : function() {
		this.viewDate = this.getPreviousMonth();
		this.updateUI();
	},
	next : function() {
		this.viewDate = this.getNextMonth();
		this.updateUI();
	},
	selectCell : function(index) {
		this.value = this.indexToDate(index);
		this.viewDate = new Date(this.value.getTime());
		this.viewDate.setDate(1);
		this.updateUI();
		In2iGui.callDelegates(this,'dateChanged',this.value);
	},
	indexToDate : function(index) {
		var first = this.viewDate.getDay();
		var days = this.viewDate.getDaysInMonth();
		var previousDays = this.getPreviousMonth().getDaysInMonth();
		if (index<first) {
			var date = this.getPreviousMonth();
			date.setDate(previousDays-first+index+1);
			return date;
		} else if (index>first+days-1) {
			var date = this.getPreviousMonth();
			date.setDate(index-first-days+1);
			return date;
			cell.update(i-first-days+1);
		} else {
			var date = new Date(this.viewDate.getTime());
			date.setDate(index+1-first);
			return date;
		}
	}
}

Date.monthNames =
   ["Januar",
    "Februar",
    "Marts",
    "April",
    "Maj",
    "Juni",
    "Juli",
    "August",
    "September",
    "Oktober",
    "November",
    "December"];
Date.dayNames =
   ["Søndag",
    "Mandag",
    "Tirsdag",
    "Onsdag",
    "Torsdag",
    "Fredag",
    "Lørdag"];

/* EOF */