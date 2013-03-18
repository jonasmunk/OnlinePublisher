hui.ui.listen({
	data : null,
	
	$ready : function() {
		hui.ui.tellContainers('changeSelection','tool:Optimization');
		this._loadSettings();
	},
	
	$click$saveSettings : function() {
		var values = settingsFormula.getValues();
		var data = {
			purpose : values.purpose,
			successcriteria : values.successcriteria,
			audiences : values.audiences
		}
		hui.ui.request({
			message : {start:{en:'Saving overview...',da:'Gemmer oversigt...'},delay:300,success:{en:'The overview has been saved',da:'Oversigten er gemt'}},
			url : 'actions/SaveSettings.php',
			json : {data:data}
		})
	},
	_loadSettings : function() {
		hui.ui.request({
			message : {start:{en:'Loading overview...',da:'Henter oversigt...'},delay:300},
			url : 'data/LoadSettings.php',
			$object : function(values) {
				this.data = values;
				this._updateUI();
			}.bind(this)
		});
	},
	_updateUI : function() {
		hui.log(this.data)
		//profilesList.setObjects(this.data.profiles)
		settingsFormula.setValues(this.data);
	},
	$submit$profileFormula : function(form) {
		var values = form.getValues();
		if (hui.isBlank(values.url)) {
			return;
		}
		hui.ui.request({
			message : {start:{en:'Saving profile...',da:'Gemmer profil...'},delay:300},
			url : 'actions/SaveProfile.php',
			parameters : values,
			$success : function(values) {
				profilesSource.refresh();
			}.bind(this)
		});
		profileFormula.reset();
		profilePanel.hide();
	},
	$clickIcon$profilesList : function(info) {
		if (info.data.action=='visit') {
			window.open(info.data.url);
		} else if (info.data.action=='delete') {
			hui.ui.confirmOverlay({element:info.node,text:{en:'Are you sure?',da:'Er du sikker?'},$ok : function() {
				this._deleteProfile(info.data.url);
			}.bind(this)})
		}
	},
	_deleteProfile : function(url) {
		hui.ui.request({
			message : {start:{en:'Deleting profile...',da:'Sletter profil...'},delay:300},
			url : 'actions/DeleteProfile.php',
			parameters : {url:url},
			$success : function(values) {
				profilesSource.refresh();
			}.bind(this)
		});
		
	}
});