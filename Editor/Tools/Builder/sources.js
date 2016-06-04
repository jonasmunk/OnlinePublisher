hui.ui.listen({
  sourceId : null,

  $open$selector : function(item) {
    if (item.kind=='source') {
      this.editSource(item.value);
      synchronizeSource.setEnabled(true);
    } else {
      synchronizeSource.setEnabled(false);
    }
  },
  $select$list : function(item) {
    synchronizeSource.setEnabled(item && item.kind=='source');
  },
  $open$list : function(row) {
    if (row.kind=='source') {
      this.editSource(row.id);
    }
  },

  editSource : function(id) {
    sourceFormula.reset();
    hui.ui.request({
      message : {start:{en:'Loading source...',da:'Henter kilde...'},delay:300},
      parameters : {id:id},
      url : '../../Services/Model/LoadObject.php',
      $object : function(source) {
        this.sourceId = source.id;
        sourceFormula.setValues(source);
        deleteSource.setEnabled(true);
        sourceWindow.show();
      }.bind(this)
    });
  },

  // Source
  $click$cancelSource : function() {
    sourceFormula.reset();
    sourceWindow.hide();
  },
  $click$saveSource : function() {
    var data = sourceFormula.getValues();
    data.id = this.sourceId;
    hui.ui.request({
      url : 'actions/SaveSource.php',
      json : {data:data},
      $success : function() {
        this.sourceId = null;
        sourceFormula.reset();
        sourceWindow.hide();
        list.refresh();
      }.bind(this)
    });
  },
  $click$deleteSource : function() {
    hui.ui.request({
      url : '../../Services/Model/DeleteObject.php',
      parameters : {id : this.sourceId},
      $success : function() {
        this.sourceId = null;
        sourceFormula.reset();
        sourceWindow.hide();
        list.refresh();
      }.bind(this)
    });
  },

  $click$newSource : function() {
    this.sourceId = null;
    sourceFormula.reset();
    sourceWindow.show();
    deleteSource.setEnabled(false);
    sourceFormula.focus();
  },

  $click$synchronizeSource : function() {
    var item = list.getFirstSelection();
    if (item.kind=='source') {
      hui.ui.showMessage({text:{en:'Synchronizing source...',da:'Synkroniserer kilde...'}});
      hui.ui.request({
        url : 'actions/SynchronizeSource.php',
        parameters : {id:item.id},
        $success : function() {
          hui.ui.hideMessage();
          list.refresh();
        },
        $failure : function() {
          hui.ui.showMessage({
            text:{en:'Synchronization failed',da:'Synkronisering fejlede'},
            duration:2000
          });
        }
      });
    }
  }
});