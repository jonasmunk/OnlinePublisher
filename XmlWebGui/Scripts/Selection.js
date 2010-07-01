In2iGui.Selection = function(id,value,objectName) {
	this.id=id;
	this.objectName=objectName;
	this.items = {};
	this.value = value;
}

In2iGui.Selection.prototype.callDelegate = function(event,type) {
	if (this.delegate) {
		try {
			eval('this.delegate.'+type+'(event,this)');
		} catch(e) {}
	}	
}

In2iGui.Selection.prototype.setDelegate = function(delegate) {
	this.delegate = delegate;
};

In2iGui.Selection.prototype.updateGui = function() {
	try {
		var lmnt;
		var item;
		for (item in this.items) {
			if (this.items[item].value==this.value) {
				N2i.Element.addClassName(this.items[item].element, 'Selected');
			} else {
				N2i.Element.removeClassName(this.items[item].element, 'Selected');
			}
		}
	} catch (ignore) {}
}

In2iGui.Selection.prototype.getValue = function() {
	return this.value;
};

In2iGui.Selection.prototype.setValue = function(value) {
	this.value=value;
	this.updateGui();
};

In2iGui.Selection.prototype.registerItem = function(itemId,value) {
	var item = document.getElementById(itemId);
	this.items[itemId] = {value:value,element:item};
	item.in2iGuiSelection = this;
	item.onclick = function(event) {this.in2iGuiSelection.clicked(event,this); return false;}
	item.onmouseover = function(event) {this.in2iGuiSelection.over(event,this);};
	item.onmouseout = function(event) {this.in2iGuiSelection.out(event,this);};
};

////////////////////////// Event handlers //////////////////////////

In2iGui.Selection.prototype.clicked = function(event,item) {
	// Only if value changed
	if (this.value!=this.items[item.id].value) {
		this.value = this.items[item.id].value;
		this.updateGui();
		this.callDelegate(event,'valueDidChange');
	}
};

In2iGui.Selection.prototype.over = function(event,item) {
	try {
		new N2i.Element.addClassName(item,'Over');
	} catch (ignore) {}
};

In2iGui.Selection.prototype.out = function(event,item) {
	try {
		new N2i.Element.removeClassName(item,'Over');
	} catch (ignore) {}
};