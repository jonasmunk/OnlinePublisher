OP.ProductListing = function() {
	this.images = [];
}

OP.ProductListing.get = function() {
	if (!OP.ProductListing.instance) {
		OP.ProductListing.instance = new OP.ProductListing();
	}
	return OP.ProductListing.instance;
}

OP.ProductListing.prototype = {
	addImage : function(img) {
		this.images.push(img);
	},
	showImage : function(id) {
		var viewer = In2iGui.get('productListingViewer');
		if (!viewer) {			
			viewer = In2iGui.ImageViewer.create({name:'productListingViewer'});
			for (var i=0; i < this.images.length; i++) {
				viewer.addImage(this.images[i]);
			};
			viewer.listen({
				$resolveImageUrl : function(img,width,height) {
					return OP.Page.path+'util/images/?id='+img.id+'&maxwidth='+width+'&maxheight='+height+'&format=jpg&quality=70';
				}
			});
		}
		viewer.showById(id);
	}
}