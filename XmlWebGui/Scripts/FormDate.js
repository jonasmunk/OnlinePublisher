function XWGFormDate(id,min,max) {
	this.id=id;
	this.min=min;
	this.max=max;
}

XWGFormDate.prototype.disable = function() {
	document.getElementById('day'+this.id).disabled=true;
	document.getElementById('month'+this.id).disabled=true;
	document.getElementById('year'+this.id).disabled=true;
};

XWGFormDate.prototype.enable = function() {
	document.getElementById('day'+this.id).disabled=false;
	document.getElementById('month'+this.id).disabled=false;
	document.getElementById('year'+this.id).disabled=false;
};

XWGFormDate.prototype.setHint = function(str) {
	var obj = document.getElementById(this.id+'-HINT');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormDate.prototype.setError = function(str) {
	var obj = document.getElementById(this.id+'-ERROR');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormDate.prototype.blinkError = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-ERROR\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-ERROR\').style.visibility=\'visible\';',millis+100);
};


XWGFormDate.prototype.blinkHint = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-HINT\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-HINT\').style.visibility=\'visible\';',millis+100);
};

XWGFormDate.prototype.getValue = function () {
	return document.getElementById('value'+this.id).value;
};

XWGFormDate.prototype.getDay = function () {
	var val = document.getElementById('value'+this.id).value;
	return parseInt(val.substr(6,2));
};

XWGFormDate.prototype.getMonth = function () {
	var val = document.getElementById('value'+this.id).value;
	return parseInt(val.substr(4,2));
};

XWGFormDate.prototype.getYear = function () {
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

XWGFormDate.prototype.setDay = function (day) {
	document.getElementById('day'+this.id).options[day-1].selected=true;
	this.validate();
};

XWGFormDate.prototype.setMonth = function (month) {
	document.getElementById('month'+this.id).options[month-1].selected=true;
	this.validate();
};

XWGFormDate.prototype.setYear = function (year) {
	var element = document.getElementById('year'+this.id);
	for (var i=0;i<element.options.length;i++) {
		if (element.options[i].value==year) {
			element.options[i].selected=true;
		}
	}
	this.validate();
};

XWGFormDate.prototype.validate = function validate() {
	
	// -------- Values ---------
	
	var daysofmonth   = new Array( 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var daysofmonthLY = new Array( 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	
	var minday=parseInt(this.min.substr(6,2));
	var minmonth=parseInt(this.min.substr(4,2));
	var minyear=parseInt(this.min.substr(0,4));
	var maxday=parseInt(this.max.substr(6,2));
	var maxmonth=parseInt(this.max.substr(4,2));
	var maxyear=parseInt(this.max.substr(0,4));
	
	
	// -------- Get selected values ---------

	var day=document.getElementById('day'+this.id).value;
	if (day.length==1) day='0'+day;
	var month=document.getElementById('month'+this.id).value;
	if (month.length==1) month='0'+month;
	var year=document.getElementById('year'+this.id).value;
	var combined=parseInt(year+month+day);
	
	
	// ----------- Validate min/max values --------
	
	if (combined<parseInt(this.min)) {
		document.getElementById('day'+this.id).options[minday-1].selected=true;
		document.getElementById('month'+this.id).options[minmonth-1].selected=true;
	}
	if (combined>parseInt(this.max)) {
		document.getElementById('day'+this.id).options[maxday-1].selected=true;
		document.getElementById('month'+this.id).options[maxmonth-1].selected=true;
	}

	
	// -------- Validate days of month ---------
	
	var monthdays=0
	if (LeapYear(year)) {
		monthdays=daysofmonthLY[month-1];
	}
	else {
		monthdays=daysofmonth[month-1];
	}
	if (monthdays<day) {
		document.getElementById('day'+this.id).options[monthdays-1].selected=true;
	}

	
	// -------- Put validated into new value ---------

	day=document.getElementById('day'+this.id).value;
	if (day.length==1) day='0'+day;
	month=document.getElementById('month'+this.id).value;
	if (month.length==1) month='0'+month;
	year=document.getElementById('year'+this.id).value;
	combined=parseInt(year+month+day);
	document.getElementById('value'+this.id).value=combined
}

function LeapYear(year) {
    if ((year/4)   != Math.floor(year/4))   return false;
    if ((year/100) != Math.floor(year/100)) return true;
    if ((year/400) != Math.floor(year/400)) return false;
    return true;
}