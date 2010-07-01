function In2iGuiWindowStatusbar(id,style,text,status,statusPath) {
	this.id=id;
	this.style=style;
	this.text=text;
	this.status=status;
	this.statusPath=statusPath;
}

In2iGuiWindowStatusbar.prototype.getStyle = function() {
	return this.style;
};

In2iGuiWindowStatusbar.prototype.getText = function() {
	return this.text;
};

In2iGuiWindowStatusbar.prototype.getStatus = function () {
	return this.status;
};

In2iGuiWindowStatusbar.prototype.setStyle = function (style) {
	this.style=style;
	document.getElementById(this.id+'TD').className='WindowStatusbar'+this.style;
};

In2iGuiWindowStatusbar.prototype.setStatus = function (status) {
	this.status=status;
	document.getElementById(this.id+'STATUS').src=this.statusPath+'Status'+this.status+'.gif';
	document.getElementById(this.id+'STATUS').style.display='';
};

In2iGuiWindowStatusbar.prototype.setText = function (text) {
	this.text=text;
	document.getElementById(this.id+'TEXT').innerHTML=this.text;
};

In2iGuiWindowStatusbar.prototype.blink = function (millis) {
	var b = setInterval('blinker(\''+this.id+'\')',200);
	setTimeout('window.clearInterval('+b+');',millis);
	setTimeout('document.getElementById(\''+this.id+'TEXT\').style.visibility=\'visible\';',millis+100);
};

function blinker(id) {
	var element=document.getElementById(id+'TEXT');
	if (element.style.visibility=='hidden') {
		element.style.visibility='visible';
	}
	else {
		element.style.visibility='hidden';
	}
};

In2iGuiWindowStatusbar.prototype.hideStatus = function () {
	this.status=status;
	document.getElementById(this.id+'STATUS').style.display='none';
};

In2iGuiWindowStatusbar.prototype.hide = function (millis) {
	if (millis==null) {
		document.getElementById(this.id+'TR').style.display='none';
	}
	else {
		setTimeout('document.getElementById(\''+this.id+'TR\').style.display=\'none\'',millis);
	}
};

In2iGuiWindowStatusbar.prototype.show = function () {
	document.getElementById(this.id+'TR').style.display='';
};

In2iGuiWindowStatusbar.prototype.empty = function () {
	this.setText('&nbsp;');
	this.hideStatus();
};

function pause(millis) {
	var d=new Date();
	while (1) {
		mill=new Date();
		diff = mill-d;
		if (diff > millis) {
			break;
		}
	}
}