var partController = {
	$ready : function() {
		var container = this.container = hui.get('part_table');
		this.base = hui.get.firstByClass(container,'part_table') || container;
		this.base.setAttribute('contenteditable','true');
		hui.listen(this.base,'keyup',this._sync.bind(this));
		this.editSource();
	},
	_getTable : function() {
		return hui.get.firstByTag(this.container,'table');
	},
	clean : function() {
		var table = this._getTable();
		if (!table) {return}
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
	addRow : function() {
		var table = this._getTable();
		if (!table) {return}
		var trs = hui.get.byTag(table,'tr');
		if (trs.length>0) {
			var last = trs[trs.length-1];
			var tr = hui.build('tr');
			hui.dom.insertAfter(last,tr);
			var cells = hui.get.children(last);
			for (var i=0; i < cells.length; i++) {
				hui.build(cells[i].nodeName,{parent:tr});
			};
		}
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