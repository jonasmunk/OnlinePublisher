(function (_super) {

  /**
   * Vertical rows
   * @class
   * @augments hui.ui.Component
   * @param {Object} options
   */
  hui.ui.Rows = function(options) {
    _super.call(this, options);
    this.rows = [];
    this._attach();
  }
  
  hui.ui.Rows.prototype = {
    _attach : function() {
      var children = hui.get.children(this.element);
      for (var i = 0; i < children.length; i++) {
        var node = children[i];
        var info = hui.string.fromJSON(node.getAttribute('data')) || {};
        info.node = node;
        this.rows.push(info);
      }
    },
    _findSizes : function(fullHeight) {
      var sizes = [];
      var count = this.rows.length;
      var fixedCount = 0;
      var fixedHeight = 0;
      for (var i = 0; i < count; i++) {
        var row = this.rows[i];
        if (row.height=='content') {
          var contentHeight = this._getContentHeight(row.node);
          fixedHeight += contentHeight;
          sizes.push(contentHeight);
          fixedCount++;
        } else {
          sizes.push(null);
        }
      }
      var remainingHeight = fullHeight - fixedHeight;
      var remainder = count - fixedCount;
      for (var i = 0; i < sizes.length; i++) {
        if (sizes[i] === null) {
          sizes[i] = 1 / remainder * remainingHeight;
        }
      }
      this.sizes = sizes;
    },
    _getContentHeight : function(node) {
      var height = 0;
      var children = hui.get.children(node);
      for (var i = 0; i < children.length; i++) {
        height += children[i].clientHeight;
      }
      return height;
    },
    $$childSizeChanged : function() {
      this.$$layout();
    },
    $$layout : function() {
      var fullHeight = this.element.parentNode.clientHeight;
      this.element.style.height = fullHeight + 'px';
      this._findSizes(fullHeight);
      var count = this.rows.length;
      for (var i = 0; i < count; i++) {
        var row = this.rows[i];
        row.node.style.height = this.sizes[i] + 'px';
      }
    }
  }

  hui.extend(hui.ui.Rows, _super);

})(hui.ui.Component);