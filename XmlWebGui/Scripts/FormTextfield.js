function XWGFormTextfield(id) {
	this.id=id;
}

XWGFormTextfield.prototype.focus = function() {
	return document.getElementById(this.id).focus();
};

XWGFormTextfield.prototype.disable = function() {
	document.getElementById(this.id).disabled=true;
};

XWGFormTextfield.prototype.enable = function() {
	document.getElementById(this.id).disabled=false;
};

XWGFormTextfield.prototype.getValue = function() {
	return document.getElementById(this.id).value;
};

XWGFormTextfield.prototype.setValue = function(str) {
	document.getElementById(this.id).value=str;
};

XWGFormTextfield.prototype.setHint = function(str) {
	var obj = document.getElementById(this.id+'-HINT');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormTextfield.prototype.setError = function(str) {
	var obj = document.getElementById(this.id+'-ERROR');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormTextfield.prototype.isEmpty = function() {
	return !(document.getElementById(this.id).value.length>0);
};


XWGFormTextfield.prototype.blinkError = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-ERROR\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-ERROR\').style.visibility=\'visible\';',millis+100);
};


XWGFormTextfield.prototype.blinkHint = function (millis) {
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