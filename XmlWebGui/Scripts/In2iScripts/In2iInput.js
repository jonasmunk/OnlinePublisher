if (!N2i) {var N2i = {};}

N2i.TextField = function(field,delegate) {
	this.field = $id(field);
	this.value = this.field.value;
	this.delegate = delegate;
	if (this.delegate.placeholder && this.field.value=='') {
		this.field.value=this.delegate.placeholder;
	}
	this.addBehaviour();
}

N2i.TextField.prototype.addBehaviour = function() {
	var self = this;
	this.field.onfocus = function() {
		self.focus();
	}
	this.field.onblur = function() {
		self.blur();
	}
	this.field.onkeydown = this.field.onkeyup = this.field.onkeypress = function() {
		self.key();
	}
}

N2i.TextField.prototype.getValue = function() {
	return this.value;
}

N2i.TextField.prototype.focus = function() {
	if (this.field.value==this.delegate.placeholder) {
		this.field.value='';
	}
}

N2i.TextField.prototype.blur = function() {
	if (this.field.value=='' && this.delegate.placeholder) {
		this.field.value=this.delegate.placeholder;
	}
}

N2i.TextField.prototype.key = function() {
	if (this.value != this.field.value) {
		this.value = this.field.value;
		if (this.delegate.valueDidChange) {
			this.delegate.valueDidChange(this);
		}
	}
}

/********************** Date time *******************/

N2i.DateTimeField = function(field,delegate) {
	this.field = $id(field);
	this.value = this.field.value;
	this.date = null;
	this.inputFormats = ['d-m-Y H:i:s','d-m-Y H:i','d-m-Y H','d-m-Y','d-m','d/m'];
	this.outputFormat = 'd-m-Y H:i:s';
	this.delegate = delegate || {};
	if (this.delegate.placeholder && this.field.value=='') {
		this.field.value=this.delegate.placeholder;
	}
	this.addBehaviour();
	this.check();
}

N2i.DateTimeField.prototype.addBehaviour = function() {
	var self = this;
	this.field.onfocus = function() {
		self.focus();
	}
	this.field.onblur = function() {
		self.blur();
	}
	this.field.onkeydown = this.field.onkeyup = this.field.onkeypress = function() {
		self.key();
	}
}

N2i.DateTimeField.prototype.check = function() {
	var parsed = null;
	for (var i=0; i < this.inputFormats.length && parsed==null; i++) {
		parsed = Date.parseDate(this.value,	this.inputFormats[i]);
	};
	if (parsed) {
		this.date = parsed;
	}
	this.field.value = this.date.dateFormat(this.outputFormat);
	this.value = this.field.value;
}

N2i.DateTimeField.prototype.getValue = function() {
	return this.value;
}

N2i.DateTimeField.prototype.focus = function() {
	if (this.field.value==this.delegate.placeholder) {
		this.field.value='';
	}
}

N2i.DateTimeField.prototype.blur = function() {
	this.check();
	if (this.field.value=='' && this.delegate.placeholder) {
		this.field.value=this.delegate.placeholder;
	}
}

N2i.DateTimeField.prototype.key = function() {
	if (this.value != this.field.value) {
		this.value = this.field.value;
		if (this.delegate.valueDidChange) {
			this.delegate.valueDidChange(this);
		}
	}
}