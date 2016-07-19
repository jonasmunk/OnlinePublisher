hui.ui.listen({

  $select$selector : function(item) {
    if (item.value=='listeners') {
      listBarText.setText('Listeners');
    }
  },
  $open$list : function(row) {
    if (row.kind=='listener') {
      listenerEditor.edit(row.id);
    }
  },
  $changed$listenerEditor : function() {
    list.refresh();
  }
});