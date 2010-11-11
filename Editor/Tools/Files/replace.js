ui.listen({
	$click$replace : function() {
		var obj = list.getFirstSelection();
		replaceFile.setParameter('id',obj.id);
		replaceWindow.show();
	},
	$uploadDidStartQueue$replaceFile : function() {
		cancelReplaceUpload.disable();
	},
	$uploadDidFail$replaceFile : function() {
		ui.showMessage({text:'Det lykkedes ikke at erstatte filen, den er måske for stor?',duration:5000});
		replaceFile.clear();
		cancelReplaceUpload.enable();
	},
	$uploadDidSucceed$replaceFile : function() {
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