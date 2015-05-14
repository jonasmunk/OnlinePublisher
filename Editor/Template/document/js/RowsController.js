var rowsController = {
  
  rowId : null,
  editedRow : null,
  
  editRow : function(rowId) {
    columnsController.reset();
    this.reset();
    this.rowId = rowId;
    var node = hui.get('row'+rowId);
    hui.cls.add(node,'editor_row_highlighted');
    this.editedRow = {
      id : rowId,
      style : node.getAttribute('style'),
      parentStyle : node.parentNode.getAttribute('style'),
      node : node
    }
    hui.ui.request({
      message : {start : {en:'Loading row...', da:'Åbner række...'},delay:300},
      url : 'data/LoadRow.php',
      parameters : { id : rowId },
      $object : function(obj) {
        rowWindow.show();
        rowFormula.setValues(obj);        
        return;
      }
    })
  },
  clear : function() {
    if (!this.editedRow) {
      return;
    }
    hui.cls.remove(this.editedRow.node,'editor_row_highlighted');
    this.editedRow = null;
    rowFormula.reset();
    rowWindow.hide();
  },
  reset : function() {
    if (!this.editedRow) {
      return;
    }
    this.editedRow.node.setAttribute('style',this.editedRow.style);
    this.editedRow.node.parentNode.setAttribute('style',this.editedRow.parentStyle);
    this.clear();
  },
  $valuesChanged$rowFormula : function(values) {

    var node = this.editedRow.node;
    if (node) {
      node.style.marginTop = values.top;
      node.style.marginBottom = values.bottom;
      node.style.borderSpacing = values.spacing;
      node.parentNode.style.margin = values.spacing ? '-' + values.spacing : '';
    } else {
      hui.log('Row node not found');
    }
  },
  $click$cancelRow : function() {
    this.reset();
  },
  $submit$rowFormula : function(form) {
    var values = form.getValues();
    values.id = this.editedRow.id;
    hui.log(values)
    hui.ui.request({
      url : 'actions/UpdateRow.php',
      parameters : values,
      message : {start : {en:'Saving row...',da:'Gemmer række...'},delay:300},
      $success : function() {
        hui.ui.showMessage({text:{en:'The row is saved',da:'Rækken er gemt'},duration:2000,icon:'common/success'});
        controller._markToolbarChanged();
      }.bind(this)
    });
    this.clear();
  },
  
  $click$deleteRow : function() {
    this.deleteRow(this.editedRow.id);
    this.reset();
  },
  
  deleteRow : function(id) {
    controller.partControls.hide();
    var node = hui.get('row'+id);
    hui.cls.add(node,'editor_row_highlighted');
    hui.ui.confirmOverlay({
      element : node,
      text : {da:'Vil du slette r\u00e6kken? Det kan ikke fortrydes.',en:'Delete the row? It cannot be undone.'},
      okText : {da : 'Ja, slet',en : 'Yes, delete'},
      cancelText : { da : 'Annuller', en : 'Cancel' },
      $ok : function() {
        document.location='data/DeleteRow.php?row='+id;
      },
      $cancel : function() {
        hui.cls.remove(node,'editor_row_highlighted');
      }
    })
  }
  
}
hui.ui.listen(rowsController);