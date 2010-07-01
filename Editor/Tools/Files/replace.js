ui.listen({
	$click$replace : function() {
		var obj = list.getFirstSelection();
		replaceFile.setParameter('id',obj.id);
		replaceWindow.show();
	},
	$uploadDidStartQueue$replaceFile : function() {
		cancelReplaceUpload.disable();
	},
	$uploadDidCompleteQueue$replaceFile : function() {
		filesSource.refresh();
		typesSource.refresh();
		replaceFile.clear();
		replaceWindow.hide();
		ui.showMessage({text:'Filen er nu erstattet',duration:3000});
		cancelReplaceUpload.enable();
	},
	$click$cancelReplaceUpload : function() {
		replaceWindow.hide();
	}
});