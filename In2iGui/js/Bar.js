/**
 * @constructor
 * @param {Object} options The options
 */
In2iGui.Bar = function(options) {
	this.options = n2i.override({},options);
	this.name = options.name;
	this.element = $(options.element);
	In2iGui.extend(this);
};

/**
 * @constructor
 * @param {Object} options The options
 */
In2iGui.Bar.Button = function(options) {
	this.options = n2i.override({},options);
	this.name = options.name;
	this.element = $(options.element);
	this.element.observe('click',this.onClick.bind(this));
	In2iGui.extend(this);
};

In2iGui.Bar.Button.prototype = {
	onClick : function() {
		this.fire('click');
	}
}