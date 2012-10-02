var partController = {
	$ready : function() {
		var form = document.forms.PartForm;
		if (form.imageId.value==='0') {
			this.showChooserWindow();
		}
		imageAdvancedFormula.setValues({
			text : form.text.value,
			frame : form.frame.value,
			greyscale : form.greyscale.value=='true'
		});
		imageUpload.addDropTarget({
			element : hui.get('part_image_container'),
			hoverClass : 'editor_drop',
			$drop : function() {
				imageUploadWindow.show();
			}
		});
		pasteImage.setEnabled(hui.ui.ImagePaster.isSupported());
		this.suppressLink();
	},
	$resolveImageUrl : function(obj,width,height) {
		return '../../../services/images/?id='+obj.value+'&width='+width+'&height='+height;
	},
	$select$imageGallery : function() {
		var id = imageGallery.getFirstSelection().value;
		document.forms.PartForm.imageId.value = id;
		this.preview();
	},
	showChooserWindow : function() {
		imageChooser.show();
	},
	preview : function() {
		op.part.utils.updatePreview({
			node : hui.get('part_image_container'),
			form : document.forms.PartForm,
			type : 'image',
			delay : 500
		});
	},
	suppressLink : function() {
		var container = hui.get('part_image_container');
		var a = container.getElementsByTagName('a');
		if (a) {
			a.href='javascript:void(0)';
		}
		var img = hui.get.firstByTag(container,'img');
		if (img) {
			hui.listen(img,'click',this.showChooserWindow.bind(this));
		}
	},
	showUploadWindow : function() {
		imageUploadWindow.show();
	},
	$uploadDidCompleteQueue$imageUpload : function() {
		hui.ui.request({
			'url' : '../../Parts/image/UploadStatus.php',
			onJSON : function(status) {
				if (status.id) {
					document.forms.PartForm.imageId.value = status.id;
					this.preview();
				} else {
					hui.ui.showMessage({
						text: {	en:'The image could not be added, it may be too large or of an unknown type',
								da:'Billedet kunne ikke tilføjes, det kan være for stort eller af en ukendt type'},
						icon:'common/warning',
						duration:3000
					});
				}
			}.bind(this)
		});
	},
	$click$cancelUpload : function() {
		imageUploadWindow.hide();
	},
	$click$cancelFetch : function() {
		imageUploadWindow.hide();
	},
	$click$createFromUrl : function() {
		this.$submit$urlForm();
	},
	$submit$urlForm : function() {
		var form = hui.ui.get('urlForm');
		var url = form.getValues()['url'];
		if (hui.isBlank(url)) {
			hui.ui.showMessage({text:{en:'The address is required',da:'Adressen er krævet'},duration:3000});
			form.focus();
			return;
		}
		createFromUrl.disable();
		hui.ui.showMessage({text:{en:'Fetching image...',da:'Henter billede...'},busy:true});
		hui.ui.request({
			url : '../../Parts/image/Fetch.php',
			parameters : {url:url},
			onJSON : function(status) {
				if (status.success) {
					urlForm.reset();
					urlForm.focus();
					hui.ui.hideMessage();
					document.forms.PartForm.imageId.value = status.object.id;
					this.preview();
				} else {
					hui.ui.showMessage({text:{en:'It was not possible to fetch the image',da:'Det lykkedes ikke at hente billedet'},icon:'common/warning',duration:3000});
				}
				createFromUrl.enable();
			}.bind(this)
		});
		
	},
	
	// Pasting ...
	$click$pasteImage : function() {
		this.paste();
	},
	isPasteSupported : function() {
		return hui.ui.ImagePaster.isSupported();
	},
	paste : function() {
		hui.ui.showMessage({text:{en:'Pasting...',da:'Indsætter...'},busy:true});
		window.setTimeout(function() {
			if (!this.paster) {
				this.paster = hui.ui.ImagePaster.create({invisible:true});
				this.paster.listen({
					$imageWasPasted : function(data) {
						hui.ui.showMessage({text:{en:'The image has been pasted',da:'Billedet er indsat'},icon:'common/success',duration:2000});
						this._updateWithData(data);
					}.bind(this),
					$imagePasteFailed : function(code) {
						var msg = {
							unknown : {en:'An unknown error occurred',da:'Der skete en uventet fejl'},
							empty : {en:'The clipboard is empty',da:'Udklipsholderen er tom'},
							invalid : {en:'The clipboard does not contain a valid image',da:'Der er ikke et validt billede i udklipsholderen'},
							busy : {en:'The clipboard is unavailable',da:'Udklipsholderen er ikke tilgængelig'}
						};
						hui.ui.showMessage({text:msg[code] || msg['unknown'],icon:'common/warning',duration:5000});
					}
				})
			}
			hui.log('Telling paster to paste');
			this.paster.paste();			
		}.bind(this),300)
	},
	
	
	_updateWithData : function(data) {
		hui.ui.request({
			url : '../../Services/Images/Create.php',
			parameters : {data:data,title:hui.ui.language=='da' ? 'Udklipsholder' : 'Clipboard'},
			onFailure : function() {
				hui.ui.showMessage({text:{en:'It was not possible to create an image from the clipboard',da:'Det lykkedes ikke at lave et billede fra udklipsholderen'},icon:'common/warning',duration:2000});
			},
			onJSON : function(response) {
				hui.ui.showMessage({text:{en:'The image has been inserted',da:'Billedet er nu indsat'},icon:'common/success',duration:2000});
				document.forms.PartForm.imageId.value = response.id;
				this.preview();
			}.bind(this)
		});
	},
	showAdvancedWindow : function() {
		imageAdvancedWindow.show();
	},
	$valuesChanged$imageAdvancedFormula : function(values) {
		document.forms.PartForm.text.value=values.text;
		document.forms.PartForm.greyscale.value=values.greyscale;
		document.forms.PartForm.frame.value=values.frame;
		this.preview();
	}
}

hui.ui.listen(partController);