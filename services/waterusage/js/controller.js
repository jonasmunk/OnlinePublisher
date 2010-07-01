function Controller() {
	this.numberField = new N2i.TextField('number');
	this.dateField = new N2i.DateField('date');
	this.valueField = new N2i.TextField('value');
	this.submitButton = $id('submit');
	this.addBehavior();
	this.reset();
}

Controller.prototype.reset = function() {
	this.numberField.setValue('');
	this.dateField.setDate(new Date());
	this.valueField.setValue('');
	this.checkEnabling();
	this.numberField.focus();
}

Controller.prototype.addBehavior = function() {
	var self = this;
	this.numberField.setDelegate({
		valueDidChange : function(field) {
			if (field.getValue().length > 7) {
				self.load(field.getValue());
			}
			self.checkEnabling();
		}
	});
	this.valueField.setDelegate({
		valueDidChange : function(field) {
			self.checkEnabling();
		}
	});
	this.submitButton.onclick = function() {
		self.save();
	}
}

Controller.prototype.checkEnabling = function() {
	if (this.numberField.getValue().length > 7) {
		this.dateField.setEnabled(true);
		this.valueField.setEnabled(true);
		this.submitButton.disabled = false;
		$id('number_error').innerHTML = '';
		if (this.valueField.getValue().length>0) {
			this.submitButton.disabled = false;
			$id('value_error').innerHTML = '';
		} else {
			$id('value_error').innerHTML = 'Udfyld dette felt med aflæsningen af måleren';
			this.submitButton.disabled = true;
		}
	} else {
		this.dateField.setEnabled(false);
		this.valueField.setEnabled(false);
		$id('number_error').innerHTML = 'Målernummeret skal være på mindst 8 tegn';
		$id('latest').innerHTML = 'Her vises seneste aflæsning når du har indtastet målernummeret.'
		$id('value_error').innerHTML = '';
		this.submitButton.disabled = true;
	}
}

Controller.prototype.parse = function(doc) {
	var value = doc.getElementsByTagName('value')[0];
	var date = doc.getElementsByTagName('date')[0];
	var msg = '';
	if (date.firstChild && date.firstChild.nodeType==3) {
		msg += '<strong>Dato:</strong> '+date.firstChild.nodeValue+'<br/>';
	}
	if (value.firstChild && value.firstChild.nodeType==3) {
		msg += '<strong>Aflæsning:</strong> '+value.firstChild.nodeValue+'<br/>';
	}
	if (msg.length==0) msg = 'Der er ingen tidligere registrering.';
	$id('latest').innerHTML=msg;
}

Controller.prototype.load = function(value) {
	var self = this;
	var delegate = {
		onSuccess : function(t) {
			self.parse(t.responseXML);
		},onError : function(t,ex) {alert(ex)}
	}
	$get('read.php?number='+value,delegate);
}

Controller.prototype.save = function() {
	var number = this.numberField.getValue();
	var date = this.dateField.getDate().dateFormat('Y-m-d');
	var value = this.valueField.getValue();
	
	var self = this;
	var delegate = {
		onSuccess : function(t) {
			alert('Din aflæsning er nu registreret');
			self.reset();
		},
		onFailure : function() {
			alert('Der skete desværre en teknisk fejl under registreringen.')
		},onError : function(t,ex) {alert(ex)}
	}
	$get('write.php?number='+number+'&date='+date+'&value='+value,delegate);
}

N2i.Event.addLoadListener(function() {
	new Controller();
});