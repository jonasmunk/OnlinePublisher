require(['hui'],function(hui) {
  
  var MoviePoster = function(options) {
    this.element = options.element;
    this.key = this.element.getAttribute('data-video');
    hui.listen(this.element,'click',this._click.bind(this));
  }
    
  MoviePoster.prototype = {
    _click : function() {
      this._moveTop();
      MoviePoster.active && MoviePoster.active.disable();      
      var src = 'http://www.youtube.com/embed/' +this.key;
      var html = '<iframe class="movies_iframe" width="640" height="480" src="' + src + '?autoplay=1" frameborder="0" allowfullscreen="allowfullscreen"></iframe>'
      hui.build('div',{'class' : 'movies_player', html : html, parent : this.element});
      MoviePoster.active = this;
    },
    _moveTop : function() {
      var item = this.element.parentNode;
      var items = item.parentNode;
      
      var first = hui.find('.movies_item', items);
      if (first == item) {
        return;
      }
      items.removeChild(first);
      items.insertBefore(first,item);
      
      items.removeChild(item);
      first = hui.find('.movies_item', items);
      items.insertBefore(item,first);
    },
    disable : function() {
      var player = hui.find('.movies_player',this.element);
      hui.dom.remove(player);
    }
  }
  
  var p = hui.get.byClass(document.body,'js-movie-poster');
  for (var i = 0; i < p.length; i++) {
    new MoviePoster({element:p[i]});
  }
});