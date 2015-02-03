(function(window,document,tool) {
  tool.loadFont = function(options) {
    var weights = options.weights || ['normal'];
    weights = ['300','400','700'];
    var count = weights.length;
    var operation = function(weight) {
      var dummy = tool._build('div',{style:'font:400px fantasy;position:absolute;top:-9999px;left:-9999px'})
      dummy.innerHTML = 'Am-i#w^o';
      document.body.appendChild(dummy);
      var width = dummy.clientWidth;
      //console.log('Checking: '+weight);
      dummy.style.fontFamily = "'" + options.family + "',fantasy";
      dummy.style.fontWeight = weight;
      var tester;
      var timeout = 0.01;
      tester = function() {
        timeout *= 1.5;
        if (width==0 || width != dummy.clientWidth) {
          count--;
          //console.log('found: '+weight+','+width+'/'+dummy.clientWidth);
          if (count==0) {
            document.body.className += ' ' + options.cls;
          }
          dummy.parentNode.removeChild(dummy);
        } else {
          window.setTimeout(tester,timeout);
        }
      }
      tester();
    }
    for (var i = 0; i < weights.length; i++) {
      operation(weights[i]);
    }

    tool.inject(tool._build('link',{
      rel : 'stylesheet',
      type : 'text/css',
      href : options.href
    }));
  }
})(window,document,_editor);