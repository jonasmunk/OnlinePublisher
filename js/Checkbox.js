/**
 * A check box
 * @constructor
 */
hui.ui.Checkbox = function(o) {
	this.element = hui.get(o.element);
	this.control = hui.get.firstByTag(this.element,'span');
	this.options = o;
	this.name = o.name;
	this.value = o.value==='true' || o.value===true;
	hui.ui.extend(this);
	this.addBehavior();
};

/**
 * Creates a new checkbox
 */
hui.ui.Checkbox.create = function(o) {
	var e = o.element = hui.build('a',{'class':'hui_checkbox',href:'javascript://',html:'<span><span></span></span>'});
	if (o.value) {
		hui.cls.add(e,'hui_checkbox_selected');
	}
	return new hui.ui.Checkbox(o);
};

hui.ui.Checkbox.prototype = {
	/** @private */
	addBehavior : function() {
		hui.ui.addFocusClass({element:this.element,'class':'hui_checkbox_focused'});
		hui.listen(this.element,'click',this.click.bind(this));
	},
	/** @private */
	click : function(e) {
		hui.stop(e);
		this.element.focus();
		this.value = !this.value;
		this.updateUI();
		hui.ui.callAncestors(this,'childValueChanged',this.value);
		this.fire('valueChanged',this.value);
		hui.ui.firePropertyChange(this,'value',this.value);
	},
	/** @private */
	updateUI : function() {
		hui.cls.set(this.element,'hui_checkbox_selected',this.value);
	},
	/** Sets the value
	 * @param {Boolean} value Whether the checkbox is checked
	 */
	setValue : function(value) {
		this.value = value===true || value==='true';
		this.updateUI();
	},
	/** Gets the value
	 * @return {Boolean} Whether the checkbox is checked
	 */
	getValue : function() {
		return this.value;
	},
	/** Resets the checkbox */
	reset : function() {
		this.setValue(false);
	},
	/** Gets the label
	 * @return {String} The checkbox label
	 */
	getLabel : function() {
		return this.options.label;
	}
};