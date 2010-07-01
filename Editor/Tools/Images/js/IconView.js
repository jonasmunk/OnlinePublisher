var menuDelegate = {
	active : null,
	contextMenuWillShow : function(icon,event) {
		this.active = icon;
		in2iMenuHandler.showMenu(ContextMenu, icon.element, 'vertical', event, true);
		return false;
	},
	viewImage : function() {
		window.parent.location.href = 'ImageView.php?id='+(this.active.unique);
	},
	deleteImage : function() {
		window.parent.location.href = 'DeleteImage.php?id='+(this.active.unique);
	},
	downloadImage : function() {
		window.location.href = 'DownloadImage.php?id='+(this.active.unique);
	},
	imageProperties : function() {
		window.parent.location.href = 'ImageProperties.php?id='+(this.active.unique);
	},
	imageInfo : function() {
		window.parent.location.href = 'ImageInfo.php?id='+(this.active.unique);
	},
	removeFromGroup : function() {
		window.parent.location.href = 'RemoveFromGroup.php?id='+(this.active.unique);
	},
	addToGroup : function(group) {
		window.parent.location.href = 'AddImageToGroup.php?imageId='+(this.active.unique)+'&groupId='+group;
	},
	moveToGroup : function(group) {
		window.parent.location.href = 'MoveImageToGroup.php?imageId='+(this.active.unique)+'&groupId='+group;
	}
}

Icons.setDelegate(menuDelegate);