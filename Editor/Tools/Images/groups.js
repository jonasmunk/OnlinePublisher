hui.ui.listen({
	$click$groupInfo : function() {
		var item = selector.getValue();
		this._openGroup(item.value);		
	},
	$click$newGroup : function() {
		this.groupId = null;
		deleteGroup.setEnabled(false);
		groupFormula.reset();
		groupWindow.show();
		groupFormula.focus();
	},
	$click$cancelGroup : function() {
		this.groupId = null;
		groupFormula.reset();
		groupWindow.hide();
	},
	$click$saveGroup : function() {
		var values = groupFormula.getValues();
		if (hui.isBlank(values.title)) {
			hui.ui.showMessage({text:'Du skal angive en titel!',duration:2000});
			groupFormula.focus();
		} else {
			values.id = this.groupId;
			hui.ui.request({
				json:{data:values},
				url:'SaveGroup.php',
				message:{start:'Gemmer gruppe...',success:'Gruppen er gemt',delay:300},
				onSuccess : function() {
					groupTitle.setText(values.title);
					groupSource.refresh();
					groupOptionsSource.refresh();
				}
			});
			this.groupId = null;
			groupFormula.reset();
			groupWindow.hide();
		}
	},
	$submit$groupFormula : function() {
		this.$click$saveGroup();
	},
	$selectionWasOpened$selector : function(item) {
		if (item.type!='imagegroup') {
			return;
		}
		this._openGroup(item.value);
	},
	_openGroup : function(id) {
		hui.ui.request({
			parameters:{id:id},
			url:'../../Services/Model/LoadObject.php',
			onSuccess:'loadGroup',
			message:{start:'Åbner gruppe...',delay:300}
		});
	},
	$success$loadGroup : function(data) {
		this.groupId = data.id;
		groupFormula.setValues(data);
		deleteGroup.setEnabled(true);
		groupWindow.show();
		groupFormula.focus();
	},
	$click$deleteGroup : function() {
		hui.ui.request({
			json:{data:{id:this.groupId}},
			url:'../../Services/Model/DeleteObject.php',
			onSuccess:'deleteGroup',
			message:{start:'Sletter gruppe...',success:'Gruppen er slettet',delay:300}
		});
		this.groupId = null;
		groupFormula.reset();
		groupWindow.hide();
	},
	$success$deleteGroup : function() {
		groupSource.refresh();
		groupOptionsSource.refresh();
	}
	
});