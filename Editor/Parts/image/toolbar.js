hui.ui.listen({
	$ready : function() {
		var form = partToolbar.partForm;
		alignment.setValue(form.align.value);
		greyscale.setValue(form.greyscale.value);
		scaleWidth.setValue(form.scalewidth.value);
		scaleHeight.setValue(form.scaleheight.value);
		scalePercent.setValue(form.scalepercent.value);
		text.setValue(form.text.value);
		if (form.linkType.value=='page') {
			page.setValue(form.linkValue.value);
		} else if (form.linkType.value=='file') {
			file.setValue(form.linkValue.value);
		} else if (form.linkType=='url') {
			url.setValue(form.linkValue.value);
		} else if (form.linkType.value=='email') {
			email.setValue(form.linkValue.value);
		} else if (form.linkType.value=='image') {
			image.setValue(form.linkValue.value);
		} else if (form.linkType.value=='sameimage') {
			sameimage.setValue(true);
		}
		pasteImage.setEnabled(partToolbar.getMainController().isPasteSupported());
	},
	$valueChanged$page : function() {
		partToolbar.partForm.linkType.value='page';
		partToolbar.partForm.linkValue.value=page.getValue();
		file.reset();
		url.reset();
		email.reset();
		image.reset();
		sameimage.reset();
	},
	$valueChanged$file : function() {
		partToolbar.partForm.linkType.value='file';
		partToolbar.partForm.linkValue.value=file.getValue();
		page.reset();
		url.reset();
		email.reset();
		image.reset();
		sameimage.reset();
	},
	$valueChanged$url : function() {
		partToolbar.partForm.linkType.value='url';
		partToolbar.partForm.linkValue.value=url.getValue();
		file.reset();
		page.reset();
		email.reset();
		image.reset();
		sameimage.reset();
	},
	$valueChanged$email : function() {
		partToolbar.partForm.linkType.value='email';
		partToolbar.partForm.linkValue.value=email.getValue();
		file.reset();
		page.reset();
		url.reset();
		image.reset();
		sameimage.reset();
	},
	$valueChanged$image : function() {
		partToolbar.partForm.linkType.value='image';
		partToolbar.partForm.linkValue.value=image.getValue();
		file.reset();
		page.reset();
		url.reset();
		email.reset();
		sameimage.reset();
	},
	$valueChanged$sameimage : function() {
		partToolbar.partForm.linkType.value='sameimage';
		partToolbar.partForm.linkValue.value='';
		file.reset();
		page.reset();
		url.reset();
		email.reset();
		image.reset();
	},
	$valueChanged$alignment : function() {
		this.update();
	},
	$valueChanged$greyscale : function() {
		this.update();
	},
	$valueChanged$scaleWidth : function() {
		scalePercent.reset();
		this.update();
	},
	$valueChanged$scaleHeight : function() {
		scalePercent.reset();
		this.update();
	},
	$valueChanged$scalePercent : function() {
		scaleHeight.reset();
		scaleWidth.reset();
		this.update();
	},
	$valueChanged$text : function() {
		this.update();
	},
	update : function() {
		partToolbar.partForm.align.value=alignment.getValue();
		partToolbar.partForm.greyscale.value=greyscale.getValue();
		partToolbar.partForm.scalewidth.value=scaleWidth.getValue();
		partToolbar.partForm.scaleheight.value=scaleHeight.getValue();
		partToolbar.partForm.scalepercent.value=scalePercent.getValue();
		partToolbar.partForm.scalemethod.value = scalePercent.getValue()>0 ? 'percent' : 'max';
		partToolbar.partForm.text.value=text.getValue();
		partToolbar.preview();
	},
	$click$addImage : function() {
		partToolbar.getMainController().showUploadWindow();
	},
	$click$chooseImage : function() {
		partToolbar.getMainController().showChooserWindow();
	},
	
	// Pasting...
	
	$click$pasteImage : function() {
		partToolbar.getMainController().paste();
	}
});