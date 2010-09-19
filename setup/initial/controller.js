ui.listen({
	$ready : function() {
		formula.focus();
	},
	$click$save : function() {
		if (baseUrl.isBlank()) {
			ui.showMessage({text:'The web address is required',duration:2000});
			baseUrl.focus();
			return;
		}
		if (databaseHost.isBlank()) {
			ui.showMessage({text:'The database host is required',duration:2000});
			databaseHost.focus();
			return;
		}
		if (databaseName.isBlank()) {
			ui.showMessage({text:'The database name is required',duration:2000});
			databaseName.focus();
			return;
		}
		if (databaseUser.isBlank()) {
			ui.showMessage({text:'The database user is required',duration:2000});
			databaseUser.focus();
			return;
		}
		if (superUser.isBlank()) {
			ui.showMessage({text:'The super user is required',duration:2000});
			superUser.focus();
			return;
		}
		if (superPassword.isBlank()) {
			ui.showMessage({text:'The super password is required',duration:2000});
			superPassword.focus();
			return;
		}
		var values = formula.getValues();
		ui.request({
			url:'Build.php',
			parameters:values,
			onJSON : function(result) {
				if (result.failure) {
					ui.showMessage({text:result.failure,duration:2000});
				} else {
					ui.showMessage({text:'All went well :-)',duration:2000});
					window.setTimeout(function() {document.location='../'},3000);
				}
			},
			onFailure:function() {
				ui.showMessage({text:'An unexpected occurred :-(',duration:2000});
			}
		});
	},
	$click$test : function() {
		var values = formula.getValues();
		ui.request({
			url:'TestDatabase.php',
			parameters:values,
			onJSON:function(result) {
				n2i.log(result);
				if (!result.server) {
					ui.showMessage({text:'Unable to connect to host',duration:4000});
				} else if (!result.database) {
					ui.showMessage({text:'Could connect to host but NOT the database',duration:4000});
				} else {
					ui.showMessage({text:'Database connection verified :-)',duration:2000});
				}
			},
			onFailure:function() {
				ui.showMessage({text:'An error occurred when testing connection :-(',duration:2000});
			}
		})
	}
})