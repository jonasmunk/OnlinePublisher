/**
 * @constructor
 * @param {Object} options The options : {modal:false}
 */
hui.ui.Skeleton = function(options) {
	this.options = hui.override({},options);
	this.name = options.name;
	this.element = hui.get(options.element);
  this.nodes = {
    resize : 'hui_skeleton_resize'
  }
	hui.ui.extend(this);
  this._attach();
}

hui.ui.Skeleton.prototype = {
  _attach : function() {
    var initial = 0;
    hui.drag.register({
      element : this.nodes.resize,
      onBeforeMove : function(e) {
        initial = e.getLeft();
      },
      onMove : function(e) {
        console.log(e.getLeft() - initial);
      }
    })
  }
}