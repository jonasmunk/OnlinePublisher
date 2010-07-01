function XWGFormRadiobutton(id) {
	this.id=id;
}

XWGFormRadiobutton.prototype.focus = function() {
	return document.getElementById(this.id).focus();
};

XWGFormRadiobutton.prototype.isSelected = function() {
	return document.getElementById(this.id).checked;
};

XWGFormRadiobutton.prototype.setSelected = function(sel) {
	document.getElementById(this.id).checked=sel;
};

XWGFormRadiobutton.prototype.disable = function() {
	document.getElementById(this.id).disabled=true;
};

XWGFormRadiobutton.prototype.enable = function() {
	document.getElementById(this.id).disabled=false;
};

XWGFormRadiobutton.prototype.setHint = function(str) {
	var obj = document.getElementById(this.id+'-HINT');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormRadiobutton.prototype.setError = function(str) {
	var obj = document.getElementById(this.id+'-ERROR');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};


XWGFormRadiobutton.prototype.blinkError = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-ERROR\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-ERROR\').style.visibility=\'visible\';',millis+100);
};


XWGFormRadiobutton.prototype.blinkHint = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-HINT\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-HINT\').style.visibility=\'visible\';',millis+100);
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