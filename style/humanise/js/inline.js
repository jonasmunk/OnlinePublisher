require(['hui'],function(hui) {
  if (hui.browser.windows) {
    hui.cls.add(document.body,'windows');
  }
  if (hui.browser.webkit) {
    hui.cls.add(document.body,'webkit');
  }
  var check = function() {
    return (document.documentElement.scrollTop || document.body.scrollTop) > 42;
  }
  var scrolling = check();
  if (scrolling) {
    hui.cls.add(document.body,'scroll');
  }
  hui.listen(document,'scroll',function() {
    var test = check();
    if (test!==scrolling) {
      hui.cls.set(document.body,'scroll',test);
      scrolling = test;
    }
  })
})