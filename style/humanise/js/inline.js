_editor.defer(function() {
  if (hui.browser.windows) {
  	hui.cls.add(document.body,'windows');
  }
  if (hui.browser.msie) {
  	hui.cls.add(document.body,'msie');
  }
  if (hui.browser.webkit) {
  	hui.cls.add(document.body,'webkit');
  }
  new hui.ui.SearchField({element:'search',expandedWidth:200});
  hui.listen(hui.get.firstByClass(document.body,'layout_search_icon'),'click',function() {
  	hui.cls.toggle(document.body,'layout_searching');
  })
  //	hui.cls.toggle(document.body,'layout_searching');
})
if (window.devicePixelRatio > 1) {
	document.body.className+=' retina';
}            
