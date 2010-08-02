ui.listen({
	$selectionChanged$selector : function(item) {
		if (item.value=='phpInfo') {
			xyz.setUrl('PhpInfo.php');
		} else if (item.value=='session') {
			xyz.setUrl('Session.php');
		} else if (item.kind=='test') {
			xyz.setUrl('RunTest.php?test='+item.value);
		} else if (item.kind=='testgroup') {
			xyz.setUrl('RunTest.php?group='+item.value);
		} else if (item.kind=='alltests') {
			xyz.setUrl('RunTest.php?all=true');
		}
	}
});