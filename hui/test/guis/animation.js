hui.ui.listen({

  $ready : function() {
    settingsWindow.show();
    var items = [];
    for (var x in hui.ease) {
      items.push({value:x,text:x});
    }
    easing.setItems(items);
    easing.setValue(items[0].value);
  },

  $submit$settingsForm : function(form) {
    this.go(form.getValues());
  },

  $valuesChanged$settingsForm : function(values) {
    this.go(values);
  },

  go : function(values) {
    var node = hui.get('subject1');
    hui.style.set(node,{marginLeft:'0%',backgroundColor:'#f00'});
    hui.animate({
      node : node,
      css : {
        marginLeft: '100%',
        backgroundColor:'#00f'
      },
      duration: values.duration,
      ease : hui.ease[values.ease]
    })
  }
})