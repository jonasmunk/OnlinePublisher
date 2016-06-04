hui.onReady(function() {
  var configs = document.getElementsByTagName('noscript');
  for (var i = 0; i < configs.length; i++) {
    var type = configs[i].getAttribute('data-type');
    if (type) {
      var options = hui.string.fromJSON(configs[i].textContent);
      options.element = configs[i].parentNode;
      new hui.ui[type](options);
    }
  }
})

hui.onReady(function() {
  var configs = document.querySelectorAll('*[data-hui]');
  for (var i = 0; i < configs.length; i++) {
    var type = configs[i].getAttribute('data-hui');
    if (type) {
      var children = configs[i].childNodes;
      var options = {};
      var attr = configs[i].getAttribute('data-options');
      if (attr) {
        options = hui.string.fromJSON(attr);
      } else {
        for (var j = children.length - 1; j >= 0; j--) {
          if (children[j].nodeType == 8) {
            options = hui.string.fromJSON(children[j].nodeValue);
            break;
          }
        }
      }
      options.element = configs[i];
      new hui.ui[type](options);
    }
  }
})

hui.onReady(function() {
  var configs = document.querySelectorAll('script[type=hui]');
  for (var i = 0; i < configs.length; i++) {
    var data = hui.string.fromJSON(configs[i].textContent);
    data.element = configs[i].parentNode;
    new hui.ui[configs[i].getAttribute('data-type')](data);
  }
})