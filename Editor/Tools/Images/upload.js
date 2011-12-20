hui.ui.listen({
	
	userShowedUpload : false,
	
	$filesDropped$gallery : function(files) {
		uploadWindow.show();
		file.uploadFiles(files);
	},
	
	$urlDropped$gallery : function(url) {
		this._fetch(url);
	},
	
	///////////////////////// Uoload /////////////////////////
	
	$click$newFile : function() {
		uploadWindow.show();
		this.userShowedUpload = true;
	},
	$click$cancelUpload : function() {
		uploadWindow.hide();
	},
	$uploadDidComplete$file : function() {
		imagesSource.refresh();
		groupSource.refresh();
		subsetSource.refresh();		
	},
	$uploadDidCompleteQueue$file : function() {
		if (!this.userShowedUpload) {
			//uploadWindow.hide();
		}
	},
	$userClosedWindow$uploadWindow : function() {
		this.userShowedUpload = false;
	},
	
	////////////////////////// Fetch /////////////////////////
	
	$click$fetchImage : function() {
		var values = fetchFormula.getValues();
		this._fetch(values.url);
	},
	_fetch : function(url) {
		fetchImage.setEnabled(false);
		hui.ui.showMessage({text:'Henter billede...',busy:true});
		hui.ui.request({
			url : 'FetchImage.php',
			onSuccess : 'imageFetched',
			parameters : {url : url}
		});
	},
	$click$cancelFetch : function() {
		uploadWindow.hide();
	},
	$success$imageFetched : function(data) {
		if (data.success) {
			fetchFormula.reset();
			hui.ui.showMessage({text:'Billedet er hentet',icon:'common/success',duration:2000});
			imagesSource.refresh();
			subsetSource.refresh();
			groupSource.refresh();
		} else {
			hui.log(data);
			hui.ui.showMessage({text:data.errorDetails,icon:'common/warning',duration:2000});
		}
		fetchImage.setEnabled(true);
	},
	$valueChanged$uploadAddToGroup : function(value) {
		hui.ui.request({url:'ChangeUploadAddToGroup.php',parameters:{uploadAddToGroup:value ? 'true' : 'false'},onSuccess:function() {
			hui.log('Saved: '+value);
		}})
	}
})
