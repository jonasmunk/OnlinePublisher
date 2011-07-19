hui.ui.listen({
	$ready : function() {
		if (window.top!=window.self) {
			window.top.location=window.self.location;
		} else {
			username.focus();
		}
		if (hui.location.getBoolean('logout')) {
			hui.ui.showMessage({text:'Du er nu logget ud',icon:'common/success',duration:2000});
		}
	},
	$submit$formula : function() {
		hui.ui.showMessage({text:'Authenticating...',busy:true,delay:100});
		hui.ui.request({
			url:'Login.php',
			parameters:formula.getValues(),
			onFailure:function() {
				hui.ui.showMessage({text:'An unexpected error occurred!',icon:'common/warning',duration:4000});
			},
			onSuccess:'login'
		});
	},
	$success$login : function(data) {
		if (data.success) {
			hui.ui.showMessage({text:'Du er nu logget ind, øjeblik...',icon:'common/success',delay:200});
			if (hui.browser.ipad) {
				document.location = './Touch/';
			} else {
				document.location = './index.php';
			}
		} else {
			hui.ui.showMessage({text:'Access denied',icon:'common/warning',duration:2000});
			formula.focus();
		}
	}
});