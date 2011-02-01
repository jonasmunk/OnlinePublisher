var controller = {
	$ready : function() {
		if (window.top!=window.self) {
			window.top.location=window.self.location;
		} else {
			username.focus();
		}
		if (n2i.browser.msie && !n2i.browser.msie8) {
			ui.alert({
				emotion:'gasp',
				title:'Din software er forældet',
				text:'Systemet understøtter ikke Internet Explorer tidligere end version 8. Opgrader venligst til en nyere version eller fortsæt på eget ansvar.'
			});
		}
	},
	$submit$formula : function() {
		ui.request({
			url:'Services/Core/Authentication.php',
			onSuccess:'login',
			parameters:formula.getValues(),
			onFailure:function() {
				ui.showMessage({text:'Der skete en fejl internt i systemet!',icon:'common/warning',duration:4000});
			}
		});
	},
	$success$login : function(data) {
		if (data.success) {
			var page = n2i.location.getParameter('page');
			document.location=page===null ? './index.php' : '.?page='+page;
		} else {
			ui.showMessage({text:'Brugeren blev ikke fundet!',icon:'common/warning',duration:2000});
			formula.focus();
		}
	},
	
	$click$forgot : function() {
		ui.changeState('recover');
		recoveryForm.focus();
	},
	
	$submit$recoveryForm : function() {
		var text = recoveryForm.getValues()['nameOrMail'];
		ui.showMessage({text:'Leder efter bruger, og sender e-mail...',busy:true});
		ui.request({
			url:'Services/Core/RecoverPassword.php',
			onSuccess:'recovery',
			parameters:{text:text},
			onFailure:function() {
				ui.showMessage({text:'Der skete en fejl internt i systemet!',icon:'common/warning',duration:4000});
			}
		});
	},
	$success$recovery : function(data) {
		if (data.success) {
			ui.hideMessage();
			ui.changeState('recoveryMessage');
		} else {
			ui.showMessage({text:data.message,icon:'common/warning',duration:4000});
		}
	}
}