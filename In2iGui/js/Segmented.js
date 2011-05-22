/**
 * @constructor
 * @param {Object} options The options
 */
hui.ui.Segmented = function(options) {
	this.options = hui.override({value:null,allowNull:false},options);
	this.element = hui.get(options.element);
	this.name = options.name;
	this.value = this.options.value;
	hui.ui.extend(this);
	hui.listen(this.element,'mousedown',this.onClick.bind(this));
}

hui.ui.Segmented.prototype = {
	/** @private */
	onClick : function(e) {
		e = new hui.Event(e);
		var a = e.findByTag('a');
		if (a) {
			var changed = false;
			var value = a.getAttribute('rel');
			var x = hui.byClass(this.element,'in2igui_segmented_selected');
			for (var i=0; i < x.length; i++) {
				hui.removeClass(x[i],'in2igui_segmented_selected');
			};
			if (value===this.value && this.options.allowNull) {
				changed=true;
				this.value = null;
				this.fire('valueChanged',this.value);
			} else {
				hui.addClass(a,'in2igui_segmented_selected');
				changed=this.value!== value;
				this.value = value;
			}
			if (changed) {
				this.fire('valueChanged',this.value);
			}
		}
	},
	setValue : function(value) {
		if (value===undefined) {
			value=null;
		}
		var as = this.element.getElementsByTagName('a');
		this.value = null;
		for (var i=0; i < as.length; i++) {
			if (as[i].getAttribute('rel')===value) {
				hui.addClass(as[i],'in2igui_segmented_selected');
				this.value=value;
			} else {
				hui.removeClass(as[i],'in2igui_segmented_selected');
			}
		};
	},
	getValue : function() {
		return this.value;
	}
}

/* EOF */