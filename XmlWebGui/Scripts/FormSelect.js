function XWGFormSelect(id) {
	this.id=id;
}

XWGFormSelect.prototype.getValue = function() {
	var element = document.getElementById(this.id);
	return element.options[element.selectedIndex].value;
};

XWGFormSelect.prototype.getText = function() {
	var element = document.getElementById(this.id);
	return element.options[element.selectedIndex].text;
};

XWGFormSelect.prototype.setValue = function(value) {
	var element = document.getElementById(this.id);
	for (var i=0;i<element.options.length;i++) {
		if (element.options[i].value==value) {
			element.options[i].selected=true;
		}
	}
};

XWGFormSelect.prototype.focus = function() {
	return document.getElementById(this.id).focus();
};

XWGFormSelect.prototype.disable = function() {
	document.getElementById(this.id).disabled=true;
};

XWGFormSelect.prototype.enable = function() {
	document.getElementById(this.id).disabled=false;
};

XWGFormSelect.prototype.setHint = function(str) {
	var obj = document.getElementById(this.id+'-HINT');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormSelect.prototype.setError = function(str) {
	var obj = document.getElementById(this.id+'-ERROR');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};


XWGFormSelect.prototype.blinkError = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-ERROR\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-ERROR\').style.visibility=\'visible\';',millis+100);
};


XWGFormSelect.prototype.blinkHint = function (millis) {
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