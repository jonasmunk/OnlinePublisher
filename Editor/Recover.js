var controller = {
	$ready : function() {
		password1.focus();
		this.key = hui.location.getParameter('key');
	},
	$submit$formula : function() {
		var values = formula.getValues();
		if (hui.isBlank(values.password1) || hui.isBlank(values.password2)) {
			hui.ui.showMessage({text:'Begge kodeord skal være udfyldt',duration:2000});
			formula.focus();
			return;
		} else if (values.password1!==values.password2) {
			hui.ui.showMessage({text:'Det to kodeord skal være ens',duration:2000});
			formula.focus();
			return;
		}
		change.disable();
		hui.ui.showMessage({text:'Ændrer kode...'});
		hui.ui.request({
			url : 'Services/Core/ChangePassword.php',
			onSuccess : 'change',
			parameters : {key:this.key,password:values.password1},
			onFailure : function() {
				hui.ui.showMessage({text:'Der skete en fejl internt i systemet!',duration:2000});
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
			hui.ui.showMessage({text:'Det lykkedes ikke at ændre kodeordet',duration:2000});
		}
	}
}