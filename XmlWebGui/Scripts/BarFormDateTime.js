In2iGui.BarFormDateTime = function(id,min,max) {
	this.id=id;
	this.min=min;
	this.max=max;
}

In2iGui.BarFormDateTime.prototype.disable = function() {
	document.getElementById('second'+this.id).disabled=true;
	document.getElementById('minute'+this.id).disabled=true;
	document.getElementById('hour'+this.id).disabled=true;
	document.getElementById('day'+this.id).disabled=true;
	document.getElementById('month'+this.id).disabled=true;
	document.getElementById('year'+this.id).disabled=true;
};

In2iGui.BarFormDateTime.prototype.enable = function() {
	document.getElementById('second'+this.id).disabled=false;
	document.getElementById('minute'+this.id).disabled=false;
	document.getElementById('hour'+this.id).disabled=false;
	document.getElementById('day'+this.id).disabled=false;
	document.getElementById('month'+this.id).disabled=false;
	document.getElementById('year'+this.id).disabled=false;
};

In2iGui.BarFormDateTime.prototype.getValue = function () {
	return document.getElementById('value'+this.id).value;
};

In2iGui.BarFormDateTime.prototype.getDay = function () {
	var val = document.getElementById('value'+this.id).value;
	return parseInt(val.substr(6,2));
};

In2iGui.BarFormDateTime.prototype.getMonth = function () {
	var val = document.getElementById('value'+this.id).value;
	return parseInt(val.substr(4,2));
};

In2iGui.BarFormDateTime.prototype.getYear = function () {
	var val = document.getElementById('value'+this.id).value;
	return parseInt(val.substr(0,4));
};

In2iGui.BarFormDateTime.prototype.setDay = function (day) {
	document.getElementById('day'+this.id).options[day-1].selected=true;
	this.validate();
};

In2iGui.BarFormDateTime.prototype.setMonth = function (month) {
	document.getElementById('month'+this.id).options[month-1].selected=true;
	this.validate();
};

In2iGui.BarFormDateTime.prototype.setYear = function (year) {
	var element = document.getElementById('year'+this.id);
	for (var i=0;i<element.options.length;i++) {
		if (element.options[i].value==year) {
			element.options[i].selected=true;
		}
	}
	this.validate();
};

In2iGui.BarFormDateTime.prototype.validate = function() {
	
	// -------- Values ---------
	
	var daysofmonth   = new Array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var daysofmonthLY = new Array( 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	
	// -------- Get values ---------

	var x = document.getElementById('second'+this.id).value;
	var second=parseNumber(document.getElementById('second'+this.id).value,59);
	var minute=parseNumber(document.getElementById('minute'+this.id).value,59);
	var hour=parseNumber(document.getElementById('hour'+this.id).value,23);
	var day=parseNumber(document.getElementById('day'+this.id).value,31);
	var month=parseNumber(document.getElementById('month'+this.id).value,12);
	var year=parseNumber(document.getElementById('year'+this.id).value,6000);
	// -------- Validate days of month ---------
	window.status=x+'-'+second;
	var monthdays=0
	if (LeapYear(year)) {
		monthdays=daysofmonthLY[month-1];
	}
	else {
		monthdays=daysofmonth[month-1];
	}
	if (monthdays<day) {
		day=monthdays;
	}
	
	var combined = setLen(year,4) + setLen(month,2) + setLen(day,2) + setLen(hour,2) + setLen(minute,2) + setLen(second,2);
	
	
	// ----------- Validate min/max values --------
	
	if (parseInt(combined)<parseInt(this.min)) {
		combined=this.min;
	}
	if (parseInt(combined)>parseInt(this.max)) {
		combined=this.max;
	}
	
	// -------- Put validated into new value ---------

	document.getElementById('second'+this.id).value=combined.substr(12,2);
	document.getElementById('minute'+this.id).value=combined.substr(10,2);
	hour=document.getElementById('hour'+this.id).value=combined.substr(8,2);
	day=document.getElementById('day'+this.id).value=combined.substr(6,2)
	month=document.getElementById('month'+this.id).value=combined.substr(4,2);
	year=document.getElementById('year'+this.id).value=combined.substr(0,4);
	document.getElementById('value'+this.id).value=combined
}

function LeapYear(year) {
    if ((year/4)   != Math.floor(year/4))   return false;
    if ((year/100) != Math.floor(year/100)) return true;
    if ((year/400) != Math.floor(year/400)) return false;
    return true;
}

function parseNumber(val,max) {
	val=parseInt(val,10);
	if (isNaN(val)) {
		val = 0;
	}
	else if (val>max) {
		val=max;
	}
	return val;
}

function setLen(val,len) {
	val = new String(val);
	var delta = len-val.length;
	for (i=0;i<delta;i++) {
		val='0'+val;
	}
	return val;
}