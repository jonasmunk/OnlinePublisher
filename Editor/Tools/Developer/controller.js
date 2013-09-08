hui.ui.listen({
	$ready : function() {
		hui.ui.tellContainers('changeSelection','tool:Developer');
	},
	$click$rebuildClasses : function() {
		hui.ui.request({
			message : {start:'Rebuilding'},
			url : 'data/Rebuild.php',
			$success : function() {
				hui.ui.msg.success({text:'Class path is rebuild!'});
			},
			$failure : function() {
				hui.ui.msg.fail({text:'Unable to rebuild classes!'})
			}
		})
	},
	$select$selector : function(item) {
		if (item.value=='settings') {
			iframe.clear();
			hui.ui.changeState('settings');
		} else if (item.value=='graph') {
			iframe.clear();
			hui.ui.changeState('graph');
		} else if (item.value=='diagram') {
			iframe.clear();
			hui.ui.changeState('diagram');
		} else if (item.value=='classes') {
			hui.ui.changeState('list');
		} else {
			hui.ui.changeState('frame');
		}
		if (item.value=='phpInfo') {
			iframe.clear();
			iframe.setUrl('data/PhpInfo.php');
		} else if (item.value=='actions') {
			iframe.clear();
			iframe.setUrl('actions.php');
		} else if (item.value=='session') {
			iframe.clear();
			iframe.setUrl('data/Session.php');
		} else if (item.value=='report') {
			iframe.clear();
			iframe.setUrl('data/Report.php');
		} else if (item.kind=='test') {
			iframe.clear();
			iframe.setUrl('data/RunTest.php?test='+item.value);
		} else if (item.kind=='testgroup') {
			iframe.clear();
			iframe.setUrl('data/RunTest.php?group='+item.value);
		} else if (item.kind=='alltests') {
			iframe.clear();
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
	},
	$click$playDiagram : function() {
		diagram.resume();
	},
	$click$expandDiagram : function() {
		diagram.expand();
	},
	$click$contractDiagram : function() {
		diagram.contract();
	}
});