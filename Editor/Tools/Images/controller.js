hui.ui.listen({
	imageId : null,
	dragDrop : [
		{drag:'image',drop:'imagegroup'}
	],
	
	$ready : function() {
		if (window.parent!=window) {
			window.parent.baseController.changeSelection('tool:Images');
		}

	},
	$accessDenied : function() {
		//alert('Access denied');
		//return true;
	},
	
	$valueChanged$viewSwitch : function(value) {
		if (value=='gallery') {
			var selection = selector.getValue().value;
			if (selection=='pages' || selection=='products' || selection=='persons') {
				selector.setValue('all');
			}
		} else {
			this.$selectionReset$gallery();
		}
		hui.ui.changeState(value);
	},
	
	$drop$image$imagegroup : function(dragged,dropped) {
		hui.ui.request({
			url:'AddImageToGroup.php',
			json:{data:{image:dragged.id,group:dropped.value}},
			message:{start:'Tilføjer til gruppe...',delay:300},
			onSuccess:function() {
				imagesSource.refresh();
				listSource.refresh();
				groupSource.refresh();
			}
		});
	},
		
	$resolveImageUrl : function(img,width,height) {
		return '../../../services/images/?id='+img.id+'&width='+width+'&height='+height+'&format=jpg';
	},
	
	
	$select$selector : function(item) {
		if (item.value=='pages' || item.value=='products' || item.value=='persons') {
			hui.ui.changeState('list');
			viewSwitch.setValue('list');
		} else {
			//hui.ui.changeState('gallery');
		}
		groupBar.setVisible(item.kind=='imagegroup');
		groupTitle.setText(item.title);
	},
	
	
	
	// Selection
	
	$select$list : function() {
		this._updateIcons();
	},
	_updateIcons : function() {
		var count = 0;
		if (hui.ui.state == 'list') {
			count = list.getSelectionSize();
		} else {
			count = gallery.getSelectionSize();
		}
		hui.ui.get('delete').setEnabled(count>0);
		hui.ui.get('view').setEnabled(count==1);
		hui.ui.get('download').setEnabled(count==1);
		hui.ui.get('info').setEnabled(count==1);
	},
	
	$select$gallery : function() {
		this._updateIcons();
	},
	$selectionReset$gallery : function() {
		this._updateIcons();
	},
	
	
	
	// Toolbar icons
	
	_getSelectionIds : function() {
		if (hui.ui.state=='list') {
			return list.getSelectionIds();
		} else {
			return gallery.getSelectionIds();
		}		
	},
	
	_getFirstSelection : function() {
		if (hui.ui.state=='list') {
			return list.getFirstSelection();
		} else {
			return gallery.getFirstSelection();
		}		
	},
	
	$click$view : function() {
		var obj = this._getFirstSelection();
		if (obj) {
			window.open('../../../services/images/?id='+obj.id,"filewindow"+obj.id);
		}
	},
	$click$download : function() {
		var obj = this._getFirstSelection();
		if (obj) {
			document.location = 'data/DownloadImage.php?id='+obj.id;
		}
	},
	$click$delete : function() {
		var selection = this._getSelectionIds();
		if (selection.size==0) {
			return;
		}
		
		hui.ui.request({
			url:'data/DeleteImages.php',
			json:{ids:selection},
			message:{start:'Sletter billede...',delay:300},
			onSuccess:function() {
				imagesSource.refresh();
				listSource.refresh();
				groupSource.refresh();
				subsetSource.refresh();
				hui.ui.showMessage({text:'Billedet er nu slettet',icon:'common/success',duration:2000});
			}
		});
		return;
		
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
		var obj = this._getFirstSelection();
		if (obj) {
			this._loadImage(obj.id);
		}
	},
	$open$list : function(row) {
		this._loadImage(row.id);
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
				listSource.refresh();
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
				listSource.refresh();
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