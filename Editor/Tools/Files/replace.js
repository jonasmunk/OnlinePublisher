hui.ui.listen({
	$click$replace : function() {
		var obj = list.getFirstSelection();
		replaceFile.setParameter('id',obj.id);
		replaceWindow.show();
	},
	$uploadDidStartQueue$replaceFile : function() {
		cancelReplaceUpload.disable();
	},
	$uploadDidFail$replaceFile : function() {
		hui.ui.showMessage({icon:'common/warning',text:'Det lykkedes ikke at erstatte filen. Den er måske for stor.',duration:5000});
		replaceFile.clear();
		cancelReplaceUpload.enable();
	},
	$uploadDidComplete$replaceFile : function() {
		filesSource.refresh();
		typesSource.refresh();
		replaceFile.clear();
		replaceWindow.hide();
		hui.ui.showMessage({text:'Filen er nu erstattet',icon:'common/success',duration:3000});
		cancelReplaceUpload.enable();
	},
	$click$cancelReplaceUpload : function() {
		replaceWindow.hide();
	}
});