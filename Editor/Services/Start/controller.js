hui.ui.listen({
	$ready : function() {
		if (window.parent!=window) {
			window.parent.baseController.changeSelection('service:start');
		}
	},
	$clickIcon$newsList : function(info) {
		window.open(info.data.url);
	},
	$clickIcon$taskList : function(info) {
		if (info.data.action=='edit') {
			document.location='../../Template/Edit.php?id='+info.data.id;
		}
		if (info.data.action=='view') {
			document.location='../../Services/Preview/?id='+info.data.id;
		}
	},

	$clickIcon : function(info) {
		if (info.key=="expand") {
			info.tile.toggleFullScreen();
		}
	},
	$clickIcon$developmentTile : function(info) {
		if (info.key=="expand") {
			info.tile.toggleFullScreen();
			developmentPages.expand();
		}
		else if (info.key=="next") {
			developmentPages.next();
		} else if (info.key=="previous") {
			developmentPages.previous();
		}
	},
	
	$click$userSettings : function() {
		settingsPanel.position(userSettings);
		settingsPanel.show();
	},
	$click$saveSettings : function() {
		settingsPanel.hide();
	},
	$submit$feedbackForm : function() {
		var values = feedbackForm.getValues();
		hui.ui.showMessage({text:'Sender besked...',busy:true});
		sendFeedback.disable();
		hui.ui.request({
			url : 'data/SendFeedback.php',
			parameters : values,
			onFailure : function() {
				hui.ui.showMessage({text:'Det lykkedes desværre ikke at sende beskeden',icon:'common/warning',duration:3000})
				sendFeedback.enable();
			},
			onSuccess : function() {
				feedbackForm.reset();
				hui.ui.hideMessage();
				sendFeedback.enable();
				feedbackPages.next();
			}
		})
	},
	
	
	$clickIcon$helpTile : function(info) {
		info.tile.toggleFullScreen();
		userManual.setSize(info.tile.isFullScreen() ? 128 : 64);
		contact.setSize(info.tile.isFullScreen() ? 128 : 64);
	},
	$click$userManual : function() {
		window.open('http://www.in2isoft.dk/support/onlinepublisher/');
	},
	$click$contact : function() {
		window.open('http://www.in2isoft.dk/kontakt/');
	},
	
	// Password...
$ready : function() {
	this.$click$changePassword();
},
	$click$changePassword : function() {
		settingsPanel.hide();
		passwordBox.show();
		passwordFormula.focus();
	},
	$submit$passwordFormula : function(form) {
		var values = form.getValues();
		if (hui.isBlank(values.old) || hui.isBlank(values.password) || hui.isBlank(values.password2)) {
			hui.ui.showMessage({text:'Alle felter skal udfyldes',icon:'common/warning',duration:3000});
			passwordFormula.focus();
			return;
		}
		if (values.password!==values.password2) {
			hui.ui.showMessage({text:'De to kodeord er ikke ens',icon:'common/warning',duration:3000});
			passwordFormula.focus();
			return;
		}
		submitPassword.disable();
		hui.ui.request({
			url : 'data/ChangePassword.php',
			parameters : values,
			onFailure : function() {
				hui.ui.showMessage({text:'Det lykkedes desværre ikke at ændre kodeordet',icon:'common/warning',duration:3000})
				submitPassword.enable();
			},
			onSuccess : function() {
				feedbackForm.reset();
				hui.ui.hideMessage();
				submitPassword.enable();
				feedbackPages.next();
			}
		})
	}
})