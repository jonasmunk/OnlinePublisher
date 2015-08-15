require(['hui'],function() {

  var SearchField = function(options) {
    this.element = options.element;
    this.nodes = hui.collect(this.nodes,this.element);
    this._attach();
  }

  SearchField.prototype = {
    nodes : {
      icon : 'layout_search_icon',
      text : 'layout_search_text'
    },
    _attach : function() {
      hui.listen(this.nodes.icon,'click',this._toggle.bind(this));
      hui.listen(this.nodes.text,'focus',this._focus.bind(this));
      hui.listen(this.nodes.text,'blur',this._blur.bind(this));
      // Dont remember why - some browser sets focus on svgs
      this.nodes.icon.setAttribute("focusable","false");
    },
    _toggle : function() {
      hui.cls.toggle(this.element,'layout_search_active');
      try {
        this.nodes.text.focus();
      } catch (ignore) {}
      //window.setTimeout(function() {
      //}.bind(this),100)
    },
    _focus : function() {
      hui.cls.add(this.element,'layout_search_active');
    },
    _blur : function() {
      hui.cls.remove(this.element,'layout_search_active');
    }
  }
  new SearchField({element:hui.find('.layout_search')});

});
