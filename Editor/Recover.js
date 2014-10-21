var controller = {
	$ready : function() {
		password1.focus();
		this.key = hui.location.getParameter('key');
	},
	$submit$formula : function() {
		var values = formula.getValues();
		if (hui.isBlank(values.password1) || hui.isBlank(values.password2)) {
			hui.ui.showMessage({text:{en:'Please fill in both password',da:'Begge kodeord skal være udfyldt'},duration:2000});
			formula.focus();
			return;
		} else if (values.password1!==values.password2) {
			hui.ui.showMessage({text:{en:'The two passwords must be the same',da:'De to kodeord skal være ens'},duration:2000});
			formula.focus();
			return;
		}
		change.disable();
		hui.ui.showMessage({text:{en:'Changing password...',da:'Ændrer kode...'}});
		hui.ui.request({
			url : 'Services/Core/ChangePassword.php',
			$success : 'change',
			parameters : {key:this.key,password:values.password1},
			$failure : function() {
				hui.ui.showMessage({text:{en:'An internal error occurred',da:'Der skete en fejl internt i systemet'},duration:2000});
				change.enable();
			}
		});
	},
	$success$change : function(response) {
		change.enable();
		if (response.success) {
			hui.ui.hideMessage();
			hui.ui.changeState('success');
		} else {
			hui.ui.showMessage({text:{en:'It was not possible to change the password',da:'Det lykkedes ikke at ændre kodeordet'},duration:2000});
		}
	},
	$click$english : function() {
		hui.location.setParameter('language','en');
	},
	$click$danish : function() {
		hui.location.setParameter('language','da');
	}

}