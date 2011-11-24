hui.ui.listen({
	$ready : function() {
		if (window.parent!=window) {
			window.parent.baseController.changeSelection('tool:Developer');
		}
	},
	$selectionChanged$selector : function(item) {
		if (item.value=='settings') {
			iframe.clear();
			hui.ui.changeState('settings');
		} else if (item.value=='graph') {
			iframe.clear();
			hui.ui.changeState('graph');
		} else if (item.value=='classes') {
			hui.ui.changeState('list');
		} else {
			hui.ui.changeState('frame');
		}
		if (item.value=='phpInfo') {
			iframe.setUrl('data/PhpInfo.php');
		} else if (item.value=='session') {
			iframe.setUrl('data/Session.php');
		} else if (item.kind=='test') {
			iframe.setUrl('data/RunTest.php?test='+item.value);
		} else if (item.kind=='testgroup') {
			iframe.setUrl('data/RunTest.php?group='+item.value);
		} else if (item.kind=='alltests') {
			iframe.setUrl('data/RunTest.php?all=true');
		}
	},
	$valuesChanged$settingsFormula : function(values) {
		hui.ui.request({
			url : 'data/SaveSettings.php',
			json : {data:values},
			message : {success:'Saved'}
		})
	},
	$clickNode$graph : function(node) {
		hui.ui.showMessage({text:node.label,duration:2000});
	}
});