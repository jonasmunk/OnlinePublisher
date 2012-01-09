var partController = {
	$ready : function() {
		var container = this.container = hui.get('part_table');
		this.base = hui.get.firstByClass(container,'part_table') || container;
		this.base.setAttribute('contenteditable','true');
		hui.listen(this.base,'keyup',this._sync.bind(this));
		this.editSource();
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
	_sync : function() {
		sourceFormula.setValues({source:this.base.innerHTML});
		this._updateValue();
	},
	_updateValue : function() {
		document.forms.PartForm.html.value = this.base.innerHTML;
	},
	editSource : function() {
		sourceWindow.show();
		sourceFormula.setValues({source:this.base.innerHTML});
	},
	$valuesChanged$sourceFormula : function(values) {
		this.base.innerHTML = values.source;
		this._updateValue();
	}
};

hui.ui.listen(partController);