In2iGui.Gallery = function(id) {
	this.id = id;
	this.images = [];
	this.delegate = null;
}

In2iGui.Gallery.prototype.setDelegate = function(delegate) {
	this.delegate = delegate;
}

In2iGui.Gallery.prototype.registerImage = function(id,unique) {
	this.images[this.images.length] = new In2iGui.GalleryImage(id,unique,this);
}

In2iGui.Gallery.prototype.contextmenu = function(icon,event) {
	if (this.delegate && this.delegate.contextMenuWillShow) {
		return this.delegate.contextMenuWillShow(icon,event);
	}
}





In2iGui.GalleryImage = function(id,unique,gallery) {
	this.id = id;
	this.unique = unique;
	this.gallery = gallery;
	this.element = $id(id+'_base');
	this.element.in2iGuiGalleryImage = this;
	this.element.oncontextmenu = function(event) {
		return this.in2iGuiGalleryImage.contextmenu(event);
	}
}

In2iGui.GalleryImage.prototype.contextmenu = function(event) {
	return this.gallery.contextmenu(this,event);
}