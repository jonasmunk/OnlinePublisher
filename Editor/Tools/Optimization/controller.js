hui.ui.listen({
	$ready : function() {
		hui.ui.tellContainers('changeSelection','tool:Optimization');
		this._loadSettings();
	},
	
	$select$selector : function(item) {
		if (item.value=='overview') {
			hui.ui.changeState('overview');
		} else {
			hui.ui.changeState('list');
			if (item.value=='warnings') {
				listDescription.setText({en:'Shows warnings for missing content and more',da:'Viser advarsler om manglende indhold med mere'});
			} else if (item.value=='wordcheck') {
				listDescription.setText({en:'List of sentences that should appear on pages',da:'Liste over sætninger det bør forekomme på sider'});
			} else if (item.value=='index') {
				listDescription.setText({en:'Shows the search index for all pages',da:'Viser søgeindekset for alle sider'});
			} else if (item.value=='words') {
				listDescription.setText({en:'Shows all words and how often they appear',da:'Viser alle ord og hvor ofte de optræder'});
			} else if (item.value=='pagenotfound') {
				listDescription.setText({en:'Shows all requests that could not be found',da:'Viser alle forespørsler der ikke kunne findes'});
			} else {
				listDescription.setText();
			}
		}
	},
	$click$newWord : function() {
		newWordPanel.show();
		wordFormula.focus();
	},
	
	$click$saveSettings : function() {
		var values = settingsFormula.getValues();
		hui.ui.request({
			message : {start:{en:'Saving overview...',da:'Gemmer oversigt...'},delay:300,success:{en:'The overview has been saved',da:'Oversigten er gemt'}},
			url : 'actions/SaveSettings.php',
			json : {data:values}
		})
	},
	_loadSettings : function() {
		hui.ui.request({
			message : {start:{en:'Loading overview...',da:'Henter oversigt...'},delay:300},
			url : 'data/LoadSettings.php',
			onJSON : function(values) {
				settingsFormula.setValues(values);
			}
		});
	},
	$clickIcon$phrasePageList : function(info) {
		if (info.data.action=='edit' && info.row.kind=='page') {
			document.location = '../../Template/Edit.php?id='+info.row.id;
		}
		else if (info.data.action=='view' && info.row.kind=='page') {
			document.location = '../../Services/Preview/?id='+info.row.id;
		}
	},
	$clickIcon$list : function(info) {
		if (info.data.action=='delete') {
			hui.ui.confirmOverlay({
				element : info.node,
				text : 'Er du sikker?',
				okText : 'Ja, slet',
				cancelText : 'Nej',
				onOk : function() {
					hui.ui.request({
						url : '../../Services/Model/DeleteObject.php',
						message : {start:{en:'Deleting words...',da:'Sletter ord...'},delay:300,success:{en:'The word has been deleted',da:'Ordet er slettet'}},
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
			url : 'actions/AddTestPhrase.php',
			message : {start:{en:'Adding word...',da:'Tilføjer ord...'},delay:300,success:{en:'The word is added',da:'Ordet er tilføjet'}},
			parameters : {word:word},
			onSuccess : function() {
				list.refresh();
				wordFormula.reset();
				wordFormula.focus();
			}
		})
	},
	
	
	////////////// Re-index /////////////
	
	$click$reindex : function() {
		var row = list.getFirstSelection();
		hui.ui.request({
			url : 'actions/ReIndex.php',
			message : {start:{en:'Analyzing...',da:'Analyserer...'},delay:300},
			parameters : {id:row.id},
			$success : function(obj) {
				list.refresh();
			}
		})
	},
	
	////////////// Analysis /////////////
	
	$click$analyse : function() {
		var row = list.getFirstSelection();
		hui.ui.request({
			url : 'data/PageInfo.php',
			message : {start:{en:'Analyzing...',da:'Analyserer...'},delay:300},
			parameters : {id:row.id},
			$object : function(obj) {
				if (obj==null) {
					hui.ui.showMessage({text:{da:'Ingen data',en:'No data'},icon:'common/warning',duration:4000});
					return;
				}
				var html = '<p><strong>Language:</strong> '+obj.language+'</p>';
				html+='<h2>Ukendte ord</h2>';
				html+='<ul>';
				for (var i=0; i < obj.unknownWords.length; i++) {
					html+='<li>'+obj.unknownWords[i]+'</li>';
				};
				html+='</ul>';
				html+='<h2>Kendte ord</h2>';
				html+='<ul>';
				for (var i=0; i < obj.knownWords.length; i++) {
					html+='<li>'+obj.knownWords[i].text+'</li>';
				};
				html+='</ul>';
				hui.get('analysis').innerHTML = html;
				analysisWindow.show();
			},
			$failure : function() {
				hui.ui.showMessage({text:{da:'Der skete en uventet fejl',en:'An unexpected error occurred'},icon:'common/warning',duration:4000});
			}
		})
	}
	
});