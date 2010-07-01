In2iGui.Form = function(id) {
	this.id=id;
}

In2iGui.Form.prototype.submit = function() {
	var valid = eval('validate'+this.id+'()')==true;
	if (valid) {
		document.getElementById(this.id).submit();
	}
	//return valid;
};



function inverseCheckboxes(FormObj,names) {
	for(i=0;i<FormObj.length;i++) {
		if(FormObj[i].type=='checkbox' && FormObj[i].name==names) {
			if (FormObj[i].checked)
				FormObj[i].checked=false;
			else
				FormObj[i].checked=true;
		}
	}
}

function inverseCheckboxes(names) {
	FormObj=document.forms[0];
	for(i=0;i<FormObj.length;i++) {
		if(FormObj[i].type=='checkbox' && FormObj[i].name==names) {
			if (FormObj[i].checked)
				FormObj[i].checked=false;
			else
				FormObj[i].checked=true;
		}
	}
}

function inverseCheckboxes() {
	FormObj=document.forms[0];
	for(i=0;i<FormObj.length;i++) {
		if(FormObj[i].type=='checkbox') {
			if (FormObj[i].checked)
				FormObj[i].checked=false;
			else
				FormObj[i].checked=true;
		}
	}
}