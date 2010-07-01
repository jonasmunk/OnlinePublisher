function XWGFormDateTime(id,min,max) {
	this.id=id;
	this.min=min;
	this.max=max;
}

XWGFormDateTime.prototype.disable = function() {
	document.getElementById('second'+this.id).disabled=true;
	document.getElementById('minute'+this.id).disabled=true;
	document.getElementById('hour'+this.id).disabled=true;
	document.getElementById('day'+this.id).disabled=true;
	document.getElementById('month'+this.id).disabled=true;
	document.getElementById('year'+this.id).disabled=true;
};

XWGFormDateTime.prototype.enable = function() {
	document.getElementById('second'+this.id).disabled=false;
	document.getElementById('minute'+this.id).disabled=false;
	document.getElementById('hour'+this.id).disabled=false;
	document.getElementById('day'+this.id).disabled=false;
	document.getElementById('month'+this.id).disabled=false;
	document.getElementById('year'+this.id).disabled=false;
};

XWGFormDateTime.prototype.setHint = function(str) {
	var obj = document.getElementById(this.id+'-HINT');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormDateTime.prototype.setError = function(str) {
	var obj = document.getElementById(this.id+'-ERROR');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormDateTime.prototype.blinkError = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-ERROR\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-ERROR\').style.visibility=\'visible\';',millis+100);
};


XWGFormDateTime.prototype.blinkHint = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-HINT\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-HINT\').style.visibility=\'visible\';',millis+100);
};

XWGFormDateTime.prototype.getValue = function () {
	return document.getElementById('value'+this.id).value;
};

XWGFormDateTime.prototype.getDay = function () {
	var val = document.getElementById('value'+this.id).value;
	return parseInt(val.substr(6,2));
};

XWGFormDateTime.prototype.getMonth = function () {
	var val = document.getElementById('value'+this.id).value;
	return parseInt(val.substr(4,2));
};

XWGFormDateTime.prototype.getYear = function () {
	var val = document.getElementById('value'+this.id).value;
	return parseInt(val.substr(0,4));
};

function blinkSomething(id) {
	var element=document.getElementById(id);
	if (element.style.visibility=='hidden') {
		element.style.visibility='visible';
	}
	else {
		element.style.visibility='hidden';
	}
};

XWGFormDateTime.prototype.setDay = function (day) {
	document.getElementById('day'+this.id).options[day-1].selected=true;
	this.validate();
};

XWGFormDateTime.prototype.setMonth = function (month) {
	document.getElementById('month'+this.id).options[month-1].selected=true;
	this.validate();
};

XWGFormDateTime.prototype.setYear = function (year) {
	var element = document.getElementById('year'+this.id);
	for (var i=0;i<element.options.length;i++) {
		if (element.options[i].value==year) {
			element.options[i].selected=true;
		}
	}
	this.validate();
};

XWGFormDateTime.prototype.validate = function () {
	
	// -------- Values ---------
	
	var daysofmonth   = new Array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var daysofmonthLY = new Array( 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	
	// -------- Get values ---------

	var second=this.parseNumber($id('second'+this.id).value,0,59);
	var minute=this.parseNumber($id('minute'+this.id).value,0,59);
	var hour=this.parseNumber($id('hour'+this.id).value,0,23);
	var day=this.parseNumber($id('day'+this.id).value,1,31);
	var month=this.parseNumber($id('month'+this.id).value,1,12);
	var year=this.parseNumber($id('year'+this.id).value,0,6000);
	// -------- Validate days of month ---------
	
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

	$id('second'+this.id).value=combined.substr(12,2);
	$id('minute'+this.id).value=combined.substr(10,2);
	hour=$id('hour'+this.id).value=combined.substr(8,2);
	day=$id('day'+this.id).value=combined.substr(6,2)
	month=$id('month'+this.id).value=combined.substr(4,2);
	year=$id('year'+this.id).value=combined.substr(0,4);
	$id('value'+this.id).value=combined
};


XWGFormDateTime.prototype.parseNumber = function(value,min,max) {
	value=parseInt(value,10);
	if (isNaN(value) || value<min) {
		value = min;
	} else if (value>max) {
		value = max;
	}
	return value;
};

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