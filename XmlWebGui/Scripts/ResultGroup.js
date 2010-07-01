In2iGui.ResultGroup = function(id) {
	this.id=id;
	this.openAction;
	this.closeAction;
}

In2iGui.ResultGroup.prototype.toggle = function() {
	var content = $id(this.id+'_group_content');
	if (content.style.display=='none') {
		content.style.display='';
		$id(this.id+'_group_title_disclosure').src=In2iGui.paths.graphics+'ResultGroupOpen.gif';
		this.callAction(this.openAction);
	} else {
		content.style.display='none';
		$id(this.id+'_group_title_disclosure').src=In2iGui.paths.graphics+'ResultGroupClosed.gif';
		this.callAction(this.closeAction);
	}
}

In2iGui.ResultGroup.prototype.callAction = function(action) {
	if (action) {
		new N2i.Request().request(action);
	}
}