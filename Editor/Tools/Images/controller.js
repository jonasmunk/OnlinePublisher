hui.ui.listen({
	imageId : null,
	dragDrop : [
		{drag:'image',drop:'imagegroup'}
	],
	
	$accessDenied : function() {
		//alert('Access denied');
		//return true;
	},
	
	$drop$image$imagegroup : function(dragged,dropped) {
		hui.ui.request({
			url:'AddImageToGroup.php',
			json:{data:{image:dragged.id,group:dropped.value}},
			message:{start:'Tilføjer til gruppe...',delay:300},
			onSuccess:function() {
				imagesSource.refresh();
				groupSource.refresh();
			}
		});
	},
		
	$resolveImageUrl : function(img,width,height) {
		return '../../../services/images/?id='+img.id+'&width='+width+'&height='+height+'&format=jpg';
	},
	
	
	$selectionChanged$selector : function(item) {
		if (item.value=='pages' || item.value=='products' || item.value=='persons') {
			hui.ui.changeState('list');
		} else {
			hui.ui.changeState('gallery');
		}
	},
	
	$selectionChanged$gallery : function() {
		hui.ui.get('delete').setEnabled(true);
		hui.ui.get('view').setEnabled(true);
		hui.ui.get('download').setEnabled(true);
		hui.ui.get('info').setEnabled(true);
	},
	$selectionReset$gallery : function() {
		hui.ui.get('delete').setEnabled(false);
		hui.ui.get('view').setEnabled(false);
		hui.ui.get('download').setEnabled(false);
		hui.ui.get('info').setEnabled(false);
	},
	
	$click$view : function() {
		var obj = gallery.getFirstSelection();
		window.open('../../../services/images/?id='+obj.id,"filewindow"+obj.id);
	},
	$click$download : function() {
		var obj = gallery.getFirstSelection();
		document.location = 'DownloadImage.php?id='+obj.id;
	},
	$click$delete : function() {
		var obj = gallery.getFirstSelection();
		this._deleteImage(obj.id);
		if (obj.id===this.imageId) {
			this._cancelImage();
		}

	},
	$click$deleteImage : function() {
		if (!this.imageId) {
			return;
		}
		this._deleteImage(this.imageId);
		this._cancelImage();
	},
	$click$info : function() {
		var obj = gallery.getFirstSelection();
		this._loadImage(obj.id);
	},
	
	$itemOpened$gallery : function(item) {
		this._loadImage(item.id);
	},
	$click$cancelImage : function() {
		this._cancelImage();
	},
	
	$submit$imageFormula : function() {
		if (!this.imageId) {
			return; // important guard
		}
		var self = this;
		var data = imageFormula.getValues();
		data.id = this.imageId;
		hui.ui.request({
			url:'SaveImage.php',
			json:{data:data},
			message:{start:'Gemmer billede...',delay:300},
			onSuccess:function() {
				imagesSource.refresh();
				groupSource.refresh();
				hui.ui.showMessage({text:'Billedet er gemt!',icon:'common/success',duration:2000});			
			}
		});
		self._cancelImage();
	},
	_cancelImage : function() {
		imageFormula.reset();
		imageWindow.hide();
		this.imageId = null;
	},
	_loadImage : function(id) {
		var self = this;
		hui.ui.request({
			url:'LoadImage.php',
			parameters:{id:id},
			message:{start:'Åbner billede...',delay:300},
			onJSON:function(data) {
				self.imageId = id;
				imageFormula.setValues(data.image);
				imageGroups.setValue(data.groups);
				imageWindow.show();
				imageFormula.focus();
			}
		});
	},
	_deleteImage : function(id) {
		hui.ui.request({
			url:'DeleteImage.php',
			parameters:{id:id},
			message:{start:'Sletter billede...',delay:300},
			onSuccess:function() {
				imagesSource.refresh();
				groupSource.refresh();
				subsetSource.refresh();
				hui.ui.showMessage({text:'Billedet er nu slettet',icon:'common/success',duration:2000});
			}
		});
	},
	
	
	$clickIcon$list : function(info) {
		if (info.data.action=='editPage') {
			document.location='../../Template/Edit.php?id='+info.data.id;
		} else if (info.data.action=='editPerson') {
			document.location='../Customers/?person='+info.data.id;
		} else if (info.data.action=='editProduct') {
			document.location='../Shop/?product='+info.data.id;
		}
	}
});