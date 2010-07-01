function XWGFormPassword(id) {
	this.id=id;
}

XWGFormPassword.prototype.focus = function() {
	return document.getElementById(this.id).focus();
};

XWGFormPassword.prototype.disable = function() {
	document.getElementById(this.id).disabled=true;
};

XWGFormPassword.prototype.enable = function() {
	document.getElementById(this.id).disabled=false;
};

XWGFormPassword.prototype.getValue = function() {
	return document.getElementById(this.id).value;
};

XWGFormPassword.prototype.setValue = function(str) {
	document.getElementById(this.id).value=str;
};

XWGFormPassword.prototype.setHint = function(str) {
	var obj = document.getElementById(this.id+'-HINT');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormPassword.prototype.setError = function(str) {
	var obj = document.getElementById(this.id+'-ERROR');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormPassword.prototype.isEmpty = function() {
	return !(document.getElementById(this.id).value.length>0);
};


XWGFormPassword.prototype.blinkError = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-ERROR\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-ERROR\').style.visibility=\'visible\';',millis+100);
};


XWGFormPassword.prototype.blinkHint = function (millis) {
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