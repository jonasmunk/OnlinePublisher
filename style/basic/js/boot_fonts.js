(function(window,document) {
  _editor.loadFont = function(options) {
    var dummy = document.createElement('div');
    dummy.setAttribute('style','font: 400px fantasy;position:absolute;top:-9999px;left:-9999px');
    dummy.innerHTML = 'Am-i#o';
    document.body.appendChild(dummy);
    var width = dummy.clientWidth;
    var e = document.createElement('link');
    e.rel = 'stylesheet';
    e.type = 'text/css';
    e.href = options.href;
    this.inject(e);
    if (width==0) { // TODO IE7 (we cannot test) so we play it safe
      document.body.className += ' ' + options.cls;
      return;
    }
    dummy.style.fontFamily = "'" + options.family + "',fantasy";
    var tester;
    var timeout = 0.01;
    tester = function() {
      timeout *= 1.5;
      if (width != dummy.clientWidth) {
        document.body.className += ' ' + options.cls;
        dummy.parentNode.removeChild(dummy);
      } else {
        window.setTimeout(tester,timeout);
      }
    }
    tester();    
  }
})(window,document);