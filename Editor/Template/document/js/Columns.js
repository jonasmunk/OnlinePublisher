var columnsController = {
	
	columnId : null,
	
	editColumn : function(columnId) {
		if (this.editedColumn) {
			this._resetColumn();
		}
		this.columnId = columnId;
		var node = hui.get('column'+this.columnId);
		hui.cls.add(node,'editor_column_highlighted');
		this.editedColumn = {
			id : this.columnId,
			initialWidth : node.style.width,
			node : node
		}
		hui.ui.request({
			message : {start : 'Åbner kolonne...',delay:300},
			url : 'data/LoadColumn.php',
			parameters : { id : this.editedColumn.id },
			onJSON : function(obj) {
				var values = {preset:'dynamic',width:''};
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
			text : controller.strings.get('confirm_delete_column'),
			okText : controller.strings.get('confirm_delete_ok'),
			cancelText : controller.strings.get('cancel'),
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
		} else {
			hui.log('Column node not found');
		}
	},
	_resetColumn : function() {
		this.editedColumn.node.style.width = this.editedColumn.initialWidth;
		hui.cls.remove(this.editedColumn.node,'editor_column_highlighted');
		this.editedColumn = null;
		columnFormula.reset();
		columnWindow.hide();
	},
	$userClosedWindow$columnWindow : function() {
		this._resetColumn();
	},
	$click$cancelColumn : function() {
		this._resetColumn();
	},
	$click$deleteColumn : function() {
		document.location='data/DeleteColumn.php?column='+this.editedColumn.id;
	},
	$submit$columnFormula : function(form) {
		var values = form.getValues();
		var p = {
			id : this.editedColumn.id
		};
		if (values.preset=='min' || values.preset=='max') {
			p.width = values.preset;
		} else if (values.preset='specific') {
			p.width = values.width;
		}
		hui.ui.request({
			url : 'data/UpdateColumn.php',
			parameters : p,
			message : {start : 'Gemmer kolonne...',delay:300},
			onSuccess : function() {
				hui.ui.showMessage({text:'Kolonnen er gemt',duration:2000,icon:'common/success'});
				this._markToolbarChanged();
			}.bind(this)
		});
		hui.cls.remove(this.editedColumn.node,'editor_column_highlighted');
		this.editedColumn = null;
		columnFormula.reset();
		columnWindow.hide();
	}
}

hui.ui.listen(columnsController);