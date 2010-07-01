In2iGui.BarFormTextfield = function(id,objectName) {
	this.id=id;
	this.objectName=objectName;
	this.delegate = null;
	this.textfield = document.getElementById(this.id);
	this.textfield.xwgController = this;
	this.textfield.onkeydown = function(event) {
		return this.xwgController.handleEvent(event,'keyDown');
	}
	this.textfield.onkeyup = function(event) {
		return this.xwgController.handleEvent(event,'keyUp');
	}
	this.textfield.onkeypress = function(event) {
		return this.xwgController.handleEvent(event,'keyPress');
	}
	this.value=this.textfield.value;
	this.delay=0;
	this.timer=null;
}

In2iGui.BarFormTextfield.prototype.getObjectName = function() {
	return this.objectName;
}

In2iGui.BarFormTextfield.prototype.delayEvent = function(event,type) {
	if (this.delay>0) {
		if (this.timer!=null) {
			window.clearTimeout(this.timer);
		}
		this.timer = window.setTimeout(function() {},this.delay);
	} else {
		this.handleEvent(event,type);
	}
}

In2iGui.BarFormTextfield.prototype.handleEvent = function(event,type) {
	this.callDelegate(event,type);
	if (this.value!=this.textfield.value) {
		this.value = this.textfield.value;
		this.callDelegate(event,'valueChanged');
	}
	return true;
}

In2iGui.BarFormTextfield.prototype.callDelegate = function(event,type) {
	if (this.delegate) {
		try {
			eval('this.delegate.'+type+'(event,this)');
		} catch(e) {}
	}	
}

In2iGui.BarFormTextfield.prototype.setDelegate = function(delegate) {
	this.delegate = delegate;
};

In2iGui.BarFormTextfield.prototype.focus = function() {
	return document.getElementById(this.id).focus();
};

In2iGui.BarFormTextfield.prototype.disable = function() {
	document.getElementById(this.id).disabled=true;
};

In2iGui.BarFormTextfield.prototype.enable = function() {
	document.getElementById(this.id).disabled=false;
};

In2iGui.BarFormTextfield.prototype.getValue = function() {
	return document.getElementById(this.id).value;
};

In2iGui.BarFormTextfield.prototype.setValue = function(str) {
	this.textfield.value = str;
	this.value = this.textfield.value;
};

In2iGui.BarFormTextfield.prototype.isEmpty = function() {
	return !(document.getElementById(this.id).value.length>0);
};