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
			hui.ui.showMessage({text:{en:'The title is required', da:'Titlen er krævet'},duration:2000});
			groupFormula.focus();
		} else {
			values.id = this.groupId;
			hui.ui.request({
				json : {data:values},
				url : 'actions/SaveGroup.php',
				message : {start: {en:'Saving group...', da:'Gemmer gruppe...'},success:{en:'The group has been saved', da:'Gruppen er gemt'},delay:300},
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
	$open$selector : function(item) {
		if (item.kind!='imagegroup') {
			return;
		}
		this._openGroup(item.value);
	},
	_openGroup : function(id) {
		hui.ui.request({
			parameters : {id:id},
			url : '../../Services/Model/LoadObject.php',
			onSuccess : 'loadGroup',
			message : {start:{en:'Loading group...', da:'Åbner gruppe...'},delay:300}
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
			message : {start: {en:'Deleting group...', da:'Sletter gruppe...'},success:{en:'The group has been deleted', da:'Gruppen er slettet'},delay:300},
			json : {data:{id:this.groupId}},
			url : '../../Services/Model/DeleteObject.php',
			onSuccess : function() {
				selector.setValue('all')
				groupSource.refresh();
				groupOptionsSource.refresh();
			}
		});
		this.groupId = null;
		groupFormula.reset();
		groupWindow.hide();
	}
	
});