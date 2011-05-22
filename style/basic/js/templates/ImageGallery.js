op.ImageGallery = function() {
	this.images = [];
}

op.ImageGallery.get = function() {
	if (!op.ImageGallery.instance) {
		op.ImageGallery.instance = new op.ImageGallery();
	}
	return op.ImageGallery.instance;
}

op.ImageGallery.prototype = {
	addImage : function(img) {
		this.images.push(img);
	},
	showImage : function(id) {
		var viewer = hui.ui.get('imageGalleryViewer');
		if (!viewer) {			
			viewer = hui.ui.ImageViewer.create({name:'ImageGallery'});
			for (var i=0; i < this.images.length; i++) {
				viewer.addImage(this.images[i]);
			};
			viewer.listen({
				$resolveImageUrl : function(img,width,height) {
					var w = img.width ? Math.min(width,img.width) : width;
					var h = img.height ? Math.min(height,img.height) : height;
					return op.page.path+'services/images/?id='+img.id+'&width='+w+'&height='+h+'&format=jpg&quality=70';
				}
			});
		}
		viewer.showById(id);
	}
}