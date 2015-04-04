hui.ui.listen({
	userId : 0,
	
	$ready : function() {
		hui.ui.tellContainers('changeSelection','tool:Security');
	},

	$open$list : function(obj) {
		if (obj.kind=='user') {
			this._loadUser(obj.id);
		}
	},
	$select$list : function(obj) {
		deleteItem.setEnabled(obj!==null);
		editItem.setEnabled(obj!==null);
	},
	$open$selector : function(obj) {
		if (obj.kind == 'securityzone') {
			this._loadZone(obj.value);
		}
	},
	$select$selector : function(obj) {
		if (obj.kind == 'securityzone') {
			this.activeZoneId = obj.value;
			zoneTitle.setText(obj.title);
			pages.goTo('zone');
			addPage.enable();
			addUser.enable();
		} else {
			pages.goTo('users');
			addPage.disable();
			addUser.disable();
		}
	},
	
	// Toolbar
	
	$click$editItem : function() {
		var obj = list.getFirstSelection();
		if (obj) {
			this._loadUser(obj.id);
		}
	},
	
	$click$deleteItem : function() {
		var obj = list.getFirstSelection();
		if (obj) {
			this._deleteUser(obj.id);
		}
	},
	
	////////////////////////////// Users /////////////////////////////
	
	_loadUser : function(id) {
		var data = {id:id};
		userFormula.reset();
		deleteUser.setEnabled(false);
		saveUser.setEnabled(false);
		hui.ui.request({
			json : {data:data},
			url : '../../Services/Model/LoadObject.php',
			$object : function(data) {
				this.userId = data.id;
				data.password = null;
				userFormula.setValues(data);
				userWindow.show();
				saveUser.setEnabled(true);
				deleteUser.setEnabled(true);
				userFormula.focus();
			}.bind(this)
		});
	},
	$click$newUser : function() {
		this.userId = 0;
		userFormula.reset();
		userWindow.show();
		deleteUser.setEnabled(false);
	},
	$click$cancelUser : function() {
		userWindow.hide();
		userFormula.reset();
	},
	$click$saveUser : function() {
		var data = userFormula.getValues();
		data.id = this.userId;
		if (hui.isBlank(data.username)) {
			hui.ui.msg.fail({text:{da:'Brugernavnet er ikke udfyldt',en:'Please provide a username'}});
			userFormula.focus('username');
			return;
		}
		hui.ui.request({
			json : {data:data},
			url : 'actions/SaveUser.php',
			$success:function() {
				userWindow.hide();
				userFormula.reset();
				list.refresh();
			},
			$failure : function() {
				hui.ui.msg.fail({text:{da:'Brugeren kunne ikke gemmes',en:'The user could not be saved'}});
			}
		});
	},
	$click$deleteUser : function() {
		this._deleteUser(this.userId);
	},
	_deleteUser : function(id) {
		// TODO (jm) More ribust deletion (should current user be deletable?)
		hui.ui.request({
			parameters : {id:id},
			url : '../../Services/Model/DeleteObject.php',
			$success:function() {
				userWindow.hide();
				userFormula.reset();
				list.refresh();
			}
		});
	},
	
	////////////////////////// Zone editing ////////////////
	
	activeZoneId : 0,
	
	$click$addPage : function() {
		pageFinder.show();
	},
	
	$select$pageFinder : function(obj) {
		pageFinder.hide();
		hui.ui.request({
			message : {start:{en:'Adding page', da:'Tilføjer side'}},
			parameters : {
				zoneId : this.activeZoneId,
				pageId : obj.id
			},
			url : 'actions/AddPageToZone.php',
			$success : function() {
				zonePagesSource.refresh();
			}
		});
	},
	$clickIcon$zonePages : function(info) {
		hui.ui.request({
			message : {start:{en:'Removing page', da:'Fjerner side'}},
			parameters : {
				zoneId : this.activeZoneId,
				pageId : info.row.id
			},
			url : 'actions/RemovePageFromZone.php',
			$success : function() {
				zonePagesSource.refresh();
			}
		});
	},
	
	$click$addUser : function() {
		userFinder.show();
	},
	
	$select$userFinder : function(obj) {
		userFinder.hide();
		hui.ui.request({
			message : {start:{en:'Adding user', da:'Tilføjer bruger'}},
			parameters : {
				zoneId : this.activeZoneId,
				userId : obj.id
			},
			url : 'actions/AddUserToZone.php',
			$success : function() {
				zoneUsersSource.refresh();
			}
		});
	},
	$clickIcon$zoneUsers : function(info) {
		hui.ui.request({
			message : {start:{en:'Removing user', da:'Fjerner bruger'}},
			parameters : {
				zoneId : this.activeZoneId,
				userId : info.row.id
			},
			url : 'actions/RemoveUserFromZone.php',
			$success : function() {
				zoneUsersSource.refresh();
			}
		});
	},
	
	////////////////////////////// Zones /////////////////////////////

	zoneId : 0,

	$click$newZone : function() {
		this.zoneId = 0;
		zoneFormula.reset();
		zoneWindow.show();
		deleteZone.setEnabled(false);
		zoneFormula.focus();
	},
	$click$cancelZone : function() {
		this.zoneId = 0;
		zoneWindow.hide();
		zoneFormula.reset();
	},
	$submit$zoneFormula : function() {
		var data = zoneFormula.getValues();
		data.id = this.zoneId;
		zoneWindow.setBusy(true);
		hui.ui.request({
			json : {data:data},
			url : 'actions/SaveZone.php',
			$success:function() {
				zoneWindow.hide();
				zoneFormula.reset();
				sidebarSource.refresh();
			},
			$failure : function() {
				hui.ui.msg.fail({text:{da:'Zonen kunne ikke gemmes',en:'The zone could not be saved'}});
			},
			$finally : function() {
				zoneWindow.setBusy(false);
			}
		});
	},
	_loadZone : function(id) {
		zoneWindow.setBusy(true);
		zoneFormula.reset();
		deleteZone.setEnabled(false);
		saveZone.setEnabled(false);
		hui.ui.request({
			json : {data:{id:id}},
			url : '../../Services/Model/LoadObject.php',
			$object : function(data) {
				this.zoneId = data.id;
				zoneFormula.setValues(data);
				zoneWindow.show();
				saveZone.setEnabled(true);
				deleteZone.setEnabled(true);
				zoneFormula.focus();
			}.bind(this),
			$finally : function() {
				zoneWindow.setBusy(false);
			}
		});
	},
	$click$deleteZone : function() {
		this._deleteZone(this.zoneId);
	},
	_deleteZone : function(id) {
		zoneWindow.hide();
		zoneFormula.reset();
		hui.ui.request({
			parameters : {id:id},
			url : '../../Services/Model/DeleteObject.php',
			$success : function() {
				sidebarSource.refresh();
			}
		});
	},
	
})