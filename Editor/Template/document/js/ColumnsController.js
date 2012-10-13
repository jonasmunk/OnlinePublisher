var columnsController = {
	
	columnId : null,
	
	editColumn : function(columnId) {
		rowsController.reset();
		this.reset();
		this.columnId = columnId;
		var node = hui.get('column'+this.columnId);
		hui.cls.add(node,'editor_column_highlighted');
		this.editedColumn = {
			id : this.columnId,
			style : node.getAttribute('style'),
			node : node
		}
		hui.ui.request({
			message : {start : {en:'Loading column...',da:'Åbner kolonne...'},delay:300},
			url : 'data/LoadColumn.php',
			parameters : { id : this.editedColumn.id },
			onJSON : function(obj) {
				var values = {preset:'dynamic',width:'',left:obj.left,right:obj.right,top:obj.top,bottom:obj.bottom};
				if (obj.width=='min') {
					values.preset='min';
				} else if (obj.width=='max') {
					values.preset='max';
				} else if (!hui.isBlank(obj.width)) {
					values.preset='specific';
					values.width = obj.width;
				}
				columnWindow.show();
				columnFormula.setValues(values);
			}
		})
	},
	moveColumn : function(id, dir) {
		document.location='data/MoveColumn.php?column='+id+'&dir='+dir;
	},
	
	deleteColumn : function(id) {
		controller.partControls.hide();
		var node = hui.get('column'+id);
		hui.cls.add(node,'editor_column_highlighted');
		hui.ui.confirmOverlay({
			element : node,
			text : {da:'Vil du slette kolonnen? Det kan ikke fortrydes.', en:'Delete the column? It cannot be undone.'},
			okText : {en:'Yes, delete',da:'Ja, slet'},
			cancelText : {en:'No',da:'Nej'},
			onOk : function() {
				document.location='data/DeleteColumn.php?column='+id;
			},
			onCancel : function() {
				hui.cls.remove(node,'editor_column_highlighted');
			}
		})
	},

	$valueChanged$columnWidth : function() {
		columnPreset.setValue('specific');
	},
	$valueChanged$columnPreset : function(value) {
		if (columnPreset.getValue()=='specific') {
			columnWidth.focus();
		} else {
			columnWidth.reset();
		}
	},
	$valuesChanged$columnFormula : function(values) {
		var node = hui.get('column'+this.columnId);
		if (node) {
			if (values.preset=='min') {
				node.style.width = '1%';
			} else if (values.preset=='max') {
				node.style.width = '100%';
			} else if (values.preset=='dynamic') {
				node.style.width = 'auto';
			} else {
				node.style.width=values.width || 'auto';
			}
			node.style.paddingLeft = values.left;
			node.style.paddingRight = values.right;
			node.style.paddingTop = values.top;
			node.style.paddingBottom = values.bottom;
		} else {
			hui.log('Column node not found');
		}
	},
	reset : function() {
		if (!this.editedColumn) {
			return;
		}
		this.editedColumn.node.setAttribute('style',this.editedColumn.style);
		hui.cls.remove(this.editedColumn.node,'editor_column_highlighted');
		this.editedColumn = null;
		columnFormula.reset();
		columnWindow.hide();
	},
	$userClosedWindow$columnWindow : function() {
		this.reset();
	},
	$click$cancelColumn : function() {
		this.reset();
	},
	$click$deleteColumn : function() {
		document.location='data/DeleteColumn.php?column='+this.editedColumn.id;
	},
	$submit$columnFormula : function(form) {
		var values = form.getValues();
		var p = {
			id : this.editedColumn.id,
			left : values.left,
			right : values.right,
			top : values.top,
			bottom : values.bottom
		};
		if (values.preset=='min' || values.preset=='max') {
			p.width = values.preset;
		} else if (values.preset='specific') {
			p.width = values.width;
		}
		hui.ui.request({
			url : 'data/UpdateColumn.php',
			parameters : p,
			message : {start : {en:'Saving column...',da:'Gemmer kolonne...'},delay:300},
			onSuccess : function() {
				hui.ui.showMessage({text:{en:'The column is saved',da:'Kolonnen er gemt'},duration:2000,icon:'common/success'});
				controller._markToolbarChanged();
			}.bind(this)
		});
		this.reset();
	}
}

hui.ui.listen(columnsController);