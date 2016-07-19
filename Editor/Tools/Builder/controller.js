hui.ui.listen({
  $click$heartbeat : function(icon) {
    icon.setEnabled(false);
    hui.ui.request({
      message : {
        start: {en:'Beating the heart...',da:'Slår med hjertet...'},
        success: {en:'The heart has beaten...',da:'Hjertet har slået...'},
        delay:300
      },
      url : 'actions/Heartbeat.php',//'../../../services/heartbeat/',
      $finally : function() {
        icon.setEnabled(true);
        list.refresh();
      }
    })
  }
})