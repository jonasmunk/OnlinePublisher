In2iGui.Form.Combo = function(id) {
	this.id=id;
	this.select = $id(id+'_select');
	this.selectedIndex = this.select.selectedIndex;
	this.changeDisplay(true);
	this.addBehavior();
}

In2iGui.Form.Combo.prototype.addBehavior = function() {
	var self = this;
	this.select.onchange = this.select.onkeyup = function() {
		self.selectMightHaveChanged();
	}
}

In2iGui.Form.Combo.prototype.selectMightHaveChanged = function() {
	if (this.selectedIndex!=this.select.selectedIndex) {
		this.changeDisplay(false);
		this.selectedIndex=this.select.selectedIndex;
		this.changeDisplay(true);
	}
}

In2iGui.Form.Combo.prototype.changeDisplay = function(show) {
	try {
		var sub = $id(this.id+'_'+this.selectedIndex);
		if (sub) {
			sub.style.display=(show ? 'block' : 'none');
		}
	} catch (e) {};
}