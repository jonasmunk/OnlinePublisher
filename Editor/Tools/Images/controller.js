hui.ui.listen({
	imageId : null,
	dragDrop : [
		{drag:'image',drop:'imagegroup'}
	],
	
	$ready : function() {
		hui.ui.tellContainers('changeSelection','tool:Images');
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
			url : 'actions/AddImageToGroup.php',
			json : {data:{image:dragged.id,group:dropped.value}},
			message : {start:{en:'Adding to group...', da:'Tilføjer til gruppe...'},delay:300},
			$success : function() {
				imagesSource.refresh();
				listSource.refresh();
				groupSource.refresh();
				hui.ui.showMessage({text:{en:'The image has been added to the group',da:'Billedet er blevet tilføjet til gruppen'},icon:'common/success',duration:2000})
			}
		});
	},
		
	$resolveImageUrl : function(img,width,height) {
		return '../../../services/images/?id='+img.id+'&width='+width+'&height='+height+'&format=jpg';
	},
	
	$valueChanged$sizeSlider : function(value) {
		gallery.setSize(20+value*180);
	},
	$valueChangedEnd$sizeSlider : function() {
		gallery.reRender();
	},
	
	$select$selector : function(item) {
		list.resetState();
		if (item.value=='pages' || item.value=='products' || item.value=='persons') {
			hui.ui.changeState('list');
			viewSwitch.setValue('list');
		} else {
			//hui.ui.changeState('gallery');
		}
		groupBar.setVisible(item.kind=='imagegroup');
		groupTitle.setText(item.title);
	},
	$valueChanged$search : function() {
		list.resetState();
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
			document.location = 'actions/DownloadImage.php?id='+obj.id;
		}
	},
	$click$delete : function() {
		var selection = this._getSelectionIds();
		if (selection.size==0) {
			return;
		}
		
		hui.ui.request({
			url:'actions/DeleteImages.php',
			json:{ids:selection},
			message:{start:{en:'Deleting image..', da:'Sletter billede...'},delay:300},
			$success:function() {
				imagesSource.refresh();
				listSource.refresh();
				groupSource.refresh();
				subsetSource.refresh();
				hui.ui.showMessage({text:{en:'The image has been deleted', da:'Billedet er nu slettet'},icon:'common/success',duration:2000});
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
			url:'actions/SaveImage.php',
			json:{data:data},
			message:{start:{en:'Saving image...', da:'Gemmer billede...'},delay:300},
			$success:function() {
				imagesSource.refresh();
				listSource.refresh();
				groupSource.refresh();
				hui.ui.showMessage({text:{en:'The image has been saved', da:'Billedet er gemt'},icon:'common/success',duration:2000});			
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
		var photo = hui.get('photo');
		photo.innerHTML = '';
		hui.ui.request({
			url : 'data/LoadImage.php',
			parameters : {id:id},
			message : {start:{en:'Loading image...',da:'Åbner billede...'},delay:300},
			$object : function(data) {
				this.imageId = id;
				imageFormula.setValues(data.image);
				imageGroups.setValue(data.groups);
				imageWindow.show();
				imageFormula.focus();
				hui.build('img',{src:'../../../services/images/?id='+id+'&width=150',parent:photo,title:id});
			}.bind(this)
		});
	},
	_deleteImage : function(id) {
		hui.ui.request({
			url:'actions/DeleteImage.php',
			parameters:{id:id},
			message:{start:{en:'Deleting image...', da:'Sletter billede...'},delay:300},
			$success:function() {
				imagesSource.refresh();
				listSource.refresh();
				groupSource.refresh();
				subsetSource.refresh();
				hui.ui.showMessage({text:{en:'The image has been deleted',da:'Billedet er slettet'},icon:'common/success',duration:2000});
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