function RichText(id) {
    var agt=navigator.userAgent.toLowerCase();
	this.id=id;
	this.compatible = (agt.indexOf('msie 6')>-1 || (agt.indexOf('gecko')>-1 && agt.indexOf('safari')<0));
	if (this.compatible) {
		var html=document.getElementById("data").value;
		document.getElementById("IFRAME").contentWindow.document.designMode='on';
		if (agt.indexOf('msie 6')>-1) {
			var action = 'document.getElementById("IFRAME").contentWindow.document.body.innerHTML="'+html+'"';
			action=action.replace('\n','');
			action=action.replace('\r','');
			setTimeout(action,500);
		}
		else {
			document.getElementById('IFRAME').contentWindow.document.body.innerHTML=html;
		}
	}
	else {
		document.getElementById(this.id+"-IFRAME").style.display='none';
		document.getElementById(this.id+"-BUTTONS").style.display='none';
		document.getElementById(this.id+"-VALUE").style.display='';
	}
}

RichText.prototype.command = function(cmd) {
	document.getElementById("IFRAME").contentWindow.document.execCommand(cmd,false,null);
};

RichText.prototype.format = function(tag) {
	document.getElementById("IFRAME").contentWindow.document.execCommand("formatblock",false,"<"+tag+">");
};

RichText.prototype.save = function() {
	if (this.compatible) {
		document.getElementById("data").value=document.getElementById("IFRAME").contentWindow.document.body.innerHTML;
	}
};

RichText.prototype.getValue = function() {
	this.save();
	return document.getElementById("data").value;
	//return document.getElementById("IFRAME").contentWindow.document.body.innerHTML;
};

RichText.prototype.setValue = function(val) {
	this.save();
	document.getElementById(this.id+"-VALUE").value=val;
	if (this.compatible) {
		document.getElementById(this.id+'-IFRAME').contentWindow.document.body.innerHTML=val;
	}
};
