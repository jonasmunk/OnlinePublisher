hui.ui.listen({
	$select$selector : function(obj) {
		if (obj.value=='settings') {
			if (!this.loaded) {
				this.loaded = true;
				hui.ui.request({url:'LoadSettings.php',onSuccess:'loadSettings'});
			}
		}
	},
	$success$loadSettings : function(data) {
		emailFormula.setValues(data.email);
		onlineobjectsFormula.setValues(data.onlineobjects);
		analyticsFormula.setValues(data.analytics);
		uiFormula.setValues(data.ui);
	},
	
	//UI
	
	$click$saveUI : function(value) {
		var data = {'ui':uiFormula.getValues()};
		hui.ui.request({json:{data:data},url:'SaveSettings.php',onSuccess:function() {
			hui.ui.showMessage({text:'Gemt!',icon:'common/success',duration:2000})
		}});
	},
	
	// OnlineObjects
	$click$saveOnlineObjects : function() {
		saveOnlineObjects.setEnabled(false);
		var data = {'onlineobjects':onlineobjectsFormula.getValues()};
		hui.ui.request({json:{data:data},url:'SaveSettings.php',onSuccess:'saveOnlineObjects'});
	},
	$success$saveOnlineObjects : function() {
		saveOnlineObjects.setEnabled(true);
	},
	
	// OnlineObjects
	$click$saveAnalytics : function() {
		saveAnalytics.setEnabled(false);
		var data = {'analytics':analyticsFormula.getValues()};
		hui.ui.request({json:{data:data},url:'SaveSettings.php',onSuccess:'saveAnalytics'});
	},
	$success$saveAnalytics : function() {
		saveAnalytics.setEnabled(true);
	},
	$click$testAnalytics : function() {
		hui.ui.showMessage({text:'Tester forbindelse til Google Analytics...'});
		hui.ui.request({json:{},url:'TestAnalytics.php',onSuccess:'testAnalytics'});
	},
	$success$testAnalytics : function(success) {
		hui.ui.showMessage({text:success ? 'Det virkede!' : 'Det virkede ikke!',duration:2000});
	},
	
	// Email
	$click$saveEmail : function() {
		saveEmail.setEnabled(false);
		var data = {'email':emailFormula.getValues()};
		hui.ui.request({json:{data:data},url:'SaveSettings.php',onSuccess:'saveEmail'});
	},
	$success$saveEmail : function() {
		saveEmail.setEnabled(true);
	},
	$click$showEmailTest : function() {
		emailTestWindow.show();
	},
	$click$testEmail : function() {
		hui.ui.showMessage({text:'Sender e-mail...'});
		var data = emailTestFormula.getValues();
		hui.ui.request({json:{data:data},url:'TestEmailSetup.php',onSuccess:'testEmail'});
	},
	$success$testEmail : function(data) {
		if (data.success) {
			hui.ui.showMessage({text:'E-mail er sendt!',duration:2000});
		} else {
			hui.ui.showMessage({text:'Det lykkedes ikke at sende email!',duration:2000});
		}
	}
});