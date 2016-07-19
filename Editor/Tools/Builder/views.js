hui.ui.listen({

  $select$selector : function(item) {
    if (item.value=='views') {
      listBarText.setText('Views');
    }
  },
  $open$list : function(row) {
    if (row.kind=='view') {
      viewEditor.edit(row.id);
    }
  },
  $changed$viewEditor : function() {
    list.refresh();
  }
});