/**
 * A push button
 * <pre><strong>options:</strong> {
 *  element : «Element | ID»,
 *  name : «String»
 * }
 *
 * <strong>Events:</strong>
 * $click(button) - When the icon is clicked
 * </pre>
 *
 * @param options {Object} The options
 * @constructor
 */
hui.ui.Icon = function(options) {
	this.options = options;
	this.name = options.name;
	this.icon = this.options.icon;
	this.size = this.options.size;
	this.element = hui.get(options.element);
	hui.ui.extend(this);
	this._addBehavior();
}

hui.ui.Icon.prototype = {
	_addBehavior : function() {
		hui.listen(this.element,'click',function() {
			this.fire('click');
		}.bind(this));
	},
	setSize : function(size) {
		this.size = size;
		this.element.className = 'hui_icon_labeled hui_icon_labeled_'+this.size;
		var inner = hui.get.firstByTag(this.element,'span');
		inner.className = 'hui_icon_'+this.size;
		inner.style.backgroundImage = 'url('+hui.ui.getIconUrl(this.options.icon,this.size)+')';
	}
};