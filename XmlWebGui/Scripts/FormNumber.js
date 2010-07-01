function XWGFormNumber(id,delimiter,decimals,min,max,empty) {
	this.id=id;
	if (delimiter.length==1) this.delimiter = delimiter;
	if (!isNaN(parseInt(decimals))) {
		this.decimals=parseInt(decimals);
	}
	if (!isNaN(parseFloat(min))) {
		this.min=parseFloat(min);
	}
	if (!isNaN(parseFloat(max))) {
		this.max=parseFloat(max);
	}
	this.empty=(empty=='true');
}

XWGFormNumber.prototype.validate = function() {
	var value=document.getElementById(this.id).value;
	
	if (this.delimiter!=null) {
		var validChars='0123456789+-'+this.delimiter;
	}
	else {
		var validChars='0123456789+-.';
	}
	var validValue=getValidCharacters(value,validChars);
	
	if (!this.empty || validValue.length>0) {
		
		if (this.delimiter!=null) {
			var x = new RegExp(this.delimiter,"i");
			validValue=validValue.replace(x,'.');
		}
		value=parseFloat(validValue);
		if (isNaN(value)) {
			value=0;
		}
		
		if (this.decimals!=null) {
			value=Math.round(value*Math.pow(10,this.decimals))/Math.pow(10,this.decimals)
		}
		
		if (this.min!=null && value<this.min) {
			value=this.min;
		}
		if (this.max!=null && value>this.max) {
			value=this.max;
		}
		this.setValue(value);
	}
	else {
		this.setValue('');
	}
}

XWGFormNumber.prototype.focus = function() {
	return document.getElementById(this.id).focus();
};

XWGFormNumber.prototype.disable = function() {
	document.getElementById(this.id).disabled=true;
};

XWGFormNumber.prototype.enable = function() {
	document.getElementById(this.id).disabled=false;
};

XWGFormNumber.prototype.getValue = function() {
	return document.getElementById(this.id+'-HIDDEN').value;
};

XWGFormNumber.prototype.setValue = function(str) {
	document.getElementById(this.id+'-HIDDEN').value=str;
	if (this.delimiter) {
		str=String(str).replace(/\./i,this.delimiter);
	}
	document.getElementById(this.id).value=str;
};

XWGFormNumber.prototype.setHint = function(str) {
	var obj = document.getElementById(this.id+'-HINT');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormNumber.prototype.setError = function(str) {
	var obj = document.getElementById(this.id+'-ERROR');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormNumber.prototype.isEmpty = function() {
	return !(document.getElementById(this.id).value.length>0);
};


XWGFormNumber.prototype.blinkError = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-ERROR\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-ERROR\').style.visibility=\'visible\';',millis+100);
};


XWGFormNumber.prototype.blinkHint = function (millis) {
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

function getValidCharacters(input,chars) {
	output='';
	for (i=0;i<input.length;i++) {
		if (chars.indexOf(input.charAt(i))!=-1) {
			output=output+input.charAt(i);
		}
	}
	return output;
}