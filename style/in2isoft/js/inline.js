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
})
if (window.devicePixelRatio > 1) {
	document.body.className+=' retina';
}            
