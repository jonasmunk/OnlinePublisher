var controller = {
	$ready : function() {
		formula1.setValues({username:'john',password:'pass',radios:'ipsum',tokens:['hep','hop','hey']});
	},
	$click$showValues : function() {
		var v = formula1.getValues();
		alert(Object.toJSON(v));
	}
}