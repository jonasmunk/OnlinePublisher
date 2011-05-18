hui.ui.listen({
	
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
		subsetSource.refresh();
	},
	
	////////////////////////// Fetch /////////////////////////
	
	$click$fetchImage : function() {
		fetchImage.setEnabled(false);
		ui.showMessage({text:'Henter billede...'});
		hui.ui.request({url:'FetchImage.php',onSuccess:'imageFetched',parameters:fetchFormula.getValues()});
	},
	$click$cancelFetch : function() {
		uploadWindow.hide();
	},
	$success$imageFetched : function(data) {
		if (data.success) {
			fetchFormula.reset();
			ui.showMessage({text:'Billedet er hentet',duration:2000});
		} else {
			hui.log(data);
			ui.showMessage({text:data.errorMessage,duration:2000});
		}
		fetchImage.setEnabled(true);
		imagesSource.refresh();
		subsetSource.refresh();
		groupSource.refresh();
	},
	$valueChanged$uploadAddToGroup : function(value) {
		hui.ui.request({url:'ChangeUploadAddToGroup.php',parameters:{uploadAddToGroup:value ? 'true' : 'false'},onSuccess:function() {
			hui.log('Saved: '+value);
		}})
	}
})
