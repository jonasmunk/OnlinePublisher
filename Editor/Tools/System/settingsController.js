ui.listen({
	$selectionChanged$selector : function(obj) {
		if (obj.value=='settings') {
			if (!this.loaded) {
				this.loaded = true;
				ui.request({url:'LoadSettings.php',onSuccess:'loadSettings'});
			}
		}
	},
	$success$loadSettings : function(data) {
		emailFormula.setValues(data.email);
		onlineobjectsFormula.setValues(data.onlineobjects);
		analyticsFormula.setValues(data.analytics);
	},
	
	// OnlineObjects
	$click$saveOnlineObjects : function() {
		saveOnlineObjects.setEnabled(false);
		var data = {'onlineobjects':onlineobjectsFormula.getValues()};
		ui.request({json:{data:data},url:'SaveSettings.php',onSuccess:'saveOnlineObjects'});
	},
	$success$saveOnlineObjects : function() {
		saveOnlineObjects.setEnabled(true);
	},
	
	// OnlineObjects
	$click$saveAnalytics : function() {
		saveAnalytics.setEnabled(false);
		var data = {'analytics':analyticsFormula.getValues()};
		ui.request({json:{data:data},url:'SaveSettings.php',onSuccess:'saveAnalytics'});
	},
	$success$saveAnalytics : function() {
		saveAnalytics.setEnabled(true);
	},
	$click$testAnalytics : function() {
		ui.showMessage({text:'Tester forbindelse til Google Analytics...'});
		ui.request({json:{},url:'TestAnalytics.php',onSuccess:'testAnalytics'});
	},
	$success$testAnalytics : function(success) {
		ui.showMessage({text:success ? 'Det virkede!' : 'Det virkede ikke!',duration:2000});
	},
	
	// Email
	$click$saveEmail : function() {
		saveEmail.setEnabled(false);
		var data = {'email':emailFormula.getValues()};
		ui.request({json:{data:data},url:'SaveSettings.php',onSuccess:'saveEmail'});
	},
	$success$saveEmail : function() {
		saveEmail.setEnabled(true);
	},
	$click$showEmailTest : function() {
		emailTestWindow.show();
	},
	$click$testEmail : function() {
		ui.showMessage({text:'Sender e-mail...'});
		var data = emailTestFormula.getValues();
		ui.request({json:{data:data},url:'TestEmailSetup.php',onSuccess:'testEmail'});
	},
	$success$testEmail : function(data) {
		if (data.success) {
			ui.showMessage({text:'E-mail er sendt!',duration:2000});
		} else {
			ui.showMessage({text:'Det lykkedes ikke at sende email!',duration:2000});
		}
	}
});