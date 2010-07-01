var controller = {
	$interfaceIsReady : function() {
		if (window.top!=window.self) {
			window.top.location=window.self.location;
		} else {
			username.focus();
		}
		if (n2i.browser.msie && !n2i.browser.msie8) {
			ui.alert({emotion:'gasp',title:'Din software er forældet',text:'Systemet understøtter ikke Internet Explorer tidligere end version 8. Opgrader venligst til en nyere version eller fortsæt på eget ansvar.'});
		}
	},
	$submit$formula : function() {
		ui.request({url:'Services/Core/Authentication.php',onSuccess:'login',parameters:formula.getValues()});
	},
	$success$login : function(data) {
		if (data.success) {
			var page = n2i.location.getParameter('page');
			document.location=page===null ? './index.php' : '.?page='+page;
		} else {
			ui.showMessage({text:'Brugeren blev ikke fundet!',duration:2000});
			formula.focus();
		}
	}
}