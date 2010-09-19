/**
 * @constructor
 * @param {Object} options The options
 */
In2iGui.Segmented = function(options) {
	this.options = n2i.override({value:null,allowNull:false},options);
	this.element = $(options.element);
	this.name = options.name;
	this.value = this.options.value;
	In2iGui.extend(this);
	this.element.observe('mousedown',this.onClick.bind(this));
}

In2iGui.Segmented.prototype = {
	/** @private */
	onClick : function(e) {
		var a = e.findElement('a');
		if (a) {
			var changed = false;
			var value = a.getAttribute('rel');
			this.element.select('.in2igui_segmented_selected').each(function(node) {
				node.removeClassName('in2igui_segmented_selected');
			});
			if (value===this.value && this.options.allowNull) {
				changed=true;
				this.value = null;
				this.fire('valueChanged',this.value);
			} else {
				a.addClassName('in2igui_segmented_selected');
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
		var as = this.element.select('a');
		this.value = null;
		for (var i=0; i < as.length; i++) {
			if (as[i].getAttribute('rel')===value) {
				as[i].addClassName('in2igui_segmented_selected');
				this.value=value;
			} else {
				as[i].removeClassName('in2igui_segmented_selected');
			}
		};
	},
	getValue : function() {
		return this.value;
	}
}

/* EOF */