In2iGui.ResultSelection = function(id,value,objectName) {
	this.id=id;
	this.objectName=objectName;
	this.items = {};
	this.value = value;
}

In2iGui.ResultSelection.prototype.itemClicked = function(event,item) {
	// Only if value changed
	if (this.value!=this.items[item.id].value) {
		this.value = this.items[item.id].value;
		this.updateGui();
		this.callDelegate(event,'valueDidChange');
	}
}

In2iGui.ResultSelection.prototype.callDelegate = function(event,type) {
	if (this.delegate) {
		try {
			eval('this.delegate.'+type+'(event,this)');
		} catch(e) {}
	}	
}

In2iGui.ResultSelection.prototype.setDelegate = function(delegate) {
	this.delegate = delegate;
};

In2iGui.ResultSelection.prototype.updateGui = function() {
	for (var item in this.items) {
		if (this.items[item].value==this.value) {
			this.items[item].element.className='Selected';
		} else {
			this.items[item].element.className='';
		}
	}
}

In2iGui.ResultSelection.prototype.getValue = function() {
	return this.value;
};

In2iGui.ResultSelection.prototype.registerItem = function(itemId,value) {
	var item = document.getElementById(itemId);
	this.items[itemId] = {value:value,element:item};
	item.in2iGuiResultSelection = this;
	item.onclick = function(event) {this.in2iGuiResultSelection.itemClicked(event,this); return false;}
};