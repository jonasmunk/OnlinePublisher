hui.ui.listen({
	$ready : function() {
		formula.focus();
		this.$valuesChanged$formula();
	},
	$click$save : function() {
		if (baseUrl.isBlank()) {
			hui.ui.showMessage({text:'The web address is required',icon:'common/warning',duration:2000});
			baseUrl.focus();
			return;
		}
		if (databaseHost.isBlank()) {
			hui.ui.showMessage({text:'The database host is required',icon:'common/warning',duration:2000});
			databaseHost.focus();
			return;
		}
		if (databaseName.isBlank()) {
			hui.ui.showMessage({text:'The database name is required',icon:'common/warning',duration:2000});
			databaseName.focus();
			return;
		}
		if (databaseUser.isBlank()) {
			hui.ui.showMessage({text:'The database user is required',icon:'common/warning',duration:2000});
			databaseUser.focus();
			return;
		}
		if (superUser.isBlank()) {
			hui.ui.showMessage({text:'The super user is required',icon:'common/warning',duration:2000});
			superUser.focus();
			return;
		}
		if (superPassword.isBlank()) {
			hui.ui.showMessage({text:'The super password is required',icon:'common/warning',duration:2000});
			superPassword.focus();
			return;
		}
		var values = formula.getValues();
		hui.ui.request({
			url:'Build.php',
			parameters:values,
			$object : function(result) {
				if (result.failure) {
					hui.ui.showMessage({text:result.failure,duration:2000,icon:'common/warning'});
				} else {
					hui.ui.showMessage({text:'The config file has been created, just a moment...',busy:true,duration:2000});
					window.setTimeout(function() {document.location='../../'},3000);
				}
			},
			$failure:function() {
				hui.ui.showMessage({text:'An unexpected occurred :-(',duration:2000});
			}
		});
	},
	$click$test : function() {
		var values = formula.getValues();
		hui.ui.request({
			url:'TestDatabase.php',
			parameters:values,
			$object:function(result) {
				hui.log(result);
				if (!result.server) {
					hui.ui.showMessage({text:'Unable to connect to host',duration:4000,icon:'common/warning'});
				} else if (!result.database) {
					hui.ui.showMessage({text:'Could connect to host but NOT the database',duration:4000,icon:'common/warning'});
				} else {
					hui.ui.showMessage({text:'Database connection verified :-)',duration:2000,icon:'common/success'});
				}
			},
			$failure:function() {
				hui.ui.showMessage({text:'An error occurred when testing connection :-(',duration:2000});
			}
		})
	},
	$valuesChanged$formula : function() {
		if (this.previewing) {
			this.prendingPreview = true;
			return;
		}
		this.prendingPreview = false;
		this.previewing = true;
		var values = formula.getValues();
		hui.ui.request({
			url : 'PreviewConfiguration.php',
			parameters : values,
			$object : function(result) {
				preview.setValue(result.config);
				this.previewing = false;
				if (this.prendingPreview) {
					hui.log('Running again');
					this.$valuesChanged$formula();
				}
			}.bind(this),
			$failure:function() {
				hui.ui.showMessage({text:'An error occurred when building preview',duration:2000});
				this.previewing = false;
			}.bind(this)
		})
		
	}
})