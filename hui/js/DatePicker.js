/**
	Fires dateChanged(date) when the user changes the date
	@constructor
	@param options The options (non)
*/
hui.ui.DatePicker = function(options) {
	this.name = options.name;
	this.element = hui.get(options.element);
	this.options = {};
	hui.override(this.options,options);
	this.cells = [];
	this.title = hui.get.firstByTag(this.element,'strong');
	this.today = new Date();
	this.value = this.options.value ? new Date(this.options.value.getTime()) : new Date();
	this.viewDate = new Date(this.value.getTime());
	this.viewDate.setDate(1);
	hui.ui.extend(this);
	this._addBehavior();
	this._updateUI();
}

hui.ui.DatePicker.create = function(options) {
	var element = options.element = hui.build('div',{
		'class' : 'hui_datepicker',
		html : '<div class="hui_datepicker_header"><a class="hui_datepicker_next"></a><a class="hui_datepicker_previous"></a><strong></strong></div>'
		}),
		table = hui.build('table',{parent:element}),
		thead = hui.build('thead',{parent:table}),
		head = hui.build('tr',{parent:thead});
	for (var i=0;i<7;i++) {
		head.appendChild(hui.build('th',{text:Date.dayNames[i].substring(0,3)}));
	}
	var body = hui.build('tbody',{parent:table});
	for (var j=0;j<6;j++) {
		var row = hui.build('tr',{parent:body});
		for (var k=0;k<7;k++) {
			hui.build('td',{parent:row});
		}
	}
	return new hui.ui.DatePicker(options);
}

hui.ui.DatePicker.prototype = {
	_addBehavior : function() {
		var self = this;
		this.cells = hui.get.byTag(this.element,'td');
		hui.each(this.cells,function(cell,index) {
			hui.listen(cell,'mousedown',function(e) {hui.stop(e);self._selectCell(index)});
		})
		var next = hui.get.firstByClass(this.element,'hui_datepicker_next');
		var previous = hui.get.firstByClass(this.element,'hui_datepicker_previous');
		hui.listen(next,'mousedown',function(e) {hui.stop(e);self.next()});
		hui.listen(previous,'mousedown',function(e) {hui.stop(e);self.previous()});
	},
	/** Set the date
	  * @param date The js Date to set
	  */
	setValue : function(date) {
		if (!date) {
			date = new Date();
		}
		this.value = new Date(date.getTime());
		this.viewDate = new Date(date.getTime());
		this.viewDate.setDate(1);
		this._updateUI();
	},
	_updateUI : function() {
		hui.dom.setText(this.title,this.viewDate.dateFormat('F Y'));
		var isSelectedYear =  this.value.getFullYear()==this.viewDate.getFullYear();
		var month = this.viewDate.getMonth();
		for (var i=0; i < this.cells.length; i++) {
			var date = this._indexToDate(i);
			var cell = this.cells[i];
			if (date.getMonth()<month) {
				cell.className = 'hui_datepicker_dimmed';
			} else if (date.getMonth()>month) {
				cell.className = 'hui_datepicker_dimmed';
			} else {
				cell.className = '';
			}
			if (date.getDate()==this.value.getDate() && date.getMonth()==this.value.getMonth() && isSelectedYear) {
				hui.cls.add(cell,'hui_datepicker_selected');
			}
			if (date.getDate()==this.today.getDate() && date.getMonth()==this.today.getMonth() && date.getFullYear()==this.today.getFullYear()) {
				hui.cls.add(cell,'hui_datepicker_today');
			}
			hui.dom.setText(cell,date.getDate());
		};
	},
	_getPreviousMonth : function() {
		var previous = new Date(this.viewDate.getTime());
		previous.setMonth(previous.getMonth()-1);
		return previous;
	},
	_getNextMonth : function() {
		var previous = new Date(this.viewDate.getTime());
		previous.setMonth(previous.getMonth()+1);
		return previous;
	},

	////////////////// Events ///////////////
	/** Change to previous month */
	previous : function() {
		this.viewDate = this._getPreviousMonth();
		this._updateUI();
	},
	/** Change to next month */
	next : function() {
		this.viewDate = this._getNextMonth();
		this._updateUI();
	},
	_selectCell : function(index) {
		this.value = this._indexToDate(index);
		this.viewDate = new Date(this.value.getTime());
		this.viewDate.setDate(1);
		this._updateUI();
		hui.ui.callDelegates(this,'dateChanged',this.value);
	},
	_indexToDate : function(index) {
		var first = this.viewDate.getDay(),
			days = this.viewDate.getDaysInMonth(),
			previousDays = this._getPreviousMonth().getDaysInMonth(),
			date;
		if (index<first) {
			date = this._getPreviousMonth();
			date.setDate(previousDays-first+index+1);
		} else if (index>first+days-1) {
			date = this._getPreviousMonth();
			date.setDate(index-first-days+1);
		} else {
			date = new Date(this.viewDate.getTime());
			date.setDate(index+1-first);
		}
		return date;
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