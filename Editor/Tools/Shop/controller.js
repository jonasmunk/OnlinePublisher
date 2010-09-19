ui.listen({
	offerId : 0,
	groupId : 0,
	dragDrop : [
		{drag:'product',drop:'productgroup'},
		{drag:'product',drop:'producttype'}
	],
	
	$ready : function() {
		list.loadData('ListProducts.php');
	},
	
	$resolveImageUrl : function(img,width,height) {
		return '../../../util/images/?id='+img.id+'&maxwidth='+width+'&maxheight='+height+'&format=jpg';
	},
	
	$selectionChanged$selector : function(obj) {
		if (obj.value=='productgroup') {
			list.loadData('../../Services/Model/ListObjects.php?type=productgroup');
		} else if (obj.value=='product') {
			list.loadData('ListProducts.php');
		} else if (obj.value=='productoffer') {
			list.loadData('ListOffers.php');
		} else if (obj.kind=='productgroup') {
			list.loadData('ListProducts.php?productgroup='+obj.value);
		} else if (obj.kind=='producttype') {
			list.loadData('ListProducts.php?producttype='+obj.value);
		}
	},
	$selectionWasOpened : function() {
		var obj = selector.getValue();
		if (obj.kind=='productgroup') {
			this.loadGroup(obj.value);
		}
		else if (obj.kind=='producttype') {
			this.loadType(obj.value);
		}
	},
	
	$listRowsWasOpened$list : function(list) {
		var obj = list.getFirstSelection();
		var data = {id:obj.id};
		if (obj.kind=='productoffer') {
			offerFormula.reset();
			deleteOffer.setEnabled(false);
			saveOffer.setEnabled(false);
			In2iGui.json({data:data},'../../Services/Model/LoadObject.php','loadOffer');
		} else if (obj.kind=='productgroup') {
			this.loadGroup(obj.id);
		} else if (obj.kind=='product') {
			this.loadProduct(obj.id);
		}
	},
	
	/////////////////// Product ///////////////
	
	loadProduct : function(id) {
		var data = {id:id};
		productFormula.reset();
		deleteProduct.setEnabled(false);
		saveProduct.setEnabled(false);
		In2iGui.json({data:data},'LoadProduct.php','loadProduct');		
	},
	$success$loadProduct : function(data) {
		this.productId = data.product.id;
		productTitle.setValue(data.product.title);
		productNote.setValue(data.product.note);
		productNumber.setValue(data.product.number);
		productAllowOffer.setValue(data.product.allowOffer);
		productImage.setObject(data.product.imageId>0 ? {id:data.product.imageId} : null);
		productAttributes.setObjects(data.attributes);
		productPrices.setObjects(data.prices);
		productType.setValue(data.product.productTypeId)
		productGroups.setValues(data.groups);
		productSearchable.setValue(data.product.searchable);
		productEditor.show();
		deleteProduct.setEnabled(true);
		saveProduct.setEnabled(true);
		productTitle.focus();
	},
	$click$newProduct : function() {
		this.productId = 0;
		productFormula.reset();
		productEditor.show();
		deleteProduct.setEnabled(false);
		productTitle.focus();
	},
	$click$saveProduct : function() {
		var data = {
			product:{
				id:this.productId,
				title:productTitle.getValue(),
				note:productNote.getValue(),
				imageId : (productImage.getObject() ? productImage.getObject().id : null),
				number:productNumber.getValue(),
				allowOffer:productAllowOffer.getValue(),
				productTypeId:productType.getValue(),
				searchable:productSearchable.getValue()
			},
			attributes : productAttributes.getObjects(),
			prices : productPrices.getObjects(),
			groups:productGroups.getValues()
		};
		n2i.log(data);
		In2iGui.json({data:data},'SaveProduct.php','saveProduct');
	},
	$success$saveProduct : function() {
		list.refresh();
		productFormula.reset();
		productEditor.hide();
	},
	$click$cancelProduct : function() {
		productEditor.hide();
		productFormula.reset();
	},
	$getImageUrl$productImage : function(picker) {
		var obj = picker.getObject();
		return '../../../util/images/?id='+obj.id+'&maxwidth=48&maxheight=48&format=jpg';
	},
	$click$deleteProduct : function() {
		In2iGui.json({data:{id:this.productId}},'../../Services/Model/DeleteObject.php','deleteProduct');
	},
	$success$deleteProduct : function() {
		this.productId=null;
		productEditor.hide();
		productFormula.reset();
		list.refresh();
	},
	
	//////////////////// Offer ////////////////
	
	$success$loadOffer : function(data) {
		this.offerId = data.id;
		offerOffer.setValue(data.offer);
		offerNote.setValue(data.note);
		offerExpiry.setValue(data.expiry);
		offerEditor.show();
		deleteOffer.setEnabled(true);
		saveOffer.setEnabled(true);
		offerOffer.focus();
	},
	$click$cancelOffer : function() {
		offerEditor.hide();
		offerFormula.reset();
	},
	$click$deleteOffer : function() {
		In2iGui.json({data:{id:this.offerId}},'../../Services/Model/DeleteObject.php','deleteOffer');
	},
	$success$deleteOffer : function() {
		offerEditor.hide();
		offerFormula.reset();
		list.refresh();
	},
	$click$saveOffer : function() {
		var data = {
			id:this.offerId,
			offer:offerOffer.getValue(),
			note:offerNote.getValue(),
			expiry:offerExpiry.getValue()
		};
		In2iGui.json({data:data},'SaveOffer.php','saveOffer');
	},
	$success$saveOffer : function() {
		offerEditor.hide();
		offerFormula.reset();
		list.refresh();
	},
	
	//////////////////// Group ////////////////
	
	loadGroup : function(id) {
		var data = {id:id};
		groupFormula.reset();
		deleteGroup.setEnabled(false);
		saveGroup.setEnabled(false);
		In2iGui.json({data:data},'../../Services/Model/LoadObject.php','loadGroup');
	},
	$click$newGroup : function() {
		this.groupId = 0;
		groupFormula.reset();
		groupEditor.show();
		deleteGroup.setEnabled(false);
		groupFormula.focus();
	},
	$success$loadGroup : function(data) {
		this.groupId = data.id;
		groupFormula.setValues(data);
		groupEditor.show();
		deleteGroup.setEnabled(true);
		saveGroup.setEnabled(true);
		groupFormula.focus();
	},
	$click$cancelGroup : function() {
		groupEditor.hide();
		groupFormula.reset();
	},
	$click$deleteGroup : function() {
		In2iGui.json({data:{id:this.groupId}},'../../Services/Model/DeleteObject.php','deleteGroup');
	},
	$success$deleteGroup : function() {
		groupEditor.hide();
		groupFormula.reset();
		list.refresh();
		groupSource.refresh();
	},
	$submit$groupFormula : function() {
		var data = groupFormula.getValues();
		if (n2i.isBlank(data.title)) {
			ui.showMessage({text:'Titlen skal udfyldes',duration:2000});
			groupFormula.focus();
			return;
		}
		data.id=this.groupId;
		In2iGui.json({data:data},'SaveGroup.php','saveGroup');
	},
	$success$saveGroup : function() {
		groupEditor.hide();
		groupFormula.reset();
		list.refresh();
		groupSource.refresh();
	},
	
	//////////////////// Type ////////////////
	
	loadType : function(id) {
		var data = {id:id};
		typeFormula.reset();
		deleteType.setEnabled(false);
		saveType.setEnabled(false);
		In2iGui.json({data:data},'../../Services/Model/LoadObject.php','loadType');
	},
	$click$newType : function() {
		this.typeId = 0;
		typeFormula.reset();
		typeEditor.show();
		deleteType.setEnabled(false);
		typeTitle.focus();
	},
	$success$loadType : function(data) {
		this.typeId = data.id;
		typeTitle.setValue(data.title);
		typeNote.setValue(data.note);
		typeEditor.show();
		deleteType.setEnabled(true);
		saveType.setEnabled(true);
		typeTitle.focus();
	},
	$click$cancelType : function() {
		typeEditor.hide();
		typeFormula.reset();
	},
	$click$deleteType : function() {
		In2iGui.json({data:{id:this.typeId}},'../../Services/Model/DeleteObject.php','deleteType');
	},
	$success$deleteType : function() {
		typeEditor.hide();
		groupFormula.reset();
		list.refresh();
		typeSource.refresh();
	},
	$click$saveType : function() {
		var data = {
			id:this.typeId,
			title:typeTitle.getValue(),
			note:typeNote.getValue()
		};
		In2iGui.json({data:data},'SaveType.php','saveType');
	},
	$success$saveType : function() {
		typeEditor.hide();
		typeFormula.reset();
		list.refresh();
		typeSource.refresh();
	}
});