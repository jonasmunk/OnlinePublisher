function XWGMatrix(id) {
	this.id=id;
}

XWGMatrix.prototype.toggleBoolean = function(id) {
	var addedField = document.getElementById('matrix_'+this.id+'_added');
	var removedField = document.getElementById('matrix_'+this.id+'_removed');
	var element = document.getElementById(id);
	if (element.checked) {
		if (this.existsInField(removedField,element.value)) {
			this.removeFromField(removedField,element.value);
		} else {
			this.addToField(addedField,element.value);
		}
	} else {
		if (this.existsInField(addedField,element.value)) {
			this.removeFromField(addedField,element.value);
		} else {
			this.addToField(removedField,element.value);
		}
	}
};

XWGMatrix.prototype.addToField = function(field,value) {
	var fieldValue = field.value;
	if (fieldValue.length>0) {
		fieldValue+=','+value;
	} else {
		fieldValue=value;
	}
	field.value=fieldValue;
}

XWGMatrix.prototype.existsInField = function(field,value) {
	var output = false;
	var fieldArray = field.value.split(/,/);
	for (var i=0;i<fieldArray.length;i++) {
		if (fieldArray[i]==value) {
			output=true;
			break;
		}
	}
	return output;
}

XWGMatrix.prototype.removeFromField = function(field,value) {
	var output = false;
	var fieldArray = field.value.split(/,/);
	var newValue = '';
	for (var i=0;i<fieldArray.length;i++) {
		if (fieldArray[i]!=value) {
			if (newValue.length>0) newValue+=',';
			newValue+=fieldArray[i];
		}
	}
	field.value=newValue;
}