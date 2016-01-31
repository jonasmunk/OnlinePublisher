require(['hui'],function(hui) {

  var easeInout = function(num) {
    return (num*2-1) * (num*2-1) * -1 + 1;
  }

  hui.onReady(function() {
    var photos = hui.get.byClass(document.body,'js-photo');
    hui.each(photos,function(photo) {
      var effect = hui.find('.js-photo-effect',photo);
      var pos = hui.position.get(photo);
      var size = {width:photo.clientWidth,height:photo.clientHeight};
      hui.ui.listen({
        $$afterResize : function() {
          pos = hui.position.get(photo);
          size = {width:photo.clientWidth,height:photo.clientHeight};
        }
      })
      hui.listen(photo,'click',function(e) {
        hui.stop(e);
        hui.ui.get('photoGallery').showById(photo.getAttribute('data-id'));
      });
      hui.listen(window,'mousemove',function(e) {
        e = hui.event(e);
        var horz = (e.getLeft() - pos.left) / size.width;
        var vert = (e.getTop() - pos.top) / size.height;
        effect.style.marginLeft = (horz * 20) + 'px';
        effect.style.marginTop = (vert * 20) + 'px';
        var op = hui.between(0,easeInout(horz),1) * hui.between(0,easeInout(vert),1);
        effect.style.opacity = op;
      })    
    })
  })

});