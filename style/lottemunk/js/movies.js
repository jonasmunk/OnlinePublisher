require(['hui'],function(hui) {
  
  var MoviePoster = function(options) {
    this.element = options.element;
    this.key = this.element.getAttribute('data-key');
    hui.listen(this.element,'click',this._click.bind(this));
  }
    
  MoviePoster.prototype = {
    _click : function() {
      this.element.innerHTML = '<iframe class="movies_iframe" width="640" height="480" src="http://www.youtube.com/embed/' +this.key + '?autoplay=1" frameborder="0" allowfullscreen="allowfullscreen"></iframe>'
    }
  }
  
  var p = hui.get.byClass(document.body,'js-movie-poster');
  for (var i = 0; i < p.length; i++) {
    new MoviePoster({element:p[i]});
  }
});