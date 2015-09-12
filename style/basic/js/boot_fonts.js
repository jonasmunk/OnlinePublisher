(function(window,document,tool) {
  tool.loadFont = function(options) {
    if (false && window.localStorage && localStorage.getItem(options.href)) {
      document.body.className += ' ' + options.cls;
    } else {
      var weights = options.weights || ['normal'];
      var sizes = {};
      //weights = ['300','400','700'];
      var count = weights.length;
      var operation = function(weight) {
        var dummy = document.createElement('div');
        dummy.style.position = 'absolute';
        dummy.style.whiteSpace = 'nowrap';
        dummy.style.top = '-9999px';
        dummy.style.left = '-9999px';
        dummy.style.font = '999px fantasy';
        dummy.style.fontWeight = weight;
        dummy.innerHTML = 'Am-i#w^o';
        document.body.appendChild(dummy);
        var width = dummy.clientWidth;

        //console.log('Checking: '+weight);
        dummy.style.fontFamily = "'" + options.family + "',fantasy";
        var tester;
        var timeout = 0.01;
        tester = function() {
          timeout *= 1.5;
          var currentWidth = dummy.clientWidth;
          if (width==0 || (width != currentWidth && !sizes[currentWidth])) {
            count--;
            console.log('found: '+weight+','+width+'/'+dummy.clientWidth);
            if (count==0) {
              console.log('finished: '+options.family);
              document.body.className += ' ' + options.cls;
              window.localStorage && localStorage.setItem(options.href,'1');
            }
            sizes[currentWidth] = 1;
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
    }
    tool.inject(tool._build('link',{
      rel : 'stylesheet',
      type : 'text/css',
      href : options.href
    }));
  }
})(window,document,_editor);