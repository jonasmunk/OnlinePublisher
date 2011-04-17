var controller = {
	$ready : function() {
		if (window.top!=window.self) {
			window.top.location=window.self.location;
		} else {
			username.focus();
		}
		if (n2i.browser.msie && (!n2i.browser.msie8 && !n2i.browser.msie9)) {
			ui.alert({
				emotion:'gasp',
				title:'Din software er forældet',
				text:'Systemet understøtter ikke Internet Explorer tidligere end version 8. Opgrader venligst til en nyere version eller fortsæt på eget ansvar.'
			});
		}
		if (n2i.location.getBoolean('logout')) {
			ui.showMessage({text:'Du er nu logget ud',icon:'common/success',duration:2000});
		}
		ui.request({
			url : '../In2iGui/info/preload.json',
			onJSON : function(obj) {
				var p = new n2i.Preloader({context:ui.context+'/In2iGui'});
				p.addImages(obj);
				p.setDelegate({
					imageDidLoad : function(count,total) {
						n2i.log(count/total);
					}
				});
				p.load();
			}
		});
	},
	$submit$formula : function() {
		ui.showMessage({text:'Logger ind...',busy:true,delay:100});
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
			ui.showMessage({text:'Du er nu logget ind, øjeblik...',icon:'common/success',delay:200});
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