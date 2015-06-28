/**
 * A component
 * @constructor
 * @param {Object} options
 * @param {Element} options.element
 * @param {String} options.name
 */
hui.ui.Component = function(options) {
	this.name = options.name;
	if (!this.name) {
		hui.ui.latestObjectIndex++;
		this.name = 'unnamed'+hui.ui.latestObjectIndex;
	}
	this.element = hui.get(options.element);
  this.listeners = [];
  if (this.nodes) {
  	this.nodes = hui.collect(this.nodes,this.element);
  }
  hui.ui.registerComponent(this);
}

hui.ui.Component.prototype = {
  /**
   * Add event listener
   * @param {Object} listener An object with methods for different events
   */
  listen : function(listener) {
    this.listeners.push(listener);
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
  }
}