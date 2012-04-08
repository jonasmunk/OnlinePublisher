var controller = {
	$ready : function() {
		if (window.top!=window.self) {
			window.top.location=window.self.location;
		} else {
			username.focus();
		}
		if (hui.browser.msie && (!hui.browser.msie8 && !hui.browser.msie9)) {
			if (hui.browser.msie9compat) {
				hui.ui.alert({
					emotion : 'gasp',
					title : '"Compatibility View" er slået til',
					text : 'Det ser ud til at du har slået "Compatibility View" til. Slå det venligst fra for en mere stabil oplevelse. Det gøres ved at klikke på det blå dokument-ikon i adresse linjen øverst.'
				});
			} else {
				hui.ui.alert({
					emotion:'gasp',
					title:'Din software er forældet',
					text:'Systemet understøtter ikke Internet Explorer tidligere end version 8. Opgrader venligst til en nyere version eller fortsæt på eget ansvar.'
				});
			}
		}
		if (hui.location.getBoolean('logout')) {
			hui.ui.showMessage({text:'Du er nu logget ud',icon:'common/success',duration:2000});
		}
		else if (hui.location.getBoolean('forgot')) {
			this.$click$forgot();
		}
		hui.ui.request({
			method : 'GET',
			url : '../hui/info/preload.json',
			onJSON : function(obj) {
				var p = new hui.Preloader({context:hui.ui.context+'/hui'});
				p.addImages(obj);
				p.setDelegate({
					imageDidLoad : function(count,total) {
						hui.log(count/total);
					}
				});
				p.load();
			}
		});
	},
	$submit$formula : function() {
		if (this.loggingIn) {
			return;
		}
		var values = formula.getValues();
		if (username.isBlank()) {
			username.stress();
			username.focus();
			return;
		}
		if (password.isBlank()) {
			password.stress();
			password.focus();
			return;
		}
		hui.ui.showMessage({text:'Logger ind...',busy:true,delay:100});
		this.loggingIn = true;
		login.disable();
		hui.ui.request({
			url:'Services/Core/Authentication.php',
			onSuccess : 'login',
			parameters : values,
			onFailure:function() {
				hui.ui.showMessage({text:'Der skete en fejl internt i systemet!',icon:'common/warning',duration:4000});
				this._enableLogin();
			}.bind(this)
		});
	},
	$success$login : function(data) {
		if (data.success) {
			hui.ui.showMessage({text:'Du er nu logget ind, øjeblik...',icon:'common/success',delay:200});
			if (hui.browser.ipad) {
				document.location = './Touch/';
			} else {
				var page = hui.location.getParameter('page');
				document.location = page===null ? './index.php' : '.?page='+page;
			}
		} else {
			box.shake();
			//hui.ui.stress(box);
			hui.ui.showMessage({text:'Brugeren blev ikke fundet!',icon:'common/warning',duration:2000});
			formula.focus();
		}
		this._enableLogin();
	},
	_enableLogin : function() {
		this.loggingIn = false;
		login.enable();
	},
	
	$click$forgot : function() {
		hui.ui.changeState('recover');
		recoveryForm.focus();
	},
	
	$submit$recoveryForm : function() {
		var text = recoveryForm.getValues()['nameOrMail'];
		hui.ui.showMessage({text:'Leder efter bruger, og sender e-mail...',busy:true});
		hui.ui.request({
			url:'Services/Core/RecoverPassword.php',
			onSuccess:'recovery',
			parameters:{text:text},
			onFailure:function() {
				hui.ui.showMessage({text:'Der skete en fejl internt i systemet!',icon:'common/warning',duration:4000});
			}
		});
	},
	$success$recovery : function(data) {
		if (data.success) {
			hui.ui.hideMessage();
			hui.ui.changeState('recoveryMessage');
		} else {
			hui.ui.showMessage({text:data.message,icon:'common/warning',duration:4000});
		}
	},
	
	// Database...
	
	$click$updateDatabase : function() {
		databaseWindow.show();
		databaseFormula.focus();
	},
	
	$submit$databaseFormula : function(form) {
		var values = form.getValues();
		if (hui.isBlank(values.username) || hui.isBlank(values.password)) {
			form.focus();
			return;
		}
		hui.ui.showMessage({text:'Opdaterer database...',busy:true});
		hui.ui.request({
			url : 'Services/Core/UpdateDatabase.php',
			parameters : values,
			onFailure : function() {
				hui.ui.showMessage({text:'Der skete en fejl internt i systemet!',icon:'common/warning',duration:4000});
			},
			onForbidden : function() {
				hui.ui.showMessage({text:'Bruger eller kode er ikke korrekt',icon:'common/warning',duration:4000});
				form.focus();
			},
			onJSON : function(response) {
				databaseLog.setValue(response.log);
				databaseLogWindow.show();
				if (!response.updated) {
					hui.ui.showMessage({text:'Databasen er endnu ikke fuldt opdateret, prøv igen',icon:'common/warning',duration:4000});
					return;
				}
				hui.ui.showMessage({text:'Databasen er nu opdateret',icon:'common/success',duration:4000});
				databaseFormula.reset();
				databaseWindow.hide();
				hui.ui.changeState('login');
				formula.focus()
			}
		});
	},
	
	// Admin...
	$click$createAdmin : function() {
		adminWindow.show();
		adminFormula.focus();
	},
	$submit$adminFormula : function(form) {
		var values = form.getValues();
		if (hui.isBlank(values.superUsername) || hui.isBlank(values.superPassword) || hui.isBlank(values.adminUsername) || hui.isBlank(values.adminPassword)) {
			form.focus();
			return;
		}
		hui.ui.request({
			url : 'Services/Core/CreateAdministrator.php',
			parameters : values,
			onFailure : function() {
				hui.ui.showMessage({text:'Der skete en fejl internt i systemet!',icon:'common/warning',duration:4000});
			},
			onForbidden : function() {
				hui.ui.showMessage({text:'Bruger eller kode er ikke korrekt',icon:'common/warning',duration:4000});
				form.focus();
			},
			onSuccess : function(response) {
				hui.ui.showMessage({text:'Administratoren er nu oprettet',icon:'common/success',duration:4000});
				form.reset();
				adminWindow.hide();
				hui.ui.changeState('login');
				formula.focus()
			}
		});
	}
}