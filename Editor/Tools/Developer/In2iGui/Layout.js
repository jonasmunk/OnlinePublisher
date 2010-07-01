var controller = {
	editedId : 0,
	searchFieldChanged : function(searchField) {
		this.search();
	},
	selectionChanged : function(selector) {
		this.search();
	},
	search : function() {
		var selected = selector.getValues();
		list.loadData("ListData.php?query="+search.getValue()+(selected.length>0 ? "&type="+selected[0] : ""));
	},
	listRowsWasOpened : function(list) {
		var item = list.getFirstSelection();
		this.editedId = item.uid;
		if (item.kind=='person') {
			var dlgt = {
				onSuccess:function() {
					editor.hide();
					personEditor.show();
				}
			};
			In2iGui.update('LoadPerson.php?id='+item.uid,dlgt);
		} else {
			var dlgt = {
				onSuccess:function() {
					personEditor.hide();
					editor.show();
				}
			};
			In2iGui.update('LoadObject.php?id='+item.uid,dlgt);
		}
	},
	toolbarIconWasClicked$newPerson : function(icon) {
		this.editedId = null;
		editor.hide();
		personFormula.reset();
		personEditor.show();
	},
	toolbarIconWasClicked$changeToIconView : function(icon) {
		viewStack.change('iconView');
	},
	toolbarIconWasClicked$changeToListView : function(icon) {
		viewStack.change('listView');
	},
	buttonWasClicked$editorSave : function() {
		var delegate = {
			onSuccess : function() {
				editor.hide();
				list.refresh();
			}
		}
		var parms = formula.getValues();
		parms.id = this.editedId;
		var options = {method:'post',parameters:parms};
		$get('UpdateObject.php',delegate,options);
	},
	buttonWasClicked$personSave : function() {
		var delegate = {
			onSuccess : function() {
				personEditor.hide();
				list.refresh();
			}
		}
		var parms = personFormula.getValues();
		parms.id = this.editedId;
		var options = {method:'post',parameters:parms};
		$get('SavePerson.php',delegate,options);
	}
}
ui.listen(controller);