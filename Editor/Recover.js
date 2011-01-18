var controller = {
	$ready : function() {
		password1.focus();
		this.key = n2i.location.getParameter('key');
	},
	$submit$formula : function() {
		var values = formula.getValues();
		if (n2i.isBlank(values.password1) || n2i.isBlank(values.password2)) {
			ui.showMessage({text:'Begge kodeord skal være udfyldt',duration:2000});
			formula.focus();
			return;
		} else if (values.password1!==values.password2) {
			ui.showMessage({text:'Det to kodeord skal være ens',duration:2000});
			formula.focus();
			return;
		}
		change.disable();
		ui.showMessage({text:'Ændrer kode...'});
		ui.request({
			url : 'Services/Core/ChangePassword.php',
			onSuccess : 'change',
			parameters : {key:this.key,password:values.password1},
			onFailure : function() {
				ui.showMessage({text:'Der skete en fejl internt i systemet!',duration:2000});
				change.enable();
			}
		});
	},
	$success$change : function(response) {
		change.enable();
		if (response.success) {
			ui.hideMessage();
			ui.changeState('success');
		} else {
			ui.showMessage({text:'Det lykkedes ikke at ændre kodeordet',duration:2000});
		}
	}
}