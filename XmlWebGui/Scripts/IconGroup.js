In2iGui.IconGroup = function(id) {
	this.id = id;
	this.icons = [];
	this.delegate = null;
}

In2iGui.IconGroup.prototype.setDelegate = function(delegate) {
	this.delegate = delegate;
}

In2iGui.IconGroup.prototype.registerIcon = function(id,unique) {
	this.icons[this.icons.length] = new In2iGui.IconGroupIcon(id,unique,this);
}

In2iGui.IconGroup.prototype.contextmenu = function(icon,event) {
	if (this.delegate && this.delegate.contextMenuWillShow) {
		return this.delegate.contextMenuWillShow(icon,event);
	}
}





In2iGui.IconGroupIcon = function(id,unique,group) {
	this.id = id;
	this.unique = unique;
	this.group = group;
	this.element = $id(id);
	this.element.in2iGuiIconGroupIcon = this;
	this.element.oncontextmenu = function(event) {
		return this.in2iGuiIconGroupIcon.contextmenu(event);
	}
}

In2iGui.IconGroupIcon.prototype.contextmenu = function(event) {
	return this.group.contextmenu(this,event);
}