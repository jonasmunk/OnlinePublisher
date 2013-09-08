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
		hui.ui.request({
			json : {data:{id:this.userId}},
			url : '../../Services/Model/DeleteObject.php',
			$success:function() {
				userEditor.hide();
				userFormula.reset();
				list.refresh();
			}
		});
	}
})