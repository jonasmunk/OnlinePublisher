hui.ui.listen({
	$selectionChanged$selector : function(item) {
		if (item.value=='settings') {
			ui.changeState('settings');
		} else {
			ui.changeState('frame');
		}
		if (item.value=='phpInfo') {
			iframe.setUrl('PhpInfo.php');
		} else if (item.value=='session') {
			iframe.setUrl('Session.php');
		} else if (item.kind=='test') {
			iframe.setUrl('RunTest.php?test='+item.value);
		} else if (item.kind=='testgroup') {
			iframe.setUrl('RunTest.php?group='+item.value);
		} else if (item.kind=='alltests') {
			iframe.setUrl('RunTest.php?all=true');
		}
	},
	$valuesChanged$settingsFormula : function(values) {
		hui.ui.request({
			url : 'SaveSettings.php',
			json : {data:values},
			message : {success:'Saved'}
		})
	}
});