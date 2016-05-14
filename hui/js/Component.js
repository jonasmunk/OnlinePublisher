/**
 * A component
 * @constructor
 * @param {Object} options
 * @param {Element} options.element
 * @param {String} options.name
 * @param {Object} options.listen A listener
 */
hui.ui.Component = function(options) {
	this.name = options.name;
	if (!this.name) {
		hui.ui.latestObjectIndex++;
		this.name = 'unnamed'+hui.ui.latestObjectIndex;
	}
	this.element = hui.get(options.element);
  this.delegates = [];
  if (this.nodes) {
  	this.nodes = hui.collect(this.nodes,this.element);
  }
  if (options.listen) {
    this.listen(options.listen);
  }
  hui.ui.registerComponent(this);
}

hui.ui.Component.prototype = {
  /**
   * Add event listener
   * @param {Object} listener An object with methods for different events
   */
  listen : function(listener) {
    this.delegates.push(listener);
  },
  fire : function(name,value,event) {
  		return hui.ui.callDelegates(this,name,value,event);
  },
  /**
   * Get the components root element
   * @returns Element
   */
  getElement : function() {
    return this.element;
  },
  destroy : function() {
    if (this.element) {
      hui.dom.remove(this.element);
    }
  },
	valueForProperty : function(property) {
	  return this[property];
	},
	fireValueChange : function() {
		this.fire('valueChanged',this.value);
		hui.ui.firePropertyChange(this,'value',this.value);
		hui.ui.callAncestors(this,'childValueChanged',this.value);
	},
	fireSizeChange : function() {
		hui.ui.callAncestors(this,'$$childSizeChanged');
	}
};