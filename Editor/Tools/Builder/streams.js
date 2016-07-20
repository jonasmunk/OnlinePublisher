hui.ui.listen({
  streamId : null,

  $open$selector : function(item) {
    if (item.kind=='stream') {
      this.editStream(item.value);
    }
  },
  $select$selector : function(item) {
    if (item.kind=='stream') {
      listBarText.setText(item.title + " (" + item.value + ")");
    }
  },
  $open$list : function(row) {
    if (row.kind=='streamitem') {
      streamItemEditor.edit(row.id);
    }
  },
  $changed$streamItemEditor : function() {
    list.refresh();
  },

  editStream : function(id) {
    sourceFormula.reset();
    hui.ui.request({
      message : {start:{en:'Loading source...',da:'Henter kilde...'},delay:300},
      parameters : {id:id},
      url : '../../Services/Model/LoadObject.php',
      $object : function(stream) {
        this.streamId = stream.id;
        streamFormula.setValues(stream);
        deleteStream.setEnabled(true);
        streamWindow.show();
      }.bind(this)
    });
  },

  // Source
  $click$cancelStream : function() {
    streamFormula.reset();
    streamWindow.hide();
  },
  $click$saveStream : function() {
    var data = streamFormula.getValues();
    data.id = this.streamId;
    hui.ui.request({
      url : 'actions/SaveStream.php',
      json : {data:data},
      $success : function() {
        this.streamId = null;
        streamFormula.reset();
        streamWindow.hide();
        selector.refresh();
      }.bind(this)
    });
  },
  $click$deleteStream : function() {
    hui.ui.request({
      url : '../../Services/Model/DeleteObject.php',
      parameters : {id : this.streamId},
      $success : function() {
        this.streamId = null;
        streamFormula.reset();
        streamWindow.hide();
        selector.refresh();
      }.bind(this)
    });
  },

  $click$newStream : function() {
    this.streamId = null;
    streamFormula.reset();
    streamWindow.show();
    deleteStream.setEnabled(false);
    streamFormula.focus();
  }
});