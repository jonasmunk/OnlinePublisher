var partController = {
	$ready : function() {
		var container = this.container = hui.get('part_table');
		var node = hui.get.firstByClass(container,'part_table') || container;
		node.setAttribute('contenteditable','true');
	},
	clean : function() {
		var table = hui.get.firstByTag(this.container,'table');
		var nodes = this.container.getElementsByTagName('*');
		for (var i = nodes.length - 1; i >= 0; i--){
			nodes[i].removeAttribute('style');
		};
		var nodes = this.container.getElementsByTagName('td');
		for (var i = nodes.length - 1; i >= 0; i--) {
			hui.dom.setText(nodes[i],hui.dom.getText(nodes[i]));
		};
		hui.ui.showMessage({text:'Your royalty is now clean!',duration:3000});
		this._updateValue();
	},
	_updateValue : function() {
		document.forms.PartForm.html.value = this.container.innerHTML;
	},
	editSource : function() {
		sourceWindow.show();
		sourceFormula.setValues({source:this.container.innerHTML});
	}
};

hui.ui.listen(partController);