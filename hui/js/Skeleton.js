(function (_super) {

  /**
   * A base skeleton
   * @class
   * @augments hui.ui.Component
   * @param {Object} options
   */
  hui.ui.Skeleton = function(options) {
    this.nodes = {
      resizeNavigation : '.hui_skeleton_resize_navigation',
      resizeResults : '.hui_skeleton_resize_results',
      navigation : '.hui_skeleton_navigation',
      results : '.hui_skeleton_results',
      content : '.hui_skeleton_content',
      actions : '.hui_skeleton_actions'
    }
    _super.call(this, options);
    this._attach();
  }
  
  hui.ui.Skeleton.prototype = {
    _attach : function() {
      var initial = 0,
        navigation = this.nodes.navigation,
        results = this.nodes.results,
        content = this.nodes.content,
        actions = this.nodes.actions,
        navWidth, fullWidth, resultsWidth,
        self = this;

      hui.drag.register({
        element : this.nodes.resizeNavigation,
        onBeforeMove : function(e) {
          initial = e.getLeft();
          navWidth = navigation.clientWidth;
          resultsWidth = results.clientWidth;
          fullWidth = self.element.clientWidth;
        },
        onMove : function(e) {
          var diff = e.getLeft() - initial;
          navigation.style.width = ((navWidth + diff) / fullWidth * 100) + '%';
          results.style.left = ((navWidth + diff) / fullWidth * 100) + '%';
          content.style.left = ((navWidth + resultsWidth + diff + 1) / fullWidth * 100) + '%';
          actions.style.left = ((navWidth + resultsWidth + diff + 1) / fullWidth * 100) + '%';
        }
      })

      hui.drag.register({
        element : this.nodes.resizeResults,
        onBeforeMove : function(e) {
          initial = e.getLeft();
          navWidth = navigation.clientWidth;
          resultsWidth = results.clientWidth;
          fullWidth = self.element.clientWidth;
        },
        onMove : function(e) {
          var diff = e.getLeft() - initial;
          results.style.width = ((resultsWidth + diff) / fullWidth * 100) + '%';
          content.style.left = ((navWidth + resultsWidth + diff + 1) / fullWidth * 100) + '%';
          actions.style.left = ((navWidth + resultsWidth + diff + 1) / fullWidth * 100) + '%';
        }
      })
    },
    $$layout : function() {
      var h = this.nodes.actions.clientHeight;
      this.nodes.content.style.top = h + 'px'
    }
  }

  hui.extend(hui.ui.Skeleton, _super);

})(hui.ui.Component);