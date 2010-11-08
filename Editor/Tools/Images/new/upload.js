ui.listen({
	
	///////////////////////// Uoload /////////////////////////
	
	$click$newFile : function() {
		uploadWindow.show();
	},
	$click$cancelUpload : function() {
		uploadWindow.hide();
	},
	$uploadDidCompleteQueue$file : function() {
		imagesSource.refresh();
		groupSource.refresh();
	},
	
	////////////////////////// Fetch /////////////////////////
	
	$click$fetchImage : function() {
		fetchImage.setEnabled(false);
		ui.showMessage({text:'Henter billede...'});
		In2iGui.request({url:'FetchImage.php',onSuccess:'imageFetched',parameters:fetchFormula.getValues()});
	},
	$success$imageFetched : function(data) {
		if (data.success) {
			fetchFormula.reset();
			ui.showMessage({text:'Billedet er hentet',duration:2000});
		} else {
			ui.showMessage({text:data.message,duration:2000});
		}
		fetchImage.setEnabled(true);
		imagesSource.refresh();
		groupSource.refresh();
	}
	
})
