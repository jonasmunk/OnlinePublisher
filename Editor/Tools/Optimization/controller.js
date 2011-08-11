hui.ui.listen({
	$ready : function() {
		if (window.parent) {
			window.parent.baseController.changeSelection('tool:Optimization');
		}
		this._loadSettings();
	},
	
	$selectionChanged$selector : function(item) {
		if (item.value=='overview') {
			hui.ui.changeState('overview');
		} else {
			hui.ui.changeState('list');
			if (item.value=='warnings') {
				listDescription.setText('Viser advarsler om manglende indhold med mere');
			} else if (item.value=='wordcheck') {
				listDescription.setText('Liste over sætninger det bør forekomme på siden');
			} else {
				listDescription.setText();
			}
		}
	},
	$click$saveSettings : function() {
		var values = settingsFormula.getValues();
		hui.ui.request({
			message : {start:'Gemmer oversigt',delay:300,success:'Oversigten er gemt'},
			url : 'data/SaveSettings.php',
			json : {data:values}
		})
	},
	_loadSettings : function() {
		hui.ui.request({
			message : {start:'Indlæser oversigt',delay:300},
			url : 'data/LoadSettings.php',
			onJSON : function(values) {
				settingsFormula.setValues(values);
			}
		});
	},
	$clickIcon$list : function(info) {
		if (info.data.action=='view' && info.row.type=='page') {
			parent.location = '../Template/Edit.php?id='+(info.row.id);
		} else if (info.data.action=='delete') {
			hui.ui.confirmOverlay({
				element : info.node,
				text : 'Er du sikker?',
				okText : 'Ja, slet',
				cancelText : 'Nej',
				onOk : function() {
					hui.ui.request({
						url : '../../Services/Model/DeleteObject.php',
						message : {start:'Sletter ord...',delay:300,success:'Ordet er slettet'},
						parameters : {id:info.row.id},
						onSuccess : function() {
							list.refresh();
						}
					})
				}
			});
		} else {
			phrasePageList.setUrl('data/ListPagesWithPhrase.php?id='+info.row.id);
			wordPanel.position(info.node);
			wordPanel.show();
		}
	},
	$submit$wordFormula : function() {
		var word = wordFormula.getValues().word;
		hui.ui.request({
			url : 'data/AddTestPhrase.php',
			message : {start:'Gemmer ord...',delay:300,success:'Ordet er tilføjet'},
			parameters:{word:word},
			onSuccess : function() {
				list.refresh();
				wordFormula.reset();
				wordFormula.focus();
			}
		})
	}
	
});