(function(_super) {

  /**
   * A component with a value
   * @class
   * @augments hui.ui.Component
   * @param {Object} options
   * @param {any} options.value The value
   */
  hui.ui.Editable = function(options) {
    _super.call(this, options);
    this.value = options.value;
  }

  hui.ui.Editable.prototype = {
    setValue : function(value) {
      var changed = value !== this.value;
      this.value = value;
      changed && this.fireValueChange();
    },
    getValue : function() {
      return this.value;
    },
  	fireValueChange : function() {
  		this.fire('valueChanged',this.value);
  		hui.ui.firePropertyChange(this,'value',this.value);
  		hui.ui.callAncestors(this,'childValueChanged',this.value);
  	},
    getElement : function() {
      return _super.prototype.getElement.call(this);
    }
  }

  hui.extend(hui.ui.Editable, _super);
  
})(hui.ui.Component)
