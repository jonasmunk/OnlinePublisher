/**
 * @constructor
 * @param {Object} options The options
 */
In2iGui.Segmented = function(options) {
	this.options = n2i.override({value:null,allowNull:false},options);
	this.element = n2i.get(options.element);
	this.name = options.name;
	this.value = this.options.value;
	In2iGui.extend(this);
	n2i.listen(this.element,'mousedown',this.onClick.bind(this));
}

In2iGui.Segmented.prototype = {
	/** @private */
	onClick : function(e) {
		e = new n2i.Event(e);
		var a = e.findByTag('a');
		if (a) {
			var changed = false;
			var value = a.getAttribute('rel');
			var x = n2i.byClass(this.element,'in2igui_segmented_selected');
			for (var i=0; i < x.length; i++) {
				n2i.removeClass(x[i],'in2igui_segmented_selected');
			};
			if (value===this.value && this.options.allowNull) {
				changed=true;
				this.value = null;
				this.fire('valueChanged',this.value);
			} else {
				n2i.addClass(a,'in2igui_segmented_selected');
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
				n2i.addClass(as[i],'in2igui_segmented_selected');
				this.value=value;
			} else {
				n2i.removeClass(as[i],'in2igui_segmented_selected');
			}
		};
	},
	getValue : function() {
		return this.value;
	}
}

/* EOF */