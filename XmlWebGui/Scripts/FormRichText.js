function XWGFormRichText(id) {
    var agt=navigator.userAgent.toLowerCase();
	this.id=id;
	this.compatible = (agt.indexOf('msie 6')>-1 || (agt.indexOf('gecko')>-1 && agt.indexOf('safari')<0));
	if (this.compatible) {
		var html=document.getElementById(this.id+"-VALUE").value;
		document.getElementById(this.id+"-IFRAME").contentWindow.document.designMode='on';
		if (agt.indexOf('msie 6')>-1) {
			var action = 'document.getElementById("'+this.id+'-IFRAME").contentWindow.document.body.innerHTML="'+html+'"';
			action=action.replace('\n','');
			action=action.replace('\r','');
			setTimeout(action,500);
		}
		else {
			document.getElementById(this.id+'-IFRAME').contentWindow.document.body.innerHTML=html;
		}
	
		//
	}
	else {
		document.getElementById(this.id+"-IFRAME").style.display='none';
		document.getElementById(this.id+"-BUTTONS").style.display='none';
		document.getElementById(this.id+"-VALUE").style.display='';
	}
}

XWGFormRichText.prototype.command = function(cmd) {
	document.getElementById(this.id+"-IFRAME").contentWindow.document.execCommand(cmd,false,null);
};

XWGFormRichText.prototype.format = function(tag) {
	document.getElementById(this.id+"-IFRAME").contentWindow.document.execCommand("formatblock",false,"<"+tag+">");
};

XWGFormRichText.prototype.save = function() {
	if (this.compatible) {
		document.getElementById(this.id+"-VALUE").value=document.getElementById(this.id+"-IFRAME").contentWindow.document.body.innerHTML;
	}
};

XWGFormRichText.prototype.getValue = function() {
	this.save();
	return document.getElementById(this.id+"-VALUE").value;
};

XWGFormRichText.prototype.setValue = function(val) {
	this.save();
	document.getElementById(this.id+"-VALUE").value=val;
	if (this.compatible) {
		document.getElementById(this.id+'-IFRAME').contentWindow.document.body.innerHTML=val;
	}
};



XWGFormRichText.prototype.setHint = function(str) {
	var obj = document.getElementById(this.id+'-HINT');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormRichText.prototype.setError = function(str) {
	var obj = document.getElementById(this.id+'-ERROR');
	obj.innerHTML=str;
	if (str.length>0) {
		obj.style.display='block';
	}
	else {
		obj.style.display='none';
	}
};

XWGFormRichText.prototype.blinkError = function (millis) {
	var b = setInterval('blinkSomething(\''+this.id+'-ERROR\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'-ERROR\').style.visibility=\'visible\';',millis+100);
};


XWGFormRichText.prototype.blinkHint = function (millis) {
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
