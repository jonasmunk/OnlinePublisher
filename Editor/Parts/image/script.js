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
			onDrop : function() {
				imageUploadWindow.show();
			}
		});
		pasteImage.setEnabled(hui.ui.ImagePaster.isSupported());
		this.suppressLink();
	},
	$resolveImageUrl : function(obj,width,height) {
		return '../../../services/images/?id='+obj.value+'&width='+width+'&height='+height;
	},
	$selectionChanged$imageGallery : function(list) {
		var id = list.getFirstSelection().value;
		document.forms.PartForm.imageId.value = id;
		this.preview();
	},
	showChooserWindow : function() {
		imageChooser.show();
	},
	preview : function() {
		var self = this;
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
		var img = hui.firstByTag(container,'img');
		if (img) {
			hui.listen(img,'click',this.showChooserWindow.bind(this));
		}
	},
	showUploadWindow : function() {
		imageUploadWindow.show();
	},
	$uploadDidCompleteQueue$imageUpload : function() {
		hui.ui.request({'url':'../../Parts/image/UploadStatus.php',onJSON:function(status) {
			document.forms.PartForm.imageId.value = status.id;
			this.preview();
		}.bind(this)});
	},
	$click$cancelUpload : function() {
		imageUploadWindow.hide();
	},
	$click$cancelFetch : function() {
		imageUploadWindow.hide();
	},
	$submit$urlForm : function() {
		var form = hui.ui.get('urlForm');
		var url = form.getValues()['url'];
		if (hui.isBlank(url)) {
			form.focus();
			return;
		}
		createFromUrl.disable();
		hui.ui.showMessage({text:'Henter billede...',busy:true});
		hui.ui.request({
			url : '../../Parts/image/Fetch.php',
			parameters : {url:url},
			onJSON : function(status) {
				if (status.success) {
					urlForm.reset();
					urlForm.focus();
					hui.ui.hideMessage();
					document.forms.PartForm.imageId.value = status.id;
					this.preview();
				} else {
					hui.ui.showMessage({text:'Det lykkedes ikke at hente billedet',icon:'common/warning',duration:3000});
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
		hui.ui.showMessage({text:'Pasting...',busy:true});
		if (!this.paster) {
			this.paster = hui.ui.ImagePaster.create({invisible:true});
			this.paster.listen({
				$imageWasPasted : function(data) {
					hui.ui.showMessage({text:'Pasted!',icon:'common/success',duration:2000});
					this._updateWithData(data);
				}.bind(this),
				$imagePasteFailed : function(code) {
					var msg = {
						unknown:'Der skete en uventet fejl',
						empty:'Udklipsholderen er tom',
						invalid:'Der er ikke et validt billede i udklipsholderen',
						busy:'Udklipsholderen er i brug'
					};
					hui.ui.showMessage({text:msg[code] || 'Der skete en ukendt fejl',icon:'common/warning',duration:5000});
				}
			})
		}
		hui.log('Telling paster to paste');
		this.paster.paste();
	},
	
	
	_updateWithData : function(data) {
		hui.ui.request({
			url : '../../Services/Images/Create.php',
			parameters : {data:data,title:'Udklipsholder'},
			onFailure : function() {
				hui.ui.showMessage({text:'Det lykkedes ikke at lave et billede fra udklipsholderen',icon:'common/warning',duration:2000});
			},
			onJSON : function(response) {
				hui.ui.showMessage({text:'Billedet er nu indsat',icon:'common/success',duration:2000});
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