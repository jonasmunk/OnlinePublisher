require(['hui'],function() {

  var SearchField = function(options) {
    this.element = options.element;
    hui.collect(this.nodes,this.element);
    this._attach();
  }

  SearchField.prototype = {
    nodes : {
      icon : 'layout_search_icon',
      text : 'layout_search_text'
    },
    _attach : function() {
      hui.listen(this.nodes.icon,'click',this._toggle.bind(this));
      hui.listen(this.nodes.text,'blur',this._blur.bind(this));
      this._toggle();
    },
    _toggle : function() {
    	hui.cls.toggle(document.body,'layout_searching');
      window.setTimeout(function() {
		  try {
	          this.nodes.text.focus();		  	
		  } catch (ignore) {}
      }.bind(this),100)
    },
    _blur : function() {
      hui.cls.remove(document.body,'layout_searching');
    }
  }
  new SearchField({element:hui.find('.layout_search')});

});
