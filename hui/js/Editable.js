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
      this.value = value;
    },
    getValue : function() {
      return this.value;
    },
    getElement : function() {
      hui.log('Hijacked')
      return _super.prototype.getElement.call(this);
    }
  }

  hui.extend(hui.ui.Editable, _super);
  
})(hui.ui.Component)
