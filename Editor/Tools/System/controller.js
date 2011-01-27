ui.listen({
	weblogGroupId : 0,
	userId : 0,
	$ready : function() {
		//list.setUrl('ListUsers.php');
	},
	
	$selectionChanged$selector : function(obj) {
		if (obj.value=='settings') {
			ui.changeState('settings');
			list.clear();
			return;
		}
		ui.changeState('list');
		switch (obj.value) {
			case 'databaseTables' : list.setUrl('ListDatabaseTables.php')
		}
		if (obj.value=='databaseInfo') {
			list.setUrl('ListDatabaseInfo.php');
		} else if (obj.value=='user') {
			list.setUrl('ListUsers.php');
		} else if (obj.value=='object') {
			list.setSource(allObjectsSource);
		} else if (obj.value=='log') {
			list.setSource(logSource);
		} else if (obj.value=='webloggroup') {
			list.setUrl('../../Services/Model/ListObjects.php?type=webloggroup');
		} else if (obj.value=='path') {
			list.setUrl('ListPaths.php');
		} else if (obj.value=='design') {
			list.setUrl('ListDesigns.php');
		}
	},
	$listRowWasOpened$list : function(obj) {
		//var obj = list.getFirstSelection();
		if (obj.kind=='webloggroup') {
			this.loadWeblogGroup(obj.id);
		} else if (obj.kind=='user') {
			this.loadUser(obj.id);
		} else if (obj.kind=='path') {
			this.loadPath(obj.id);
		}
	},
	$valueChanged$searchField : function(value) {
		list.resetState();
		if (!list.options.source) { // HACK to not refresh if list is source based
			list.setParameter('query',value);
			list.refresh();
		}
	},
	
	////////////////////////////// Users /////////////////////////////
	loadUser : function(id) {
		var data = {id:id};
		userFormula.reset();
		deleteUser.setEnabled(false);
		saveUser.setEnabled(false);
		ui.request({json:{data:data},url:'../../Services/Model/LoadObject.php',onSuccess:'loadUser'});
	},
	$success$loadUser : function(data) {
		this.userId = data.id;
		userTitle.setValue(data.title);
		userUsername.setValue(data.username);
		userEmail.setValue(data.email);
		userAdministrator.setValue(data.administrator);
		userInternal.setValue(data.internal);
		userExternal.setValue(data.external);
		userNote.setValue(data.note);
		userEditor.show();
		saveUser.setEnabled(true);
		deleteUser.setEnabled(true);
		userTitle.focus();
	},
	$click$newUser : function() {
		this.userId = 0;
		userFormula.reset();
		userEditor.show();
		deleteUser.setEnabled(false);
	},
	$click$cancelUser : function() {
		userEditor.hide();
		userFormula.reset();
	},
	$click$saveUser : function() {
		if (userUsername.isEmpty()) {
			In2iGui.showMessage({text:'Brugernavnet er ikke udfyldt',duration:2000});
			userUsername.focus();
			return;
		}
		var data = {
			id:this.userId,
			title:userTitle.getValue(),
			note:userNote.getValue(),
			username:userUsername.getValue(),
			password:userPassword.getValue(),
			email:userEmail.getValue(),
			internal:userInternal.getValue(),
			external:userExternal.getValue(),
			administrator:userAdministrator.getValue()
		};
		ui.request({json:{data:data},url:'SaveUser.php',onSuccess:'saveUser'});
	},
	$success$saveUser : function() {
		userEditor.hide();
		userFormula.reset();
		list.refresh();
	},
	$click$deleteUser : function() {
		ui.request({json:{data:{id:this.userId}},url:'../../Services/Model/DeleteObject.php',onSuccess:'deleteUser'});
	},
	$success$deleteUser : function() {
		userEditor.hide();
		userFormula.reset();
		list.refresh();
	},
	
	/////////////////////////// Weblog group /////////////////////////
	
	$submit$weblogGroupFormula : function() {
		this.$click$saveWeblogGroup();
	},
	$click$newWeblogGroup : function() {
		this.weblogGroupId = 0;
		weblogGroupFormula.reset();
		weblogGroupEditor.show();
		deleteWeblogGroup.setEnabled(false);
		weblogGroupFormula.focus();
	},
	loadWeblogGroup : function(id) {
		var data = {id:id};
		weblogGroupFormula.reset();
		deleteWeblogGroup.setEnabled(false);
		saveWeblogGroup.setEnabled(false);
		ui.request({json:{data:data},url:'../../Services/Model/LoadObject.php',onSuccess:'loadWeblogGroup'});
	},
	$success$loadWeblogGroup : function(data) {
		this.weblogGroupId = data.id;
		weblogGroupFormula.setValues(data);
		weblogGroupEditor.show();
		saveWeblogGroup.setEnabled(true);
		deleteWeblogGroup.setEnabled(true);
		weblogGroupFormula.focus();
	},
	$click$cancelWeblogGroup : function() {
		weblogGroupEditor.hide();
		weblogGroupFormula.reset();
	},
	$click$deleteWeblogGroup : function() {
		ui.request({json:{data:{id:this.weblogGroupId}},url:'../../Services/Model/DeleteObject.php',onSuccess:'deleteWeblogGroup'});
	},
	$success$deleteWeblogGroup : function() {
		weblogGroupEditor.hide();
		weblogGroupFormula.reset();
		list.refresh();
	},
	$click$saveWeblogGroup : function() {
		var data = weblogGroupFormula.getValues();
		data.id = this.weblogGroupId;
		In2iGui.request({json:{data:data},url:'SaveWeblogGroup.php',onSuccess:'saveWeblogGroup'});
	},
	$success$saveWeblogGroup : function() {
		weblogGroupEditor.hide();
		weblogGroupFormula.reset();
		list.refresh();
	},
	
	/////////////////////////// Path /////////////////////////
	
	$click$newPath : function() {
		this.pathId = 0;
		pathFormula.reset();
		pathEditor.show();
		deletePath.setEnabled(false);
	},
	$click$savePath : function() {
		var data = pathFormula.getValues();
		data.id=this.pathId;
		ui.request({json:{data:data},url:'SavePath.php',onSuccess:'savePath'});
	},
	$success$savePath : function() {
		pathEditor.hide();
		pathFormula.reset();
		list.refresh();
	},
	loadPath : function(id) {
		pathFormula.reset();
		deletePath.setEnabled(false);
		savePath.setEnabled(false);
		ui.request({json:{data:{id:id}},url:'../../Services/Model/LoadObject.php',onSuccess:'loadPath'});
	},
	$success$loadPath : function(data) {
		this.pathId = data.id;
		pathFormula.setValues(data);
		pathEditor.show();
		savePath.setEnabled(true);
		deletePath.setEnabled(true);
	},
	$click$deletePath : function() {
		ui.request({json:{data:{id:this.pathId}},url:'../../Services/Model/DeleteObject.php',onSuccess:'deletePath'});
	},
	$success$deletePath : function() {
		pathEditor.hide();
		pathFormula.reset();
		list.refresh();
	},
	$click$cancelPath : function() {
		pathEditor.hide();
		pathFormula.reset();
	}
});